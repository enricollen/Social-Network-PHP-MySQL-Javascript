<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina da loggati, svento eventuali tentativi bypass
  check_login();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title>PausaCaff&egrave</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_loggato.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Utente.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_pagina_personale.css">
    
<script>
  // funzioni per comparsa/scomparsa sezioni 
function mostra_sezioni(bottone){
  if(bottone == "Galleria"){
	_("sezione_galleria").style.display = "block";
	_("sezione_informazioni").style.display = "none";
  }
  else if(bottone == "Informazioni"){
	_("sezione_informazioni").style.display = "block";
	_("sezione_galleria").style.display = "none";
  }
}

</script>
    
</head>

<body>

<?php
#Controllo se si arriva a questa pagina da loggati
if(	isset($_SESSION['iduser'])	){

  include("include/header.php");
  include("include/modal/modalUtente.php");
  
	#Recupero variabili sessione
	$profilo_di = $_GET['user'];
	$my_username = $_SESSION['username'];
  $my_iduser = $_SESSION['iduser'];
  $username_target = ($profilo_di == "me") ? $my_username : $profilo_di;
}
?>

<div class="header">
			<a href="#" class="aside-trigger"><span></span></a>
		</div>
		<div class="side-aside">
			
			<aside>
				<ul>
          
					<li>
            <div class="abbassa">
						<a href="#">
							<span><i class="fa fa-user"></i></span>
							<span onclick="mostra_sezioni('Informazioni');">Informazioni</span>
						</a>
            </div>
					</li>
					<li>
						<a href="#">
							<span><i class="fa fa-bar-chart"></i></span>
							<span onclick="mostra_sezioni('Galleria');">Galleria</span>
						</a>
					</li>
    
					
        <?php
        //visita al profilo di utenti terzi
        if(isset($profilo_di) && $profilo_di !== "me"){
        echo '<li>
              <a href="#">
              <span><i class="fa fa-credit-card-alt"></i></span>
              <span>';
        #recupero l'id dell'utente di cui si sta visitando il profilo
        $recupero_iduser_utente_visitato = "	SELECT iduser
                                              FROM users
                                              WHERE username = '" . $profilo_di . "' ";
        

        $risultato = mysqli_query($conn, $recupero_iduser_utente_visitato);
        $iduser_visitato = mysqli_fetch_assoc($risultato);
        $iduser_target = $iduser_visitato['iduser'];
        
          echo '<form action="include/eGestioneAmicizie.php" method="POST">
          <input type="text" name="iduser_visitato" value="' . $iduser_target . '" style="display:none">
          <input type="text" name="username_visitato" value="' . $username_target . '" style="display:none">';
        //controllo se l'utente che visita il profilo è gia amico dell utente di cui sta visitando il profilo,
        // o VICERVERSA! infatti un amicizia è bivalente, dipende da come è stata registrata nel DB (user1 e user2 / user 2 e user1)
        $query_amicizia = ' SELECT COUNT(*) AS AmiciziaTrovata
                            FROM amicizie
                            WHERE (
                                    (iduser1 = "' . $_SESSION['iduser'] . '"
                                     AND iduser2 = "' . $iduser_target . '")
                                      OR
                                    (iduser2 = "' . $_SESSION['iduser'] . '"
                                     AND iduser1 = "' . $iduser_target . '")
                                  )';
        
        $risultato = mysqli_query($conn, $query_amicizia);
        #se 1 amici se 0 non amici
        $gia_amici = mysqli_fetch_assoc($risultato);
        
        #Guardo se la variabile d'appoggio che cerca l'amicizia tra i due utenti contiene 1 o 0 e
        #in base a ciò faccio comparire bottoni diversi
        
        #Se non sono amici
        if($gia_amici['AmiciziaTrovata'] == 0){
          
          #controllo se tra i due esiste una richiesta di amicizia in attesa di risposta
          $richieste_amicizia_pendenti = ' SELECT IFNULL(iduser_richiedente, NULL) AS Richiedente
                                           FROM richiesteamicizia
                                           WHERE (
                                                  (iduser_richiedente = "' . $_SESSION['iduser'] . '"
                                                   AND iduser_destinatario = "' . $iduser_target . '")
                                                   OR
                                                   (iduser_destinatario = "' . $_SESSION['iduser'] . '"
                                                   AND iduser_richiedente = "' . $iduser_target . '")
                                                )';
        
          $risultato = mysqli_query($conn, $richieste_amicizia_pendenti);
          
          #se esiste una richiesta $esiste_richiesta_amicizia['Richiedente'] conterrà il nome del mittente della richiesta
          $esiste_richiesta_amicizia = mysqli_fetch_assoc($risultato);
          
          #se ci sono richieste in sospeso,
          if($esiste_richiesta_amicizia['Richiedente'] !== NULL){
            
            #salvo in variabile locale il mittente della richiesta
            $iduser_richiedente = $esiste_richiesta_amicizia['Richiedente'];
            
            #devo chiedermi se chi visita il profilo è colui che ha INVIATO la richiesta o che ha RICEVUTO la richiesta:
            #se chi visualizza è il MITTENTE DELLA RICHIESTA
              if($my_iduser == $iduser_richiedente){
                echo 'Richiesta in attesa di risposta...
                      <input type="submit" class="BottoneAnnullaRichiesta" name="BottoneAnnullaRichiesta" onclick="prompt_successo()"
                      value="Annulla Richiesta Amicizia"></form>';
              }
            #se invece chi visualizza è il DESTINATARIO DI UNA RICHIESTA DI AMICIZIA da parte del profilo che sta visitando
              else{
                echo '<input type="submit" class="AccettaRichiestaAmicizia" name="AccettaRichiestaAmicizia" onclick="prompt_successo()"
                      value="Accetta">
                      <input type="submit" class="RifiutaRichiestaAmicizia" name="RifiutaRichiestaAmicizia" onclick="prompt_successo()"
                      value="Rifiuta"></form>';
              }
          
          }//chiude caso "non sono amici MA ci sono richieste in sospeso"
          
          #se NON ci sono richieste in sospeso mostra bottone Richiedi Amicizia
          else{
            echo '<input type="submit" class="BottoneRichiediAmicizia" name="BottoneRichiediAmicizia" onclick="prompt_successo()"
                  value="Richiedi Amicizia"></form>';
          }
          
        }// chiude il caso "se non sono amici"
        
        
        #Se sono amici
        else{
          echo '<input type="submit" class="BottoneRimuoviAmicizia" name="BottoneRimuoviAmicizia" onclick="prompt_successo()"
                value="Rimuovi dagli Amici">
                </form>';
        }
        echo '</li></span>';
        }
        ?>
              
						</a>
					</li>
				</ul>
			</aside>
		</div>
		<div class="main-content">
			<div class="title">
				<p style="font-size: 16px;">Profilo di <?php echo $username_target ?></p>
			</div>
      
      <!-- SEZIONE STANDARD, INFORMAZIONI DEL PROFILO -->
			<div class='ad' id="sezione_informazioni">
				<?php
        require_once("include/recupera_avatar_funzione.php");
          $avatar = recupera_avatar($username_target);
          echo '<div style="margin-left: 1em; margin-top: 1em;">'.$avatar.'</div>';
          $informazioni = recupera_informazioni_profilo($username_target);
          echo '<div style="margin-left: 1em; margin-top: 1em;">'.$informazioni.'</div>';
        ?>
			</div>
      
      <!-- SEZIONE A COMPARSA SU CLICK "Galleria" -->
      <div class='ad' id="sezione_galleria" style="display: none;">
        <h2 style="margin: 1em;">Galleria</h2>
        <?php recupera_galleria($username_target); ?>
      </div>
      
			<div class="main">
        
				<div class="widget">
					<div class="title">Amici</div>
					<div class="chart">
