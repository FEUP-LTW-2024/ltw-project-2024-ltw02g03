<?php
$uploadDirectory = 'database/uploads/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES['images'])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['images']['name'][$key];
            $file_tmp = $_FILES['images']['tmp_name'][$key];
            $file_type = $_FILES['images']['type'][$key];
            
            if ($file_type == 'image/jpeg' || $file_type == 'image/png') {
                $itemFolder = $uploadDirectory . 'item_' . $_POST['item_id'] . '/';
                
                if (!file_exists($itemFolder)) {
                    mkdir($itemFolder, 0777, true);
                }
                
                $targetFilePath = $itemFolder . $file_name;
                
                if (move_uploaded_file($file_tmp, $targetFilePath)) {
                    echo "Arquivo $file_name enviado com sucesso.<br>";
                } else {
                    echo "Ocorreu um erro ao enviar o arquivo $file_name.<br>";
                }
            } else {
                echo "Apenas arquivos JPEG e PNG são permitidos.<br>";
            }
        }
    }
}
?>

<!-- forms para receber imagems-->


<!-- <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="item_id" value="123"> <!-- ID do item 
    Selecione as imagens: <input type="file" name="images[]" multiple><br>
    <input type="submit" value="Enviar">
</form> -->