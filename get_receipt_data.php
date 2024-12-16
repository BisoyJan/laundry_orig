<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the laundry list data
    $qry = $conn->query("SELECT * FROM laundry_list WHERE id = $id");
    $laundry = $qry->fetch_assoc();

    // Fetch the laundry items
    $items = [];
    $itemQuery = $conn->query("SELECT * FROM laundry_items WHERE laundry_id = $id");
    while ($item = $itemQuery->fetch_assoc()) {
        $items[] = $item;
    }

    // Prepare the response
    $response = [
        'customer_name' => $laundry['customer_name'],
        'phone' => $laundry['phone'],
        'status' => ($laundry['status'] == 0) ? 'Pending' : ($laundry['status'] == 1 ? 'Processing' : ($laundry['status'] == 2 ? 'Ready to be Claim' : 'Claimed')),
        'total_amount' => $laundry['total_amount'],
        'amount_tendered' => $laundry['amount_tendered'],
        'amount_change' => $laundry['amount_change'],
        'items' => $items
    ];

    // Return the data as JSON
    echo json_encode($response);
}
?>

