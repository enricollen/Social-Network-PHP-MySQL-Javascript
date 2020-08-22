<?php
    #Passare "$profilo_di" settato con l'username terzo di cui si vuole ricavare l'avatar, altrimenti
    #stampa il proprio avatar
    
    $my_username = $_SESSION['username'];
    $username_target = (isset($profilo_di) && $profilo_di !== "me") ? $profilo_di : $my_username;
    
    #recupero genere dell'utente loggato, se è maschio avrà avatar maschile, femminile altrimenti
    $recupera_genere = "SELECT U.nome, U.cognome, U.citta, U.genere, U.avatar, I.eta, I.occupazione, I.stato_sentimentale
                        FROM users U NATURAL LEFT OUTER JOIN informazioni I
                        WHERE U.username = '" . $username_target . "' ";
                            
    $risultato = mysqli_query($conn, $recupera_genere);
    $info = mysqli_fetch_assoc($risultato);
        
    #se non hanno mai uploadato un avatar custom, stampo quello default maschile/femminile
    if($info['avatar'] == 'default'){
			if( ($info['genere'] == "F") )
			  echo '<img width="100" height="100" src="immagini/avatar_femminile.png" alt="Avatar Default Femminile">';
			else if( ($info['genere'] == "M") )
			  echo '<img width="100" height="100" src="immagini/avatar_maschile.png" alt="Avatar Default Maschile">';
		}
    #altrimenti l utente ha un avatar custom 
    else{
      $query_recupera_avatar_custom = " SELECT avatar
                                        FROM users
                                        WHERE username = '". $username_target ."'";
                            
      $risultato = @mysqli_query($conn, $query_recupera_avatar_custom);
      $avatar_custom = @mysqli_fetch_assoc($risultato);
      echo "<img width='100' height='100' src='immagini/avatar/".$avatar_custom['avatar']."' alt='Avatar Personale'>";
    }

?>