<?php
$recupero_iduser_utente_visitato = "	SELECT iduser
                                      FROM users
                                      WHERE username = '" . $username_target . "' ";
        

        $risultato = mysqli_query($conn, $recupero_iduser_utente_visitato);
        $iduser_visitato = mysqli_fetch_assoc($risultato);
        $iduser_target = $iduser_visitato['iduser'];
  #mi ricavo il numero degli utenti con i quali ho stretto amicizia
  $query_ho_almeno_1_amico = 'SELECT COUNT(*) AS QuantiAmici
                              FROM amicizie
                              WHERE (
                                    iduser1 = "' . $my_iduser . '"
                                    OR iduser2 = "' . $my_iduser . '"
                                    )';
  
  $risultato = @mysqli_query($conn, $query_ho_almeno_1_amico);
  $Ho_Almeno_Un_Amico = @mysqli_fetch_assoc($risultato);
  
  #se il numero di miei amici (consolidati, non pending) è >= 1,
  #ovvero se ho almeno un amico
  if($Ho_Almeno_Un_Amico['QuantiAmici'] >=1){
    
	#allora posso ricavarmi gli iduser di tutti gli amici
	$query_amici = "SELECT *
                  FROM(
                  SELECT DISTINCT D.Amico AS iduserAmico, U.username AS usernameAmico
                  FROM(
                  SELECT DISTINCT A.iduser1 AS Amico
                  FROM amicizie A INNER JOIN users U ON (A.iduser1 = U.iduser OR A.iduser2 = U.iduser)
                  WHERE U.iduser = " . $iduser_target . "
                  
                  UNION 
                  
                  SELECT DISTINCT A.iduser2 AS Amico
                  FROM amicizie A INNER JOIN users U ON (A.iduser1 = U.iduser OR A.iduser2 = U.iduser)
                  WHERE U.iduser = " . $iduser_target . "
                  ) AS D INNER JOIN users U ON D.Amico = U.iduser
                  WHERE D.Amico <> " . $iduser_target . "
                  ) AS D2
                  LIMIT 4";
                  
  $RicercaAmici = mysqli_query($conn, $query_amici);
      
  echo '<p>';
            
      //row = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query
      while($row = mysqli_fetch_assoc($RicercaAmici)){ 
        $usr = $row['usernameAmico'];
        $avatar = recupera_avatar($usr);
          echo $avatar;
        if($row['usernameAmico'] == $my_username)
        echo $usr . $nl;
        else
        echo '<a href="PaginaPersonale.php?user=' . $usr . '">' . $usr . '</a>' . $nl;
      }
      echo '</p>';
    }
    
    else {
    echo '<p> Nessuna amicizia<p>';
  }
    ?>
          </div>
				</div>
				<div class="widget">
					<div class="title">Attivit&agrave Recenti</div>
					<div class="chart">
            <?php
                $testo_attivita = recupera_attivita_recenti($username_target);
                if(strpos($testo_attivita, '9656') == false) echo " Nessuna attivit&agrave recente";
                else echo $testo_attivita;
            ?>
          </div>
				</div>
				<div class="widget">
					<div class="title">Interessi</div>
					<div class="chart">
            <?php
                $testo_interessi = recupera_interessi($username_target);
                if(strpos($testo_interessi, '9656') == false) echo "Ancora nessun interesse specificato";
                else echo $testo_interessi;
            ?>
          </div>
				</div>
			</div>
		</div>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>

