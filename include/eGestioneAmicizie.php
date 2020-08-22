<?php
include("setup.php");


if($_SERVER['REQUEST_METHOD'] == "POST"){
	
	#recupero username inviato tramite l'input type-text Invisibile nella form
	$my_iduser = $_SESSION['iduser'];
	$profilo_di = $_POST['username_visitato'];
	$iduser_visitato = $_POST['iduser_visitato'];
	
	#foreach serve solo a recuperare il contenuto dell'array $_POST, conterrà un solo valore ovvero uno dei due bottoni
	#in base a se si è amici dell'utente visitato (si è richiesto di eliminare amicizia) o se non si era amici (invio richiesta amicizia)
	foreach ($_POST as $quale_bottone){
    
		switch($quale_bottone){
			
	########## SE E' STATO CLICCATO IL PULSANTE RICHIEDI AMICIZIA ##########
			case 'Richiedi Amicizia':
					
					$query_inserimento_richiesta_amicizia =
					'INSERT INTO richiesteamicizia
					VALUES(NULL,  ' . $my_iduser . ', ' . $iduser_visitato . ', "in attesa", CURRENT_TIMESTAMP);';
																					
					$risultato_inserimento_richiesta = mysqli_query($conn, $query_inserimento_richiesta_amicizia);
					
					#se l'inserimento nella tabella richieste è andato a buon fine
					if($risultato_inserimento_richiesta){
						
						#Inserisco anche nella tabella notifiche
						$query_inserimento_notifica =
						'INSERT INTO notifiche
						VALUES(NULL,  ' . $my_iduser . ', ' . $iduser_visitato . ', NULL, "richiesta amicizia", CURRENT_TIMESTAMP);';
						
						$risultato_inserimento_notifica = mysqli_query($conn, $query_inserimento_notifica);
						
						#se entrambi gli inserimenti sono andati a buon fine
						if($risultato_inserimento_notifica)
						#redirect (dovrebbe farlo alla pagina del profilo, con bottone cliccato ma serve ajax)
						header('Location: ../richiesteamicizia.php');
						
						else header("Location: ../paginaErrore.php?errore=inserimento_nuova_notifica");
					}//chiude caso successo nel primo inserimento 
					
					#Errore sintassi nel 1 inserimento richiesta amicizia
					else{
						header("Location: ../paginaErrore.php?errore=inserimento_nuova_richiesta_amicizia");
					}
					
				break;
				
				
	########## SE E' STATO CLICCATO IL PULSANTE RIMUOVI AMICIZIA ##########
			case 'Rimuovi dagli Amici':					
					
					$query_eliminazione_amicizia = 'DELETE A.*
													FROM amicizie A
													WHERE (
															(iduser1 = "' . $my_iduser . '"
															AND iduser2 = "' . $iduser_visitato . '")
															OR
															(iduser2 = "' . $my_iduser . '"
															AND iduser1 = "' . $iduser_visitato . '")
														)';
														
					#non compare la notifica a chi ha inviato una richiesta, è stata accettata dal ricevente e poi il ricevente
					#elimina dagli amici il mittente prima che lui faccia un check notifiche
					$query_eliminazione_notifiche = 'DELETE N.*
													FROM notifiche N
													WHERE (
															(iduser_che_causa_la_notifica = "' . $my_iduser . '"
															AND iduser_destinatario_notifica = "' . $iduser_visitato . '")
															OR
															(iduser_destinatario_notifica = "' . $my_iduser . '"
															AND iduser_che_causa_la_notifica = "' . $iduser_visitato . '")
														)';
																					
					$risultato = mysqli_query($conn, $query_eliminazione_amicizia);
					$risultato2 = mysqli_query($conn, $query_eliminazione_notifiche);
					
					if($risultato && $risultato2){
						header('Location: ../richiesteamicizia.php');		
					}
					else{
						header("Location: ../paginaErrore.php?errore=rimozione_amicizia");
					}
					
				break;
				
				
			########## SE E' STATO CLICCATO IL PULSANTE ANNULLA RICHIESTA AMICIZIA ##########
				case 'Annulla Richiesta Amicizia':
					
					$query_eliminazione_richiesta_amicizia = '	DELETE RA.*
																FROM richiesteamicizia RA
																WHERE (
																		(iduser_richiedente = "' . $my_iduser . '"
																		AND iduser_destinatario = "' . $iduser_visitato . '")
																		OR
																		(iduser_destinatario = "' . $my_iduser . '"
																		AND iduser_richiedente = "' . $iduser_visitato . '")
																	)';
																					
					$risultato_eliminazione_richiesta = @mysqli_query($conn, $query_eliminazione_richiesta_amicizia);
					
					if($risultato_eliminazione_richiesta){
						
						$query_annullamento_notifica = 'DELETE N.*
														FROM notifiche N
														WHERE (N.iduser_che_causa_la_notifica = "' . $my_iduser . '"
																AND
															N.iduser_destinatario_notifica = "' . $iduser_visitato . '")
															AND N.tipologia_notifica = "richiesta amicizia"';
															
						$risultato_annulla_notifica = mysqli_query($conn, $query_annullamento_notifica);
						
						if($risultato_annulla_notifica)
						header('Location: ../richiesteamicizia.php');
						
						else
						echo mysqli_connect_error.
						$query_annullamento_notifica ;
					}//chiude caso 1 operazione a buon fine (annullamento richiesta amicizia)
					
					#errore sintassi query 1 operazione (annullamento richiesta amicizia)
					else{
						header("Location: ../paginaErrore.php?errore=rimozione_amicizia");
					}
					
				break;
			
			
				########## SE E' STATO CLICCATO IL PULSANTE ACCETTA RICHIESTA AMICIZIA ##########
				case 'Accetta':
					
					#Prima inserisco in Amicizie, se tutto va bene, dopo elimino la richiesta da RichiesteAmicizia
					$query_amicizia_accettata = 'INSERT INTO amicizie
												VALUES(NULL,  ' . $my_iduser . ', ' . $iduser_visitato . ', CURRENT_TIMESTAMP);';
																					
					$risultato_inserimento_amicizia = mysqli_query($conn, $query_amicizia_accettata);
					
					#se l'inserimento va a buon fine, allora elimino
					if($risultato_inserimento_amicizia){
						
						$query_eliminazione_richiesta_amicizia = '	DELETE RA.*
																	FROM richiesteamicizia RA
																	WHERE (
																			(iduser_richiedente = "' . $my_iduser . '"
																			AND iduser_destinatario = "' . $iduser_visitato . '")
																			OR
																			(iduser_destinatario = "' . $my_iduser . '"
																			AND iduser_richiedente = "' . $iduser_visitato . '")
																		)';
																					
						$risultato_eliminazione_richiesta = mysqli_query($conn, $query_eliminazione_richiesta_amicizia);
						
						#se entrambe le azioni vanno a buon fine
						if($risultato_eliminazione_richiesta){
							
							#come 3 query inserisco anche nella tabella notifiche nel seguente modo:
							#inserisco con modalità notifica "amicizia confermata" dove io che la accetto sono
							#il destinatario, in modo tale che il mittente riceva la notifica quando accetto la sua richiesta!
							$query_inserimento_notifica =
							'INSERT INTO notifiche
							VALUES(NULL,  ' . $iduser_visitato . ', ' . $my_iduser . ', NULL, "amicizia confermata", CURRENT_TIMESTAMP);';
							
							$risultato_inserimento_notifica = mysqli_query($conn, $query_inserimento_notifica);
							
							#se tutte e tre le azioni (1 inserimento-> 1 eliminazione-> 1 inserimento) sono andati a buon fine
							if($risultato_inserimento_notifica)
							#redirect
							header('Location: ../richiesteamicizia.php');
							
							else header("Location: ../paginaErrore.php?errore=inserimento_nuova_notifica");
						}#fine inserimento notifica eseguito con successo, solo dopo aver inserito in amicizie, e eliminato la richiesta!
						
					}#fine eliminazione richiesta di amicizia eseguita con successo, solo dopo aver inserito in amicizie!
					
					#se fallito tentativo inserimento amicizia
					else{
						header("Location: ../paginaErrore.php?errore=rimozione_amicizia");
					}
					
				break;
			
				
				########## SE E' STATO CLICCATO IL PULSANTE RIFIUTA RICHIESTA AMICIZIA ##########
				case 'Rifiuta':
					
					$query_eliminazione_richiesta_amicizia = '	DELETE RA.*
																FROM richiesteamicizia RA
																WHERE (
																		(iduser_richiedente = "' . $my_iduser . '"
																		AND iduser_destinatario = "' . $iduser_visitato . '")
																		OR
																		(iduser_destinatario = "' . $my_iduser . '"
																		AND iduser_richiedente = "' . $iduser_visitato . '")
																	)';
																					
					$risultato = mysqli_query($conn, $query_eliminazione_richiesta_amicizia);
					
					if($risultato){
						/*echo "Richiesta di Amicizia rifiutata con successo.";
						echo $nl . '<a href="bacheca.php">Torna alla bacheca.</a>';*/
						header('Location: ../richiesteamicizia.php');	
					}
					else{
						/*echo "Errore durante il tentativo di rifiutare la richiesta di amicizia.";
						echo $nl . '<a href="bacheca.php">Torna alla bacheca.</a>';*/
						header("Location: ../paginaErrore.php?errore=rifiuto_amicizia");
					}
					
				break;
				
		}//fine switch sui bottoni
		
	}//fine foreach che pesca il dato di post su quale bottone è stato premuto
	
} // chiudo metodo POST

#Altrimenti: utente non autenticato, redirect a index (tentativo bypass)
else{
	header("Location: ../paginaErrore.php?errore=autenticazione_richiesta");
}

?>