<?php
  include("setup.php");

#Controllo se si arriva a questa pagina da loggati
if(	isset($_SESSION['iduser'])	){
  
  /***** CASO INSERIMENTO NUOVO INTERESSE *****/
  if(isset($_POST['interesse'])){
    
    $my_username = $_SESSION['username'];
    $nuovo_interesse = $_POST['interesse'];
    $comandoSQL = "INSERT INTO interessi VALUES('".$my_username."','".$nuovo_interesse."')";
                            
    $risultato3 = @mysqli_query($conn, $comandoSQL);
    if($risultato3)header("Location: ../ModificaProfilo.php");
  }
}//chiude controllo utente loggato

#Altrimenti: utente non autenticato, redirect a index (tentativo bypass)
else{
	header("Location: ../paginaErrore.php?errore=autenticazione_richiesta"); //tentativo bypass, senza passare per metodo post
}

?>