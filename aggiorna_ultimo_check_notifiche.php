<!-- Arrivo a questa pagina dopo che è stata richiamata la funzione js check_notifiche_ajax.js (tramite ajax)
che richiama questa pagina di elaborazione PHP per l'aggiornamento dell'ultimo check delle notifiche dell'utente loggato.
di fatto qui eseguo solo la query di aggiornamento, non ci sono parametri passati ne ritorni. Con l'update dell'ultima
visualizzazione però mi assicuro il corretto funzionamento della visualizzazione delle notifiche che verranno, ovvero quelle che
avranno il timestamp_notifica > timestamp_ultimo_check_notifiche.
In altre parole con questa semplice logica mantengo aggiornato il campo dell'ultimo check, rendendo "Lette" le notifiche in entrata
dopo un click e preparandomi per eventuali altre notifiche future.-->

<?php
	include("include/setup.php");

	#recupero iduser
	$my_iduser = $_SESSION['iduser'];
	#mi ricavo il timestamp al momento del click e lo utilizzo per aggiornare il campo ultimo_check_notifiche
	$current_timestamp = date("Y-m-d H:i:s");
	
	#Aggiorno il campo ultimo check notifiche dell'utente loggato che ha cliccato la campanella, mentre un altra funzione js pura
	#fa apparire il modal relativo alle notifiche
	$query_aggiornamento_ultimo_check_notifiche =
	'UPDATE users
	SET ultimo_check_notifiche = "' . $current_timestamp . '"
	WHERE iduser = "' . $my_iduser . '"';
																					
	$risultato = mysqli_query($conn, $query_aggiornamento_ultimo_check_notifiche);
?>