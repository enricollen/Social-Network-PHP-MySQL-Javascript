/* INSERIMENTO POST */
function postToStatus(action,type,user,ta){ // user = utente che posta (per forza me stesso)
	var data = _(ta).value;
	if(data == ""){
		alert("campo vuoto!");
		return false;
	}
	_("statusBtn").disabled = true;
	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
				var sid = datArray[1];
				var avatar = datArray[2]; //mio avatar, sono sempre io a postare sulla bacheca
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				/*scrivo incima a statusarea che è il div che conterrà lo statuslist (altri post e commenti successivi a quello appena postato)*/
				var currentHTML = _("statusarea").innerHTML;
				_("statusarea").innerHTML = '<div id="status_'+sid+'" class="status_boxes"><div>'+avatar+'<b> Postato da te adesso:</b> <span id="sdb_'+
				sid+'"><a href="#" onclick="return false;" onmousedown="deleteStatus(\''+sid+'\',\'status_'+sid+'\');" title="Elimina il post e i relativi commenti">Elimina Post</a></span><br />'+
				data+'</div><img width="45" height="40" src="immagini/like.png" alt="Mi Piace:" title="Mi Piace Ottenuti">	0</div><textarea id="replytext_'+
				sid+'" class="replytext" onkeyup="statusMax(this,250)" placeholder="Scrivi un commento"></textarea><button class="bottoni_bacheca" id="replyBtn_'+sid+'" onclick="replyToStatus('+sid+',\''+user+'\',\'replytext_'+sid+'\',this)">Rispondi</button>'+currentHTML;
				_("statusBtn").disabled = false;
				_(ta).value = "";
		}
	}
	ajax.send("action="+action+"&type="+type+"&user="+user+"&data="+data);
}


/* INSERIMENTO COMMENTO */
function replyToStatus(sid,user,ta,btn){
	var data = _(ta).value;
	if(data == ""){
		alert("campo vuoto!");
		return false;
	}
	//user = autore originario del post! chiunque esso sia (anche non me stesso)
	_("replyBtn_"+sid).disabled = true;
	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
				var rid = datArray[1];
				var avatar = datArray[2]; //mio avatar, sono io a rispondere sui miei post o post di altri
				data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br />").replace(/\r/g,"<br />");
				_("status_"+sid).innerHTML += '<div id="reply_'+rid+'" class="reply_boxes"><div>'+avatar+'<b> Risposta inviata da te adesso:</b><span id="srdb_'+
				rid+'"><a href="#" onclick="return false;" onmousedown="deleteReply(\''+rid+'\',\'reply_'+rid+'\',\'immagine_mi_piace'+rid+'\',\'like_counter'+rid+'\');" title="Elimina questo commento">Elimina</a></span><br />'+data
				+'</div></div><img width="45" height="40" src="immagini/like.png" id="immagine_mi_piace'+rid+'" alt="Mi Piace:" title="Mi Piace Ottenuti">	<span id="like_counter'+rid+'">0</span>';
				_("replyBtn_"+sid).disabled = false;
				_(ta).value = "";
		}
	}
	//user = autore originario del post!
	ajax.send("action=status_reply&sid="+sid+"&user="+user+"&data="+data);
}


/* ELIMINAZIONE POST */
function deleteStatus(statusid,statusbox){
	var conf = confirm("Premere OK per confermare l'eliminazione del post e dei relativi commenti");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
				_(statusbox).style.display = 'none';
				_("replytext_"+statusid).style.display = 'none';
				_("replyBtn_"+statusid).style.display = 'none';
		}
	}
	ajax.send("action=delete_status&statusid="+statusid);
}

/* ELIMINAZIONE COMMENTO */
function deleteReply(replyid,replybox, mi_piace_immagine, likes_counter){
	var conf = confirm("Premere OK per confermare l'eliminazione del commento");
	if(conf != true){
		return false;
	}
	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			_(replybox).style.display = 'none'; //faccio sparire il div del commento
			_(mi_piace_immagine).style.display = 'none'; //faccio sparire l'immagine "Mi piace:" che precede il numero likes
			_(likes_counter).style.display = 'none'; //faccio sparire il numero di like del post
		}
	}
	ajax.send("action=delete_reply&replyid="+replyid);
}

