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
  <link rel="stylesheet" type="text/css" href="CSS/stile_pagina_richieste_amicizia.css">
  <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Utente.css">
</head>

<body>

<?php

  include("include/header.php");
  include("include/modal/modalUtente.php");
  
	#Recupero variabili sessione
	$my_username = $_SESSION['username'];
	$my_iduser = $_SESSION['iduser'];
  
  #preparo la prima colonna di sinistra
        echo '<div class="Banner"></div>
              <section class="richiesteamicizia">
              <div class="row">
              <div class="column" style="background-color:#f7e1cf;">
              <h3>Richieste di Amicizia Ricevute</h3>';
      
    ########## SE SONO IO IL DESTINATARIO DI RICHIESTE ############
    
    $query_amicizie_pendenti = 'SELECT COUNT(*) AS QuanteAmiciziePendenti_Ricevute
                                FROM richiesteamicizia
                                WHERE iduser_destinatario = "' . $my_iduser . '"';
  
    $risultato = @mysqli_query($conn, $query_amicizie_pendenti);
    $Quante_Amicizie_Pendenti = @mysqli_fetch_assoc($risultato);
  
  #se il numero di richieste che ho ricevuto è >= 1,
  #ovvero se ho almeno un altro utente che ha richiesto la mia amicizia
  if($Quante_Amicizie_Pendenti['QuanteAmiciziePendenti_Ricevute'] >=1){
    
    #allora posso ricavarmi gli iduser di tutti gli utenti che mi hanno inviato una richiesta di amicizia (ordinati dal piu recente)
    $query_richieste_ricevute_in_sospeso = 'SELECT DISTINCT RA.iduser_richiedente, U.username AS username_richiedente, data_invio_richiesta
                                            FROM richiesteamicizia RA INNER JOIN users U ON RA.iduser_richiedente = U.iduser
                                            WHERE RA.iduser_destinatario = "' . $my_iduser . '"
                                            ORDER BY data_invio_richiesta DESC';
                              
    $Ricerca_Richiedenti_Richieste = @mysqli_query($conn, $query_richieste_ricevute_in_sospeso);
  
    if($Ricerca_Richiedenti_Richieste){
      
      $contatore = 1;
      //$potenziale_amico = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query
      while($potenziale_amico = mysqli_fetch_assoc($Ricerca_Richiedenti_Richieste)){
        
        $recupera_genere = "SELECT genere
                            FROM users
                            WHERE username = '" . $potenziale_amico['username_richiedente'] . "' ";
                              
          $risultato = @mysqli_query($conn, $recupera_genere);
          $genere = @mysqli_fetch_assoc($risultato);
          $data_richiesta = $potenziale_amico['data_invio_richiesta'];
          #è necessario per ogni richiesta pendente ricevuta, riaprire il form per l'invio dell'username e iduser,
          #in modo che ogni bottone "accetta/rifiuta amicizia" accetti/rifiuti la richiesta relativa all'utente giusto
          echo '<form action="include/eGestioneAmicizie.php" method="POST"><p>
                <div class="row">
                <div class="column">
                <div class="card">
                <h3 style="color: #000">Richiesta ' . $contatore . $nl . $data_richiesta . '</h3>';
          
          #stampo avatar in base al genere
          if( ($genere['genere'] == "F") )
            echo '<div><img src="immagini/avatar3_femminile.png" alt="AvatarFemminile" height="42" width="42"></div>';
          else if( ($genere['genere'] == "M") )
            echo '<div><img src="immagini/avatar2_maschile.png" alt="AvatarMaschile" height="42" width="42"></div>';
        
        #stampo bottoni accetta/rifiuta
        $usr = $potenziale_amico['username_richiedente'];
        echo '<a href="PaginaPersonale.php?user=' . $usr . '">' . $usr . '</a>' . $nl .
              '<input type="submit" class="AccettaRichiestaAmicizia" name="AccettaRichiestaAmicizia" value="Accetta">
              <input type="submit" class="RifiutaRichiestaAmicizia" name="RifiutaRichiestaAmicizia" value="Rifiuta">' . $nl;
              
        #silenziosamente (display:none) inserisco nella form anche l'iduser e l'username dell'utente che stiamo visitando,
        #in modo che,se si preme un qualsiasi bottone input ("rimuovi","invia/annulla richiesta"), nella pagina che elabora vengano
        #passati anche questi valori altrimenti irrecuperabili!
        echo '<input type="text" name="iduser_visitato" value="' . $potenziale_amico['iduser_richiedente'] . '" style="display:none">
              <input type="text" name="username_visitato" value="' . $potenziale_amico['username_richiedente'] . '" style="display:none">';
        
        #è necessario per ogni richiesta pendente nel ciclo, chiudere il form  
        echo '</p></form></div></div></div>';
        $contatore = $contatore + 1;
      }//chiude while che pesca tutte le richieste di amicizia da me inviate a altri
      
      #chiudo colonna 1
      echo '</div>';
      
    }//chiude correttezza sintattica query ricerca degli utenti che hanno richiesto a me l'amicizia
    
    #errore sintattico nell'esecuzione della ricerca degli utenti che hanno richiesto a me l'amicizia
    else{
      header("Location: paginaErrore.php?errore=errore_query");
    }

  }//chiude il caso "hai almeno una richiesta DA PARTE DI UN ALTRO UTENTE"
  
  #Altrimenti significa che non hai nessuna richiesta di amicizia RICEVUTA pendente! chiudo colonna 1
  else {
    echo '<p>Al momento non sembra esserci nessuna richiesta di amicizia, controlla pi&ugrave tardi.<p></div>';
  }
  
  ########## CHIUDE SE SONO IO IL DESTINATARIO DI RICHIESTE ############
  
      #preparo 2 colonna di destra
      echo '<div class="column" style="background-color:#f7e1cf;">
      <h3>Richieste di Amicizia Inviate</h3>';
      
  ########## SE SONO IO IL MITTENTE DI RICHIESTE #######################

  #mi ricavo il numero di richieste di amicizia PENDENTI che mi riguardano: INVIATE (in attesa di esito) e RICEVUTE
  $query_amicizie_pendenti = 'SELECT COUNT(*) AS QuanteAmiciziePendenti_Inviate
                              FROM richiesteamicizia
                              WHERE iduser_richiedente = "' . $my_iduser . '"';
  
  $risultato = @mysqli_query($conn, $query_amicizie_pendenti);
  $Quante_Amicizie_Pendenti = @mysqli_fetch_assoc($risultato);
  
    
  #se il numero di richieste che mi riguardano è >= 1,
  #ovvero se ho almeno una richiesta di amicizia in sospeso che mi riguarda
  if($Quante_Amicizie_Pendenti['QuanteAmiciziePendenti_Inviate'] >=1){
    
    #allora posso ricavarmi gli iduser di tutti gli utenti ai quali ho inviato la richiesta di amicizia
    $query_richieste_inviate_in_sospeso = ' SELECT DISTINCT RA.iduser_destinatario, U.username AS username_destinatario, data_invio_richiesta
                                            FROM richiesteamicizia RA INNER JOIN users U ON RA.iduser_destinatario = U.iduser
                                            WHERE RA.iduser_richiedente = "' . $my_iduser . '"
                                            ORDER BY data_invio_richiesta DESC';
                                
    $Ricerca_Destinatari_Mie_Richieste = @mysqli_query($conn, $query_richieste_inviate_in_sospeso);
    
      if($Ricerca_Destinatari_Mie_Richieste){
        
        $contatore = 1;
        //$potenziale_amico = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query
        while($potenziale_amico = mysqli_fetch_assoc($Ricerca_Destinatari_Mie_Richieste)){
          
          $recupera_genere = "SELECT genere
                              FROM users
                              WHERE username = '" . $potenziale_amico['username_destinatario'] . "' ";
                                
            $risultato = @mysqli_query($conn, $recupera_genere);
            $genere = @mysqli_fetch_assoc($risultato);
            $data_richiesta = $potenziale_amico['data_invio_richiesta'];
            #è necessario per ogni richiesta pendente, riaprire il form per l'invio dell'username e iduser, in modo che ogni
            #bottone "annulla richiesta amicizia" annulli la richiesta relativa all'utente giusto
            echo '<form action="include/eGestioneAmicizie.php" method="POST"><p>
            <div class="row">
            <div class="column">
            <div class="card">
            <h3 style="color: #000">Richiesta ' . $contatore . $nl . $data_richiesta . '</h3>';
            
            #stampo avatar in base al genere
            if( ($genere['genere'] == "F") )
              echo '<div><img src="immagini/avatar3_femminile.png" alt="AvatarFemminile" height="42" width="42"></div>';
            else if( ($genere['genere'] == "M") )
              echo '<div><img src="immagini/avatar2_maschile.png" alt="AvatarMaschile" height="42" width="42"></div>';
          
          #stampo messaggio richiesta in attesa
          $usr = $potenziale_amico['username_destinatario'];
          echo '<a href="PaginaPersonale.php?user=' . $usr . '">' . $usr . '</a>' . $nl .
                'Richiesta in attesa di risposta...
                <input type="submit" class="BottoneAnnullaRichiesta" name="BottoneAnnullaRichiesta"
                value="Annulla Richiesta Amicizia">' . $nl;
                
          #silenziosamente (display:none) inserisco nella form anche l'iduser e l'username dell'utente che stiamo visitando,
          #in modo che,se si preme un qualsiasi bottone input ("rimuovi","invia/annulla richiesta"), nella pagina che elabora vengano
          #passati anche questi valori altrimenti irrecuperabili!
          echo '<input type="text" name="iduser_visitato" value="' . $potenziale_amico['iduser_destinatario'] . '" style="display:none">
                <input type="text" name="username_visitato" value="' . $potenziale_amico['username_destinatario'] . '" style="display:none">';
          
          #è necessario per ogni richiesta pendente nel ciclo, chiudere il form  
          echo '</p></form></div></div></div>';
          $contatore = $contatore + 1;
        }//chiude while che pesca tutte le richieste di amicizia da me inviate a altri
        
        #chiudo la sezione
        #echo '</section>';
        
        }//chiude correttezza sintattica query ricerca degli utenti ai quali ho richiesto l'amicizia
      
      #errore sintattico nell'esecuzione della ricerca degli utenti ai quali ho richiesto l'amicizia
      else{
        header("Location: paginaErrore.php?errore=errore_query");
      }
    #chiusura della colonna 2, caso hai richieste
    echo '</div>';
  }//chiude il caso "hai INVIATO ALMENO UNA RICHIESTA ad un altro utente"
  
  #Altrimenti significa che non hai nessuna richiesta di amicizia INVIATA pendente! chiudo div colonna e la sezione che contiene le 2 colonne
  else {
    echo '<p>Non hai inviato nessuna richiesta di amicizia.<p></div></div></section>';
  }
  
    ########## CHIUDE SE SONO IO IL MITTENTE DI RICHIESTE #########

?>


</body>
<script src="JS/main_script.js"> </script>
</html>