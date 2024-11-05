<?php include('./partials/header.php') ?>

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

    .product {
        font-size: 35px;
        display: block;
        margin-bottom: 50px;
    }

    .product-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 10px;
    }

    .product-card {
        background-color: white;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.2s;
        text-decoration: none;
    }

    .product-card:hover {
        transform: scale(1.02);
    }

    .product-image {
        width: 100%;
        height: 300px;
        /* object-fit: cover; */
    }

    .product-title {
        margin-bottom: 10px;
        color: #333;
        font-size: 12px;
    }

    .product-title:hover {
        text-decoration: underline;
    }

    .product-price {
        color: #333;
        margin-bottom: 10px;
        font-size: 12px;
    }

    .add-to-cart {
        background-color: #28a745;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .add-to-cart:hover {
        background-color: #218838;
    }

    .cart-message {
        text-align: center;
        margin-top: 20px;
        color: #28a745;
        font-size: 1.2rem;
        display: none;
    }
</style>
</head>

<body>

    <div class="container">
        <span class="product">Products</span>
        <div class="product-container" id="productContainer">
        </div>
    </div>

    <script>
        const products = [{
                id: 1,
                title: 'AI Tracking module adapted to L7CPro (Black)',
                slug: 'aI-tracking-module-adapted-to-l7Cpro-black',
                image: 'images/img1.png',
                price: 19.99
            },
            {
                id: 2,
                title: 'AI Tracking module adapted to L7CPro (Grey)',
                slug: 'aI-tracking-module-adapted-to-l7Cpro-grey',
                image: 'images/img2.png',
                price: 19.99
            },
            {
                id: 3,
                title: 'Comitok L7C Pro AI Tracking, Gimbal Stabilizer for TikTokers/Youtubers, Handheld Phone Gimbal with Magnetic AI Active Tracking, Foldable Phone Stabilizer for Video Recording, for iPhone& Android, Vlogging',
                slug: 'comitok-l7c-pro-ai-tracking-gimbal-Stabilizer-for-tikTokers/Youtubers-handheld-Phone-Gimbal-with-magnetic-ai-active-tracking-foldable-phone-stabilizer-for-video-recording-for-iphone-androidlogging',
                image: 'images/img3.png',
                price: 59.99
            },
        ];

        const productContainer = document.getElementById('productContainer');

        // Function to add products to the page
        function displayProducts() {
            const productCards = products.map(product => {
                return `
            <a href='product.php?product=${product.slug}' class="product-card">
                <img src="${product.image}" alt="${product.title}" class="product-image">
                <div class="product-details">
                    <span class="product-title">${product.title}</span>
                    <p class="product-price">$${product.price.toFixed(2)}</p>
                </div>
            </a>
        `;
            }).join('');

            productContainer.innerHTML = productCards;
        }

        // Initial call to display products
        displayProducts();
    </script>

    <?php include('./partials/footer.php') ?>