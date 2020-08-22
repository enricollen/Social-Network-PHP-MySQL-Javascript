<?php

function scelta_immagine_like($statusid, $my_username){
	
	$conn = $GLOBALS['conn'];
    
    #recupero genere dell'utente loggato, se è maschio avrà avatar maschile, femminile altrimenti
    $sql_like_gia_messo = " SELECT COUNT(*) AS like_gia_messo
                            FROM likes
                            WHERE mittente_mipiace = '" . $my_username . "'
                                AND id_importato ='".$statusid."'";
                            
    $risultato = @mysqli_query($conn, $sql_like_gia_messo);
    $check = @mysqli_fetch_assoc($risultato);
    
	$ritorno = '';
	
    #se non ho gia messoun like a un dato post, visualizzerò l'immagine del bottone like "spenta", non colorata.
    if($check['like_gia_messo'] == 1)
        $ritorno = "immagini/like.png";
    #altrimenti visualizzerò l'immagine del bottone like "accesa", colorata.
    else
		$ritorno = "immagini/like_not_pressed.png";
		
	return $ritorno;
}
?>