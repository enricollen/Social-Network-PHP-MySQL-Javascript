<?php
  include("setup.php");
  
/*Primo controllo di sicurezza, assicuriamoci che la pagina elogin sia stata richiamata da login.html tramite il metodo post e non 
direttamente da browser. Per farlo ci serviamo dell'array $_SERVER e della sua chiave request method (variabile),
che contiene proprio il metodo con la quale la pagina è stata richiamata. Se fosse stata richiamata direttamente
da browser avrebbe REQUEST_METHOD === "" */
if($_SERVER['REQUEST_METHOD'] === 'POST'){

//sanificazione parametro in entrata
function sanifica($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
  return $data;
}

  #variabile appoggio inizializzata a NULL
  $error = NULL;
  $_SESSION['email'] = $_POST['email'];
  
###### 3. INIZIO CONTROLLI INPUT LATO SERVER #########

######## INIZIO CONTROLLO PARAMETRO EMAIL ############

# Email vuota, dovrebbe essere gestito da html con attributo required, comunque per sicurezza setto errore
  if (empty($_POST["email"])) {
    $emailErr = "Inserisci una email";
    $error = 1;
    }
# Altrimenti campo mail di POST non vuoto e salvo in variabile locale la sanificazione del valore inviato tramite POST
    else {
      $email = $_POST["email"]; 
    
# Ulteriore controllo se l'email è stata inviata in un formato supportato, faccio uso di filter_var funzione di libreria
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Formato email non valido."; 
      $error= 1;
    }
    
      //4. comando SQL
      $comandoSQL = "	SELECT iduser, psw
                      FROM users
                      WHERE email = '" . $email . "';";
  
      //per il vincolo unique nel DB sulla mail, trova o 1 corrispondenza o nessuna
      $RisultatoRicercaEmail = @mysqli_query($conn, $comandoSQL);
    
  		if(@mysqli_fetch_assoc($RisultatoRicercaEmail)){// se vera, mail già in uso, non ci interessa che mail
  			#mysqli_close($conn);
        //email gia in uso
        $emailErr = "Questa email risulta associata ad un altro account.";
        $error = 1;
      }// fine email in uso
    } // fine $_POST['email'] non vuoto
    
    # Se si è verificato un errore nel parametro email, salvo in sessione il valore dell'errore in base a ciò che è accaduto
    if(isset($emailErr)){
      $_SESSION['emailErr']= $emailErr;
    }
    
######## FINE CONTROLLO PARAMETRO EMAIL ############


######## INIZIO CONTROLLO PARAMETRO NOME ############

# Nome vuoto, dovrebbe essere gestito da html con attributo required, comunque per sicurezza setto errore
    if (empty($_POST["nome"])) {
      $nomeErr = "Inserisci il tuo nome.";
      $error = 1;
    }
# Altrimenti campo mail di POST non vuoto e salvo in variabile locale la sanificazione del valore inviato tramite POST
    else{
      $nome = sanifica($_POST["nome"]);
      # Controllo formato
      if (!preg_match("/^[a-zA-Z ]*$/",$nome)) {
        $nomeErr = "Il nome deve contenere solo lettere o spazi bianchi"; 
        $error = 1;
      }
      # Controllo lunghezza
      elseif (strlen($nome) < 3) {
        $nomeErr = "Inserisci un nome valido (almeno 3 caratteri)."; 
        $error = 1;
      }
    }
    
    # Se si è verificato un errore nel parametro nome, salvo in sessione il valore dell'errore in base a ciò che è accaduto
    if(isset($nomeErr)){
      $_SESSION['nomeErr']= $nomeErr;
    }

######## FINE CONTROLLO PARAMETRO NOME ############


######## INIZIO CONTROLLO PARAMETRO COGNOME ############

# Cognome vuoto, dovrebbe essere gestito da html con attributo required, comunque per sicurezza setto errore
    if (empty($_POST["cognome"])) {
      $nomeErr = "Inserisci il tuo cognome.";
      $error = 1;
    }
# Altrimenti campo mail di POST non vuoto e salvo in variabile locale la sanificazione del valore inviato tramite POST
    else{
      $cognome = sanifica($_POST["cognome"]);
      # Controllo formato
      if (!preg_match("/^[a-zA-Z ]*$/",$cognome)) {
        $nomeErr = "Il cognome deve contenere solo lettere o spazi bianchi"; 
        $error = 1;
      }
      # Controllo lunghezza
      elseif (strlen($cognome) < 3) {
        $nomeErr = "Inserisci un cognome valido (almeno 3 caratteri)."; 
        $error = 1;
      }
    }
    
    # Se si è verificato un errore nel parametro cognome, salvo in sessione il valore dell'errore in base a ciò che è accaduto
    if(isset($nomeErr)){
      $_SESSION['cognomeErr'] = $nomeErr;
    }
    
######## FINE CONTROLLO PARAMETRO COGNOME ############


######## INIZIO CONTROLLO PARAMETRO USERNAME ############

# Username vuoto, dovrebbe essere gestito da html con attributo required, comunque per sicurezza setto errore
    if (empty($_POST["username"])) {
      $usernameErr = "Inserisci uno username";
    }
