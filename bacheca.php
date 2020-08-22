<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina dal login
  check_login();
?>

<!DOCTYPE html>
<html lang='it'>
<head>
	<title>PausaCaff&egrave</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_loggato.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Utente.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_pagina_bacheca.css">
</head>

<body>

<?php
  
  include("include/recupera_avatar_funzione.php");
  include("include/scelta_immagine_like.php");
  require_once("include/modal/modalUtente.php");
  require_once("include/header.php");
  
  
  echo '<div class="Banner"></div>';
  
#inizializzo variabili $status_ui e $statuslist da stampare infondo al file
$status_ui = ""; 
$statuslist = "";
$my_iduser = $_SESSION['iduser'];
$my_username = $_SESSION['username'];


# TEXT AREA PER NUOVO POST + BOTTONE POSTA
	$status_ui = '<textarea id="statustext" onkeyup="statusMax(this,250)" placeholder="A cosa stai pensando, '.$my_username.'?"></textarea>';
	$status_ui .= '<br /><button class="bottoni_bacheca" id="statusBtn" onclick="postToStatus(\'status_post\',\'a\',\''.$my_username.'\',\'statustext\')">Pubblica</button>';


######POST#####

/*
1) tipologia A = post sulla pagina del post owner X
2) tipologia C = post di amico sulla pagina di X
3) tipologia B = tutti i commenti in risposta a un post di tipo A o C
*/
    
#Raccolgo tutti i post
$sql = "SELECT P.*
				FROM post P INNER JOIN users U ON P.account_name = U.username
				WHERE (P.type='a'
					OR P.type='c')
          AND (U.iduser IN (
                              SELECT DISTINCT(IF(iduser1 = '".$my_iduser."', iduser2, iduser1)) AS Amico
                              FROM amicizie
                              WHERE iduser1 = '".$my_iduser."'
                                OR
                                   iduser2 = '".$my_iduser."'
                          )
              OR U.iduser = '".$my_iduser."')
				ORDER BY P.postdate DESC";
$query = mysqli_query($conn, $sql);
if(!$query){
  header("Location: paginaErrore.php?errore=errore_query");
}

