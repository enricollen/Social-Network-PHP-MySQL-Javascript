<?php
  session_start();
  
  #Recupero credenziali per accesso server da file esterno
  $accessData=parse_ini_file('configDB.ini');
  #Recupero testi per eventuali errori da file esterno
  $messaggi_errore=parse_ini_file('messaggi_errore.ini');
  
  //1 stabilisco una (o più) connessione/i con un server e seleziono il DB con cui lavorare (dati acesso da file esterno)
	$conn = @mysqli_connect($accessData['host'],$accessData['username'],$accessData['password']);
	if(!$conn){//gestione errore di connessione al server
	  header("Location: paginaErrore.php?errore=connessione_fallita");
	die;  
	}
	
	//2 seleziono il db con cui lavorare
	if ( !@mysqli_select_db($conn, $accessData['database']) ){
	    header("Location: paginaErrore.php?errore=db_non_trovato");
	    die;       
	}
  
  $nl="<br />";
  
  $GLOBALS['conn'] = $conn;
  
  //se nessuna delle variabili di sessione è settata, significa che si sta effettuando un tentativo di bypass e
  //se viene reindirizzati ad una pagina di errore che a sua volta riporta alla index.php
  function check_login(){
    if(!isset($_SESSION['idadmin']) && !isset($_SESSION['username']) && !isset($_SESSION['iduser']))
      header("Location: paginaErrore.php?errore=autenticazione_richiesta");
  }

?>