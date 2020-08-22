<?php
  include_once("include/setup.php");
  include_once("include/recupera_avatar_funzione.php");
?>


<?php
/********* NUOVO POST **********/
if (isset($_POST['action']) && $_POST['action'] == "status_post"){
  
	// Mi assicuro che la sottomissione non sia vuota
	if(strlen($_POST['data']) < 1){
		mysqli_close($conn);
	    echo "campo vuoto!";
	    exit();
	}
	
	// Mi sincero che la tipologia del post sia effettivamente a o c
	if($_POST['type'] != "a" && $_POST['type'] != "c"){
		mysqli_close($conn);
	    echo "tipologia sconosciuta";
	    exit();
	}
	
	// Sanifico tutti i dati passati per _POST e inserisco in variabili locali
	$type = preg_replace('#[^a-z]#', '', $_POST['type']);
	$account_name = $_POST['user']; // che nel caso della bacheca l'autore sono per forza IO
	$data = htmlentities($_POST['data']);
	$data = mysqli_real_escape_string($conn, $data);
	
	// Inserisco il post nel DB
	$sql = "INSERT INTO post(account_name, author, type, data, postdate) 
			VALUES('$account_name','$account_name','$type','$data',now())"; //inserisco 2 volte account name perchè sono io stesso a postare sulla bacheca
	$query = mysqli_query($conn, $sql);
	$id = mysqli_insert_id($conn);
	mysqli_query($conn, "UPDATE post SET osid='$id' WHERE id='$id' LIMIT 1");
	
	$my_iduser = $_SESSION['iduser'];
  $avatar = recupera_avatar("me");
  
	// Invio notifica "nuovo post" agli amici dell'autore
  #1) recupero amici a cui mandare notifica
	$query_recupera_amici = "SELECT DISTINCT D.Amico AS iduserAmico, U.username AS usernameAmico
                          FROM(
                          SELECT DISTINCT A.iduser1 AS Amico
                          FROM Amicizie A INNER JOIN Users U ON (A.iduser1 = U.iduser OR A.iduser2 = U.iduser)
                          WHERE U.iduser = " . $my_iduser . "
                          
                          UNION 
                          
                          SELECT DISTINCT A.iduser2 AS Amico
                          FROM Amicizie A INNER JOIN Users U ON (A.iduser1 = U.iduser OR A.iduser2 = U.iduser)
                          WHERE U.iduser = " . $my_iduser . "
                          ) AS D INNER JOIN users U ON D.Amico = U.iduser
                          WHERE D.Amico <> " . $my_iduser . " ";
  
  $Risultato = mysqli_query($conn, $query_recupera_amici);

  $Quanti_Amici = mysqli_num_rows($Risultato);
  
  #ricavo timestamp al momento di postare
  $timestamp = date('Y-m-d H:i:s');
  
  #se l'utente non ha amici non invio nessuna notifica ovviamente
  if($Quanti_Amici > 0){
    #inserisco nella tabella notifiche la tipologia "nuovo post amico" per tutti gli amici di chi ha postato
      while($amico = mysqli_fetch_assoc($Risultato)){
        $query_nuova_notifica_post = "INSERT INTO notifiche(iduser_che_causa_la_notifica, iduser_destinatario_notifica, tipologia_notifica, timestamp_notifica) VALUES('".$my_iduser."','".$amico['iduserAmico']."','nuovo post amico','".$timestamp."')";
        $inserisci_nuova_notifica = mysqli_query($conn, $query_nuova_notifica_post);
      }
  }
	
  #ritorno al chiamante
	echo "post_ok|$id|$avatar";
	exit();
}
?>



