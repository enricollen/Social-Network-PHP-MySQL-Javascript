function _(x){
	return document.getElementById(x);
}

// Get the modal
var modal = document.getElementById('id01');
var modal1 = document.getElementById('id02');
var modal2 = document.getElementById('id03');
var modal3 = document.getElementById('id04');
var modal4 = document.getElementById('id05');
var CampanellaNotifiche = document.getElementById('campanella_notifiche');
var NumerinoNotifiche = document.getElementById('numerino_notifiche');


function openmodalRegistrazione(){
	modal.style.display='block';
}

function closemodalRegistrazione(){
	modal.style.display='none';
}

function openmodalAccesso(){
	modal1.style.display='block';
}

function closemodalAccesso(){
	modal1.style.display='none';
}

function openmodalUtente(){
	modal2.style.display='block';
}

function closemodalUtente(){
	modal2.style.display='none';
}

function openmodalNotifiche(){
	modal3.style.display='block';
}

function openmodalAmministrazione(){
	modal4.style.display='block';
}

function closemodalAmministrazione(){
	modal4.style.display='none';
}

//per il modal delle notifiche, chiudo modal in più faccio sparire anche la campanella e il numerino delle notifiche
function closemodalNotifiche(){
	modal3.style.display='none';
	CampanellaNotifiche.style.display='none';
	NumerinoNotifiche.style.display='none';
}

/*##############GESTIONE CHIUSURA MODAL CLICK OUTSIDE###############*/

// Quando un utente clicca al di fuori dei modal, essi vengono automaticamente chiusi

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
	if (event.target == modal1) {
        modal1.style.display = "none";
    }
	if (event.target == modal2) {
        modal2.style.display = "none";
    }
	if (event.target == modal4) {
        modal4.style.display = "none";
    }
	//per il modal delle notifiche, chiudo modal in più faccio sparire anche la campanella e il numerino delle notifiche
	if (event.target == modal3) {
        modal3.style.display = "none";
		CampanellaNotifiche.style.display='none';
		NumerinoNotifiche.style.display='none';
    }
};


/*################FUNZIONI VALIDAZIONE EMAIL################*/

function validateEmail(email) { /* Funzione per validare email */
  	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
  	return re.test(email);
}


function validateNameInput(nomeinput){
	var reex = /^[a-zA-Z0-9]{2,10}$/;
	return reex.test(nomeinput);
}

function validatePassword(password){
	var rex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
	return rex.test(password);
}
	
/*##################################*/

function validateFormRegister(){

	var _errore = 0;

/*********CONTROLLO EMAIL***********/

	var _email = document.forms["form_registrazione"]["email"].value;
	
	if(!validateEmail(_email)){
		document.getElementById("modal_registrazione_email").style.backgroundColor = "#f44336"; /* setta colore input a red */
		element_email.focus();
		document.getElementById("err_email_js_a_comparsa").style.display='block';
		event.preventDefault(); /* impedisce il refresh della pagina con una submit come se fosse andato a buon fine*/
		_errore=1;
	}
	else {
		document.getElementById("err_email_js_a_comparsa").style.display='none';
		document.getElementById("modal_registrazione_email").style.backgroundColor='white';
	}

/*********CONTROLLO NOME***********/
	var _nomeform = document.forms["form_registrazione"]["nome"].value;

	if(!validateNameInput(_nomeform)){ /* Controlla nome dai 4 ai 10 caratteri composto solo da caratteri alfanumerici */
		document.getElementById("modal_registrazione_nome").style.backgroundColor = "#f44336"; /* setta colore input a red */
		document.getElementById("modal_registrazione_nome").focus();
		document.getElementById("err_nome_js_a_comparsa").style.display='block';
		event.preventDefault();
		_errore=1;
	}
	else {
		document.getElementById("err_nome_js_a_comparsa").style.display='none';
		document.getElementById("modal_registrazione_nome").style.backgroundColor='white';
	}

	
/*********CONTROLLO COGNOME***********/
	var _cognome = document.forms["form_registrazione"]["cognome"].value;

	if(!validateNameInput(_cognome)){ /* Controlla cognome dai 4 ai 10 caratteri composto solo da caratteri alfanumerici */
		document.getElementById("modal_registrazione_cognome").style.backgroundColor = "#f44336"; /* setta colore input a red */
		document.getElementById("modal_registrazione_cognome").focus();
		document.getElementById("err_cognome_js_a_comparsa").style.display='block';
		event.preventDefault();
		_errore=1;
	}
	else {
		document.getElementById("err_cognome_js_a_comparsa").style.display='none';
		document.getElementById("modal_registrazione_cognome").style.backgroundColor='white';
	}
	
	
/*********CONTROLLO PASSWORD***********/
	var _password = document.forms["form_registrazione"]["password"].value;

	if(!validatePassword(_password)){ /* Controlla password, almeno 8 caratteri di cui una lettera, un numero e un carattere speciale*/
		document.getElementById("modal_registrazione_password").style.backgroundColor = "#f44336"; /* setta colore input a red */
		document.getElementById("modal_registrazione_password").focus();
		document.getElementById("err_password_js_a_comparsa").style.display='block';
		event.preventDefault();
		_errore=1;
	}
	else {
		document.getElementById("err_password_js_a_comparsa").style.display='none';
		document.getElementById("modal_registrazione_password").style.backgroundColor='white';
	}

	
/*********CONTROLLO RIPETIZIONE PASSWORD***********/
	var psw_repeat = document.forms["form_registrazione"]["password-repeat"].value;

	if(psw_repeat != _password){
		document.getElementById("modal_registrazione_password-repeat").style.backgroundColor = "#f44336"; /* setta colore input a red */
		document.getElementById("modal_registrazione_password-repeat").focus();
		document.getElementById("err_password-repeat_js_a_comparsa").style.display='block';
		event.preventDefault();
		_errore=1;
	}
	else {
		document.getElementById("err_password-repeat_js_a_comparsa").style.display='none';
		document.getElementById("modal_registrazione_password-repeat").style.backgroundColor='white';
	}

	if(_errore === 1) return false;
		else return true;
}


/********** APPARIZIONE/SPARIZIONE SEARCH BAR + ICONA CERCA IN NAVBAR  **********/


var searchbar = document.getElementById('searchbar');
var icona_cerca_utente = document.getElementById('icona_cerca_utente');


function mostra_searchbar_nascondi_icona(){
	icona_cerca_utente.style.display ='none';
	searchbar.style.display='block';
}

function nascondi_searchbar_mostra_icona(){
	icona_cerca_utente.style.display ='block';
	searchbar.style.display='none';
}

/*** STAMPA ALERT MODIFICA ***/
function prompt_modifica() {
  alert("Modifiche apportate correttamente!");
}

/*** STAMPA ALERT SUCCESSO ***/
function prompt_successo() {
  alert("Azione eseguita con successo");
}