# Altrimenti campo username di POST non vuoto e salvo in variabile locale la sanificazione del valore inviato tramite POST
    else{
      $username = $_POST['username'];
      $comandoSQL = "	SELECT iduser, psw
                      FROM users
                      WHERE username = '" . $username . "';";
  
      //per il vincolo unique nel DB sullo username, trova o 1 corrispondenza o nessuna
      $RisultatoRicercaUsername = @mysqli_query($conn, $comandoSQL);
    
  		if(@mysqli_fetch_assoc($RisultatoRicercaUsername)){// se vera, username già in uso
  			mysqli_close($conn);
        $usernameErr =  "Username gi&agrave; presente, inserirne uno nuovo";
        $error = 1;
      }
    }
    
    # Se si è verificato un errore nel parametro username, salvo in sessione il valore dell'errore in base a ciò che è accaduto
    if(isset($usernameErr)){
        $_SESSION['usernameErr'] = $usernameErr;
    }

######### FINE CONTROLLO PARAMETRO USERNAME ############


######## INIZIO CONTROLLO PARAMETRO CITTA' ############

  # Citta vuoto, dovrebbe essere gestito da html con attributo required, comunque per sicurezza setto errore
    if(empty($_POST["citta"])) {
          $cittaErr = "Inserisci una citt&agrave;";
          $error = 1;
    }
    else{
      $citta = sanifica($_POST['citta']);
    }
   
   $genere = $_POST['genere'];
    
######### FINE CONTROLLO PARAMETRO CITTA' ############


######### INIZIO CONTROLLO PARAMETRO PASSWORD ############

  # Password vuota, dovrebbe essere gestito da html con attributo required, comunque per sicurezza setto errore
    if(empty($_POST["password"])){
      $pswErr = "Scegli una password.";
    }
    else{ #salvo in una variabile locale
      $psw = $_POST['password'];
    #controllo che contenga almeno 8 carattere, di cui un numero, un carattere minuscolo e uno maiuscolo
        if (strlen($psw) <= '7') {
         $pswErr = "La password deve contenere almeno 8 caratteri.";
         $error = 1;
        }
        elseif (!preg_match("#[0-9]+#",$psw)) {
          $pswErr = "La password deve contenere almeno un numero";
          $error = 1;
        }
        elseif(!preg_match("#[A-Z]+#",$psw)) {
          $pswErr = "La password deve contenere almeno un carattere maiuscolo";
          $error = 1;
        }
        elseif(!preg_match("#[a-z]+#",$psw)) {
          $pswErr = "La password deve contenere almeno un carattere minuscolo";
          $error = 1;
        }
          
    }

# Se si è verificato un errore nel parametro password, salvo in sessione il valore dell'errore in base a ciò che è accaduto
    if(isset($pswErr)){
        $_SESSION['pswErr'] = $pswErr;
    }
    
######### FINE CONTROLLO PARAMETRO PASSWORD ############
  
  
######### INIZIO CONTROLLO PARAMETRO PASSWORD-RIPETUTA ############

    if(empty($_POST["password-repeat"])){
      $psw_repeatErr = "Devi reinserire la password.";
      $error = 1;
    }
    else{
      $psw_repeat = $_POST['password-repeat'];
    #Controllo coincidenza password
      if($psw !== $psw_repeat){
        $psw_repeatErr = "Le due password non coincidono.";
        $error = 1;
      } 
    }

# Se si è verificato un errore nel parametro password, salvo in sessione il valore dell'errore in base a ciò che è accaduto
    if(isset($psw_repeatErr)){
      $_SESSION['psw_repeatErr'] = $psw_repeatErr;
    }
  
######### FINE CONTROLLO PARAMETRO PASSWORD-RIPETUTA ############

    #se nel corso dei controlli la variabile non ha cambiato lo stato iniziale
    if($error == NULL){
      
      #inserisco:
  		$comandoSQL = " INSERT INTO users
                      VALUES(null, '" . $username . "' , '" . $psw . "' , '" . $email . "' , '" . $nome . "' , '" . $cognome . "' , '" . $citta . "' , '" . $genere . "', 'default', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
  		#echo $comandoSQL . $nl;
  		$esito = mysqli_query($conn, $comandoSQL);

  			if($esito){
  				# se tutto va bene, si inserisce nell'array associativo SESSION, con chiave iduser, l'iduser dell'utente
  				#appena inserito, che però ancora non esisteva prima di effettuare l'INSERT: grazie alla fx mysqli_insert_id riesco
  				#a risalire all'ultimo id incrementale (chiave della tabella users) associato alla connessione $conn
  				$_SESSION['iduser'] = mysqli_insert_id($conn);
          $_SESSION['username'] = $username;
          $_SESSION['genere'] = $genere;
  				mysqli_close($conn);
          
        	header("Location: ../bacheca.php"); //redirect equivalente al login successo.
  			}
        else{//Errore (sintassi) nell'inserimento del nuovo user
  				mysqli_close($conn);
        		header("Location: ../paginaErrore.php?errore=inserimento_nuovo_utente_fallito");
  			}
    } #chiude lo stato di "nessun errore riscontrato"
    
    #Altrimenti si è verificato uno degli errori nei controlli di uno dei parametri sopra
  	else {
      
      header("Location: ../index.php");
    }
  
}//chiude metodo post

#Altrimenti: utente non autenticato col metodo POST, redirect a login (tentativo bypass)
else{
	header("Location: ../paginaErrore.php?errore=autenticazione_richiesta"); //tentativo bypass, senza passare per metodo post
  exit;
}

?>