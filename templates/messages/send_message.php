<?php
declare(strict_types=1);
ini_set('display_errors', 1); 
ini_set('display_startup_errors', 1); 
error_reporting(E_ALL);
require_once(__DIR__ . '/../../database/connection.db.php');
require_once(__DIR__ . '/../../database/communication.class.php');
require_once(__DIR__ . '/../../utils/session.php');
$session = new Session();
$db = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['message-input'], $_POST['receiverId'], $_POST['item-id'])) {
        $message = $_POST['message-input'];
        $senderId = $session->getId();
        $receiverId = isset($_POST['receiverId']) ? intval($_POST['receiverId']) : 0;
        $itemId = isset($_POST['item-id']) ? intval($_POST['item-id']) : 0;
        
        try {
            Communication::insertCommunication($db, $senderId, $receiverId, $itemId, $message);
            
            // Após o envio bem-sucedido da mensagem, retorne um JSON com a mensagem enviada
            http_response_code(200);
            echo json_encode(["success" => true, "message" => "Mensagem enviada com sucesso!", "sentMessage" => $message]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => "Erro ao enviar mensagem: " . $e->getMessage()]);
        }
        exit;
    } else {
        // Se o campo de mensagem estiver ausente, retorna um erro
        http_response_code(400);
        echo json_encode(["success" => false, "error" => "Erro: Campo de mensagem, ID do destinatário ou ID do item não encontrado na solicitação."]);
        exit;
    }
} else {
    // Se a solicitação não for POST, retorna um erro
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Erro: Método não permitido. Apenas solicitações POST são suportadas."]);
    exit;
}
?>