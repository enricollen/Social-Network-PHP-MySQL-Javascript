<?php
$my_iduser = $_SESSION['iduser'];

echo '<div id="id04" style="display: none" class="modal2">
        <div class="modal-content2">
                <div class="container2">
			<span onclick="closemodalNotifiche()" class="close2" title="Close Modal">&times;</span>';
        
        $query_recupera_notifiche_piu_recenti =
		'SELECT U.username AS Account_Terzo_Coinvolto, D.tipologia_notifica, D.timestamp_notifica
        FROM (
        SELECT N.iduser_che_causa_la_notifica AS Account_Terzo_Coinvolto, N.tipologia_notifica, N.timestamp_notifica
        FROM notifiche N INNER JOIN users U ON N.iduser_destinatario_notifica = U.iduser
        WHERE N.tipologia_notifica = "richiesta amicizia"
                AND N.iduser_destinatario_notifica = "' . $my_iduser . '"
                AND N.timestamp_notifica > U.ultimo_check_notifiche
                                        
                    UNION ALL 
                    
        SELECT N.iduser_destinatario_notifica AS Account_Terzo_Coinvolto, N.tipologia_notifica, N.timestamp_notifica
        FROM notifiche N INNER JOIN users U ON N.iduser_che_causa_la_notifica = U.iduser
        WHERE N.tipologia_notifica = "amicizia confermata"
                AND N.iduser_che_causa_la_notifica = "' . $my_iduser . '"
                AND N.timestamp_notifica > U.ultimo_check_notifiche
				
					UNION ALL
				
		SELECT N.iduser_che_causa_la_notifica AS Account_Terzo_Coinvolto, N.tipologia_notifica, N.timestamp_notifica
        FROM notifiche N INNER JOIN users U ON N.iduser_destinatario_notifica = U.iduser
        WHERE N.tipologia_notifica = "nuovo post amico"
                AND N.iduser_destinatario_notifica = "' . $my_iduser . '"
                AND N.timestamp_notifica > U.ultimo_check_notifiche
		
					UNION ALL
					
		SELECT N.iduser_che_causa_la_notifica AS Account_Terzo_Coinvolto, N.tipologia_notifica, N.timestamp_notifica
        FROM notifiche N INNER JOIN users U ON N.iduser_destinatario_notifica = U.iduser
        WHERE N.tipologia_notifica = "nuovo commento su proprio post"
				AND N.iduser_destinatario_notifica = "' . $my_iduser . '"
                AND N.timestamp_notifica > U.ultimo_check_notifiche
				
					UNION ALL
					
		SELECT N.iduser_che_causa_la_notifica AS Account_Terzo_Coinvolto, N.tipologia_notifica, N.timestamp_notifica
        FROM notifiche N INNER JOIN users U ON N.iduser_destinatario_notifica = U.iduser
        WHERE N.tipologia_notifica = "nuovo like su proprio post"
				AND N.iduser_destinatario_notifica = "' . $my_iduser . '"
                AND N.timestamp_notifica > U.ultimo_check_notifiche
				
					UNION ALL
					
		SELECT N.iduser_che_causa_la_notifica AS Account_Terzo_Coinvolto, N.tipologia_notifica, N.timestamp_notifica
        FROM notifiche N INNER JOIN users U ON N.iduser_destinatario_notifica = U.iduser
        WHERE N.tipologia_notifica = "nuovo like su proprio commento"
				AND N.iduser_destinatario_notifica = "' . $my_iduser . '"
                AND N.timestamp_notifica > U.ultimo_check_notifiche
				
        ) AS D INNER JOIN users U ON D.Account_Terzo_Coinvolto = U.iduser
        ORDER BY D.timestamp_notifica DESC';
	
        #Eseguo la query per contare il NUMERO di notifiche nuove											
	$risultato = mysqli_query($conn, $query_recupera_notifiche_piu_recenti);
        #Ricavo il NUMERO delle notifiche da mostrare
	$quante_notifiche_da_mostrare = mysqli_num_rows($risultato);
        #Creo una variabile d'appoggio contenente la parte terminale della query soprastante, ovvero:
        # LIMIT + $quante_notifiche_da_mostrare => in modo da mostrare nel modal solo le N nuove notifiche
        $append_limite_notifiche_da_mostrare = " LIMIT " . $quante_notifiche_da_mostrare . ";";
        #Append con stringa della query, a cui attacco LIMIT + $quante_notifiche_da_mostrare
        $query_recupera_notifiche_piu_recenti = $query_recupera_notifiche_piu_recenti . $append_limite_notifiche_da_mostrare;
        
        #Adesso eseguo query con parte finale (limite di notifiche visualizzate, tante quante sono le nuove notifiche)
        $risultato = mysqli_query($conn, $query_recupera_notifiche_piu_recenti);

        $i = 1;
        
		#elenco di tutte le tipologie di notifica possibili, stampo le N notifiche non ancora visualizzate
        while($row = mysqli_fetch_assoc($risultato)){
        
                echo '<p>' . $row['timestamp_notifica'] . ' ('. $i . ') Notifica:  <br /> ';
                
                if($row['tipologia_notifica'] == "richiesta amicizia")
                        echo $row['Account_Terzo_Coinvolto'] . ' ti ha inviato una richiesta di amicizia';
						
                else if($row['tipologia_notifica'] == "amicizia confermata")
                        echo $row['Account_Terzo_Coinvolto'] . ' ha accettato la tua richiesta di amicizia';
				
				else if($row['tipologia_notifica'] == "nuovo post amico")
                        echo $row['Account_Terzo_Coinvolto'] . ' ha appena postato qualcosa in Bacheca!';
						
				else if($row['tipologia_notifica'] == "nuovo like su proprio post")
                        echo $row['Account_Terzo_Coinvolto'] . ' ha appena messo like a un tuo post!';
						
				else if($row['tipologia_notifica'] == "nuovo like su proprio commento")
                        echo $row['Account_Terzo_Coinvolto'] . ' ha appena messo like a un tuo commento!';
				
				else if($row['tipologia_notifica'] == "nuovo commento su proprio post")
                        echo $row['Account_Terzo_Coinvolto'] . ' ha appena commentato un tuo post!';
						
                echo '</p><br /><br />';
          $i++;
        }
       echo '</div>
            
           </div>
          
        </div>';
?>