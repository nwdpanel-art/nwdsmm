<?php

require '../config.php';

admin();


/*
|--------------------------------------------------------------------------
| DASHBOARD DATA
|--------------------------------------------------------------------------
*/

$users = $db->query("
    SELECT COUNT(*) c
    FROM users
")->fetch_assoc()['c'];


$orders = $db->query("
    SELECT COUNT(*) c
    FROM orders
")->fetch_assoc()['c'];


$rev = $db->query("
    SELECT COALESCE(SUM(amount),0) s
    FROM orders
")->fetch_assoc()['s'];

?>

<!doctype html>

<html>

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width,initial-scale=1"
    >

    <title>Admin</title>

    <link
        rel="stylesheet"
        href="../assets/style.css"
    >

</head>


<body>


<div class="app">


    <!-- SIDEBAR -->

    <?php include __DIR__ . "/_sidebar.php"; ?>


    <!-- MAIN -->

    <main class="main">


        <p class="eyebrow">
            ADMIN
        </p>


        <h1>
            Control Center
        </h1>


        <!-- STATS -->

        <div class="stats">


            <div class="stat">

                <small>
                    USERS
                </small>

                <b>
                    <?= number_format(
                        $users
                    ) ?>
                </b>

            </div>


            <div class="stat">

                <small>
                    ORDERS
                </small>

                <b>
                    <?= number_format(
                        $orders
                    ) ?>
                </b>

            </div>


            <div class="stat">

                <small>
                    ORDER VALUE
                </small>

                <b>
                    Rs. <?= number_format(
                        (float)$rev,
                        2
                    ) ?>
                </b>

            </div>


        </div>


        <!-- MANAGEMENT -->

        <div class="card">


            <h2>
                Management
            </h2>


            <p class="lead">

                Use the navigation to manage
                users, orders, services,
                deposits and support tickets.

            </p>


        </div>


    </main>


</div>


</body>

</html>