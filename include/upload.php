<?php

if(isset($_POST['submit'])){
// Configurazione directory e estensioni
    $targetDir = "immagini/album/";
    $allowTypes = array('jpg','png','jpeg','gif');
    
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = '';
    if(!empty(array_filter($_FILES['files']['name']))){
        foreach($_FILES['files']['name'] as $key=>$val){
            // path di upload
            $fileName = basename($_FILES['files']['name'][$key]);
            $targetFilePath = $targetDir . $fileName;
            
            // Check validita estensione file
            $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
            if(in_array($fileType, $allowTypes)){
                // Upload immagini singole
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){
                    // sql di inserimento
                    $insertValuesSQL .= "('".$fileName."', NOW(),'".$my_username."'),";
                }else{
                    $errorUpload .= $_FILES['files']['name'][$key].', ';
                }
            }else{
                $errorUploadType .= $_FILES['files']['name'][$key].', ';
            }
        }
        
        if(!empty($insertValuesSQL)){
            $insertValuesSQL = trim($insertValuesSQL,',');
            // Inserimento immagini
            $insert = mysqli_query($conn, "INSERT INTO album_foto (file_name, uploaded_on, username) VALUES $insertValuesSQL");
            if($insert){
                $errorUpload = !empty($errorUpload)?'Errore caricamento: '.$errorUpload:'';
                $errorUploadType = !empty($errorUploadType)?'Errore file: '.$errorUploadType:'';
                $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType;
                $statusMsg = "Le immagini sono state correttamente caricate.".$errorMsg;
            }else{
                $statusMsg = "Si &egrave verificato un errore durante il caricamento.";
            }
        }
    }else{
        $statusMsg = '<p style="margin: 1em;">Seleziona una foto da aggiungere alla galleria.</p>';
    }
    
    // Display status message
    echo $statusMsg;
}
else $statusMsg = '<p style="margin: 1em;">Seleziona una foto da aggiungere alla galleria.</p>';
?>