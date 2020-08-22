<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina dal pannello admin
  check_login();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title>PausaCaff&egrave - Analisi Dati</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_pannello_admin.css">
</head>

<body>

<?php
include("include/header_admin.php");
?>
  
<section class="bacheca" id="section_analisi_dati">
    <h1 id="analisi_dati_titolo">Analisi Dati</h1>

    <!-- DIAGRAMMA 1: PRIME 3 CITTA DI PROVENIENZA DEGLI UTENTI ISCRITTI AL SOCIAL (in %) -->
    <div class="skill">
     <img style="float: right;" height="120" width="200" src="immagini/cities.png" alt="provenienza utenti" title="provenienza utenti">
     <h2 style="float: left; margin-top: 2em;">Provenienza Iscritti</h2>
<?php

    #Ricavo le citta di provenienza più diffuse sul social network
	$query_iscritti_x_citta =
	'SELECT D.Citta_Provenienza,
 concat(round((D.Numero_Iscritti * 100 / D.Totale_Iscritti),2),"%") AS Percentuale_Provenienza
FROM (
SELECT U.citta AS Citta_Provenienza, COUNT(DISTINCT U.iduser) AS Numero_Iscritti,
(SELECT COUNT(*) FROM users) AS Totale_Iscritti
	FROM users U
	GROUP BY U.citta
	ORDER BY Numero_Iscritti DESC
) AS D
LIMIT 3;';
																					
	$esecuzione = mysqli_query($conn, $query_iscritti_x_citta);
 $colori = array("#de00ff", "#20da02", "#ff8a00", "#ff4800", "#00d2ff", "pink");
 $i = 0;
 
    while ($row = mysqli_fetch_array($esecuzione, MYSQLI_ASSOC)) {
      $Citta_Provenienza = $row['Citta_Provenienza'];
      $Percentuale_Provenienza = str_replace('%', '', $row['Percentuale_Provenienza']);
      $lunghezza_barra = $Percentuale_Provenienza; // per adattare la lunghezza della barra colorata, moltiplico per 10
      
     echo '
       <div class="skill-container">
         <div class="skill-graph" style="width:'.$lunghezza_barra.'%;background:'.$colori[$i].'"></div>
         <p>'.$Citta_Provenienza.'</p>
         <p>'.$Percentuale_Provenienza.'%</p>
       </div>';
      $i++;
    }
 ?>
    </div>
   
  <!-- DIAGRAMMA 2: UTENTI PIU ATTIVI SUL SOCIAL (contando i post e commenti nell'ultima settimana) -->
    <div class="skill" id="contenitore2">
     <img id="utenti_piu_attivi" height="130" width="100" src="immagini/utenti_piu_attivi.png" alt="utenti piu attivi" title="utenti piu attivi">
<h2 style="float: right; margin-top: 2em; margin-right: 2em;">Utenti pi&ugrave attivi</h2>
<?php
 #Ricavo i tre utenti piu attivi nell'ultimo mese
	$query_classifica_utenti_piu_attivi =
	'SELECT P.account_name AS username_podio, COUNT(*) AS Numero_Interazioni
	FROM post P
	WHERE P.postdate BETWEEN now() - INTERVAL 1 MONTH AND now()
	GROUP BY P.account_name
	ORDER BY Numero_Interazioni DESC
	LIMIT 3;';
																					
	$esecuzione1 = mysqli_query($conn, $query_classifica_utenti_piu_attivi);
	$j = 5;
 echo'';
    while ($row1 = mysqli_fetch_array($esecuzione1, MYSQLI_ASSOC)) {
      $username_podio = $row1['username_podio'];
      $Numero_Interazioni = $row1['Numero_Interazioni'];
      $lunghezza_barra = $Numero_Interazioni * 5; // per adattare la lunghezza della barra colorata, moltiplico per 10
      
     echo '
       <div class="skill-container">
         <div class="skill-graph" style="width:'.$lunghezza_barra.'%;background:'.$colori[$j].'"></div>
         <p>'.$username_podio.'</p>
         <p>'.$Numero_Interazioni.'</p>
       </div>';
      $j--;
    }
?>
    
 </div>

</section>

</body>
<script src="JS/main_script.js"> </script>
<script src="JS/ajax.js"> </script>
<script src="JS/pannello_admin.js"> </script>
</html>