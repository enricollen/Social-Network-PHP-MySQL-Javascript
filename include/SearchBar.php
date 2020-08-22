<?php

#stampo la search bar collegata alla pagina di elaborazione tramite una form gestita mediante metodo GET
echo '
    <img src="immagini/User_Search_Logo.png"  height="38" id="icona_cerca_utente" alt="icona cerca utente"
    title="Cerca un utente" style="display:block;" onmouseover="mostra_searchbar_nascondi_icona()" height="45" width="45">
    
    <li>
        <form action="RisultatoSearchBarMembri.php" method="GET">
            <input type="search" style="display:none;" onmouseout="nascondi_searchbar_mostra_icona()" id="searchbar" name="Ricerca"
            placeholder="Cerca..">
        </form>
    </li>';

?>