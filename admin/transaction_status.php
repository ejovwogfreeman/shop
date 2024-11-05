<?php
// include('../config/session.php');
// include('../config/db.php');

// if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] !== 'true') {
//     $_SESSION['msg'] = "You are not authorized to perform this action.";
//     header("Location: https://capitalstreamexchange.com/dashboard?id={$_SESSION['user']['user_id']}");
//     exit;
// }

// // Check if the 'id' parameter is set in the GET request
// if (isset($_GET['id'])) {
//     // Sanitize and retrieve the 'id' parameter
//     $transactionId = htmlspecialchars($_GET['id']); // Sanitize output to prevent XSS
// }

// // Debug: Print the result of the regex match
// if ($transactionId === null) {
//     echo "No TRANSACTION ID found in the URL.<br>";
// } else {
//     echo "TRANSACTION ID: $transactionId<br>";
// }

// if ($transactionId !== null) {
//     // Fetch the current status of the transaction
//     $sql = "SELECT status FROM transactions WHERE transaction_id = '$transactionId'";
//     $result = mysqli_query($conn, $sql);

//     if (mysqli_num_rows($result) > 0) {
//         $transaction = mysqli_fetch_assoc($result);
//         $currentStatus = $transaction['status'];

//         // Toggle the status
//         $newStatus = ($currentStatus === 'pending') ? 'approved' : 'pending';

//         // Update the status in the database
//         $updateSql = "UPDATE transactions SET status = '$newStatus' WHERE transaction_id = '$transactionId'";
//         if (mysqli_query($conn, $updateSql)) {
//             $_SESSION['msg'] = "Transaction status updated to $newStatus Successfully.";
//             header("Location: https://capitalstreamexchange.com/dashboard?id={$_SESSION['user']['user_id']}");
//             exit;
//         } else {
//             echo "Error updating transaction status.";
//         }
//     } else {
//         echo "transaction not found.";
//     }
// } else {
//     echo "Invalid TRANSACTION ID.";
// }

// // Close the database connection
// mysqli_close($conn);

include('../config/session.php');
include('../config/db.php');

if (!isset($_SESSION['user']) || $_SESSION['user']['is_admin'] !== 'true') {
    $_SESSION['msg'] = "You are not authorized to perform this action.";
    header("Location: https://capitalstreamexchange.com/dashboard?id={$_SESSION['user']['user_id']}");
    exit;
}

// Check if the 'id' parameter is set in the GET request
if (isset($_GET['id'])) {
    // Sanitize and retrieve the 'id' parameter
    $transactionId = htmlspecialchars($_GET['id']); // Sanitize output to prevent XSS
} else {
    echo "Invalid TRANSACTION ID.";
    exit;
}

// Debug: Print the result of the regex match
if ($transactionId === null) {
    echo "No TRANSACTION ID found in the URL.<br>";
} else {
    echo "TRANSACTION ID: $transactionId<br>";
}

if ($transactionId !== null) {
    // Fetch the current status, type, amount, and user_id of the transaction
    $sql = "SELECT status, amount, type, user_id FROM transactions WHERE transaction_id = '$transactionId'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $transaction = mysqli_fetch_assoc($result);
        $currentStatus = $transaction['status'];
        $transactionAmount = $transaction['amount'];
        $transactionType = $transaction['type'];
        $userId = $transaction['user_id'];

        // Determine the new status
        $newStatus = ($currentStatus === 'pending') ? 'approved' : 'pending';

        // Update the status in the database
        $updateSql = "UPDATE transactions SET status = '$newStatus' WHERE transaction_id = '$transactionId'";
        if (mysqli_query($conn, $updateSql)) {
            // Fetch the current balance of the user
            $userSql = "SELECT balance FROM users WHERE user_id = '$userId'";
            $userResult = mysqli_query($conn, $userSql);

            if (mysqli_num_rows($userResult) > 0) {
                $user = mysqli_fetch_assoc($userResult);
                $currentBalance = $user['balance'];

                // Update the user's balance based on the transaction type and status change
                if ($transactionType === 'withdrawal') {
                    if ($newStatus === 'approved') {
                        // Subtract the amount for withdrawal
                        $newBalance = $currentBalance - $transactionAmount;
                    } else {
                        // Add the amount back for withdrawal
                        $newBalance = $currentBalance + $transactionAmount;
                    }
                } elseif ($transactionType === 'deposit') {
                    if ($newStatus === 'approved') {
                        // Add the amount for deposit
                        $newBalance = $currentBalance + $transactionAmount;
                    } else {
                        // Subtract the amount for deposit
                        $newBalance = $currentBalance - $transactionAmount;
                    }
                } else {
                    $_SESSION['msg'] = "Unknown transaction type.";
                    header("Location: https://capitalstreamexchange.com/dashboard?id={$_SESSION['user']['user_id']}");
                    exit;
                }

                // Update the user's balance in the database
                $updateBalanceSql = "UPDATE users SET balance = '$newBalance' WHERE user_id = '$userId'";
                if (mysqli_query($conn, $updateBalanceSql)) {
                    $_SESSION['msg'] = "Transaction status updated to $newStatus and user's balance adjusted successfully.";
                } else {
                    $_SESSION['msg'] = "Error updating user's balance.";
                }
            } else {
                $_SESSION['msg'] = "User not found.";
            }
            header("Location: https://capitalstreamexchange.com/dashboard?id={$_SESSION['user']['user_id']}");
            exit;
        } else {
            echo "Error updating transaction status.";
        }
    } else {
        echo "Transaction not found.";
    }
} else {
    echo "Invalid TRANSACTION ID.";
}

// Close the database connection
mysqli_close($conn);
