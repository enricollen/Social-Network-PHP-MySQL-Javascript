<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina dal login ADMIN
  check_login();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title>PausaCaff&egrave - Pannello Admin</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_pannello_admin.css">
</head>

<body>

<?php
include("include/header_admin.php");
?>
  
<section class="bacheca">
    <h6 class="segnalazioni_titolo">Segnalazioni</h6>
 <?php
          
  #Raccolgo tutte le segnalazioni
   $sql = "SELECT S.*
           FROM segnalazioni S
           WHERE stato_segnalazione ='in attesa' OR stato_segnalazione ='visionata';";
           
   $query = mysqli_query($conn, $sql);
   if(!$query){
     header("Location: paginaErrore.php?errore=errore_query");
   }

    if(mysqli_num_rows($query) > 0){
      
        echo '<table id="tabella">
          <thead>
            <tr>
              <th>ID Segnalazione</th>
              <th>ID Post</th>
              <th>Contenuto Post</th>
              <th>Username Mittente</th>
              <th>Username Coinvolto</th>
              <th>Tipologia Segnalazione</th>
              <th>Azioni</th>
            </tr>
          </thead>
          <tbody>';
      
    $costruzione_tabella = '';
    $i = 1;
    
    #while esterno per gathering post
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
      $id_segnalazione = $row["idsegnalazione"];
      $id_post = $row["id_post"];
      $contenuto_post = $row["contenuto_post"];
      $username_mittente = $row["username_mittente"];
      $username_coinvolto = $row["username_coinvolto"];
      $tipologia = $row["tipologia_segnalazione"];
      $stato_segnalazione = $row["stato_segnalazione"];
      
        $costruzione_tabella .= '<tr id="riga_'.$i.'">
                                  <td data-column="ID Segnalazione">'.$id_segnalazione.'</td>
                                  <td data-column="ID Post">'.$id_post.'</td>
                                  <td data-column="Contenuto Post" style="font-size: 14px;">'.$contenuto_post.'</td>
                                  <td data-column="Username Mittente">'.$username_mittente.'</td>
                                  <td data-column="Username Coinvolto">'.$username_coinvolto.'</td>
                                  <td data-column="Tipologia Segnalazione">'.$tipologia.'</td>
                                  <td data-column="Azioni">
                                   <img width="20" height="20" onclick="nascondi_riga_tabella_e_aggiorna_stato_segnalazione('.$i.', '.$id_segnalazione.',\'ignora\');" src="immagini/V.png" alt="V" title="Mantieni Post">
                                   <img width="20" height="20" onclick="nascondi_riga_tabella_e_aggiorna_stato_segnalazione('.$i.', '.$id_segnalazione.',\'elimina\','.$id_post.');" src="immagini/X.png" alt="X" title="Elimina Post">
                                  </td>
                                </tr>';
        $i++;
    }
    
    echo $costruzione_tabella.'</tbody></table>
    <button onclick="location.href = \'Pannello_Admin.php\';" id="bottone_indietro">Indietro</button>';
  }
  //se non ci sono segnalazioni o sono tutte state risolte stampo immagine
  else{
    echo '<img src="immagini/no_report2.png" alt="nessuna segnalazione" id="nessuna_segnalazione" title="nessuna segnalazione">
          <p id="nessuna_segnalazione_testo">Nessuna segnalazione al momento</p>
          <button onclick="location.href = \'Pannello_Admin.php\';" id="nessuna_segnalazione_bottone">Indietro</button>';
  }
?>

</section>

</body>
<script src="JS/main_script.js"> </script>
<script src="JS/ajax.js"> </script>
<script src="JS/pannello_admin.js"> </script>
</html>