<?php
/********* NUOVO COMMENTO **********/
//action=status_reply&osid="+osid+"&user="+user+"&data="+data
if (isset($_POST['action']) && $_POST['action'] == "status_reply"){
	// Make sure data is not empty
	if(strlen($_POST['data']) < 1){
		mysqli_close($conn);
	    echo "campo vuoto!";
	    exit();
	}
	
	// Sanifico variabili passate per argomento
	$osid = preg_replace('#[^0-9]#', '', $_POST['sid']);
	$account_name = /*preg_replace('#[^a-z0-9]#i', '', */$_POST['user']/*)*/;//$_POST['user'] = autore originario del post! chiunque esso sia
	$data = htmlentities($_POST['data']);
	$data = mysqli_real_escape_string($conn, $data);
	
  #L'autore sarò per forza io
   $my_username = $_SESSION['username'];
   $my_iduser = $_SESSION['iduser'];
   
	// Inserisco il commento nel DB
	//$account_name = autore originario del post! chiunque esso sia
	$sql = "INSERT INTO post(osid, account_name, author, type, data, postdate)
	        VALUES('$osid','$account_name','$my_username','b','$data',now())";
	$query = @mysqli_query($conn, $sql);
	$id = mysqli_insert_id($conn);
	
	// Invio notifica "nuovo commento su proprio post" all'autore
  #1) recupero l'id utente corrispondente all'autore del post originario ($account_name è lo username)
  $query_recupera_iduser_autore_post =
  "SELECT iduser
    FROM users
    WHERE username = '".$account_name."'";
  
  $risultato_query_iduser = @mysqli_query($conn, $query_recupera_iduser_autore_post);
  $iduser_autore_originario_post = mysqli_fetch_assoc($risultato_query_iduser);
  $avatar = recupera_avatar("me");
  #2) inserisco row nel DB, tabella notifiche, dove iduser_che_causa_la_notifica sarà $my_iduser
  #e iduser_destinatario_notifica sarà il risultato della query precedente, la tipologia è "nuovo commento su proprio post"
  #Ovviamente inserisco la notifica agli amici, e NON se commento su un MIO stato.
  if($iduser_autore_originario_post['iduser'] !== $my_iduser){
    
    $timestamp = date('Y-m-d H:i:s');
    
    $query_nuova_notifica_commento =
    "INSERT INTO notifiche(iduser_che_causa_la_notifica, iduser_destinatario_notifica, id_importato, tipologia_notifica, timestamp_notifica)
    VALUES('".$my_iduser."','".$iduser_autore_originario_post['iduser']."','".$osid."','nuovo commento su proprio post','".$timestamp."')";
    $Risultato = @mysqli_query($conn, $query_nuova_notifica_commento);
  }
  
  #ritorno al chiamante
	echo "commento_ok|$id|$avatar";
	exit();
}
?>



<?php
/********* ELIMINAZIONE POST **********/
if (isset($_POST['action']) && $_POST['action'] == "delete_status"){
	if(!isset($_POST['statusid']) || $_POST['statusid'] == ""){
		mysqli_close($conn);
		echo "id post mancante";
		exit();
	}
	$statusid = preg_replace('#[^0-9]#', '', $_POST['statusid']);
	// Check to make sure this logged in user actually owns that comment
	$query = mysqli_query($conn, "SELECT account_name, author FROM post WHERE id='$statusid' LIMIT 1");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$account_name = $row["account_name"]; 
		$author = $row["author"];
	}
    if ($author == $_SESSION['username'] || $account_name == $_SESSION['username']) {
		mysqli_query($conn, "DELETE FROM post WHERE osid='$statusid'");
	    echo "delete_ok";
		exit();
	}
}
?>


