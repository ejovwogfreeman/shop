<?php
// Start session

ob_start();

include('../config/session.php');
include('../partials/header.php');
require_once('../config/db.php');

$amount = '';
$errors = [];

// Function to get User ID from the URL using regular expression
if (isset($_GET['id'])) {
    // Sanitize and retrieve the 'id' parameter
    $userId = htmlspecialchars($_GET['id']); // Sanitize output to prevent XSS
}

// If User ID is not found, set an error message
if ($userId === null) {
    echo "No User ID found in the URL.<br>";
    exit;
}

// Ensure the user is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] !== 'true') {
    $_SESSION['msg'] = "You are not authorized to perform this action.";
    header('Location: https://capitalstreamexchange.com/dashboard');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate amount
    if (empty($_POST["amount"])) {
        $errors['amount'] = 'Amount is required.';
    } elseif (!is_numeric($_POST["amount"])) {
        $errors['amount'] = 'Amount must be a number.';
    } else {
        $amount = htmlspecialchars($_POST["amount"]);
    }

    // If no errors, proceed to update the user's balance
    if (empty($errors)) {
        // Fetch the current balance of the user
        $sql = "SELECT * FROM users WHERE user_id = '$userId'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $currentBalance = $user['balance'];
            $newBalance = $currentBalance + $amount;

            // Update the user's balance
            $updateSql = "UPDATE users SET balance = '$newBalance' WHERE user_id = '$userId'";
            if (mysqli_query($conn, $updateSql)) {
                $_SESSION['msg'] = "{$user['username']}'s account has been funded successfully! New balance: $ " . number_format($newBalance);
                header("Location: https://capitalstreamexchange.com/dashboard?id={$_SESSION['user']['user_id']}");
            } else {
                echo "Error updating user balance.";
            }
        } else {
            echo "User not found.";
        }
    }
}

// Close the database connection
// mysqli_close($conn);

ob_end_flush();

?>
<div class="container">
    <style>
        <?php  ?>
    </style>

    <?php include('../partials/sidebar.php'); ?>

    <button id="menuBtn" class="menu-btn">&#9776;</button>

    <form id="form" method="POST">
        <?php echo isset($err) ? "<div class='error'>" . $err . "</div>" : ""; ?>
        <h1 class="h2"><span>Fund</span> User</h1>
        <div class="input-container">
            <label for="amount" class="form-label">Amount</label>
            <input type="text" id="amount" name="amount" placeholder="Enter amount" value="<?php echo $amount; ?>" class="<?php echo isset($errors['amount']) ? 'is-invalid' : ''; ?>">
            <?php echo isset($errors['amount']) ? "<div class='invalid-feedback'>" . $errors['amount'] . "</div>" : ""; ?>
        </div>
        <div>
            <button class="btn" type="submit">Submit</button>
        </div>
    </form>
</div>

<style>
    :root {
        --golden-puppy: #ffcc00;
        /* Define your color variable here */
        --input-background: black;
        /* Background color for inputs and buttons */
        --input-text-color: var(--primary);
        /* Text color for inputs and buttons */
    }

    #form {
        width: 500px;
        margin: auto;
        margin-top: 50px;
        margin-bottom: 50px;
        padding: 50px 30px;
        border-radius: 8px;
    }

    .input-container {
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 18px;
        color: var(--primary);
        display: block;
        margin-bottom: 5px;
    }

    #form .btn,
    #form .link {
        color: white;
        background-color: var(--primary);
        font-size: 18px;
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid var(--primary);
        border-radius: 3px;
        margin-bottom: 5px;
        text-align: center;
        text-decoration: none;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="file"],
    textarea {
        color: var(--input-text-color);
        background-color: var(--eerie-black-1);
        font-size: 18px;
        display: block;
        width: 100%;
        padding: 10px 20px;
        border: 1px solid rgba(26, 69, 111, 0.2);
        border-radius: 3px;
        margin-bottom: 5px;
        text-decoration: none;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="password"]:focus {
        border-color: var(--primary);
        background-color: var(--eerie-black-1);
        /* box-shadow: 0 0 5px 3px var(--primary); */
        outline: none;
    }

    input[type="text"].is-invalid,
    input[type="email"].is-invalid,
    input[type="password"].is-invalid {
        margin-top: 10px;
        border-color: red;
        box-shadow: 0px 0px 5px rgba(255, 0, 0, 0.5);
    }

    .invalid-feedback {
        margin-top: 10px;
        color: red;
        font-size: 14px;
    }

    .success-message {
        color: var(--primary);
        margin-top: 20px;
    }

    .error-message {
        color: red;
        margin-top: 20px;
    }

    .btn:hover,
    .link:hover {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white !important;
        /* Change text color on hover */
    }

    .bottom-box {
        margin-top: 20px;
    }

    #form .bottom-box .link {
        background-color: var(--eerie-black-1);
        border: none;
        color: var(--primary);
        border: 0.5px solid var(--primary);
        transition: 0.2s ease-in-out;
    }

    #form .bottom-box .link:hover {
        background-color: var(--primary);
        color: #000;
    }

    @media (max-width: 799px) {
        #form {
            margin-top: 50px;
            margin-bottom: 50px;
            margin: auto;
            padding: 50px 0px;
            width: 90%;
        }
    }

    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0px 1000px var(--eerie-black-1) inset !important;
        box-shadow: 0 0 0px 1000px var(--eerie-black-1) inset !important;
        -webkit-text-fill-color: var(--input-text-color) !important;
        text-fill-color: var(--input-text-color) !important;
    }

    .success-msg {
        color: var(--primary);
        background-color: rgba(255, 193, 7, 0.1);
        font-size: 18px;
        display: block;
        width: 100%;
        padding: 10px;
        border: 1px solid var(--primary);
        border-radius: 3px;
        margin-bottom: 15px;
        text-align: center;
        text-decoration: none;
    }

    .h2 {
        font-size: 30px;
        margin-bottom: 30px;
    }

    .h2 span {
        display: inline;
        background: linear-gradient(to right, var(--primary), red);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .menu-btn {
        font-size: 30px;
        margin-top: 50px;
    }
</style>


<?php include('../partials/footer.php') ?>
<?php include('../partials/bottom_tab.php') ?>