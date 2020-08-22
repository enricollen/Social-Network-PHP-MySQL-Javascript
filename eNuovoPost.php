<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina dal login
  check_login();
?>

<!DOCTYPE html>
<html>
<head>
	<title>PausaCaff&egrave</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/style_loggato.css">
</head>

<body>

<?php

	$my_iduser = $_SESSION['iduser'];
	$contenuto_post = $_POST['contenuto_post'];
	
    #Recupero con una query tutte le informazioni riguardanti post da me effettuati o scritti da autori che sono miei AMICI
    $query_inserisci_nuovo_post =
    'INSERT INTO post
    VALUES(NULL,  ' . $my_iduser . ', "' . $contenuto_post . '", 0, CURRENT_TIMESTAMP)';
                    
  $risultato_query_recupero_post = mysqli_query($conn, $query_inserisci_nuovo_post);
  
  # se inserisce con successo
  if($risultato_query_recupero_post)
  header("Location: bacheca.php");
  
  else
  header("Location: paginaErrore.php?errore=errore_query");

?>

<script src="JS/main_script.js"> </script>
</body>
</html>
