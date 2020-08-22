<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina dal login
  check_login();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title>PausaCaff&egrave</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_loggato.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_pagina_amici.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Utente.css">
</head>

<body>

<?php

  include("include/header.php");
  include("include/modal/modalUtente.php");
  
	#Recupero variabili sessione
	$my_username = $_SESSION['username'];
  $my_iduser = $_SESSION['iduser'];

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
	$query_amici = "SELECT DISTINCT D.Amico AS iduserAmico, U.username AS usernameAmico
                  FROM(
                  SELECT DISTINCT A.iduser1 AS Amico
                  FROM amicizie A INNER JOIN users U ON (A.iduser1 = U.iduser OR A.iduser2 = U.iduser)
                  WHERE U.iduser = " . $my_iduser . "
                  
                  UNION 
                  
                  SELECT DISTINCT A.iduser2 AS Amico
                  FROM amicizie A INNER JOIN users U ON (A.iduser1 = U.iduser OR A.iduser2 = U.iduser)
                  WHERE U.iduser = " . $my_iduser . "
                  ) AS D INNER JOIN users U ON D.Amico = U.iduser
                  WHERE D.Amico <> " . $my_iduser . " ";
                  
  $RicercaAmici = @mysqli_query($conn, $query_amici);
  
    if($RicercaAmici){
      
      echo '<div class="Banner"></div>
            <section class="amici">
            <p>';
            
      //row = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query
      while($row = mysqli_fetch_assoc($RicercaAmici)){
        
        $recupera_genere = "SELECT genere
                            FROM users
                            WHERE username = '" . $row['usernameAmico'] . "' ";
                              
          $risultato = @mysqli_query($conn, $recupera_genere);
          $genere = @mysqli_fetch_assoc($risultato);
          
          if( ($genere['genere'] == "F") )
            echo '<img src="immagini/avatar3_femminile.png" alt="AvatarFemminile" height="42" width="42">';
          else if( ($genere['genere'] == "M") )
            echo '<img src="immagini/avatar2_maschile.png" alt="AvatarMaschile" height="42" width="42">';
            
        $usr = $row['usernameAmico'];
        echo '<a href="PaginaPersonale.php?user=' . $usr . '">' . $usr . '</a>' . $nl;
      }
      echo '</p></section>';
      
    }//chiude correttezza sintattica query ricerca amici
    
    #errore sintattico nell'esecuzione della ricerca amici
    else{
      header("Location: paginaErrore.php?errore=errore_query");
    }
    
  }//chiude il caso "hai almeno un amico"
  
  #Altrimenti significa che non ho ancora stretto amicizia con nessuno!
  else {
    echo '<section class="amici">
          <h2>Amici</h2>
          <p>Non hai ancora stretto amicizia con nessun utente,
            scopri subito se <a href="membri.php">Conosci Qualcuno!</a><p></section>';
  }

?>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>
</body>
<script src="JS/main_script.js"> </script>
</html>

