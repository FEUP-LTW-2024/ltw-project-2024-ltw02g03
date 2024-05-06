<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/item.class.php');
require_once(__DIR__ . '/../database/payment.class.php');
require_once(__DIR__ . '/../database/user.class.php');
require_once(__DIR__ . '/../templates/common.tpl.php');

$session = new Session();
// Obtenha uma conexão com o banco de dados
$db = getDatabaseConnection();
drawHeader($session);

// Função para gerar o conteúdo do formulário de envio
function generateShippingFormContent($itemId, $db)
{
    // Obtenha o item com base no ID
    $item = Item::getItem($db, $itemId);
    $user = User::getUser($db,3);
    
    $payment = Payment::getPaymentByItemId($db, $itemId);
    if ($item && $payment) {
        // Extrai informações do item
        $title = $item->title;
        $price = $item->price;
        $description = $item->description;

        // Extrai informações do pagamento
        $paymentDate = $payment->paymentDate;
        $buyerId = $payment->buyerId;
        $buyer= User::getUser($db, $buyerId);
        $sellerId = $payment->sellerId;
        $seller = User::getUser($db, $sellerId);

        // Obtenha imagens relacionadas ao item
        $images = Item::getItemImage($db, $itemId);

        // Gere o conteúdo do formulário de envio preenchido
        $shippingFormContent = "
            <h1>Shipping Form for Item: $title</h1>
            <div>";

        // Adicione imagens ao conteúdo do formulário
        foreach ($images as $image) {
            $shippingFormContent .= "<img id=\"\" src=\"/../$image->imageUrl\" alt=\"\" style=\"width: 20%; height: 20%; margin-right: 1.5em;\">";
        }
        
        $shippingFormContent .= "</div>
            <p><strong>Description:</strong> $description</p>
            <p><strong>Price:</strong> $price</p>
            <p><strong>Payment Date:</strong> $paymentDate</p>
            <h2>Shipping Information</h2>
            <p><strong>Address:</strong> $payment->address</p>
            <p><strong>Postal Code:</strong> $payment->postalCode</p>
            <p><strong>City:</strong> $payment->city</p>
            <p><strong>District:</strong> $payment->district</p>
            <p><strong>Country:</strong> $payment->country</p>
            <h2>Buyer Information</h2>
            <p><strong>Buyer ID:</strong> $buyer->firstName $buyer->lastName</p>
            <p><strong>Email:</strong> $buyer->username</p>
            <p><strong>Email:</strong> $buyer->email</p>
            
            <h2>Seller Information</h2>
            <p><strong>Seller ID:</strong> $seller->firstName $seller->lastName</p>
            <p><strong>Email:</strong> $seller->username</p>
            <p><strong>Email:</strong> $seller->email</p>
        ";

        return $shippingFormContent;
    } else {
        return "<p>Item or payment not found.</p>";
    }
}

// Verifique se o ID do item foi enviado via POST
if (isset($_POST['item_id'])) {
    $itemId = intval($_POST['item_id']);
    $shippingFormContent = generateShippingFormContent($itemId, $db);
    ?>
    <h3>Download Shipping Form:</h3>
    <button id='downloadButton'>Download Shipping Form</button>
    <script>
        document.getElementById('downloadButton').addEventListener('click', function() {
            // Defina os cabeçalhos para download
            var blob = new Blob([<?php echo json_encode($shippingFormContent); ?>], { type: 'text/html' });
            var url = URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = 'shipping_form_item_<?php echo $itemId; ?>.html';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        });
    </script>
    <?php
    echo $shippingFormContent;
} else {
    $session->addMessage('error', 'Invalid Item!');
    header('Location: /pages');
    exit();
}
drawFooter();
?>
