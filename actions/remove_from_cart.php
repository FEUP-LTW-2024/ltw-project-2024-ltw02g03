<?php
declare(strict_types=1);

require_once(__DIR__ . '/../utils/session.php');
$session = new Session();

require_once(__DIR__ . '/../database/cart.class.php');
require_once(__DIR__ . '/../database/connection.db.php');
require_once(__DIR__ . '/../database/user.class.php');

$db = getDatabaseConnection();


// Verifique se o cart_id foi enviado
if(isset($_POST['cart_id'])) {
    // Obtenha o cart_id do formulário
    $cartId = (int)$_POST['cart_id'];

    try {
        
        

        
        Cart::deleteItem($db, $cartId);

        // Redirecione de volta para a página do carrinho após a remoção bem-sucedida
        header('Location: ../pages/cart.php');
        exit();
    } catch (PDOException $e) {
        // Em caso de erro de banco de dados, exiba uma mensagem de erro
        echo "Database Error: " . $e->getMessage();
    } catch (Exception $e) {
        // Em caso de outro erro, exiba uma mensagem de erro
        echo "Error: " . $e->getMessage();
    }
} else {
    // Se cart_id não foi enviado, redirecione de volta para a página do carrinho
    header('Location: /caminho_para_o_carrinho.php');
    exit();
}
?>