<?php
/********* LIKE COMMENTO **********/
if (isset($_POST['action']) && $_POST['action'] == "like_commento"){
	if(!isset($_POST['replyid']) || $_POST['replyid'] == ""){
		mysqli_close($conn);
		exit();
	}
  
	$replyid = preg_replace('#[^0-9]#', '', $_POST['replyid']);
  $mittente_mipiace = $_POST["mittente_mipiace"];
  $ricevitore_mipiace = $_POST["ricevitore_mipiace"];
  $my_iduser = $_SESSION['iduser'];
  
  /*Controllo se il like che X mette al commento di Y è già stato messo o meno:
    1) se è gia presente un like da parte di X sul commento di Y in questione => elimino il like dal DB e ritorno echo "rimuovi_like";
     per quanto riguarda il lato client controllo l'echo, se è "rimuovi_like", decremento il numero di like del commento in questione
    2) se non esiste una row nella tabella "likes" contenente un like da parte di X sul commento di Y, allora inserisco nel DB e ritorno echo "inserisci_like";
     per quanto riguarda il lato client controllo l'echo, se è "inserisci_like", incremento il numero di like del commento in questione
    */
    $comandoSQL_check_esistenza_likes = "SELECT COUNT(*) AS like_gia_messo
                                          FROM likes
                                          WHERE ricevitore_mipiace ='".$ricevitore_mipiace."'
                                            AND mittente_mipiace ='".$mittente_mipiace."'
                                            AND id_importato =".$replyid.";"; // si riferisce all'id del post 
      
      $risultato_like = mysqli_query($conn, $comandoSQL_check_esistenza_likes);
      $check_like = mysqli_fetch_assoc($risultato_like);
      
      $query_recupera_iduser_autore_post =
        "SELECT iduser
          FROM users
          WHERE username = '".$ricevitore_mipiace."'";
  
        $risultato_query_iduser = mysqli_query($conn, $query_recupera_iduser_autore_post);
        $iduser_autore_originario_post = mysqli_fetch_assoc($risultato_query_iduser);
        
    #Se esiste gia un like da parte di X sul commento di Y in questione, elimino il like e decremento il contatore con js lato client
    if($check_like["like_gia_messo"] >= 1){
      $comandoSQL_eliminazione_tabella_likes = "DELETE L.*
                                                  FROM likes L
                                                  WHERE ricevitore_mipiace ='".$ricevitore_mipiace."'
                                                    AND mittente_mipiace ='".$mittente_mipiace."'
                                                    AND id_importato ='".$replyid."';";
                                            
        //aggiorno campo like (totali) nella tabella Post
        mysqli_query($conn, "UPDATE post SET likes = likes - 1 WHERE id='$replyid'");
        //elimino anche row dalla tabella Likes dove sono mantenute le informazioni su chi riceve il like, da chi è provocato e
        // se si tratta di un like a un post o ad un commento.
        mysqli_query($conn, $comandoSQL_eliminazione_tabella_likes);
        
        $query_eliminazione_notifica_like = "DELETE N.*
                                            FROM notifiche N
                                            WHERE iduser_che_causa_la_notifica ='".$my_iduser."'
                                                AND iduser_destinatario_notifica ='".$iduser_autore_originario_post['iduser']."'
                                                AND tipologia_notifica ='nuovo like su proprio commento'
                                                AND id_importato ='".$replyid."';";
                                                
        mysqli_query($conn, $query_eliminazione_notifica_like);
        
     echo "rimuovi_like";
    }
    
    #Se NON esiste un like da parte di X sul commento di Y in questione o esisteva ed è stato eliminato,
    #inserisco/reinserisco il like e incremento il contatore con js lato client
    else if($check_like["like_gia_messo"] == 0){
    //con una insert in una tabella appoggio likes è piu facile la gestione delle notifiche per nuovi like in entrata
    	$comandoSQL_inserimento_tabella_likes_per_notifica = "INSERT INTO likes
      VALUES(NULL,".$replyid.",'b','".$ricevitore_mipiace."','".$mittente_mipiace."',now())";
      
      $comandoSQL_inserimento_nuovo_like_notifica = "INSERT INTO notifiche
      VALUES(NULL,'".$my_iduser."','".$iduser_autore_originario_post['iduser']."','".$replyid."','nuovo like su proprio commento', now())";
      
    //aggiorno campo like (totali) nella tabella Post, anche se informazione ridondante (e aggiornabile mediante trigger con semplice incremento)
    mysqli_query($conn, "UPDATE post SET likes = likes + 1 WHERE id='$replyid'");
    //inserisco quindi anche in una tabella Likes dove sono mantenute le informazioni su chi riceve il like, da chi è provocato e
    // se si tratta di un like a un post o ad un commento, in modo tale da gestire più facilmente le notifiche
    mysqli_query($conn, $comandoSQL_inserimento_tabella_likes_per_notifica);
    mysqli_query($conn, $comandoSQL_inserimento_nuovo_like_notifica);
        
    echo "inserisci_like"; //aggiungi perchè è la prima volta che si mette like o c'era ma era stato tolto
    }
    
	exit();
	}
