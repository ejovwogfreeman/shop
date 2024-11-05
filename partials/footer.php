<style>
    /* General styling */
    #footerComponent {
        padding: 100px 0px;
    }

    #footerComponent * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    #footerComponent body {
        font-family: Arial, sans-serif;
    }

    /* Footer styling */
    #footerComponent footer form {
        padding: 50px 7%;
        border-top: 1px solid #ddd;
        border-bottom: 1px solid #ddd;
        text-align: center;
    }

    #footerComponent footer form span {
        display: block;
        text-align: center;
        margin-bottom: 20px;
    }

    #footerComponent footer form .input {
        border: 1px solid #666;
        margin-top: 50px;
        padding: 5px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 400px;
        margin: auto;
    }

    #footerComponent footer form .input input {
        width: 100%;
        border: none;
        outline: none;
    }

    #footerComponent .footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 50px 7%;
    }

    /* Left section styling */
    #footerComponent .footer-left {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    /* Country dropdown styling */
    #footerComponent .location-container {
        display: flex;
        align-items: center;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0px;
        background-color: #fff;
        border: 1px solid #666;
        position: relative;
    }

    #footerComponent .dropdown-icon {
        margin-left: 0.3rem;
        margin-bottom: -3px;
    }

    /* Country dropdown modal styling */
    #footerComponent .country-modal2 {
        display: none;
        position: absolute;
        bottom: -755px;
        width: 220px;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        max-height: 200px;
        overflow-y: auto;
    }

    #footerComponent .country-modal2 .input-container {
        padding: 10px;
    }

    #footerComponent .country-modal2 input {
        width: 100%;
        padding: 0.5rem;
        border: 3px solid #333;
        outline: none;
        margin-bottom: 0.5rem;
    }

    #footerComponent .country-list2 {
        max-height: 150px;
        padding: 10px;
    }

    #footerComponent .country-list2 div {
        padding: 0.5rem;
        cursor: pointer;
        border-radius: 4px;
    }

    #footerComponent .country-list2 div:hover {
        background-color: #f0f0f0;
    }

    /* Copyright styling */
    #footerComponent .copyright {
        padding: 20px 7%;
        font-size: 0.875rem;
        color: #666;
        margin-top: 10px;
    }

    /* Right section styling */
    #footerComponent .footer-right img {
        width: 50px;
        border: 1px solid #ddd;
    }
</style>

<div id="footerComponent">
    <!-- Footer -->
    <footer>
        <!-- Left Section -->
        <form action="">
            <span class="logo">Subscribe to our emails</span>
            <div class="input">
                <input type="email" placeholder="Email">
                <span class="dropdown-icon"><img src="icons/arrow2.png" alt="" width="30px"></span>
            </div>
        </form>
        <div class="footer">
            <div class="footer-left">
                <!-- Country/Currency Display with Dropdown Icon -->
                <span>Country/region</span>
                <div class="location-container" onclick="toggleCountryDropdown()">
                    <span id="selectedCountry">Nigeria | USD $</span>
                    <span class="dropdown-icon"><img src="icons/arrow.png" alt="" width="30px"></span>
                </div>

                <!-- Country Dropdown Modal -->
                <div id="countryModal2" class="country-modal2">
                    <div class="input-container">
                        <input type="text" id="countrySearch" placeholder="Search country...">
                    </div>
                    <div id="countryList2" class="country-list2">
                        <!-- Country options will be populated here -->
                    </div>
                </div>
            </div>

            <!-- Right Section (PayPal Logo) -->
            <div class="footer-right">
                <img src="images/paypal.webp" alt="PayPal Logo">
            </div>
        </div>
        <!-- Copyright -->
        <div class="copyright">
            &copy; <?php echo date('Y') ?>, Comitok Powered by Shopify . Privacy policy
        </div>
    </footer>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const footerComponent = document.getElementById("footerComponent");
        const countryModal2 = footerComponent.querySelector("#countryModal2");
        const countryList2 = footerComponent.querySelector("#countryList2");
        const selectedCountry = footerComponent.querySelector("#selectedCountry");
        const countrySearch = footerComponent.querySelector("#countrySearch");

        // Toggle the display of the country dropdown modal
        footerComponent.querySelector(".location-container").addEventListener("click", function() {
            countryModal2.style.display = countryModal2.style.display === "block" ? "none" : "block";
        });

        // Close the dropdown if clicked outside
        document.addEventListener("click", function(event) {
            if (!footerComponent.contains(event.target) && countryModal2.style.display === "block") {
                countryModal2.style.display = "none";
            }
        });

        // Populate the country list in the modal using REST Countries API
        async function populateCountryDropdown() {
            try {
                const response = await fetch("https://restcountries.com/v3.1/all");
                const countries = await response.json();

                // Sort countries alphabetically
                countries.sort((a, b) => a.name.common.localeCompare(b.name.common));

                // Create country options
                countries.forEach(country => {
                    const countryOption = document.createElement("div");
                    countryOption.textContent = country.name.common;
                    countryOption.addEventListener("click", () => selectCountry(country.name.common));
                    countryList2.appendChild(countryOption);
                });
            } catch (error) {
                console.error("Error fetching countries:", error);
                countryList2.innerHTML = "<div>Error loading countries</div>";
            }
        }

        // Filter countries based on search input
        countrySearch.addEventListener("input", function() {
            const searchInput = countrySearch.value.toLowerCase();
            Array.from(countryList2.children).forEach(option => {
                option.style.display = option.textContent.toLowerCase().includes(searchInput) ? "" : "none";
            });
        });

        // Select a country and display it
        function selectCountry(country) {
            selectedCountry.textContent = country;
            countryModal2.style.display = "none";
        }

        // Populate the dropdown on page load
        populateCountryDropdown();
    });
</script>