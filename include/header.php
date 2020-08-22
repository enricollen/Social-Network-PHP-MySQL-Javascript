    <nav>
        <img src="immagini/Pausa_Caffe_Logo.png" id="logo" alt="logo" title="Pausa Caffè Logo" usemap="#home" height="85" width="95">
          <map name="home">
              <area shape="circle" coords="45,44,26" alt="imagemap"
                <?php
                    if(isset($_SESSION["username"]))
                        echo 'href="bacheca.php">';
                    else
                        echo 'href="index.php">';
                ?>
          </map>
        <ul>
            	<?php /* Verifico se l'utente è loggato e nel caso mostro alcuni bottoni piuttosto che altri */
                if(!isset($_SESSION["username"])){
                
                echo '
                    <li class="list"><a href="index.php">Home</a></li>
                    <li class="list"><a href="chisiamo.html">Chi Siamo</a></li>
                    <li class="list"><a onclick="openmodalAccesso()">Login</a></li>
                    <li class="list"><a onclick="openmodalRegistrazione()">Registrati</a></li>';
                }
                else{
                    ##snippet codice gestione search bar##
                    echo '<li class="list"><div id="prova">';include("include/SearchBar.php");echo'</div></li>';
                    echo '<li class="list"><a href="bacheca.php">Bacheca</a></li>';
                    echo '<li class="list"><a href="membri.php">Membri</a></li>';
                    echo '<li class="list"><a href="amici.php">Amici</a></li>';
                    echo '<li class="list"><a href="richiesteamicizia.php">Richieste</a></li>';
                    echo '<li class="list"><a onclick="openmodalUtente()"><strong>'.' '. $_SESSION["username"] . '</strong></a></li>';
                    ##snippet codice gestione notifiche##
                    echo '<li class="list">';include("include/gestione_notifiche.php");echo'</li>';
                    echo '<li class="list"><a href="logout.php">Logout</a></li>';
                }
              ?>
        </ul>
    </nav>