?>


<?php
/********* LIKE POST **********/
if (isset($_POST['action']) && $_POST['action'] == "like_post"){
	if(!isset($_POST['statusid']) || $_POST['statusid'] == ""){
		mysqli_close($conn);
		exit();
	}
	$statusid = preg_replace('#[^0-9]#', '', $_POST['statusid']);
  $mittente_mipiace = $_POST["mittente_mipiace"];
  $ricevitore_mipiace = $_POST["ricevitore_mipiace"];
  $my_iduser = $_SESSION['iduser'];
  
    /*Controllo se il like che X mette al post di Y è già stato messo o meno:
    1) se è gia presente un like da parte di X sul post di Y in questione => elimino il like dal DB e ritorno echo "like_levato";
     per quanto riguarda il lato client controllo l'echo, se è "rimuovi_like", decremento il numero di like del post in questione
    2) se non esiste una row nella tabella "likes" contenente un like da parte di X sul post di Y, allora inserisco nel DB e ritorno echo "like_messo";
     per quanto riguarda il lato client controllo l'echo, se è "inserisci_like", incremento il numero di like del post in questione
    */
    	$comandoSQL_check_esistenza_likes = "SELECT COUNT(*) AS like_gia_messo
                                          FROM likes
                                          WHERE ricevitore_mipiace ='".$ricevitore_mipiace."'
                                            AND mittente_mipiace ='".$mittente_mipiace."'
                                            AND id_importato=".$statusid.";"; // si riferisce all'id del post 
      
      $risultato_like = mysqli_query($conn, $comandoSQL_check_esistenza_likes);
      $check_like = mysqli_fetch_assoc($risultato_like);
      
      $query_recupera_iduser_autore_post =
        "SELECT iduser
          FROM users
          WHERE username = '".$ricevitore_mipiace."'";
  
        $risultato_query_iduser = mysqli_query($conn, $query_recupera_iduser_autore_post);
        $iduser_autore_originario_post = mysqli_fetch_assoc($risultato_query_iduser);
      
    #Se esiste gia un like da parte di X sul post di Y in questione, elimino il like e decremento il contatore con js lato client
      if($check_like["like_gia_messo"] >= 1){
        
        $comandoSQL_eliminazione_tabella_likes = "DELETE L.*
                                                  FROM likes L
                                                  WHERE ricevitore_mipiace ='".$ricevitore_mipiace."'
                                                    AND mittente_mipiace ='".$mittente_mipiace."'
                                                    AND id_importato =".$statusid.";";
                                            
        //aggiorno campo like (totali) nella tabella Post
        mysqli_query($conn, "UPDATE post SET likes = likes - 1 WHERE id='$statusid'");
        //elimino anche row dalla tabella Likes dove sono mantenute le informazioni su chi riceve il like, da chi è provocato e
        // se si tratta di un like a un post o ad un commento.
        mysqli_query($conn, $comandoSQL_eliminazione_tabella_likes);
        
        $query_eliminazione_notifica_like = "DELETE N.*
                                            FROM notifiche N
                                            WHERE iduser_che_causa_la_notifica ='".$my_iduser."'
                                                AND iduser_destinatario_notifica ='".$iduser_autore_originario_post['iduser']."'
                                                AND tipologia_notifica ='nuovo like su proprio post'
                                                AND id_importato =".$statusid.";";
                                                
        mysqli_query($conn, $query_eliminazione_notifica_like);                  
        
        echo "rimuovi_like"; //leva perchè era gia stato premuto pulsante like e lo si preme per una seconda volta
      }
      
      #Se NON esiste un like da parte di X sul post di Y in questione o esisteva ed è stato eliminato,
      #inserisco/reinserisco il like e incremento il contatore con js lato client
      else if($check_like["like_gia_messo"] == 0){
        //con la insert in una tabella like è piu facile la gestione delle notifiche per nuovi like,
        //mantenendo sempre però la ridondanza aggiornata con trigger sui like totali nella tabella post
          $comandoSQL_inserimento_tabella_likes_per_notifica = "INSERT INTO likes
          VALUES(NULL,".$statusid.",'a','".$ricevitore_mipiace."','".$mittente_mipiace."', now())";
          
          $comandoSQL_inserimento_nuovo_like_notifica = "INSERT INTO notifiche
      VALUES(NULL,'".$my_iduser."','".$iduser_autore_originario_post['iduser']."','".$statusid."','nuovo like su proprio post', now())";
          
        //aggiorno campo like (totali) nella tabella Post
        mysqli_query($conn, "UPDATE post SET likes = likes + 1 WHERE id='$statusid'");
        //inserisco anche in una tabella Likes dove sono mantenute le informazioni su chi riceve il like, da chi è provocato e
        // se si tratta di un like a un post o ad un commento, in modo tale da gestire più facilmente le notifiche.
        mysqli_query($conn, $comandoSQL_inserimento_tabella_likes_per_notifica);
        mysqli_query($conn, $comandoSQL_inserimento_nuovo_like_notifica);
        
        echo "inserisci_like"; //aggiungi perchè è la prima volta che si mette like o c'era ma era stato tolto
      }
      
	exit();
	}
