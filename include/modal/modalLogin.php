<!-- MODAL LOGIN -->

<div id="id02" class="modal1">
  
  <form class="modal-content1" action="include/elogin.php" method="POST">
    <div class="imgcontainer1">
      <span onclick="closemodalAccesso();" class="close1" title="Close Modal">&times;</span>
      <img src="immagini/avatar_empty.png" alt="Avatar" class="avatar">
    </div>
    
      <img width="60" height="50" src="immagini/admin-icon.png" alt="accesso admin"
      onclick="closemodalAccesso(); openmodalAmministrazione();" class="icona_admin" title="Accesso Amministrativo">
    
    <div class="container1">
      <label id="username"><b>Username</b></label>
      <input type="text" placeholder="Inserisci il tuo Username" name="username" required>

      <label id="psw"><b>Password</b></label>
      <input type="password" placeholder="Inserisci la Password" name="password" required>
      
      <?php
        if(isset($_SESSION["errore_login_php"])){
          echo '<p id="errore_login_stile">'.' '.$_SESSION['errore_login_php'].'<p>';
          unset($_SESSION['errore_login_php']);
        }
      ?>
      
      <button type="submit" name="BottoneAccedi" id="BottoneEntra">Entra</button>
      
      <label>
        <input type="checkbox" checked="checked" name="remember"> Ricordami
      </label>
    </div>

    <div class="container1" style="background-color:#f1f1f1">
      <button type="button" onclick="closemodalAccesso()" class="cancelbtn1">Annulla</button>
      <span class="psw"><a href="#">Password Dimenticata?</a></span>
    </div>
  </form>
</div>