<?php
  include("setup.php");
  
/*Primo controllo di sicurezza, assicuriamoci che la pagina elogin sia stata richiamata da login.html tramite il metodo post e non 
direttamente da browser. Per farlo ci serviamo dell'array $_SERVER e della sua chiave request method (variabile),
che contiene proprio il metodo con la quale la pagina è stata richiamata. Se fosse stata richiamata direttamente
da browser avrebbe REQUEST_METHOD === "" */
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
  /********** ACCESSO ADMIN ***************/
  
  //se l'accesso è stato effettuato dall'ADMIN del sito
  if(isset($_POST['accesso_amministrativo'])){
    $adminID = $_POST['adminID'];
    $psw = $_POST['password'];

	//3 comando SQL
	$comandoSQL = "	SELECT idadmin, password
                  FROM admin
                  WHERE idadmin = '" . $adminID . "' ";
  
  $RisultatoRicercaAdmin = @mysqli_query($conn, $comandoSQL);
  
  if($RisultatoRicercaAdmin){
        
        //riga = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query (2: id, password)
        $riga = mysqli_fetch_assoc($RisultatoRicercaAdmin);
        
        //operatore ternario per controllo password
        $autenticato = ($psw === $riga['password']) ? true : false;
        
        if($autenticato){
          //prima del redirect salvo l'IDADMIN nell'array associativo di sessione _SESSION
          $_SESSION['idadmin'] = $riga['idadmin'];
          
          header("Location: ../Pannello_Admin.php");
        }
        
        else{ //autenticazione fallita, password sbagliata o fetch_assoc non ha trovato corrispondenza email
          $_SESSION['errore_login_php'] = "ID-Admin o Password errati, riprovare.";
          header("Location: ../index.php");
          die;
        }
        
      }//chiude RisultatoRicercaAdmin
  }//fine caso accesso ADMIN

  /*********** ACCESSO UTENTE ***************/
  
  //altrimento l'accesso è stato effettuato da un utente semplice, non admin
  else{
	//Recupero campi textbox
	$username = $_POST['username'];
	$psw = $_POST['password'];

	//3 comando SQL
	$comandoSQL = "	SELECT iduser, username, psw
                  FROM users
                  WHERE username = '" . mysqli_escape_string($conn, $username) . "' ";
  
  //per il vincolo unique nel DB sulla mail, trova o 1 corrispondenza o nessuna
  $RisultatoRicercaUsername = @mysqli_query($conn, $comandoSQL);
  
  //4 Quale bottone è stato premuto?
  if(isset($_POST['BottoneAccedi'])){
      
      //query eseguita (sintatticamente) con successo
      if($RisultatoRicercaUsername){
        
        //riga = array associativo con 1 riga e tante colonne quanti gli attributi proiezione query (2: id, psw)
        $riga = mysqli_fetch_assoc($RisultatoRicercaUsername);
        
        //operatore ternario per controllo password
        $autenticato = ($psw === $riga['psw']) ? true : false;
        
        if($autenticato){
          //prima del redirect salvo l'iduser nell'array associativo di sessione _SESSION
          $_SESSION['iduser'] = $riga['iduser'];
          $_SESSION['username'] = $riga['username'];
          
          #aggiorno il timestamp dell'ultimo accesso effettuato
          $query_aggiorna_data_ultimo_accesso = " UPDATE users
                                                  SET ultimo_accesso = CURRENT_TIMESTAMP
                                                  WHERE username = '" . $riga['username'] . "'; ";
          #invio query al DB                
          $risultato = mysqli_query($conn, $query_aggiorna_data_ultimo_accesso);
          
          if($risultato)
          //redirect
          header("Location: ../bacheca.php");
          
          #errore sintassi query per aggiornamento data ultimo login
          else
          header("Location: ../paginaErrore.php?errore=errore_aggiornamento_last_login");
          
        }
        
        else{ //autenticazione fallita, password sbagliata o fetch_assoc non ha trovato corrispondenza email
          $_SESSION['errore_login_php'] = "Username o Password errati, riprovare.";
          header("Location: ../index.php");
          die;
        }
        
      }//chiude RisultatoRicercaEmail
      
      else{ //fallita mysqli_query, ovvero fallito $RisultatoRicercaEmail, ovvero fallita la query di selezione (sintassi)
        header("Location: ../paginaErrore.php?errore=errore_query");
        die;
      }
    
    }//chiude Bottone Accedi
  }//chiude caso login utente semplice (non admin)
}//chiude metodo post

#Altrimenti: utente non autenticato col metodo POST, redirect a login (tentativo bypass)
else{
	header("Location: ../paginaErrore.php?errore=autenticazione_richiesta"); //tentativo bypass, senza passare per metodo post
}

?>