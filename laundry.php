<?php include 'db_connect.php';

if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM laundry_list where id =" . $_GET['id']);
	foreach ($qry->fetch_array() as $k => $v) {
		$$k = $v;
	}
}
?>

<div class="container-fluid">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<button class="col-sm-3 float-right btn btn-primary btn-sm" type="button" id="new_laundry"><i
								class="fa fa-plus"></i> New Laundry</button>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-bordered" id="laundry-list">
							<thead>
								<tr>
									<th class="text-center">Date</th>
									<th class="text-center">Queue</th>
									<th class="text-center">Customer Name</th>
									<th class="text-center">Phone</th>
									<th class="text-center">Status</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$list = $conn->query("SELECT * FROM laundry_list order by status asc, id asc ");
								while ($row = $list->fetch_assoc()):
									?>
									<tr>
										<td class=""><?php echo date("M d, Y", strtotime($row['date_created'])) ?></td>
										<td class="text-right"><?php echo $row['queue'] ?></td>
										<td class=""><?php echo ucwords($row['customer_name']) ?></td>
										<td class=""><?php echo $row['phone'] ?></td>
										<?php if ($row['status'] == 0): ?>
											<td class="text-center"><span class="badge badge-secondary">Pending</span></td>
										<?php elseif ($row['status'] == 1): ?>
											<td class="text-center"><span class="badge badge-primary">Processing</span></td>
										<?php elseif ($row['status'] == 2): ?>
											<td class="text-center"><span class="badge badge-info">Ready to be Claim</span></td>
										<?php elseif ($row['status'] == 3): ?>
											<td class="text-center"><span class="badge badge-success">Claimed</span></td>
										<?php endif; ?>
										<td class="text-center">
											<button type="button" class="btn btn-outline-primary btn-sm edit_laundry"
												data-id="<?php echo $row['id'] ?>">Edit</button>
											<button type="button" class="btn btn-outline-danger btn-sm delete_laundry"
												data-id="<?php echo $row['id'] ?>">Delete</button>
											<!-- Print Receipt Button: Visible if status is 2 (Ready to be Claim) or 3 (Claimed) -->
											<?php if ($row['status'] == 3): ?>
												<button type="button" class="btn btn-outline-success btn-sm"
													onclick="printReceipt(<?php echo $row['id']; ?>)">Print Receipt</button>
											<?php endif; ?>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Print Receipt Section (Hidden initially) -->
	<div id="receipt" style="display:none;">
		<div id="receipt-content"
			style="font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9; border: 1px solid #ddd; width: 400px; margin: 0 auto;">
			<div style="text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px;">
				<h3 style="font-size: 24px; margin: 0; color: #333;">Receipt</h3>
				<p style="font-size: 14px; color: #777;">Thank you for your business!</p>
			</div>

			<p><strong>Customer Name:</strong> <span id="customer_name" style="font-weight: normal;"></span></p>
			<p><strong>Phone:</strong> <span id="phone" style="font-weight: normal;"></span></p>
			<p><strong>Status:</strong> <span id="status" style="font-weight: normal;"></span></p>
			<p><strong>Payment Status:</strong> <span id="payment_status" style="font-weight: normal;"></span></p>

			<table border="1" cellpadding="5" cellspacing="0"
				style="width: 100%; border-collapse: collapse; margin-top: 20px;">
				<thead>
					<tr style="background-color: #f2f2f2; text-align: left;">
						<th style="padding: 8px; font-weight: bold;">Category</th>
						<th style="padding: 8px; font-weight: bold;">Load</th>
						<th style="padding: 8px; font-weight: bold;">Unit Price</th>
						<th style="padding: 8px; font-weight: bold;">Amount</th>
					</tr>
				</thead>
				<tbody id="receipt-items">
					<!-- Items will be dynamically inserted here -->
				</tbody>
			</table>

			<div style="margin-top: 20px; text-align: right;">
				<p><strong>Total Amount: ₱</strong> <span id="total_amount" style="font-weight: normal;"></span></p>
				<p><strong>Cash Tendered: ₱</strong> <span id="amount_tendered" style="font-weight: normal;"></span></p>
				<p><strong>Change: ₱</strong> <span id="amount_change" style="font-weight: normal;"></span></p>
			</div>

			<div style="text-align: center; margin-top: 30px; border-top: 2px solid #333; padding-top: 10px;">
				<p style="font-size: 12px; color: #777;">Printed on: <span id="receipt-date"></span></p>
			</div>
		</div>
	</div>


</div>

</div>
<script>
	// Print Receipt Function
	function printReceipt(id) {
		// Fetch data for the receipt based on the passed ID
		$.ajax({
			url: 'get_receipt_data.php',
			method: 'GET',
			data: { id: id },
			success: function (response) {
				// Parse the JSON response
				var data = JSON.parse(response);

				// Populate the receipt content with the data
				document.getElementById('customer_name').innerText = data.customer_name;
				document.getElementById('phone').innerText = data.phone;
				document.getElementById('status').innerText = data.status;
				document.getElementById('total_amount').innerText = data.total_amount;
				document.getElementById('amount_tendered').innerText = data.amount_tendered;
				document.getElementById('amount_change').innerText = data.amount_change;
				document.getElementById('payment_status').innerText = data.payment_status;

				// Add current date to the receipt
				document.getElementById('receipt-date').innerText = new Date().toLocaleString();

				// Clear previous items
				var receiptItems = document.getElementById('receipt-items');
				receiptItems.innerHTML = '';

				// Add each laundry item to the receipt
				data.items.forEach(function (item) {
					var row = '<tr>' +
						'<td style="padding: 8px;">' + item.category_name + '</td>' +
						'<td style="padding: 8px;">' + item.weight + '</td>' +
						'<td style="padding: 8px;">₱ ' + item.unit_price + '</td>' +
						'<td style="padding: 8px;">₱ ' + item.amount + '</td>' +
						'</tr>';
					receiptItems.innerHTML += row;
				});

				// Open print dialog
				var receiptContent = document.getElementById('receipt').innerHTML;
				var newWindow = window.open();
				newWindow.document.write('<html><head><title>Receipt</title><style>body { font-family: Arial, sans-serif; }</style></head><body>');
				newWindow.document.write(receiptContent);
				newWindow.document.write('</body></html>');
				newWindow.document.close();
				newWindow.print();
			}
		});
	}

	$('#new_laundry').click(function () {
		uni_modal('New Laundry', 'manage_laundry.php', 'mid-large')
	})
	$('.edit_laundry').click(function () {
		uni_modal('Edit Laundry', 'manage_laundry.php?id=' + $(this).attr('data-id'), 'mid-large')
	})
	$('.delete_laundry').click(function () {
		_conf("Are you sre to remove this data from list?", "delete_laundry", [$(this).attr('data-id')])
	})
	$('#laundry-list').dataTable()
	function delete_laundry($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_laundry',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
			}
		})
	}

</script>
