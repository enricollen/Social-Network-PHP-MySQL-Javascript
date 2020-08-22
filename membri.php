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
</head>

<body>

<?php

  include("include/header.php");
  include("include/modal/modalUtente.php");
  
	#Recupero variabili sessione
	$my_username = $_SESSION['username'];
  
  #mi ricavo il numero di utenti (escluso me) iscritti al forum 
  $query_numero_iscritti = "SELECT COUNT(*) AS NumeroIscritti
                            FROM users
                            WHERE username <> '" . $my_username . "' ";
  
  $risultato = @mysqli_query($conn, $query_numero_iscritti);
  $quanti_iscritti = mysqli_fetch_assoc($risultato);
  
  #se il numero di iscritti (escluso me visitatore) è >= 1, ovvero se esiste almeno un altro utente iscritto che non sia io
  if($quanti_iscritti['NumeroIscritti'] >=1){
    
    #Cerco tutti gli username degli altri utenti registrati
    $comandoSQL = "	SELECT DISTINCT username
                    FROM users
                    WHERE username <> '" . $my_username . "' ";
                    
    $RicercaMembri = @mysqli_query($conn, $comandoSQL);
    
    #se la query è sintatticamente corretta
    if($RicercaMembri){
      
        echo '<section class="membri">
              <h2>Membri</h2>';
              
        #row = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query
        #stampo ogni utente che non sia il visitatore (me)
        while($row = mysqli_fetch_assoc($RicercaMembri)){
            
          $recupera_genere = "SELECT genere
                              FROM users
                              WHERE username = '" . $row['username'] . "' ";
                                
            $risultato = @mysqli_query($conn, $recupera_genere);
            $genere = mysqli_fetch_assoc($risultato);
            
            if( ($genere['genere'] == "F") )
              echo '<img src="immagini/avatar3_femminile.png" alt="AvatarFemminile" height="42" width="42">';
            else if( ($genere['genere'] == "M") )
              echo '<img src="immagini/avatar2_maschile.png" alt="AvatarMaschile" height="42" width="42">';
              
          $usr = $row['username'];
          echo '<a href="PaginaPersonale.php?user=' . $usr . '">' . $usr . '</a><hr>' . $nl;
          
        }//chiude ciclo while per pescare tutti utenti registrati
        echo '</section>';
        
    }//chiude caso esecuzione query ricerca membri sintatticamente corretta
    
    #errore sintattico nell'esecuzione della ricerca membri registrati
    else{
      header("Location: paginaErrore.php?errore=errore_query");
    }
  
  }//chiude caso "almeno un utente oltre me è iscritto al social"
  
  #Altrimenti significa che io visitatore sono il primo iscritto!
  else {
    echo '<section class="membri">
          <h2>Membri</h2>
          <p>Al momento non risultano essere iscritti altri utenti oltre a te.<p></section>';
  }

?>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>
</body>
<script src="JS/main_script.js"> </script>
</html>