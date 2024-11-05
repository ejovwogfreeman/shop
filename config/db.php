<?php


define('DB_HOST', 'localhost');
define('DB_USER', 'capitalstreamexchange_admin');
define('DB_PASSWORD', 'capitalstreamexchange12345#');
define('DB_NAME', 'capitalstreamexchange');

// define('DB_HOST', 'localhost');
// define('DB_USER', 'gbuser');
// define('DB_PASSWORD', 'gb12345');
// define('DB_NAME', 'pay_me');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// if ($conn) {
//     echo 'Connection successful';
// } else {
//     echo 'Not conected';
// }
