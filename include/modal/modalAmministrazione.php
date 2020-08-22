<!-- MODAL AMMINISTRAZIONE -->

<div id="id05" class="modal1">
  
  <form class="modal-content1" action="include/elogin.php" method="POST">
    <div class="imgcontainer1">
      <span onclick="closemodalAmministrazione()" class="close1" title="Close Modal">&times;</span>
      <img src="immagini/admin-icon2.png" alt="Avatar" class="avatar">
    </div>
    
      <img width="45" height="45" src="immagini/avatar_empty.png" alt="accesso admin"
      onclick="closemodalAmministrazione(); openmodalAccesso();" class="icona_admin" title="Accesso utenti">
    
    <div class="container1">
      <label id="adminID"><b>Admin ID</b></label>
      <input type="text" placeholder="Identificativo Admin" name="adminID" required>

      <label id="psw_admin"><b>Password</b></label>
      <input type="password" placeholder="Inserisci la Password" name="password" required>
      
      <?php
        if(isset($_SESSION["errore_login_php"])){
          echo '<p id="errore_login_stile">'.' '.$_SESSION['errore_login_php'].'<p>';
          unset($_SESSION['errore_login_php']);
        }
      ?>
      <input type="text" name="accesso_amministrativo" style="display: none;">
      <button type="submit" name="BottoneAccedi">Entra</button>
    </div>

    <div class="container1" style="background-color:#f1f1f1">
      <button type="button" onclick="closemodalAmministrazione()" class="cancelbtn1">Annulla</button>
    </div>
  </form>
</div>