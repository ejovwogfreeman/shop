<?php
// Sample data for products
$products = [
    [
        'id' => 1,
        'title' => 'AI Tracking module adapted to L7CPro (Black)',
        'slug' => 'aI-tracking-module-adapted-to-l7Cpro-black',
        'image' => 'images/img1.webp',
        'price' => 19.99,
        'regular' => 79.99,
    ],
    [
        'id' => 2,
        'title' => 'AI Tracking module adapted to L7CPro (Grey)',
        'slug' => 'aI-tracking-module-adapted-to-l7Cpro-grey',
        'image' => 'images/img2.webp',
        'price' => 19.99,
        'regular' => 79.99,
    ],
    [
        'id' => 3,
        'title' => 'Comitok L7C Pro AI Tracking, Gimbal Stabilizer for TikTokers/Youtubers, Handheld Phone Gimbal with Magnetic AI Active Tracking, Foldable Phone Stabilizer for Video Recording, for iPhone& Android, Vlogging',
        'slug' => 'comitok-l7c-pro-ai-tracking-gimbal-Stabilizer-for-tikTokers/Youtubers-handheld-Phone-Gimbal-with-magnetic-ai-active-tracking-foldable-phone-stabilizer-for-video-recording-for-iphone-androidlogging',
        'image' => 'images/img3.webp',
        'price' => 59.99,
        'regular' => 79.99,
    ]
];

// Convert products to JSON for use in JavaScript
$products_json = json_encode($products);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <style>
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

        .product-container {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            margin: auto;
            background: white;
            border-radius: 8px;
        }

        .img-cont {
            flex: 2;
            margin-right: 20px;
        }

        .product-image {
            width: 500px;
            display: block;
            margin: auto;
            border: 1px solid #d0d0d0
        }

        .product-details {
            flex: 1;
        }

        .product-title {
            font-size: 30px;
            line-height: 1.5em;
            display: block;
            margin-bottom: 16px;
            color: #333;
        }

        .product-price2 {
            font-size: 16px;
            margin-right: 10px;
            background-color: #d0d0d0;
        }

        .product-price {
            font-size: 20px;
            margin-right: 10px;
        }

        .sale {
            background-color: black;
            color: white;
            padding: 5px 20px;
            border-radius: 20px;
        }

        .product-description {
            font-size: 16px;
            color: #555;
        }

        .flex-display {
            display: flex;
            align-items: center;
        }

        .colors {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .qty-text {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .qty {
            border: 1px solid #d0d0d0;
            display: flex;
            justify-content: space-around;
            width: 170px;
            padding: 3px;
            margin-top: 10px;
        }

        .qty #plus {
            cursor: pointer;
        }

        .qty #minus {
            cursor: pointer;
        }

        .buttons button {
            width: 100%;
            background-color: transparent;
            padding: 12px;
            outline: none;
            cursor: pointer;
            margin-top: 20px;
        }

        #add {
            border: 1px solid #333;
            color: #333;
        }

        #buy {
            background-color: #333;
            color: #fff;
        }

        .colors {
            display: flex;
        }

        .colors span {
            width: 30px;
            height: 30px;
            display: block;
            border-radius: 50%;
            background-color: red;
            cursor: pointer;
        }

        .colors #black {
            background-color: black;
        }

        .colors #gray {
            background-color: gray;
            margin-left: 10px;
        }
    </style>
</head>

<body>

    <?php include('./partials/header.php'); ?>

    <div class="container">
        <div class="product-container" id="product-container">
            <!-- Product details will be populated here -->
        </div>
    </div>

    <?php include('./partials/footer.php'); ?>

    <script>
        const products = <?php echo $products_json; ?>;

        const urlParams = new URLSearchParams(window.location.search);
        const productSlug = urlParams.get('product');
        const product = products.find(p => p.slug === productSlug);
        const productContainer = document.getElementById('product-container');

        let quantity = 1; // Default quantity

        // Function to get the current quantity in the cart for a specific product
        function getCartQuantity(slug) {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const item = cart.find(item => item.slug === slug);
            return item ? item.quantity : 0;
        }

        // Function to update the cart badge
        function updateCartBadge() {
            const cart = JSON.parse(localStorage.getItem("cart")) || [];
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('qty-text').textContent = `Quantity (${totalItems} items in cart)`;
        }

        if (product) {
            const initialCartQuantity = getCartQuantity(product.slug);

            productContainer.innerHTML = `
            <div class='img-cont'>
                <img src="${product.image}" alt="${product.title}" class="product-image">
            </div>
            <div class="product-details">
                <span class="product-title">${product.title}</span>
                <div class='flex-display'>
                    <del class="product-price2">${product.regular.toFixed(2)} USD</del>
                    <p class="product-price">${product.price.toFixed(2)} USD</p>
                    <span class='sale'>Sale</span>
                </div>
                <div class='colors'>
                    <span id='black'></span>
                    <span id='gray'></span>
                </div>
                <div id='qty-text'>Quantity (${initialCartQuantity} items in cart)</div>
                <div class='qty'>
                    <span id='minus'>-</span>
                    <span id='count'>${quantity}</span>
                    <span id='plus'>+</span>
                </div>
                <div class='buttons'>
                    <button id='add'>Add to cart</button>
                    <button id='buy'>Buy it now</button>
                </div>
            </div>
        `;

            // Quantity increase/decrease
            document.getElementById('plus').addEventListener('click', () => {
                quantity++;
                document.getElementById('count').textContent = quantity;
            });

            document.getElementById('minus').addEventListener('click', () => {
                if (quantity > 1) {
                    quantity--;
                    document.getElementById('count').textContent = quantity;
                }
            });

            // Add to Cart
            document.getElementById('add').addEventListener('click', () => {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Check if the product is already in the cart
                const existingProductIndex = cart.findIndex(item => item.slug === product.slug);

                if (existingProductIndex !== -1) {
                    // Update quantity if the product already exists
                    cart[existingProductIndex].quantity += quantity;
                } else {
                    // Add new product to the cart
                    cart.push({
                        slug: product.slug,
                        title: product.title,
                        price: product.price,
                        image: product.image,
                        quantity: quantity
                    });
                }

                // Store updated cart back in localStorage
                localStorage.setItem('cart', JSON.stringify(cart));

                // Update the badge to reflect the new quantity in the cart
                updateCartBadge();

                // Reload the page to reflect changes in other components (if necessary)
                alert(`${quantity} item(s) added to cart`);
                window.location.reload();
            });
        } else {
            productContainer.innerHTML = `<p>Product not found.</p>`;
        }

        // Update the cart badge on page load
        window.onload = function() {
            updateCartBadge();
        };
    </script>
</body>

</html>