/* questa funzione viene chiamata se viene effettuato un click da parte dell'admin nel pannello Segnalazioni (su V o X).
    1) se è stato cliccato il testo V verde, la segnalazione viene ignorata, la riga relativa nella tabella scompare.
    2) se è stato cliccato il testo X rosso, la segnalazione viene gestita e viene quindi eliminato il post segnalato,
    la riga relativa nella tabella scompare.
*/
function nascondi_riga_tabella_e_aggiorna_stato_segnalazione(indice, id_segnalazione, bottone, id_post){
    
    var ajax = ajaxObj("POST", "gestione_segnalazioni.php");
    
    var decisione_admin = "";
    if(bottone == "elimina")
        decisione_admin = 'gestita';
    else
        decisione_admin = 'ignorata';
        
    ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var id = 'riga_';
            id += indice;
            document.getElementById(id).style.display = 'none';
		}
	}
    
    ajax.send("action=gestisci_segnalazione&id_segnalazione="+id_segnalazione+"&giudizio="+decisione_admin+"&id_post="+id_post);
}