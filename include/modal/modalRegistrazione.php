
<!-- MODAL REGISTRAZIONE -->

<div id="id01" class="modal">
  <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
  <form class="modal-content" name="form_registrazione" onsubmit="return validateFormRegister()" action="include/eregistrazione.php" method="POST">
    <div class="container">
      
      <h1>Registrati</h1>
      <p>Completa i seguenti campi.</p>
      <hr>
      
      <label><span><b>Sesso</b></span></label>
      <input type="radio" checked="checked" name="genere" value="M">
      <span>Uomo
      <input type="radio" name="genere" value="F">
      Donna
      </span>
      </label><br /><br />
      
      <label><b>Nome</b></label>
      <input type="text" placeholder="Inserisci il tuo Nome" name="nome" id="modal_registrazione_nome">
      <p class="class_err_registrazione_js_stile" id="err_nome_js_a_comparsa"> Nome non supportato, inserisci un nome valido. </p>
      
      <label><b>Cognome</b></label>
      <input type="text" placeholder="Inserisci il tuo Cognome" name="cognome" id="modal_registrazione_cognome">
      <p class="class_err_registrazione_js_stile" id="err_cognome_js_a_comparsa"> Cognome non supportato, inserisci un cognome valido. </p>
      
      <label><b>Email</b></label>
      <input type="text" placeholder="Inserisci la tua Email" name="email" id="modal_registrazione_email" required>
      <p class="class_err_registrazione_js_stile" id="err_email_js_a_comparsa"> Email non supportata, inserisci una email valida. </p>
      
      <label><b>Citt&agrave</b></label>
      <input type="text" placeholder="Specifica la tua citt&agrave di provenienza" name="citta" id="modal_registrazione_citta" required>
      
      <label><b>Username</b></label>
      <input type="text" placeholder="Scegli uno Username" name="username" id="modal_registrazione_username" required pattern="[A-Za-z0-9]{2,11}">

      <label><b>Password</b></label>
      <input type="password" placeholder="Scegli una Password" name="password" id="modal_registrazione_password" required pattern="^(?=.*\d).{8,15}$">
      <p class="class_err_registrazione_js_stile" id="err_password_js_a_comparsa"> La Password contenere almeno 8 caratteri, tra cui una lettera maiuscola, una lettera minuscola e un numero.</p>
      
      <label><b>Conferma Password</b></label>
      <input type="password" placeholder="Inserisci nuovamente la Password" name="password-repeat" id="modal_registrazione_password-repeat" required>
      <p class="class_err_registrazione_js_stile" id="err_password-repeat_js_a_comparsa"> Le due password con coincidono. </p>
      
      <label>
        <input type="checkbox" checked="checked" name="remember" style="margin-bottom:15px"> Ricordami
      </label>

      <p>Proseguendo dichiari di accettare i nostri <a href="#" style="color:dodgerblue">Termini e Condizioni</a>.</p>

      <div class="clearfix">
        <button type="button" onclick="document.getElementById('id01').style.display='none'" class="cancelbtn">Annulla</button>
        <button type="submit" class="signupbtn" name="BottoneRegistrati">Registrati</button>
      </div>
    
    </div>
  </form>
</div>