<?php
include("setup.php");


if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	#recupero gli input checkbox selezionati
	if(!$_POST['pulizia_post'] && !$_POST['cancellazione_utenza_inattiva'] && !$_POST['auto_handler_segnalazioni'])
		header('Location: ../Manutenzione.php');
	
	#foreach serve solo a recuperare il contenuto dell'array $_POST, conterrà uno tra tre valori delle checkbox possibili
	#in base a che azione è stata selezionata
	foreach ($_POST as $quale_bottone){
    
		switch($quale_bottone){
			
	########## SE E' STATA SELEZIONATA CHECKBOX PULIZIA POST ##########
			case 'pulizia_post':
					
					$query_pulizia_post =
					'DELETE P.*
					FROM post P
					WHERE P.postdate < (current_timestamp - INTERVAL 1 month);';
																					
					$risultato_pulizia = mysqli_query($conn, $query_pulizia_post);
					
					#se la cancellazione post datati è andata a buon fine
					if($risultato_pulizia)
					
						#redirect
						header('Location: ../Manutenzione.php');
						
						else header("Location: ../paginaErrore.php?errore=errore_query");
					
				break;
				
				
	########## SE E' STATA SELEZIONATA CHECKBOX CANCELLAZIONE UTENTI ##########
			case 'cancellazione_utenza_inattiva':					
				
				$query_cancellazione_utenti =
					'DELETE U.*
					FROM users U
					WHERE U.ultimo_accesso < (current_timestamp - INTERVAL 1 month);';
																					
					$risultato_cancellazione = mysqli_query($conn, $query_cancellazione_utenti);
					
					#se la cancellazione utenti inattivi è andata a buon fine
					if($risultato_cancellazione)
					
						#redirect
						header('Location: ../Manutenzione.php');
						
						else header("Location: ../paginaErrore.php?errore=errore_query");	
					
			break;
				
				
	########## SE E' STATA SELEZIONATA CHECKBOX GESTIONE AUTOMATICA SEGNALAZIONI ##########
			case 'auto_handler_segnalazioni':
				
				$query_segnalazioni =
					'DELETE S.*
					FROM segnalazioni S
					WHERE S.stato_segnalazione = "in attesa";';
																					
					$risultato_segnalazioni = mysqli_query($conn, $query_segnalazioni);
					
					#se la cancellazione delle segnalazioni in attesa di visione è andata a buon fine
					if($risultato_segnalazioni)
					
						#redirect
						header('Location: ../Manutenzione.php');
						
						else header("Location: ../paginaErrore.php?errore=errore_query");
					
			break;
			
		}//fine switch sui bottoni
		
	}//fine foreach che pesca le checkbox selezionate
	
} // chiudo metodo POST

#Altrimenti: non si è arrivati alla pagina tramite metodo POST, redirect a index (tentativo bypass)
else{
	header("Location: ../paginaErrore.php?errore=autenticazione_richiesta");
}

?>