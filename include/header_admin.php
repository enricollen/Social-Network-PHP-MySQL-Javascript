    <nav>
        <img src="immagini/Pausa_Caffe_Logo.png" id="logo" alt="logo" title="Pausa Caffè Logo" usemap="#home" height="85" width="95">
          <map name="home">
              <area shape="circle" coords="45,44,26" alt="imagemap"
                <?php
                    if(!isset($_SESSION["idadmin"]))
                        echo 'href="index.php">';
                    else
                        echo 'href="Pannello_Admin.php">';
                ?>
          </map>
        <ul>
            	<?php
                    echo '<li class="list"><a href="Pannello_Admin.php">Pannello</a></li>';
                    echo '<li class="list"><strong style="color: white;">'.' '. $_SESSION["idadmin"] . '</strong></li>';
                    echo '<li class="list"><a href="logout.php">Logout</a></li>';
              ?>
        </ul>
    </nav>