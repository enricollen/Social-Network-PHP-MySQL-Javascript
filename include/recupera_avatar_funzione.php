<?php

function recupera_avatar($profilo_di){
	
	$conn = $GLOBALS['conn'];
	$my_username = $_SESSION['username'];
    $username_target = ($profilo_di == "me") ? $my_username : $profilo_di;
    
    #recupero genere dell'utente loggato, se è maschio avrà avatar maschile, femminile altrimenti
    $recupera_genere = "SELECT nome, cognome, citta, genere, avatar
                        FROM users
                        WHERE username = '" . $username_target . "' ";
                            
    $risultato = @mysqli_query($conn, $recupera_genere);
    $info = @mysqli_fetch_assoc($risultato);
        
    #se non hanno mai uploadato un avatar custom, stampo quello default maschile/femminile
    if($info['avatar'] == 'default'){
			if( ($info['genere'] == "F") )
			  $ritorno = '<img width="50" height="50" src="immagini/avatar_femminile.png" alt="Avatar Default Femminile">';
			else if( ($info['genere'] == "M") )
			  $ritorno = '<img width="50" height="50" src="immagini/avatar_maschile.png" alt="Avatar Default Maschile">';
		}
    #altrimenti l utente ha un avatar custom 
    else{
      $query_recupera_avatar_custom = " SELECT avatar
                                        FROM users
                                        WHERE username = '". $username_target ."'";
                            
      $risultato2 = mysqli_query($conn, $query_recupera_avatar_custom);
      $avatar_custom = mysqli_fetch_assoc($risultato2);
      $ritorno = "<img width='50' height='50' src='immagini/avatar/".$avatar_custom['avatar']."' alt='Avatar Personale'>";
    }
	return $ritorno;
}
?>