/* LIKE COMMENTO */
function nuovo_mi_piace_COMMENTO(replyid,replybox, contatore_likes, ricevitore_mipiace, mittente_mipiace){
	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var img = 'immagine_mi_piace';
			img += replyid;
			//salvo in una variabile il ritorno dell'oggetto ajax, che varrà la stringa "rimuovi_like"
			//se era già presente un like da parte di X sul commento di Y, mentre varrà "inserisci_like" se
			//non esisteva un like da parte di X sul commento di Y o se esisteva ed era stato eliminato
			//faccio uso della trim() e della search per eliminare/bypassare eventuali impurità nel ritorno della stringa
			var stringa_ritorno = ajax.responseText.trim();
			stringa_ritorno = stringa_ritorno.search("rimuovi_like");
			if(stringa_ritorno == -1){
				_(contatore_likes).innerHTML++; //incremento di 1 il numero di like del commento
				_(img).src = "immagini/like.png"; // e cambio immagine like
			}
			else if(stringa_ritorno !== -1){
				_(contatore_likes).innerHTML--; //decremento di 1 il numero di like del commento
				_(img).src = "immagini/like_not_pressed.png"; // e cambio immagine like
			}
		}
	}
	ajax.send("action=like_commento&replyid="+replyid+"&ricevitore_mipiace="+ricevitore_mipiace+"&mittente_mipiace="+mittente_mipiace);
}

/* LIKE POST */
function nuovo_mi_piace_POST(statusid,statusbox, contatore_likes, ricevitore_mipiace, mittente_mipiace){
	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true){
			var img = 'immagine_mi_piace';
			img += statusid;
			//salvo in una variabile il ritorno dell'oggetto ajax, che varrà la stringa "rimuovi_like"
			//se era già presente un like da parte di X sul post di Y, mentre varrà "inserisci_like" se
			//non esisteva un like da parte di X sul post di Y o se esisteva ed era stato eliminato
			//faccio uso della trim() e della search per eliminare/bypassare eventuali impurità nel ritorno della stringa
			var stringa_ritorno = ajax.responseText.trim();
			stringa_ritorno = stringa_ritorno.search("rimuovi_like");
			if(stringa_ritorno == -1){
				_(contatore_likes).innerHTML++; //incremento di 1 il numero di like del post
				_(img).src = "immagini/like.png"; // e cambio immagine like
			}
			else if(stringa_ritorno !== -1){
				_(contatore_likes).innerHTML--; //decremento di 1 il numero di like del post
				_(img).src = "immagini/like_not_pressed.png";
			}
		}
	}
	ajax.send("action=like_post&statusid="+statusid+"&ricevitore_mipiace="+ricevitore_mipiace+"&mittente_mipiace="+mittente_mipiace);
}

/* CONTROLLO MAX CARATTERI */
function statusMax(field, maxlimit) {
	if (field.value.length > maxlimit){
		alert(maxlimit+": Limite caratteri consentiti superato!");
		field.value = field.value.substring(0, maxlimit);
		field.value = "";
	}
}


/* SEGNALAZIONE POST */

function Segnala_Post(id_post, contenuto_post, username_coinvolto) {
	//var conf = confirm("Premere OK per confermare la segnalazione del post");
	var motivazione = prompt("Specificare il motivo della segnalazione e premere OK:");
	if(motivazione == null){
		return false;
	}
	if(motivazione == ""){
		alert("E' necessario specificare la motivazione della segnalazione!");
		return false;
	}

	var ajax = ajaxObj("POST", "funzioni_php_bacheca_post_commenti.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var stringa_ritorno = ajax.responseText.trim();
			stringa_ritorno = stringa_ritorno.search("gia_esistente");
			if(stringa_ritorno == -1)
				alert("Segnalazione effettuata con successo");
			else if(stringa_ritorno !== -1)
				alert("Segnalazione gi\u00E1 effettuata!");
		}
	}
	ajax.send("action=segnalazione_post&id_post="+id_post+"&contenuto_post="+contenuto_post+"&motivazione="+motivazione+"&username_coinvolto="+username_coinvolto);
}