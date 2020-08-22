<!-- Arrivo a questa pagina dopo che è stata richiamata la funzione js
richiama_aggiornamento_visione_segnalazioni_tramite_ajax.js (tramite ajax)
che richiama questa pagina di elaborazione PHP per l'aggiornamento dello stato delle segnalazioni effettuate dagli utenti.
una volta aggiornato lo stato delle segnalazioni da "in attesa" a "visionata", il numerino di notifica sparira al
successivo caricamento della pagina; oppure ci arrivo tramite la chiamata alla funzione
nascondi_riga_tabella_e_aggiorna_stato_segnalazione.js (tramite ajax) dopo il click su V o X nel pannello admin
gestione segnalazioni-->

<?php
	include("include/setup.php");
	
	if(isset($_POST['Segnalazioni'])){
	#Aggiorno il campo ultimo check notifiche dell'utente loggato che ha cliccato la campanella, mentre un altra funzione js pura
	#fa apparire il modal relativo alle notifiche
	$query_aggiornamento_visione_segnalazioni =
	'UPDATE segnalazioni
	SET stato_segnalazione = "visionata"
	WHERE stato_segnalazione = "in attesa"';
																					
	$risultato = mysqli_query($conn, $query_aggiornamento_visione_segnalazioni);
	header("Location: Segnalazioni.php");
	}
?>


<?php
/********* AGGIORNA STATO SEGNALAZIONE **********/
if (isset($_POST['action']) && $_POST['action'] == "gestisci_segnalazione"){
	
	$id_segnalazione = $_POST['id_segnalazione'];
	$id_post = $_POST['id_post'];
	
	if($_POST['giudizio'] == "ignorata")
		$query = mysqli_query($conn, "UPDATE segnalazioni SET stato_segnalazione ='ignorata' WHERE idsegnalazione='$id_segnalazione'");
	else{
		$query = mysqli_query($conn, "UPDATE segnalazioni SET stato_segnalazione ='gestita' WHERE idsegnalazione='$id_segnalazione'");
		$query = mysqli_query($conn, "DELETE P.* FROM post P WHERE osid='$id_post'");
	}
}
?>