?>



<?php
/********* ELIMINAZIONE COMMENTO **********/
if (isset($_POST['action']) && $_POST['action'] == "delete_reply"){
	if(!isset($_POST['replyid']) || $_POST['replyid'] == ""){
		mysqli_close($conn);
		exit();
	}
	$replyid = preg_replace('#[^0-9]#', '', $_POST['replyid']);
	// Check to make sure the person deleting this reply is either the account owner or the person who wrote it
	$query = mysqli_query($conn, "SELECT osid, account_name, author FROM post WHERE id='$replyid' LIMIT 1");
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$osid = $row["osid"];
		$account_name = $row["account_name"];
		$author = $row["author"];
	}
    if ($author == $_SESSION['username'] || $account_name == $_SESSION['username']) {
		mysqli_query($conn, "DELETE FROM post WHERE id='$replyid'");
	    echo "delete_ok";
		exit();
	}
}
?>


<?php
/********* SEGNALAZIONE POST **********/
if (isset($_POST['action']) && $_POST['action'] == "segnalazione_post"){
	if(!isset($_POST['id_post']) || $_POST['id_post'] == ""){
		mysqli_close($conn);
		exit();
	}
	$id_post = $_POST['id_post'];
  $username_coinvolto = $_POST["username_coinvolto"];
  $contenuto_post = $_POST["contenuto_post"];
  $my_iduser = $_SESSION['username'];
  $motivazione = $_POST['motivazione'];
  
  $comandoSQL_check_esistenza_segnalazione = "SELECT COUNT(*) AS segnalazione_gia_inserita
                                              FROM segnalazioni
                                              WHERE username_mittente ='".$my_iduser."'
                                                AND username_coinvolto ='".$username_coinvolto."'
                                                AND id_post=".$id_post.";";
      
      $risultato = mysqli_query($conn, $comandoSQL_check_esistenza_segnalazione);
      $check_segnalazione = mysqli_fetch_assoc($risultato);
      
      if($check_segnalazione['segnalazione_gia_inserita'] == 0){
      
        $sql_inserimento_segnalazione = "INSERT INTO segnalazioni
                                          VALUES(NULL,'".$id_post."','".$contenuto_post."','".$my_iduser."','".$username_coinvolto."','".$motivazione."', 'in attesa');";
    
        $esecuzione_query = mysqli_query($conn, $sql_inserimento_segnalazione);
        
        if($esecuzione_query)
         echo "segnalazione_inserita";  
      }
      
      else{
        echo "gia_esistente";
      }
	}
?>