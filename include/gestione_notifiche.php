<script src="JS/main_script.js"> </script>
<!-- file che contiene la funzione js ajax che richiama la funzione PHP per l'Update dell'ultimo check delle notifiche -->
<script src="JS/check_notifiche_ajax.js"> </script> 


<?php
include("modal/modalNotifiche.php");

#recupero iduser
$my_iduser = $_SESSION['iduser'];

#alternativa al join della query seguente per recuperare il timestamp dell'ultimo check notifiche
/*$query_recupero_timestamp_ultimo_check = 'SELECT ultimo_check_notifiche AS timestamp
                                          FROM users
                                          WHERE iduser = "' . $my_iduser . '"';
                                                                                                    
$risultato = @mysqli_query($conn, $query_recupero_timestamp_ultimo_check);
                    
$timestamp_last_check = mysqli_fetch_assoc($risultato);*/


#controllo, basandomi sul timestamp dell'ultimo accesso, se esitono delle notifiche più recenti di esso
#(quindi non ancora visualizzate), controllo pescando dalla tabella notifiche.
#le notifiche totali sono date da (1) + (2):
# (1) se ricevo una richiesta di amicizia
# (2) se mi accettano una richiesta che ho inviato
$query_check_nuove_notifiche =
                    'SELECT SUM(D.NNotifiche) AS Numero_Nuove_Notifiche_Che_Mi_Riguardano
                    FROM (
                    SELECT COUNT(*) AS NNotifiche
                    FROM notifiche N INNER JOIN users U ON N.iduser_destinatario_notifica = U.iduser
                    WHERE (N.tipologia_notifica = "richiesta amicizia"
                        OR N.tipologia_notifica = "nuovo like su proprio commento"
                        OR N.tipologia_notifica = "nuovo like su proprio post"
                        OR N.tipologia_notifica = "nuovo commento su proprio post"
                        OR N.tipologia_notifica = "nuovo post amico"
                        )
                                        AND N.iduser_destinatario_notifica = "' . $my_iduser . '"
                                        AND N.timestamp_notifica > U.ultimo_check_notifiche
                    UNION ALL
                    SELECT COUNT(*) AS NNotifiche
                    FROM notifiche N INNER JOIN users U ON N.iduser_che_causa_la_notifica = U.iduser
                    WHERE N.tipologia_notifica = "amicizia confermata"
                                        AND N.iduser_che_causa_la_notifica = "' . $my_iduser . '"
                                        AND N.timestamp_notifica > U.ultimo_check_notifiche                                        
                    ) AS D;';
                    
$risultato = @mysqli_query($conn, $query_check_nuove_notifiche);
                                    
$quante_nuove_notifiche = mysqli_fetch_assoc($risultato);
$Numero_Nuove_Notifiche = $quante_nuove_notifiche['Numero_Nuove_Notifiche_Che_Mi_Riguardano'];

# se ho almeno 1 caso che rientra in (1) o (2), allora visualizzo la gif della notifica!
if($Numero_Nuove_Notifiche >=1){
    
/*tramite l'onclick interno all'immagine della campanella delle notifiche, richiamo 2 funzioni differenti:
1) la prima funzione (residente in un file esterno di passaggio) di fatto crea un oggetto XMLHttp che tramite ajax
invia richiesta a una pagina d'elaborazione PHP che effettua l'update del campo ultimo_check_notifiche per l'utente loggato
2) la seconda funzione invece apre semplicemente un modal dedicato alle notifiche dell'utente loggato, stampandone alcune tra le
più recenti
*/
echo '<span id="numerino_notifiche" class="dot"><small style="color: white">' . $Numero_Nuove_Notifiche . '</small></span>
<li>
<input id="campanella_notifiche" type="image" width="35" height="35" alt="notification" src="immagini/notification_bell.gif"
onclick="richiama_aggiornamento_check_notifiche_php_tramite_ajax(); openmodalNotifiche();"></li>';

}
?>