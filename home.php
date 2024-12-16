<style>

</style>

<div class="containe-fluid">

	<div class="row">
		<div class="col-lg-12">

		</div>
	</div>

	<div class="row mt-3 ml-3 mr-3">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-body">
					<?php echo "Welcome back " . $_SESSION['login_name'] . "!" ?>

				</div>
				<hr>
				<div class="row">
					<div class="alert alert-success col-md-3 ml-4">
						<p><b>
								<large>Total Profit Today</large>
							</b></p>
						<hr>
						<p class="text-right"><b>
								<large><?php
								include 'db_connect.php';
								$laundry = $conn->query("SELECT SUM(total_amount) as amount FROM laundry_list where pay_status= 1 and date(date_created)= '" . date('Y-m-d') . "'");
								echo $laundry->num_rows > 0 ? number_format($laundry->fetch_array()['amount'], 2) : "0.00";

								?></large>
							</b></p>
					</div>
					<div class="alert alert-info col-md-3 ml-4">
						<p><b>
								<large>Total Customer Today</large>
							</b></p>
						<hr>
						<p class="text-right"><b>
								<large><?php
								include 'db_connect.php';
								$laundry = $conn->query("SELECT count(id) as `count` FROM laundry_list where  date(date_created)= '" . date('Y-m-d') . "'");
								echo $laundry->num_rows > 0 ? number_format($laundry->fetch_array()['count']) : "0";

								?></large>
							</b></p>
					</div>
					<div class="alert alert-primary col-md-3 ml-4">
						<p><b>
								<large>Total Claimed Laundry Today</large>
							</b></p>
						<hr>
						<p class="text-right"><b>
								<large><?php
								include 'db_connect.php';
								$laundry = $conn->query("SELECT count(id) as `count` FROM laundry_list where status = 3 and date(date_created)= '" . date('Y-m-d') . "'");
								echo $laundry->num_rows > 0 ? number_format($laundry->fetch_array()['count']) : "0";

								?></large>
							</b></p>
					</div>
					<div class="alert alert-primary col-md-3 ml-4">
						<p><b>
								<large>Total used inventory for the month</large>
							</b></p>
						<hr>
						<p class="text-right"><b>
								<large>
									<?php
									include 'db_connect.php';

									// Query to get the total sum of used items and total price for the current month
									$query = "
												SELECT 
													SUM(i.used) AS total_used_items,
													SUM(i.used * s.price) AS total_price
												FROM 
													inventory i
												JOIN 
													supply_list s ON i.supply_id = s.id
												WHERE 
													MONTH(i.date_created) = MONTH(CURRENT_DATE)
													AND YEAR(i.date_created) = YEAR(CURRENT_DATE)
											";

									// Execute the query
									$result = $conn->query($query);

									if ($result && $result->num_rows > 0) {
										$row = $result->fetch_assoc();
										// Display total used items and total price
										echo "Total Used Items: " . number_format($row['total_used_items']) . "<br>";
										echo "Total Price: " . number_format($row['total_price'], 2);
									} else {
										// If no records are found, display 0
										echo "Total Used Items: 0<br>";
										echo "Total Price: 0.00";
									}
									?>
								</large>

					</div>
				</div>
			</div>

		</div>
	</div>
</div>

</div>
<script>

</script>
