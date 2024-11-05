<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <style>
        /* Your CSS styles here */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Fira Sans", sans-serif;
        }

        body {
            font-family: "Fira Sans", sans-serif;
        }

        .container {
            padding: 50px 7%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            border-bottom: 1px solid #d0d0d0;
            padding: 15px;
            padding-left: 0px;
            text-align: left;
            color: rgba(0, 0, 0, 0.5);
            font-weight: normal;
        }

        td {
            padding: 15px;
            text-align: left;
            padding-left: 0px;
        }

        .img-cont {
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 50px;
            height: auto;
            margin-right: 20px;
            border: 1px solid #d0d0d0;
        }

        .qty {
            display: flex;
            justify-content: space-between;
            width: 100px;
            border: 1px solid #d0d0d0;
            padding: 10px 20px;
        }

        .qty span {
            cursor: pointer;
        }

        .summary {
            margin-top: 20px;
            text-align: right;
            width: 100%;
            border-top: 1px solid #d0d0d0;
            padding-top: 15px;
        }

        .btns {
            display: flex;
            flex-direction: column;
            align-items: end;
        }

        #checkout,
        #paypal {
            background-color: #333;
            color: #fff;
            padding: 12px;
            cursor: pointer;
            margin-top: 20px;
            border: none;
            width: 400px;
        }

        .total {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .pay-button {
            margin-top: 10px;
        }

        .texts {
            font-size: 15px;
        }

        .your-cart {
            font-size: 35px;
            display: block;
            margin-bottom: 50px;
        }
    </style>
</head>

<body>

    <?php include('./partials/header.php'); ?>

    <div class="container">
        <span class="your-cart">Your Cart</span>
        <table>
            <thead>
                <tr>
                    <th>PRODUCT</th>
                    <th>QUANTITY</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody id="cartContainer">
                <!-- Cart items will be dynamically populated here -->
            </tbody>
        </table>

        <div class="summary">
            <div class="total" id="estimatedTotal">Estimated total: $0.00 USD</div>
            <div class='btns'>
                <button id="checkout">Checkout</button>
                <button id="paypal">Pay with PayPal</button>
            </div>
        </div>
    </div>

    <?php include('./partials/footer.php'); ?>

    <script>
        // Function to get the cart items from local storage
        function getCartItems() {
            return JSON.parse(localStorage.getItem('cart')) || [];
        }

        // Function to get the products from local storage
        function getProducts() {
            return JSON.parse(localStorage.getItem('cart')) || [];
        }

        // Function to display cart items in the table
        function displayCartItems() {
            const cartItems = getCartItems();
            const products = getProducts(); // Get products from local storage
            const cartContainer = document.getElementById('cartContainer');
            const estimatedTotalElement = document.getElementById('estimatedTotal');
            cartContainer.innerHTML = ''; // Clear existing items
            let estimatedTotal = 0;

            if (cartItems.length === 0) {
                cartContainer.innerHTML = `<tr><td colspan="3">Your cart is empty.</td></tr>`;
                estimatedTotalElement.textContent = 'Estimated total: $0.00 USD';
                return;
            }

            cartItems.forEach(item => {
                const product = products.find(p => p.slug === item.slug); // Find the product by slug

                if (product) {
                    const totalPrice = (product.price * item.quantity).toFixed(2);
                    estimatedTotal += product.price * item.quantity; // Accumulate the total

                    const cartItemRow = document.createElement('tr');
                    cartItemRow.innerHTML = `
                        <td>
                            <div class='img-cont'>
                                <img src="${product.image}" alt="${product.title}" class="product-image">
                                <span>${product.title}</span>
                            </div>
                        </td>
                        <td class='quantity-controls'>
                            <div class='qty'>
                                <span id='minus-${item.slug}' style="cursor: pointer;">-</span>
                                <span id='count-${item.slug}'>${item.quantity}</span>
                                <span id='plus-${item.slug}' style="cursor: pointer;">+</span>
                            </div>
                        </td>
                        <td class='total-price'>$${totalPrice}</td>
                    `;
                    cartContainer.appendChild(cartItemRow);

                    // Quantity increase/decrease
                    document.getElementById(`plus-${item.slug}`).addEventListener('click', () => {
                        item.quantity++;
                        document.getElementById(`count-${item.slug}`).textContent = item.quantity;
                        updateCart();
                        displayCartItems(); // Refresh the table to update total
                    });

                    document.getElementById(`minus-${item.slug}`).addEventListener('click', () => {
                        if (item.quantity > 1) {
                            item.quantity--;
                            document.getElementById(`count-${item.slug}`).textContent = item.quantity;
                            updateCart();
                            displayCartItems(); // Refresh the table to update total
                        }
                    });
                }
            });

            estimatedTotalElement.innerHTML = `
                <div class='texts'>
                    <p style='margin-bottom:20px'>Estimated total &nbsp; $${estimatedTotal.toFixed(2)} USD</p>
                    <p>Taxes, discounts and shipping calculated at checkout.</p>
                </div>
            `;
        }

        function updateCart() {
            const cartItems = getCartItems();
            localStorage.setItem('cart', JSON.stringify(cartItems));
        }

        // Display cart items on page load
        window.onload = function() {
            displayCartItems();
        };
    </script>
</body>

</html>