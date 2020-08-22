/*Questa funzione viene richiamata dalla pagina "gestione_notifiche.php", con l'evento onclick.
Lo scopo è quello di inviare tramite js ajax una richiesta XMLHttp alla pagina PHP "aggiorna_ultimo_check_notifiche.php"
che esegue di fatto la query (non c'è passaggio di argomenti ne scambio di informazioni tramite POST, solo esecuzione query)
di aggiornamento dell'ultimo check delle notifiche, che di fatto verrà sovrascritto con il timestamp di click in modo che
gli avvisi per le successive notifiche vengano visualizzati correttamente. */

function richiama_aggiornamento_check_notifiche_php_tramite_ajax(){
    
    // Creazione oggetto XMLHttp
    var hr = new XMLHttpRequest();
    
    //path e nome del file PHP che si occupa dell'esecuzione query (update last check notifiche)
    var url = "aggiorna_ultimo_check_notifiche.php";
    
    //definizione metodo richiesta dell'XML, di fatto non viene passato nessun parametro tramite POST
    hr.open("POST", url, true);
    
    //tipo contenuto
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    
    //invio alla pagina elaborazione PHP che effettua l'update dell'ultimo check delle notifiche, nessun ritorno
    hr.send();
}