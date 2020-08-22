<?php
  include("setup.php");

#Controllo se si arriva a questa pagina da loggati
if(	isset($_SESSION['iduser'])	){
  
	//Recupero campo id di sessione (da login o registrazione)
	$my_iduser = $_SESSION['iduser'];
	$my_username = $_SESSION['username'];
    
	$comandoSQL = "	SELECT citta, eta, occupazione, stato_sentimentale
                  FROM users U natural join informazioni I
                  WHERE username = '" . $my_username . "' ";
                            
    $risultato = @mysqli_query($conn, $comandoSQL);
    
  if($risultato){
    
    $info = @mysqli_fetch_assoc($risultato);
	
	//Recupero campi che sono stati modificati tramite post
		
	if(isset($_POST['citta']))
		$update_citta = $_POST['citta'];
	else
		$update_citta = $info['citta'];
    
  if(isset($_POST['eta']))
		$update_eta = $_POST['eta'];
	else
		$update_eta = $info['eta'];
    
  if(isset($_POST['occupazione']))
		$update_occupazione = $_POST['occupazione'];
	else
		$update_occupazione = $info['occupazione'];
    
  if(isset($_POST['stato']))
		$update_stato = $_POST['stato'];
	else
		$update_stato = $info['stato_sentimentale'];
    
    $query_check = '	SELECT COUNT(*) as N
                    FROM informazioni
                    WHERE username = "' . $my_username . '";';
                    
    $check_esistono_interessi = @mysqli_query($conn, $query_check);
    $insert_o_update = mysqli_fetch_array($check_esistono_interessi, MYSQLI_ASSOC);
    
    if($insert_o_update['N']>=1){
    
			$update = '	UPDATE users
                  SET citta = "' . $update_citta . '" ' .
                  ' WHERE iduser = "' . $my_iduser . '";';
                  
      $update2 = '	UPDATE informazioni
                  SET eta='.$update_eta.', occupazione="'.$update_occupazione.'", stato_sentimentale="'.$update_stato.'" WHERE username = "' . $my_username . '";';
        
    $risultatoAggiornamento = @mysqli_query($conn, $update);
    $risultatoAggiornamento2 = @mysqli_query($conn, $update2);
    
    if($risultatoAggiornamento && $risultatoAggiornamento2) header("Location: ../ModificaProfilo.php");
    else header("Location: ../ModificaProfilo.php");
    }
    else{ // se non sono mai state specificate le informazioni addizionali (eta, occupazione, stato sentimentale) faccio insert, non update
      $insert = '	INSERT INTO informazioni VALUES("'.$my_username.'","'.$update_occupazione.'","'.$update_eta.'","'.$update_stato.'");';
      $risultatoInserimento = @mysqli_query($conn, $insert);
      if($risultatoInserimento) header("Location: ../ModificaProfilo.php");
      else header("Location: ../ModificaProfilo.php");
    }
  } // chiude query iniziale di recupero vecchi dati dell user che sta aggiornando dati

}//chiude controllo utente loggato

#Altrimenti: utente non autenticato, redirect a index (tentativo bypass)
else{
	header("Location: ../paginaErrore.php?errore=autenticazione_richiesta"); //tentativo bypass, senza passare per metodo post
}

?>