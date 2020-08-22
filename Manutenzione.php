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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_pannello_admin.css">
</head>

<body>

<?php
include("include/header_admin.php");
?>
  
<section class="bacheca">
    
 <?php
          
  #Raccolgo tutte le segnalazioni
   $sql = "SELECT S.*
           FROM segnalazioni S
           WHERE stato_segnalazione ='in attesa' OR stato_segnalazione ='visionata';";
           
   $query = mysqli_query($conn, $sql);
   if(!$query){
     header("Location: paginaErrore.php?errore=errore_query");
   }

  else{
  }
?>
<div class="task-list middle">

      <h2>Manutenzione</h2>
      
      <form action="include/eManutenzione.php" method="POST">
       
      <label class="task">
        <input type="checkbox" name="pulizia_post" value="pulizia_post">
        <i class="fas fa-check"></i>
        <span class="text">Trigger pulizia post datati</span>
      </label>

      <label class="task">
        <input type="checkbox" name="cancellazione_utenza_inattiva" value="cancellazione_utenza_inattiva">
        <i class="fas fa-check"></i>
        <span class="text">Cancellazione utenti inattivi</span>
      </label>

      <label class="task">
        <input type="checkbox" name="auto_handler_segnalazioni" value="auto_handler_segnalazioni">
        <i class="fas fa-check"></i>
        <span class="text">Gestione automatica segnalazioni</span>
      </label>
      <input type="submit" name="esegui" value="Esegui" onclick="prompt_successo()" style="margin-left: 12em; margin-top: 2em; padding: 1em;">
      </form>
      
</div>

</section>

</body>
<script src="JS/main_script.js"> </script>
<script src="JS/ajax.js"> </script>
<script src="JS/pannello_admin.js"> </script>
</html>