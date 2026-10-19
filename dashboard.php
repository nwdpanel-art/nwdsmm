<?php

require '../config.php';

admin();


/*
|--------------------------------------------------------------------------
| DASHBOARD STATISTICS
|--------------------------------------------------------------------------
*/

$total_users = 0;
$total_orders = 0;
$total_services = 0;
$total_pending = 0;
$total_completed = 0;
$total_revenue = 0;


/*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/

$result = $db->query("
    SELECT COUNT(*) AS total
    FROM users
");

if ($result) {

    $total_users =
        (int) $result->fetch_assoc()['total'];
}


/*
|--------------------------------------------------------------------------
| ORDERS
|--------------------------------------------------------------------------
*/

$result = $db->query("
    SELECT COUNT(*) AS total
    FROM orders
");

if ($result) {

    $total_orders =
        (int) $result->fetch_assoc()['total'];
}


/*
|--------------------------------------------------------------------------
| SERVICES
|--------------------------------------------------------------------------
*/

$result = $db->query("
    SELECT COUNT(*) AS total
    FROM services
    WHERE status = 1
");

if ($result) {

    $total_services =
        (int) $result->fetch_assoc()['total'];
}


/*
|--------------------------------------------------------------------------
| PENDING ORDERS
|--------------------------------------------------------------------------
*/

$result = $db->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Pending'
");

if ($result) {

    $total_pending =
        (int) $result->fetch_assoc()['total'];
}


/*
|--------------------------------------------------------------------------
| COMPLETED ORDERS
|--------------------------------------------------------------------------
*/

$result = $db->query("
    SELECT COUNT(*) AS total
    FROM orders
    WHERE status = 'Completed'
");

if ($result) {

    $total_completed =
        (int) $result->fetch_assoc()['total'];
}


/*
|--------------------------------------------------------------------------
| REVENUE
|--------------------------------------------------------------------------
*/

$result = $db->query("
    SELECT COALESCE(SUM(amount),0) AS total
    FROM orders
    WHERE status != 'Cancelled'
");

if ($result) {

    $total_revenue =
        (float) $result->fetch_assoc()['total'];
}


/*
|--------------------------------------------------------------------------
| RECENT ORDERS
|--------------------------------------------------------------------------
*/

$recent_orders = $db->query("
    SELECT
        o.*,
        u.name AS user_name,
        s.name AS service_name

    FROM orders o

    LEFT JOIN users u
        ON u.id = o.user_id

    LEFT JOIN services s
        ON s.id = o.service_id

    ORDER BY o.id DESC

    LIMIT 10
");

?>

<!doctype html>

<html>

<head>
<?php include __DIR__ . "/../theme_init.php"; ?>


<meta charset="utf-8">

<meta
    name="viewport"
    content="width=device-width,initial-scale=1"
>

<title>Admin Dashboard - NWD PANEL</title>

<link
    rel="stylesheet"
    href="../assets/style.css"
>


<style>

.admin-stats {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(180px,1fr)
        );

    gap: 16px;

    margin-bottom: 20px;
}


.admin-stat {

    background: var(--surface);

    border: 1px solid var(--border);

    border-radius: 15px;

    padding: 20px;
}


.admin-stat small {

    display: block;

    color: var(--muted);

    font-size: 11px;

    margin-bottom: 8px;
}


.admin-stat strong {

    font-size: 27px;
}


.admin-stat p {

    margin: 7px 0 0;

    color: var(--muted);

    font-size: 13px;
}


.recent-table {

    width: 100%;

    border-collapse: collapse;
}


.recent-table th,
.recent-table td {

    padding: 12px;

    border-bottom: 1px solid var(--border);

    text-align: left;
}


.recent-table th {

    font-size: 12px;

    color: var(--muted);
}


.quick-actions {

    display: grid;

    grid-template-columns:
        repeat(
            auto-fit,
            minmax(180px,1fr)
        );

    gap: 12px;
}


.quick-action {

    display: block;

    padding: 16px;

    border: 1px solid var(--border);

    border-radius: 12px;

    text-decoration: none;

    color: inherit;

    background: var(--surface);
}


.quick-action strong {

    display: block;

    margin-bottom: 5px;
}


.quick-action span {

    font-size: 13px;

    color: var(--muted);
}

</style>

</head>


<body>


<div class="app">


<!-- SIDEBAR -->

<?php include __DIR__ . "/_sidebar.php"; ?>


<!-- MAIN -->

<main class="main">


<!-- TOP -->

<div class="top">


<div>

<p class="eyebrow">
    ADMIN PANEL
</p>


<h1>
    Admin Dashboard
</h1>

</div>


<div class="balance">

    NWD<span>PANEL</span>

</div>


</div>


<!-- STATS -->

<div class="admin-stats">


<!-- USERS -->

<div class="admin-stat">

<small>
    TOTAL USERS
</small>


<strong>
    <?= number_format($total_users) ?>
</strong>


<p>
    Registered users
</p>

</div>


<!-- ORDERS -->

<div class="admin-stat">

<small>
    TOTAL ORDERS
</small>


<strong>
    <?= number_format($total_orders) ?>
</strong>


<p>
    All orders
</p>

</div>


<!-- SERVICES -->

<div class="admin-stat">

<small>
    ACTIVE SERVICES
</small>


<strong>
    <?= number_format($total_services) ?>
</strong>


<p>
    Available services
</p>

</div>


<!-- PENDING -->

<div class="admin-stat">

<small>
    PENDING ORDERS
</small>


<strong>
    <?= number_format($total_pending) ?>
</strong>


<p>
    Waiting for processing
</p>

</div>


<!-- COMPLETED -->

<div class="admin-stat">

<small>
    COMPLETED ORDERS
</small>


<strong>
    <?= number_format($total_completed) ?>
</strong>


<p>
    Successfully completed
</p>

</div>


<!-- REVENUE -->

<div class="admin-stat">

<small>
    ORDER REVENUE
</small>


<strong>

    Rs. <?= number_format(
        $total_revenue,
        2
    ) ?>

</strong>


<p>
    Non-cancelled orders
</p>

</div>


</div>


<!-- QUICK ACTIONS -->

<div class="card">


<div class="card-head">

<h2>
    Quick Actions
</h2>

</div>


<div class="quick-actions">


<a
    class="quick-action"
    href="services.php"
>

<strong>
    ⚙️ Manage Services
</strong>

<span>
    Add, disable or delete services
</span>

</a>


<a
    class="quick-action"
    href="orders.php"
>

<strong>
    📦 Manage Orders
</strong>

<span>
    View and update customer orders
</span>

</a>


<a
    class="quick-action"
    href="deposits.php"
>

<strong>
    💳 Manage Deposits
</strong>

<span>
    Approve or reject payment requests
</span>

</a>


<a
    class="quick-action"
    href="../dashboard.php"
>

<strong>
    👤 User Dashboard
</strong>

<span>
    Open customer dashboard
</span>

</a>


</div>

</div>


<!-- RECENT ORDERS -->

<div
    class="card"
    style="margin-top:20px;"
>


<div class="card-head">

<h2>
    Recent Orders
</h2>


<a href="orders.php">
    View All →
</a>


</div>


<div class="table-wrap">


<table class="recent-table">


<thead>

<tr>

<th>
    Order
</th>

<th>
    User
</th>

<th>
    Service
</th>

<th>
    Quantity
</th>

<th>
    Amount
</th>

<th>
    Status
</th>

</tr>

</thead>


<tbody>


<?php if (
    $recent_orders &&
    $recent_orders->num_rows > 0
): ?>


<?php while (
    $order =
    $recent_orders->fetch_assoc()
): ?>


<tr>


<td>

#<?= (int) $order['id'] ?>

</td>


<td>

<?= e(
    $order['user_name']
) ?>

</td>


<td>

<?= e(
    $order['service_name']
) ?>

</td>


<td>

<?= number_format(
    $order['quantity']
) ?>

</td>


<td>

Rs. <?= number_format(
    (float) $order['amount'],
    2
) ?>

</td>


<td>

<?= e(
    $order['status']
) ?>

</td>


</tr>


<?php endwhile; ?>


<?php else: ?>


<tr>

<td
    colspan="6"
    style="text-align:center;"
>

No orders found.

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>


</div>


</main>


</div>



</body>

</html>