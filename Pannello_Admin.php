<?php
  include("include/setup.php");
  #Controllo se si arriva a questa pagina da login ADMIN, svento eventuali tentativi bypass
  check_login();
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<title>PausaCaff&egrave - Pannello Admin</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_pannello_admin.css">
</head>

<body>

<?php  
  include("include/header_admin.php");

  #inizializzo variabile $id_admin
  $id_admin = $_SESSION['idadmin'];
  
  $query_check_nuove_segnalazioni =
                    'SELECT COUNT(*) AS Numero_Segnalazioni_Non_Visionate
                      FROM segnalazioni
                      WHERE stato_segnalazione = "in attesa";';
                    
$risultato = @mysqli_query($conn, $query_check_nuove_segnalazioni);
                                    
$quante_nuove_segnalazioni = mysqli_fetch_assoc($risultato);
$Numero_Nuove_Segnalazioni = $quante_nuove_segnalazioni['Numero_Segnalazioni_Non_Visionate'];
?>
 
  <section class="bacheca">
    
            <div class="card">
        
              <div class="immagine">
                <img src="immagini/report_judge.png" alt="Gestione Segnalazioni" title="Gestione Segnalazioni">
              </div>
              
              <div class="titolo">
                <h1>Segnalazioni
                <?php if($Numero_Nuove_Segnalazioni >=1)
                echo '<span id="numerino_notifiche" class="dot"><small style="color: white">' . $Numero_Nuove_Segnalazioni . '</small></span>';
                else;
              ?></h1>
              </div>
              
              <div class="parte_bassa">
                <p>Gestione delle segnalazioni relative a post inviate da utenti</p>
                <form action="gestione_segnalazioni.php" method="post">
                  <!-- Redirect + cambio stato segnalazioni in "visionate" -->
                  <input type="submit" class="Bottoni_Pannello_Admin" name="Segnalazioni" value="VAI"> 
                </form>
              </div>
            </div>
            
            <div class="card">
        
              <div class="immagine">
                <img src="immagini/manteinance.png" alt="Manutenzione DB" title="Manutenzione Database">
              </div>
              
              <div class="titolo">
                <h1>Manutenzione</h1>
              </div>
              
              <div class="parte_bassa">
                <p>Funzionalit&agrave di pulizia e gestione utenza</p>
                <button onclick="location.href = 'Manutenzione.php';" class="Bottoni_Pannello_Admin">VAI</button>
              </div>
      
            </div>
            
            <div class="card">
        
              <div class="immagine">
                <img src="immagini/analytics.png" alt="Banner Analytics" title="Area Analytics">
              </div>
              
              <div class="titolo">
                <h1>Analisi Dati</h1>
              </div>
              
              <div class="parte_bassa">
                <p>Raccolta informazioni e elaborazione statistiche</p>
                <button onclick="location.href = 'analisi_statistiche.php';" class="Bottoni_Pannello_Admin">VAI</button>
              </div>
      
            </div>


        </section>


</body>
<script src="JS/main_script.js"> </script>
<script src="JS/pannello_admin.js"> </script>
<script src="JS/ajax.js"> </script>
</html>