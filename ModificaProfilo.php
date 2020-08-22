<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina da loggati, svento eventuali tentativi bypass
  check_login();
  ob_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title>PausaCaff&egrave</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_loggato.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Utente.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_pagina_modifica_profilo.css">
  
</head>

<body>

<?php
  include("include/header.php");
  include("include/modal/modalUtente.php");
?>

<div class="header">
		</div>
		<div class="side-aside">
			
			<aside>
				<ul>
          <li>
							<span><i class="fa fa-avatar"></i></span>
							<span onclick="mostra_avatar('avatar');">Avatar</span>
					</li>
					<li>
							<span><i class="fa fa-user"></i></span>
							<span onclick="mostra_avatar('informazioni');">Informazioni</span>
					</li>
						<li>
								<span><i class="fa fa-bar-chart"></i></span>
								<span onclick="mostra_avatar('interessi');">Interessi</span>
						</li>
            <li>
								<span><i class="fa fa-bar-chart"></i></span>
								<span onclick="window.location.href = 'aggiungi_foto_album.php';">Galleria</span>
						</li>
				</ul>
			</aside>
		</div>
    
		<div class="main-content">
			<div class="title">
        <h3>Modifica Profilo</h3>
			</div>
      
			<div class='ad'>
        <?php
    $my_username = $_SESSION['username'];
    
    // se la pagina è stata raggiunta dopo aver settato $_POST significa che si è aggiornato l'avatar
    if(isset($_POST['submit'])){
      #funzione libreria per salvare il file locale del visitatore in cartella server (avatar salvato in forma testuale)
      move_uploaded_file($_FILES['file']['tmp_name'],"immagini/avatar/".$_FILES['file']['name']);
      
      $query_aggiorna_avatar = "UPDATE users
                                SET avatar = '".$_FILES['file']['name']."'
                                WHERE username = '". $my_username ."'";
                            
      $risultato = @mysqli_query($conn, $query_aggiorna_avatar);
      
      if($query_aggiorna_avatar)
        header("Location: ModificaProfilo.php");
      else
        header("Location: paginaErrore.php?errore=aggiornamento_avatar");
    }// chiudo "se è stato premuto il tasto aggiorna avatar"
    
    echo '<div id="avatar">';
    #Stampo l'avatar corrispondente all'utente
		include("include/RecuperaAvatar.php");
    
    #Input per upload di avatar salvato in locale
		echo '
		<form action="" method="post" enctype="multipart/form-data">
			<input type="file" name="file">
      <input type="submit" name="submit" value="Carica">
		</form></div>
    <div id="informazioni" style="display: none;">';
	
	
        echo '<p style="font-weight: bold;">' . $my_username . '<p> <br />';
        echo '<form action="include/eModificaProfilo.php" method="POST">'
        . 'Citt&agrave: <input type="text" name="citta" placeholder="' . $info['citta'] . '">' . $nl
        . '<br/>Et&agrave: <input type="text" name="eta" placeholder="' . $info['eta'] . '">' . $nl
        . '<br/>Occupazione: <input type="text" name="occupazione" placeholder="' . $info['occupazione'] . '">' . $nl
        . '<br/>Stato: <input type="text" name="stato" placeholder="' . $info['stato_sentimentale'] . '">' . $nl
        . '<br /><button type="submit" class="aggiornadatiprofilo" value="AggiornaDatiProfilo" onclick="prompt_modifica();">Aggiorna</button>'
        . '</form></div>';  

?>
      <div id="interessi" style="display: none;">
        <h4>Inserisci un nuovo interesse:</h4>
        <form action="include/eNuovoInteresse.php" method="POST">
          <input type="text" name="interesse" id="input_interesse" placeholder="Specifica un interesse...">
          <button type="submit" class="aggiornadatiprofilo" value="inserimento_interesse" onclick="prompt_successo();">Inserisci</button>
        </form>
        <?php
        $query_interessi = "SELECT interesse
                        FROM interessi
                        WHERE username='".$my_username."'";
                  
  $Risultato = @mysqli_query($conn, $query_interessi);
      
  echo '<p style="margin-top: 3em;">';
      
      //row = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query
      while($row = mysqli_fetch_assoc($Risultato)){
        $interesse = ucfirst($row['interesse']);
          echo "&#9656; ".$interesse."<br />";
      }
  echo '</p>';
      ?>
      </div>
      
		</div>
	</div>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>

<script>
  // funzioni per comparsa/scomparsa sezioni 
function mostra_avatar(bottone){
  if(bottone == "avatar"){
	_("avatar").style.display = "block";
	_("informazioni").style.display = "none";
  _("interessi").style.display = "none";
  }
  else if(bottone == "informazioni"){
	_("informazioni").style.display = "block";
	_("avatar").style.display = "none";
  _("interessi").style.display = "none";
  }
  else if(bottone == "interessi"){
	_("interessi").style.display = "block";
	_("informazioni").style.display = "none";
  _("avatar").style.display = "none";
  }
}

</script>

</body>
<script src="JS/main_script.js"> </script>
</html>