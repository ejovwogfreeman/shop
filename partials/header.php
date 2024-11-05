<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comitok</title>
    <link rel="stylesheet" href="styles.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Concert+One&family=Dancing+Script:wght@400..700&family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

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

        .top {
            padding: 10px;
            text-align: center;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 7%;
            color: #333;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        .navbar-left {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .navbar-left .logo {
            font-size: 1.5rem;
        }

        .navbar-left a {
            text-decoration: none;
            color: #333;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .location-container {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 4px;
            background-color: #fff;
            position: relative;
        }

        .dropdown-icon {
            margin-left: 0.3rem;
            margin-bottom: -3px;
        }

        .icon {
            cursor: pointer;
            font-size: 1.25rem;
        }

        .cart-icon {
            position: relative;
        }

        .badge {
            position: absolute;
            bottom: -2px;
            right: -7px;
            background-color: #333;
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
        }

        /* Country Dropdown Modal */
        .country-modal {
            display: none;
            position: absolute;
            right: 250px;
            top: 100px;
            width: 220px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .country-modal .input-container {
            padding: 10px;
        }

        .country-modal input {
            width: 100%;
            padding: 0.5rem;
            border: 3px solid #333;
            outline: none;
            margin-bottom: 0.5rem;
        }

        .country-list {
            max-height: 150px;
            overflow-y: auto;
            padding-left: 5px;
        }

        .country-list div {
            padding: 0.5rem;
            cursor: pointer;
        }

        .country-list div:hover {
            background-color: #f0f0f0;
        }
    </style>

</head>

<body>

    <!-- Navbar -->
    <div class="top">
        Welcome to our store
    </div>
    <nav class="navbar">
        <!-- Left Side: Logo and Links -->
        <div class="navbar-left">
            <a href="#" class="logo">Comitok</a>
            <a href="#">Home</a>
            <a href="#">Collections</a>
            <a href="#">Contact</a>
        </div>

        <!-- Right Side: Dropdown, Icons -->
        <div class="navbar-right">
            <!-- Country/Currency Display with Dropdown Icon -->
            <div class="location-container" onclick="toggleCountryDropdown()">
                <span id="selectedCountry">Nigeria | USD $</span>
                <span class="dropdown-icon"><img src="icons/arrow.png" alt="" width="30px"></span>
            </div>

            <!-- Country Dropdown Modal -->
            <div id="countryModal" class="country-modal">
                <div class="input-container">
                    <input type="text" id="countrySearch" placeholder="Search country..." oninput="filterCountries()">
                </div>
                <div id="countryList" class="country-list">
                    <!-- Country options will be populated here -->
                </div>
            </div>

            <!-- Person Icon -->
            <span class="icon search-icon"><img src="icons/search.png" alt="" width="20px"></span>

            <span class="icon person-icon"><img src="icons/person.png" alt="" width="25px"></span>

            <!-- Cart Icon with Badge -->
            <span class="icon cart-icon">
                <img src="icons/bag.png" alt="" width="25px">
                <span class="badge">0</span>
            </span>
        </div>
    </nav>

    <script>
        // Toggle the display of the country dropdown modal
        function toggleCountryDropdown() {
            const countryModal = document.getElementById("countryModal");
            countryModal.style.display = countryModal.style.display === "block" ? "none" : "block";
        }

        // Close the dropdown if clicked outside, except when clicking on search input
        window.onclick = function(event) {
            const countryModal = document.getElementById("countryModal");
            const locationContainer = document.querySelector('.location-container');
            const countrySearch = document.getElementById("countrySearch");

            if (!locationContainer.contains(event.target) && !countryModal.contains(event.target)) {
                countryModal.style.display = "none";
            }
        };

        // Populate the country list in the modal using REST Countries API
        async function populateCountryDropdown() {
            const countryList = document.getElementById("countryList");
            try {
                const response = await fetch("https://restcountries.com/v3.1/all");
                const countries = await response.json();

                countries.sort((a, b) => a.name.common.localeCompare(b.name.common));

                countries.forEach(country => {
                    const countryOption = document.createElement("div");
                    countryOption.textContent = country.name.common;
                    countryOption.onclick = () => selectCountry(country.name.common);
                    countryList.appendChild(countryOption);
                });
            } catch (error) {
                console.error("Error fetching countries:", error);
                countryList.innerHTML = "<div>Error loading countries</div>";
            }
        }

        // Filter countries based on search input
        function filterCountries() {
            const searchInput = document.getElementById("countrySearch").value.toLowerCase();
            const countryOptions = document.getElementById("countryList").children;
            for (let option of countryOptions) {
                const countryName = option.textContent.toLowerCase();
                option.style.display = countryName.includes(searchInput) ? "" : "none";
            }
        }

        // Select a country and display it
        function selectCountry(country) {
            document.getElementById("selectedCountry").textContent = `${country} | USD`;
            toggleCountryDropdown();
        }

        // Populate the dropdown on page load
        window.onload = populateCountryDropdown;



        // Function to update the cart badge
        function updateCartBadge() {
            // Retrieve the cart items from local storage (or your cart system)
            const cartItems = JSON.parse(localStorage.getItem("cart")) || [];
            const itemCount = cartItems.length; // Count of items in the cart
            document.querySelector(".cart-icon .badge").textContent = itemCount;
        }

        updateCartBadge()
        populateCountryDropdown();
    </script>

</body>

</html>