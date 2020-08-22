<!-- MODAL UTENTE LOGGATO -->

<div id="id03" class="modal2">
  
  <div class="modal-content2">
    <div class="imgcontainer2">
      <span onclick="closemodalUtente()" class="close2" title="Close Modal">&times;</span> <!-- Span chiusura modal utente loggato -->
      
      <?php
        #Stampo l'avatar corrispondente all'utente (default o custom se lo hanno uploadato in precedenza)
        include("include/RecuperaAvatar.php");
      ?>
      
    </div>

    <div class="container2">
		 <p> Benvenuto, <strong><?php echo $_SESSION['username']; ?></strong> </p>
    <br>
    <br>
      <button type="button" onclick="location.href = 'PaginaPersonale.php?user=me';" class="bottoni-modal-utente">Pagina Personale</button>
      <button type="button" onclick="location.href = 'ModificaProfilo.php';" class="bottoni-modal-utente">Modifica Profilo</button>
      <button type="button" onclick="location.href = 'logout.php';" class="bottoni-modal-utente">Logout</button>
    </div>
    <div class="container2" style="background-color:#f1f1f1">
    </div>
  </div>
</div>