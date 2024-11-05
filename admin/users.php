<?php
include('../config/session.php');
include('../partials/header.php');
include('../config/db.php');

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $userId = $user['user_id'];
}

$sqlUsers = "SELECT * FROM users ORDER BY date_joined DESC";
$usersResult = mysqli_query($conn, $sqlUsers);
$users = mysqli_fetch_all($usersResult, MYSQLI_ASSOC);
?>

<div class="container" style="padding-top:50px; padding-bottom:50px">
    <div class="content">
        <?php if (isset($_SESSION['user'])) : ?>
            <?php include('../partials/sidebar.php'); ?>
            <button id="menuBtn" class="menu-btn">&#9776;</button>
            <h2 class="h2"><span>All</span> Users</h2>
        <?php endif ?>
    </div>

    <div class="table-container">
        <table border="">
            <tr>
                <th>S/N</th>
                <th>USERNAME</th>
                <th>EMAIL</th>
                <th>BALANCE (USD)</th>
                <th>DATE JOINED</th>
                <th>FUND</th>
                <th>VIEW</th>
            </tr>
            <?php $sn = 1; ?>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td><?php echo $sn++; ?></td>
                    <td><?php echo $user['username']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo number_format(1 * $user['balance']); ?> USD</td>
                    <td>
                        <?php
                        $date_joined = new DateTime($user['date_joined']);
                        echo $date_joined->format('d F Y');
                        ?>
                    </td>
                    <td><a href="https://capitalstreamexchange.com/admin/fund_user?id=<?php echo $user['user_id'] ?>"><small class="btn-primary" style="font-size: 14px; padding: 3px; border-radius: 3px; color: white">fund user</small></a></td>
                    <td><a href="https://capitalstreamexchange.com/profile?id=<?php echo $user['user_id'] ?>"><small class="btn-primary" style="font-size: 14px; padding: 3px; border-radius: 3px; color: white">view profile</small></a></td>
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
        margin: 0px;
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