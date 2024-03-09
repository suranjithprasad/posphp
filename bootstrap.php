<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculate Total Price</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="header1"><b>POS System</b></h1><br>

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
        <div class="form-group row">
            <label for="productCode" class="col-sm-2 col-form-label">Product Code:</label>
            <div class="col-sm-4">
                <input type="text" class="form-control" name="productCode" id="productCode">
            </div>
        </div>
        <div class="form-group row">
            <label for="productCount" class="col-sm-2 col-form-label">Product Count:</label>
            <div class="col-sm-4">
                <input type="number" class="form-control" name="productCount" id="productCount">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-10 offset-sm-2">
                <input type="submit" class="btn btn-primary" name="addProduct" value="Add Product">
                <input type="submit" class="btn btn-danger" name="clearTable" value="Clear Table">
                <!-- Add Print Button -->
                <button class="btn btn-primary" onclick="window.print()">- - - - - -Print Bill</button>
            </div>
        </div>
    </form>

    <br>

    <?php if (!empty($addedProducts)): ?>
        <table class="table table-striped table-bordered">
            <thead class="thead-dark">
            <tr>
                <th>Product Code</th>
                <th>Product</th>
                <th>Unit Price</th>
                <th>Count</th>
                <th>Total Price</th>
                <th>Action</th> <!-- Added Action column -->
            </tr>
            </thead>
            <tbody>
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
                            <input type="submit" class="btn btn-sm btn-danger" name="deleteProduct" value="Delete">
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
            </tbody>
        </table>
        <br>
    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