#while esterno per gathering post
while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
	$statusid = $row["id"];
	$account_name = $row["account_name"];
	$author = $row["author"];
	$postdate = $row["postdate"];
	$data = $row["data"];
  $likes = $row["likes"];
  $bottone_like_post = scelta_immagine_like($statusid, $my_username);
	
	#Se chi sta visualizzando la bacheca è anche l'autore del post, lo potrà eliminare
	$statusDeleteButton = '';
	if($author == $_SESSION['username'] || $account_name == $_SESSION['username'] ){
		#salvo dentro variabile uno span (con id univoco uguale all'id del post che è nel ciclo!)
		#al cui interno c'è un oggetto A => onmousedown => elimina post relativo all'id salvato in $statusid
		$statusDeleteButton = '<span id="sdb_'.$statusid.'"><a href="#" onclick="return false;"
		onmousedown="deleteStatus(\''.$statusid.'\',\'status_'.$statusid.'\');" title="Elimina il post e i relativi commenti">Elimina post</a></span> &nbsp; &nbsp;';
	}
	
	######COMMENTI#####
	
	#Raccolgo tutti i commenti per il post in questione
	$status_replies = "";
	$query_replies = mysqli_query($conn, "SELECT * FROM post WHERE osid='$statusid' AND type='b' ORDER BY postdate ASC");
	
	$replynumrows = mysqli_num_rows($query_replies);
    if($replynumrows > 0){
			#while interno per gathering commenti
        while ($row2 = mysqli_fetch_array($query_replies, MYSQLI_ASSOC)) {
					$statusreplyid = $row2["id"];
					$replyauthor = $row2["author"];
					$replydata = $row2["data"];
					$replypostdate = $row2["postdate"];
          $likes_commento = $row2["likes"];
					
					#Se chi sta visualizzando la bacheca è anche l'autore del commento, lo potrà eliminare
					$replyDeleteButton = '';
					if($replyauthor == $_SESSION['username'] || $account_name == $_SESSION['username'] ){
						$replyDeleteButton = '<span id="srdb_'.$statusreplyid.'"><a href="#" onclick="return false;"
						onmousedown="deleteReply(\''.$statusreplyid.'\',\'reply_'.$statusreplyid.'\',\'immagine_mi_piace'.$statusreplyid.'\',\'like_'.$statusreplyid.'\');" title="Elimina questo commento">Elimina</a></span>';
					}
					#salvo in una variabile stringa html da stampare in seguito.
					#includo comunque $replyDeleteButton che varrà lo span se l'autore sta visualizzando, vuoto altrimenti
					#output => "Mi piace: " + numerino like => entrambi hanno un id univoco per permettere la loro eventuale cancellazione
					$profilo_di = "me"; //per redirecting sulla mia pagina se sono io l'autore
					$profilo_di = ($replyauthor == $_SESSION['username']) ? $profilo_di : $replyauthor;
          
        /*mi ricavo 3 valori:
          1) o il mio avatar (se il post è stato pubblicato da me) o dell'amico autore del post
          2) l'immagine del "bottone like", accesa o spenta a seconda che abbia già messo un like al POST dell'amico o meno
          3) l'immagine del "bottone like", accesa o spenta a seconda che abbia già messo un like al COMMENTO dell'amico o meno
        */
          $avatar = recupera_avatar($profilo_di);
          $bottone_like_commento = scelta_immagine_like($statusreplyid, $my_username);
  
            if($profilo_di == "me"){//se il commento da stampare è stato postato da me, NON potrò mettere like al mio commento
              $status_replies .= '<div id="reply_'.$statusreplyid.'" class="reply_boxes"><div>'.$avatar.'<b> Commento inviato da te: </b><span class="timestamp">('.$replypostdate.')</span> '.$replyDeleteButton.'<br />'.$replydata.'</div></div>
              <img width="45" height="40" src="immagini/like.png" id="immagine_mi_piace'.$statusreplyid.'" alt="Mi Piace:" title="Mi Piace ottenuti"><span id="like_'.$statusreplyid.'"> '.$likes_commento.'</span>';
              }
            else{//se il commento da stampare NON è stato postato da me, potrò mettere like al commento dell'amico
              $status_replies .= '<div id="reply_'.$statusreplyid.'" class="reply_boxes"><div>'.$avatar.'<b> Commento di 
              <a href="PaginaPersonale.php?user='.$profilo_di.'">'.$replyauthor.'</a>: </b> <span class="timestamp">('.$replypostdate.')</span> '.$replyDeleteButton.'<br />'.$replydata.'</div></div>
              <span  onclick="nuovo_mi_piace_COMMENTO(\''.$statusreplyid.'\',\'reply_'.$statusreplyid.'\',\'like_'.$statusreplyid.'\',\''.$replyauthor.'\',\''.$my_username.'\');"
              title="Metti mi piace al commento"><img width="45" height="40" src="'.$bottone_like_commento.'" id="immagine_mi_piace'.$statusreplyid.'" alt="Mi Piace:"></span>
              <span id="like_'.$statusreplyid.'">'.$likes_commento.'</span>'; //numero di like, deve stare in uno span separato perchè viene incrementato con ajax come intero, va separato da stringa "Mi Piace:"
            }
        }
    }
	#AGGIORNO IN APPEND la $statuslist, (stampa effettiva infondo come stringa concatenata di TUTTI I POST CON TUTTI I RELATIVI COMMENTI)
	#che conterrà $statusDeleteButton e $status_replies relative al post interno al ciclo
	$profilo_di = "me"; //per redirecting sulla mia pagina se sono io l'autore
	$profilo_di = ($author == $_SESSION['username']) ? $profilo_di : $author;
  
  //require_once("recupera_avatar_funzione.php");
  $avatar = recupera_avatar($profilo_di);
  
  //se sono io l'autore di un post, stampo solo i like perchè non è possibile mettere mi piace a un proprio post
  if($profilo_di == "me"){
	$statuslist .= '<div id="status_'.$statusid.'" class="status_boxes"><div>'.$avatar.'<b> Postato da te: </b><span class="timestamp">('.$postdate.')</span> '.$statusDeleteButton.' <br />'.$data.'</div>
  <img width="45" height="40" src="immagini/like.png" alt="Mi Piace:" title="Mi Piace Ottenuti">  '.$likes //stampo solo i like perchè non è possibile mettere mi piace a un proprio post
  .''
  //infine aggiungo tutti i relativi commenti sottostanti il post
  . $status_replies.'</div>';
  }
  
  //se NON sono io l'autore di un post, stampo il pulsante Like per poter mettere mi piace a un post altrui
  else{
    $statuslist .= '<div id="status_'.$statusid.'" class="status_boxes"><div>'.$avatar.'<b> Postato da <a href="PaginaPersonale.php?user='.$profilo_di.'">'.$author.'</a>: </b> <span class="timestamp">('.$postdate.')</span> '.$statusDeleteButton.' <br />'.$data.
  '</div>
  <span onclick="nuovo_mi_piace_POST(\''.$statusid.'\',\'status_'.$statusid.'\',\'like_'.$statusid.'\',\''.$author.'\',\''.$my_username.'\');" title="Metti mi piace al post">
    <img width="45" height="40" src="'.$bottone_like_post.'" id="immagine_mi_piace'.$statusid.'" alt="Mi Piace:" title="Mi Piace Ottenuti">
  </span>
  <span id="like_'.$statusid.'">'.$likes.'</span>'
  .' <img width="30" height="30" src="immagini/report.png" alt="Segnala" title="Segnala" onclick="Segnala_Post(\''.$statusid.'\',\''.$data.'\',\''.$author.'\',\''.$my_username.'\');">'
  //infine aggiungo tutti i relativi commenti sottostanti il post
  . $status_replies.'</div>';
  }
  
	#stampo la textarea di input risposta
	    $statuslist .= '<textarea id="replytext_'.$statusid.'" class="replytext" onkeyup="statusMax(this,250)"
			placeholder="scrivi un commento"></textarea><br /><button class="bottoni_bacheca" id="replyBtn_'.$statusid.'" onclick="replyToStatus('.$statusid.',\''.$author.'\',\'replytext_'.$statusid.'\',this)">Rispondi</button>';	
}
?>

<!-- STAMPO LE STRINGHE TOTALI CONTENENTI POST E RELATIVI COMMENTI COME ELEMENTI HTML -->
<?php echo '<section class="bacheca"><h2 style="display: none">a</h2>'; ?>

<!-- box per nuovo post + bottone post -->
<div id="statusui">
  <?php echo $status_ui; ?> 
</div>

<!-- tutto cio che è sotto al box per nuovo post e al bottone Post, ovvero Post presenti nel DB e commenti -->
<div id="statusarea"> 
  <?php echo $statuslist; ?> 
</div>

<?php echo '</section>';?>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>
</body>
<script src="JS/main_script.js"> </script>
<script src="JS/ajax.js"> </script>
<script src="JS/logica_post_bacheca.js"> </script>
</html>

