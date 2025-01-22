<?php
include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM inventory where id=" . $_GET['id']);
	foreach ($qry->fetch_assoc() as $k => $v) {
		$$k = $v;
	}
}
?>

<div class="container-fluid">
	<form action="" id="manage-inv">
		<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
		<div class="form-group">
			<div class="form-group">
				<label for="" class="control-label">Supply Name</label>
				<select class="custom-select browser-default" name="supply_id">
					<?php
					$supply = $conn->query("SELECT * FROM supply_list order by brand asc");
					while ($row = $supply->fetch_assoc()):
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($supply_id) && $supply_id == $row['id'] ? "selected" : '' ?>>
							<?php echo $row['brand'] ?> -
							<?php echo $row['category'] ?>/<?php echo $row['classification'] ?>
						</option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="" class="control-label">Quantity</label>
				<input type="number" step="any" min="1" value="<?php echo isset($qty) ? $qty : 1 ?>"
					class="form-control text-right" name="qty">
			</div>
			<div class="form-group">
				<label for="" class="control-label">In used</label>
				<input type="number" step="any" min="1" value="<?php echo isset($used) ? $used : 0 ?>"
					class="form-control text-right" name="used">
			</div>
			<div class="form-group">
				<label for="" class="control-label">Type</label>
				<select name="stock_type" id="" class="custom-select browser-default">
					<option value="1" <?php echo isset($stock_type) && $stock_type == 1 ? "selected" : '' ?>>Stock In
					</option>
					<option value="2" <?php echo isset($stock_type) && $stock_type == 2 ? "selected" : '' ?>>Use</option>
				</select>
			</div>
		</div>
	</form>
</div>

<script>
	$('#manage-inv').submit(function (e) {
		e.preventDefault();
		start_load();
		var id = $('[name="id"]').val();
		var supply_id = $('[name="supply_id"]').val();
		var qty = $('[name="qty"]').val();
		var used = $('[name="used"]').val();
		var stock_type = $('[name="stock_type"]').val();
		$.ajax({
			url: 'ajax.php?action=save_inv',
			method: 'POST',
			data: {
				id: id,
				supply_id: supply_id,
				qty: qty,
				used: used,
				stock_type: stock_type
			},
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved", 'success');
					setTimeout(function () {
						location.reload();
					}, 1000);
				} else {
					alert_toast("Error saving data", 'danger');
				}
			},
			error: function (err) {
				console.log(err);
				alert_toast("An error occurred", 'danger');
			}
		});
	});

	$(document).ready(function () {
		var stockType = $('[name="stock_type"]').val();
		if (stockType == 1) {
			$('[name="qty"]').attr('disabled', false);
			$('[name="used"]').attr('disabled', true);
		} else {
			$('[name="qty"]').attr('disabled', true);
			$('[name="used"]').attr('disabled', false);
		}
	});

	$('#manage-inv').on('change', '[name="stock_type"]', function () {
		var stockType = $(this).val();
		if (stockType == 1) {
			$('[name="qty"]').attr('disabled', false);
			$('[name="used"]').attr('disabled', true);
		} else {
			$('[name="qty"]').attr('disabled', true);
			$('[name="used"]').attr('disabled', false);
		}
	});
</script>
