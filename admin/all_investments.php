<?php

include('../config/session.php');
include('../partials/header.php');
include('../config/db.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userId = $user['user_id'];
}

$sqlTransactions = "SELECT * FROM transactions WHERE type = 'investment' ORDER BY transaction_date DESC";
$transactionResult = mysqli_query($conn, $sqlTransactions);
$transactions = mysqli_fetch_all($transactionResult, MYSQLI_ASSOC);

?>

<div class="container" style="padding-top:50px; padding-bottom:50px">
    <div class="content">
        <?php if (isset($_SESSION['user'])) : ?>
            <?php include('../partials/sidebar.php'); ?>
            <button id="menuBtn" class="menu-btn">&#9776;</button>
            <h2 class="h2 m-0 ms-4"><span>All</span> Investments</h2>
        <?php endif ?>
    </div>

    <div class="table-container">
        <table border="">
            <tr>
                <th>S/N</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <!-- <th>PRICE (ETH)</th> -->
                <th>PLAN</th>
                <th>AMOUNT (USD)</th>
                <th>EPECTED RETURN</th>
                <th>MODE</th>
                <th>TYPE</th>
                <th>DATE</th>
                <th>STATUS</th>
                <!-- <th>VIEW</th> -->
                <!-- <th>ACTION</th> -->
                <!-- <th>VIEW</th> -->
            </tr>
            <?php $sn = 1; ?>
            <?php foreach ($transactions as $transaction) : ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $transaction['username'] ?></td>
                    <td><?php echo $transaction['email'] ?></td>
                    <td><?php echo strtoupper($transaction['plan']) ?></td>
                    <td><?php echo number_format(1 * $transaction['amount']) ?> USD</td>
                    <td><?php echo number_format(1 * $transaction['rate_of_return']) ?> USD</td>
                    <td><?php echo strtoupper($transaction['mode']) ?></td>
                    <td><?php echo $transaction['type'] ?></td>
                    <td>
                        <?php
                        $transaction_date = new DateTime($transaction['transaction_date']);
                        echo $transaction_date->format('d F Y');
                        ?>
                    </td>
                    <td>
                        <?php if ($transaction['status'] == 'open') : ?>
                            <small class="btn-primary" style="color: white; font-size: 14px; padding: 3px; border-radius: 3px; background-color: #FF5252;"><?php echo $transaction['status'] ?></small>
                        <?php else : ?>
                            <small class="btn-primary" style="color: white; font-size: 14px; padding: 3px; border-radius: 3px; background-color: var(--primary);"><?php echo $transaction['status'] ?></small>
                        <?php endif; ?>
                    </td>
                    <!-- <td><a href="https://capitalstreamexchange.com/investment?id=<?php echo $transaction['transaction_id'] ?>" class="btn-primary" style="color: white; font-size: 14px; padding: 3px; border-radius: 3px; background-color: var(--primary);">View Details</a></td> -->
                    <!-- <td><a href="/nft_website/admin/transaction_status/<?php echo $transaction['transaction_id'] ?>"><small class="btn-primary" style="color: white; font-size: 14px; padding: 3px; border-radius: 3px">change status</small></a></td> -->
                    <!-- <td><a href="/nft_website/artwork/<?php echo $transaction['transaction_id'] ?>"><small class="btn-primary" style="font-size: 14px; padding: 3px; border-radius: 3px">view nft</small></a></td> -->
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<?php include('../partials/footer.php'); ?>

<style>
    .container .content {
        display: flex;
        align-items: center;
    }

    .h2 {
        font-size: 30px;
        margin-left: 20px;
    }

    .h2 span {
        display: inline;
        background: linear-gradient(to right, var(--primary), red);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .table-container {
        width: 100%;
        margin-top: 20px;
        border: 1px solid var(--primary);
        overflow-x: scroll;
        border-radius: 3px;
    }

    table {
        width: 100%;
        text-align: center;
    }

    th,
    td {
        border: 1px solid var(--primary);
        /* Use a solid border with your primary color */
        padding: 5px;
        text-align: center;
    }

    .menu-btn {
        font-size: 30px;
    }

    @media screen and (max-width: 1024px) {
        table {
            width: 1200px;
        }
    }

    @media screen and (max-width: 499px) {
        main {
            padding: 0px;
        }
    }
</style>

<?php include('../partials/bottom_tab.php') ?>