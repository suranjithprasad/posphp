<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate Total Price</title>
</head>
<body>

<h1 class="header1">POS System</h1>

<?php
// Start the session
session_start();

// Define products as an associative array with product codes as keys
$products = array(
    "P001" => array("name" => "Ice Cream", "price" => 2),
    "P002" => array("name" => "Apple", "price" => 5),
    "P003" => array("name" => "Banana", "price" => 3),
    // Add more products as needed
);

// Initialize an array to store added products
$addedProducts = isset($_SESSION['addedProducts']) ? $_SESSION['addedProducts'] : array();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the clear table button is clicked
    if (isset($_POST["clearTable"])) {
        // Clear the session data for added products
        unset($_SESSION['addedProducts']);
        // Redirect to the same page to refresh the display
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // Check if product code is submitted via POST
    if(isset($_POST['productCode']) && isset($_POST['productCount'])) {
        $productCode = $_POST['productCode'];
        $productCount = (int)$_POST['productCount'];

        // Check if the product code is valid
        if (isset($products[$productCode]) && $productCount > 0) {
            // Add the product to the added products array
            $addedProducts[] = array(
                "code" => $productCode,
                "name" => $products[$productCode]["name"],
                "price" => $products[$productCode]["price"],
                "count" => $productCount,
                "totalPrice" => $products[$productCode]["price"] * $productCount
            );

            // Update the session variable to store the added products
            $_SESSION['addedProducts'] = $addedProducts;
        }
    }

    // Check if the delete button is clicked
    if(isset($_POST['deleteProduct']) && isset($_POST['deleteIndex'])) {
        $deleteIndex = $_POST['deleteIndex'];

        // Check if the index is valid
        if (isset($addedProducts[$deleteIndex])) {
            // Remove the product from the array
            unset($addedProducts[$deleteIndex]);
            // Update the session variable to store the modified added products
            $_SESSION['addedProducts'] = $addedProducts;
        }
    }
}
?>

<!-- HTML form for user input -->
<form method="post" action="">
    Product Code: <input type="text" name="productCode" id="productCode"><br><br>
    <div id="productCountInput">
        Product Count: <input type="number" name="productCount" id="productCount"><br><br>
    </div>
    <input type="submit" name="addProduct" value="Add Product">
    <input type="submit" name="clearTable" value="Clear Table">
</form>

<br>

<?php if (!empty($addedProducts)): ?>
    <table border="3" width="450px">
        <tr>
            <th>Product Code</th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Count</th>
            <th>Total Price</th>
            <th>Action</th> <!-- Added Action column -->
        </tr>
        <?php foreach ($addedProducts as $key => $product): ?>
            <tr>
                <td><?php echo $product["code"]; ?></td>
                <td><?php echo $product["name"]; ?></td>
                <td><?php echo "Rs ".$product["price"]; ?></td>
                <td><?php echo $product["count"]; ?></td>
                <td><?php echo "Rs ".$product["totalPrice"]; ?></td>
                <td>
                    <!-- Add a delete button -->
                    <form method="post" action="">
                        <input type="hidden" name="deleteIndex" value="<?php echo $key; ?>">
                        <input type="submit" name="deleteProduct" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="5">Final Total</td>
            <td bgcolor="red"><?php
                $finalTotalPrice = array_sum(array_column($addedProducts, 'totalPrice'));
                echo "Rs ". $finalTotalPrice;
                ?></td>
        </tr>
    </table>
    <br>
    <!-- Add Print Button -->
    <button onclick="window.print()">Print Bill</button>
<?php endif; ?>

<script>
    // Get input fields
    var productCodeInput = document.getElementById("productCode");
    var productCountInput = document.getElementById("productCount");

    // Focus on product code input initially
    productCodeInput.focus();

    // Add event listener for Enter key press on productCodeInput
    productCodeInput.addEventListener("keypress", function(event) {
        if (event.key === "Enter") {
            // Display productCountInput and addProduct button
            productCountInput.parentElement.style.display = "block";
            document.querySelector("input[name='addProduct']").style.display = "inline";
            productCountInput.focus(); // Focus on productCountInput
            event.preventDefault(); // Prevent form submission
        }
    });
</script>

</body>
</html>
