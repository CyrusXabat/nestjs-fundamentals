<?php
session_start();
require 'includes/header.php';
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $totalAmount = $_POST['total_amount'];

    $orderQuery = $pdo->prepare("INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, 'pending')");
    $orderQuery->execute([$userId, $totalAmount]);
    $orderId = $pdo->lastInsertId();

    foreach ($_SESSION['cart'] as $productId => $variations) {
        foreach ($variations as $variationId => $item) {
            $orderItemQuery = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $orderItemQuery->execute([$orderId, $productId, $item['quantity'], $item['price']]);
        }
    }

    unset($_SESSION['cart']);
    header('Location: checkout.php?success=1');
    exit;
}

$cart = $_SESSION['cart'] ?? [];
$totalAmount = 0;
?>

<main>
    <section class="checkout">
        <h2>Checkout</h2>
        <?php if (empty($cart)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <form action="checkout.php" method="post">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Variation</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    <?php foreach ($cart as $productId => $variations): ?>
                        <?php foreach ($variations as $variationId => $item): ?>
                            <tr>
                                <td><?php echo $item['product_name']; ?></td>
                                <td><?php echo $item['variation']; ?></td>
                                <td>$<?php echo $item['price']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                            </tr>
                            <?php $totalAmount += $item['price'] * $item['quantity']; ?>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
                <input type="hidden" name="total_amount" value="<?php echo $totalAmount; ?>">
                <button type="submit">Place Order</button>
            </form>
        <?php endif; ?>
    </section>
</main>

<?php
if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <section class="order-confirmation">
        <h2>Order Confirmation</h2>
        <p>Your order has been placed successfully!</p>
    </section>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>