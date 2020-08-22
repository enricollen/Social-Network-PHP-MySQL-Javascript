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
    <link rel="stylesheet" type="text/css" href="CSS/stile_index.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_pagina_modifica_profilo.css">

</head>

<body>

<?php
  include("include/header.php");
  include("include/modal/modalUtente.php");
  include("include/upload.php");
?>

<div class="header">
		</div>
		<div class="side-aside">
			
			<aside>
				<ul>
          <li>
							<span><i class="fa fa-avatar"></i></span>
							<span onclick="window.location.href = 'ModificaProfilo.php';">Indietro</span>
					</li>
						</li>
				</ul>
			</aside>
		</div>
    
		<div class="main-content">
			<div class="title">
        <h3>Aggiungi foto alla galleria personale</h3>
			</div>
      
			<div class='ad'>

        <?php
    $my_username = $_SESSION['username'];
    
    #Input per upload di avatar salvato in locale
		echo '<p>'.$statusMsg.'</p>';?>
		<form method="post" enctype="multipart/form-data">
			<input type="file" style="margin: 1em;" name="files[]" multiple>
      <input type="submit" name="submit" value="Carica">
		</form>
    
<?php

// Prelievo immagini da database
$query = mysqli_query($conn,"SELECT * FROM album_foto WHERE username='".$my_username."' ORDER BY id DESC");
$rowcount=mysqli_num_rows($query);
$contatore = 0;
if($rowcount > 0){
    while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $imageURL = 'immagini/album/'.$row["file_name"];
?>
    <img src="<?php echo $imageURL; ?>" class="immagini_galleria" alt="<?php echo $my_username."_".$contatore; ?>" />
<?php $contatore++;}
}
else{ 
    echo '<p style="margin: 5px;">Nessuna immagine ancora caricata...</p>';
} ?> 
    
      </div>      
		</div>
	</div>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>
</body>
<script src="JS/main_script.js"> </script>
</html>