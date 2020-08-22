<style>
 *{
    margin: 0;
    padding: 0;
    font-family: sans-serif;
}

nav{
    width: 100%;
    height: 4em;
    background: rgba(39, 18, 7, 0.91); /* NB!: per trasparenza #0005! altra combinazione accettata #000 e opacity: 0.9 */
    line-height: 4em;
    position: fixed; 
}

nav ul{
    float: right;
    margin-right: 0em;
}

#logo{
    float: left;
    margin-left: 7vh;
}

nav ul li{
    list-style-type: none;
    display: inline-block;
    transition: 0.8s all;
}

.list:hover{
    background-color: #FFA500;
}

section.sec1{
    box-sizing: border-box;
    background-color: #f7e1cf;
    height: auto;
}

#testo_errore{
 color: black;
}

section.immagine_errore{
    background-image: url("immagini/404error.png");
    background-repeat: no-repeat;
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 40%;
    height: 50vh;
}


</style>


<header id="index_pagina_errore">
<nav>
        <img src="immagini/Pausa_Caffe_Logo.png" id="logo" alt="logo" title="Pausa Caffè Logo" usemap="#home" height="85" width="95">
          <map name="home">
              <area shape="circle" coords="45,44,26" alt="imagemap" href="index.php">
          </map>
        <ul>
                <li class="list"><a href="index.php">Torna a Home</a></li>
        </ul>
</nav>

<?php

echo'<section class="sec1">
      <section class="immagine_errore"></section>
       <h2 id="testo_errore">Oops... qualcosa &egrave andato storto:</h2><br />';
    
 if( isset($_GET['errore']) ){
        echo "<p id='box_errore'>" . $messaggi_errore[$_GET['errore']] . "</p>";
        session_destroy(); // logout e redirect a index
 }
 
 echo '<br /><a href="index.php" id="testo_errore">Torna alla pagina d accesso</a></section>';
?>

</header>