<?php
  include("include/setup.php");
?>

<!DOCTYPE html>

<html lang="it">
  <meta charset="utf-8">
<head>
    <title>PausaCaff&egrave</title>
    <link rel="shortcut icon" type="image/png" href="immagini/coffee_icon.ico"/>
    <link rel="stylesheet" type="text/css" href="CSS/stile_index.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Login.css">
    <link rel="stylesheet" type="text/css" href="CSS/stile_modal_Registrazione.css">
</head>
<body>

<?php
  include("include/header.php");
?>

<!-- MODAL REGISTRAZIONE -->
<?php include("include/modal/modalRegistrazione.php"); ?>
<!-- FINE MODAL REGISTRAZIONE -->

<!-- MODAL AMMINISTRAZIONE -->
<?php include("include/modal/modalAmministrazione.php"); ?>
<!-- FINE MODAL AMMINISTRAZIONE -->

<!-- MODAL LOGIN -->
<?php include("include/modal/modalLogin.php"); ?>
<!-- FINE MODAL LOGIN -->
  
<section class="sezioniHome">
<img src="immagini/banner_index2.jpg" alt="Banner Principale" id="Banner_Principale">
<h2 id="slogan">Pausa Caff&egrave: la tua pausa quotidiana</h2>
</section>


<img src="immagini/connettiti2.jpg" alt="Connettiti" id="Connettiti_Immagine">
<section class="sezioniHome">
  <h2 class="titoli_home">Connettiti</h2>
<pre id="Connettiti_Testo">
<b>CONNETTITI </b>
Rilassarsi, socializzare, scambiarsi parole, pensieri ed idee.  
Davanti ai distributori automatici si parla di tutto:
sport, vacanze, politica, lavoro, pettegolezzi... 
Per te, che ami quei cinque minuti di chiacchiere per questi e mille altri motivi,
nasce PausaCaff&egrave, il nuovo Social tutto italiano
grazie al quale connettersi &egrave semplice, spezzare la routine un piacere. 
</pre>
</section>


<img src="immagini/condividi2.jpg" alt="Condividi" id="Condividi_Immagine">
<section class="sezioniHome">
  <h2 class="titoli_home">Condividi</h2>
<pre id="Condividi_Testo">
<b>CONDIVIDI </b>
Condividere non &egrave solo una moda, &egrave una necessit&agrave! 
Il desiderio di essere ascoltati risponde al nostro bisogno di fare comunit&agrave:
condividendo, hai l'opportunit&agrave di aggiungere valore ad una tua idea o ad una tua passione.
Quale modo migliore di PausaCaff&egrave per rendere partecipi i tuoi amici di ci&ograve che ami
e tenerli aggiornati su ci&ograve che succede intorno a te?
</pre>
</section>


<img id="gif-coinvolgi" src="immagini/coinvolgi.gif" onmouseover="document.getElementById('gif-coinvolgi').src='immagini/coinvolgi.gif'" onmouseout="document.getElementById('gif-coinvolgi').src='immagini/cincin_statico.jpg'" alt="immagine_coinvolgi"/>
<section class="sezioniHome">
  <h2 class="titoli_home">Coinvolgi</h2>
<pre id="Coinvolgi_Testo">
<b>COINVOLGI</b> 
Coinvolgere significa innanzitutto emozionare e creare legami.
Dare pi&ugrave valore ai tuoi contenuti ed interagire con le persone che ami
da oggi &egrave pi&ugrave semplice e intuitivo grazie a PausaCaff&egrave!
</pre>
</section>

<img src="immagini/app_pausacaffe2.jpg" alt="Applicazione Smartphone Disponibile" title="App PausaCaff&egrave disponibile!" id="mobile_app_immagine">
<section class="sezioniHome">
  <h2 class="titoli_home">App</h2>
<pre id="Mobile_App_Testo">
<b>Sempre con te!</b>
Da oggi &egrave disponibile la nuova App PausaCaff&egrave per smartphone!
Il tuo social preferito sempre a portata di mano,
da oggi PausaCaff&egrave &egrave disponibile anche su piattaforma mobile per sistemi iOS e Android.
Scansiona il codice QR qua sotto e scarica subito:
</pre>
<img src="immagini/qr.png" title="Scansiona il codice QR" alt="codice QR App" id="qr_immagine">
</section>
<section class="vuoto"><h2 class="titoli_home">CodiceQR</h2></section>

<footer id="footer">&copy; 2018 Pausa Caff&egrave</footer>
</body>
<script src="JS/main_script.js"> </script>
</html>
