<?php
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the laundry list data
    $qry = $conn->query("SELECT * FROM laundry_list WHERE id = $id");
    $laundry = $qry->fetch_assoc();

    // Fetch the laundry items
    $items = [];

    $itemQuery = $conn->query("SELECT 
                ll.id AS laundry_id,
                ll.customer_name,
                ll.phone,
                ll.status,
                ll.queue,
                ll.total_amount,
                li.id AS laundry_item_id,
                li.weight,
                li.unit_price,
                li.amount,
                lc.id AS category_id,
                lc.name AS category_name,
                lc.price AS category_price
            FROM 
                laundry_list ll
            JOIN 
                laundry_items li ON ll.id = li.laundry_id
            JOIN 
                laundry_categories lc ON li.laundry_category_id = lc.id
            WHERE 
                ll.id = $id");
    //$itemQuery = $conn->query("SELECT * FROM laundry_items WHERE laundry_id = $id");

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