<?php
/****** FUNZIONE PER RECUPERARE LE ATTIVITA' RECENTI DEL PROFILO UTENTE VISITATO *****/
  function recupera_attivita_recenti($username_target){
    $query_attivita = "SELECT '".$username_target."',
                    case 
                      when tipologia_notifica='nuovo commento su proprio post' then 'risposto con un commento a '
                      else 'nuovo like per '
                    end as testo_notifica, 
                   if(iduser_che_causa_la_notifica = (select iduser from users where username = '".$username_target."'),
                     (SELECT username from users where iduser = iduser_destinatario_notifica),
                       (SELECT username from users where iduser = iduser_che_causa_la_notifica)) AS username_amico,
                     timestamp_notifica
                   FROM notifiche N INNER JOIN users U ON N.iduser_che_causa_la_notifica = U.iduser
                   WHERE tipologia_notifica <> 'nuovo post amico'
                     AND((iduser_che_causa_la_notifica = (select iduser from users where username = '".$username_target."')
                       AND (tipologia_notifica = 'nuovo like su proprio post' 
                         OR tipologia_notifica = 'nuovo like su proprio commento' OR tipologia_notifica = 'nuovo commento su proprio post'))
                           )
                   
                  UNION 
                  
                  SELECT '".$username_target."', 'amicizia stretta con ',
                  if(iduser1 = (select iduser from users where username = '".$username_target."'),
                  (SELECT username from users where iduser = iduser2),
                  (SELECT username from users where iduser = iduser1)) as username_amico, data_inizio_amicizia as timestamp_notifica
                  from amicizie 
                  where iduser1 = (select iduser from users where username = '".$username_target."')
                    or iduser2 = (select iduser from users where username = '".$username_target."')
                  order by timestamp_notifica DESC
                  limit 6;";
    
    $conn = $GLOBALS['conn'];
    $esecuzione = @mysqli_query($conn, $query_attivita);
    
    $ritorno = "<p style='font-size: 13px; font-family: fantasy;'>";
    while ($risultato = mysqli_fetch_array($esecuzione, MYSQLI_ASSOC)){
    $ritorno .= "&#9656; ".$risultato['testo_notifica'];
    $ritorno .= $risultato['username_amico']." ";
    $ritorno .= "<span style='font-size: 9px;'>(".$risultato['timestamp_notifica'].")</span>". "<br /><br />";
    }
    
    return $ritorno."</p>";
  }
  
  /****** FUNZIONE PER RECUPERARE I DATI DEL PROFILO UTENTE VISITATO *****/
  function recupera_informazioni_profilo($username_target){
    $query_recupero = "SELECT nome, cognome, citta, eta, occupazione, stato_sentimentale
                      FROM users U NATURAL JOIN informazioni I
                      WHERE username='".$username_target."'";
    
    $conn = $GLOBALS['conn'];
    $esecuzione = @mysqli_query($conn, $query_recupero);
    
    $ritorno = "<p style='font-size: 20px; line-height: normal; font-family: fantasy;'>";
    while ($risultato = mysqli_fetch_array($esecuzione, MYSQLI_ASSOC)){
    $ritorno .= "<b>Nome: </b>".$risultato['nome']."<br /><br /><b>Cognome: </b>".$risultato['cognome']."<br /><br /><b>Citt&agrave: </b>".
    $risultato['citta']."<br /><br /><b>Et&agrave: </b>".$risultato['eta']."<br /><br /><b>Occupazione: </b>".$risultato['occupazione']."<br /><br /><b>Stato: </b>".$risultato['stato_sentimentale']."<br/>" ;
    }
    
    return $ritorno."</p>";
  }
  
  
  /****** FUNZIONE RECUPERA INTERESSI DEL PROFILO UTENTE VISITATO ******/
  
  function recupera_interessi($username_target){
    $query_interessi = "SELECT interesse
                        FROM interessi
                        WHERE username='".$username_target."';";
    
    $conn = $GLOBALS['conn'];
    $esecuzione = @mysqli_query($conn, $query_interessi);
    
    $ritorno = "<p style='font-size: 20px; font-family: fantasy;'>";
    while ($risultato = mysqli_fetch_array($esecuzione, MYSQLI_ASSOC)){
    $ritorno .= "&#9656; ".$risultato['interesse']."<br />";
    }
    
    return $ritorno."</p>";
  }
  
  /****** FUNZIONE RECUPERA IMMAGINI GALLERIA DEL PROFILO UTENTE VISITATO ******/
  
  function recupera_galleria($username_target){
      
      $conn = $GLOBALS['conn'];
      // Prelievo immagini da database
      $query = mysqli_query($conn,"SELECT * FROM album_foto WHERE username='".$username_target."' ORDER BY id DESC");
      $rowcount=mysqli_num_rows($query);
      $contatore = 0;
      if($rowcount > 0){
          while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
              $imageURL = 'immagini/album/'.$row["file_name"];
      ?>
          <img src="<?php echo $imageURL; ?>" class="immagini_galleria" alt="<?php echo $username_target."_".$contatore; ?>" />
      <?php $contatore++;}
      }
      else{ 
          echo '<p style="margin: 5px;">Nessuna immagine ancora caricata...</p>';
      }
  }
?>
</body>
<script src="JS/main_script.js"> </script>
</html>