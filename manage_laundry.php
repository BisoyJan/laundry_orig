<?php
include "db_connect.php";

if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM laundry_list where id =" . $_GET['id']);
	foreach ($qry->fetch_array() as $k => $v) {
		$$k = $v;
	}

}
?>

<div class="container-fluid">
	<form action="" id="manage-laundry">
		<div class="col-lg-12">
			<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
			<div class="row">
				<!-- Customer Name -->
				<div class="col-md-4">
					<div class="form-group">
						<label for="customer_name" class="control-label">Customer Name</label>
						<input type="text" class="form-control" name="customer_name"
							value="<?php echo isset($customer_name) ? $customer_name : '' ?>">
					</div>
				</div>

				<!-- Phone Number -->
				<div class="col-md-4">
					<div class="form-group">
						<label for="phone" class="control-label">Phone Number</label>
						<input type="tel" class="form-control" name="phone" pattern="[0-9]+"
							title="Only numbers are allowed" value="<?php echo isset($phone) ? $phone : '' ?>">
					</div>
				</div>

				<?php if (isset($_GET['id'])): ?>
					<!-- Status Dropdown -->
					<div class="col-md-4">
						<div class="form-group">
							<label for="status" class="control-label">Status</label>
							<select name="status" id="status" class="custom-select browser-default">
								<option value="0" <?php echo $status == 0 ? "selected" : '' ?>>Pending</option>
								<option value="1" <?php echo $status == 1 ? "selected" : '' ?>>Processing</option>
								<option value="2" <?php echo $status == 2 ? "selected" : '' ?>>Ready to be Claim</option>
								<option value="3" <?php echo $status == 3 ? "selected" : '' ?>>Claimed</option>
							</select>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- Remarks -->
			<div class="row">
				<div class="form-group col-md-12">
					<label for="remarks" class="control-label">Remarks</label>
					<textarea name="remarks" id="remarks" cols="30" rows="2"
						class="form-control"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
				</div>
			</div>
			<hr>

			<!-- Laundry Category & Load -->
			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="laundry_category_id" class="control-label">Laundry Category</label>
						<select class="custom-select browser-default" id="laundry_category_id">
							<?php
							$cat = $conn->query("SELECT * FROM laundry_categories order by name asc");
							while ($row = $cat->fetch_assoc()):
								$cname_arr[$row['id']] = $row['name'];
								?>
								<option value="<?php echo $row['id'] ?>" data-price="<?php echo $row['price'] ?>">
									<?php echo $row['name'] ?>
								</option>
							<?php endwhile; ?>
						</select>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="weight" class="control-label">Load</label>
						<input type="number" step="any" min="1" value="1" class="form-control text-right" id="weight">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="inventory_id" class="control-label">Detergent Brand</label>
						<select name="inventory_id" id="inventory_id" class="custom-select browser-default">
							<option value="">Select Brand</option>
							<?php
							$inv = $conn->query("SELECT inventory.id, inventory.qty, inventory.used, supply_list.brand, supply_list.category, supply_list.price, supply_list.classification, supply_list.size, supply_list.id as supply_list_id FROM inventory JOIN supply_list ON inventory.supply_id = supply_list.id ORDER BY inventory.id DESC");
							while ($row = $inv->fetch_assoc()):
								?>
								<option value="<?php echo $row['supply_list_id']; ?>"
									data-price="<?php echo $row['price']; ?>" data-size="<?php echo $row['size']; ?>"
									data-brand="<?php echo $row['brand'] . " / " . $row['classification']; ?> ">
									<?php echo $row['brand'] . " / " . $row['classification']; ?>
								</option>
							<?php endwhile; ?>

						</select>
					</div>
				</div>

				<div class="col-md-5">
					<div class="form-group">
						<label for="detergent_20ml_price" class="control-label">Product Unit Price per 20ml/Load</label>
						<input type="number" step="any" value="0" class="form-control text-right"
							id="detergent_20ml_price" readonly>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="" class="control-label">&nbsp;</label>
						<button class="btn btn-info btn-sm btn-block" type="button" id="add_to_list"><i
								class="fa fa-plus"></i> Add to List</button>
					</div>
				</div>
			</div>

			<!-- Laundry Items Table -->
			<div class="row">
				<table class="table table-bordered" id="list">
					<colgroup>
						<col width="30%">
						<col width="15%">
						<col width="25%">
						<col width="25%">
						<col width="5%">
					</colgroup>
					<thead>
						<tr>
							<th class="text-center">Category</th>
							<th class="text-center">Load</th>
							<th class="text-center">Product</th> <!-- show the product name -->
							<th class="text-center">Product price/Load</th> <!-- show product price -->
							<th class="text-center">Unit Price</th>
							<th class="text-center">Amount</th>
							<th class="text-center"></th>
						</tr>
					</thead>
					<tbody>
						<?php if (isset($_GET['id'])): ?>
							<?php
							$list = $conn->query("SELECT 
												li.id AS laundry_item_id,
												li.laundry_category_id,
												li.weight,
												li.product_price,
												li.unit_price,
												li.amount,
												sl.id AS supply_list_id,
												sl.brand,
												sl.category,
												sl.classification,
												sl.size,
												sl.price AS supply_price
											FROM 
												laundry_items li
											INNER JOIN 
												supply_list sl
											ON 
												li.supply_list_id = sl.id
											WHERE 
												li.laundry_id =" . $id);
							while ($row = $list->fetch_assoc()):
								?>
								<tr data-id="<?php echo $row['laundry_item_id'] ?>">
									<td>
										<input type="hidden" name="item_id[]" value="<?php echo $row['laundry_item_id'] ?>">
										<input type="hidden" name="laundry_category_id[]"
											value="<?php echo $row['laundry_category_id'] ?>">
										<?php echo isset($cname_arr[$row['laundry_category_id']]) ? ucwords($cname_arr[$row['laundry_category_id']]) : '' ?>
									</td>
									<td>
										<input type="number" class="text-center" name="weight[]"
											value="<?php echo $row['weight'] ?>">
									</td>
									<td>
										<input type="hidden" class="text-center" name="inventory_id[]"
											value="<?php echo $row['supply_list_id'] ?>">
										<?php echo $row['brand'] . ' / ' . $row['classification'] ?>
									</td>
									<td>
										<input type="hidden" class="text-center" name="product_price[]"
											value="<?php echo $row['product_price'] ?>">
										<?php echo number_format($row['product_price'], 2) ?>
									</td>
									<td class="text-right">
										<input type="hidden" name="unit_price[]" value="<?php echo $row['unit_price'] ?>">
										<?php echo number_format($row['unit_price'], 2) ?>
									</td>
									<td class="text-right">
										<input type="hidden" name="amount[]" value="<?php echo $row['amount'] ?>">
										<p><?php echo number_format($row['amount'], 2) ?></p>
									</td>
									<td>
										<button class="btn btn-sm btn-danger" type="button" onclick="rem_list($(this))"><i
												class="fa fa-times"></i></button>
									</td>
								</tr>
							<?php endwhile; ?>
						<?php endif; ?>
					</tbody>
					<tfoot>
						<tr>
							<th class="text-right" colspan="5">Total Amount</th>
							<th class="text-right" id="tamount"></th>
							<th class="text-right"></th>
						</tr>
					</tfoot>
				</table>
			</div>

			<hr>

			<!-- Payment Section -->
			<div class="row">
				<div class="form-group col-md-12">
					<div class="custom-control custom-switch" id="pay-switch">
						<input type="checkbox" class="custom-control-input" value="1" name="pay" id="paid" <?php echo isset($pay_status) && $pay_status == 1 ? 'checked' : '' ?>>
						<label class="custom-control-label" for="paid">Pay</label>
					</div>
				</div>
			</div>

			<div class="row" id="payment">
				<div class="col-md-4">
					<div class="form-group">
						<label for="tendered" class="control-label">Cash</label>
						<input type="number" step="any" min="0"
							value="<?php echo isset($amount_tendered) ? $amount_tendered : 0 ?>"
							class="form-control text-right" name="tendered">
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="tamount" class="control-label">Total Amount</label>
						<input type="text"
							value="<?php echo isset($total_amount) ? number_format((float) $total_amount, 2, '.', '') : '0.00' ?>"
							class="form-control text-right" name="tamount" readonly>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label for="change" class="control-label">Change</label>
						<input type="number" step="any" min="1"
							value="<?php echo isset($amount_change) ? $amount_change : 0 ?>"
							class="form-control text-right" name="change" readonly>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script>
	$(document).ready(function () {
		$('[name="phone"]').on('input', function () {
			// Replace non-numeric characters
			this.value = this.value.replace(/[^0-9]/g, '');
		});
	});

	if ('<?php echo isset($_GET['id']) ?>' == 1) {
		calc()
	}
	if ($('[name="pay"]').prop('checked') == true) {
		$('[name="tendered"]').attr('required', true)
		$('#payment').show();
	} else {
		$('#payment').hide();
		$('[name="tendered"]').attr('required', false)
	}
	$('#pay-switch').click(function () {
		if ($('[name="pay"]').prop('checked') == true) {
			$('[name="tendered"]').attr('required', true)
			$('#payment').show('slideDown');
		} else {
			$('#payment').hide('SlideUp');
			$('[name="tendered"]').attr('required', false)
		}
	})
	$('[name="tendered"],[name="tamount"]').on('keypup keydown keypress change input', function () {
		var tend = $('[name="tendered"]').val();
		var amount = $('[name="tamount"]').val();
		var change = parseFloat(tend) - parseFloat(amount)
		change = parseFloat(change).toLocaleString('en-US', { style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2 })
		$('[name="change"]').val(change)
	})
	$('#add_to_list').click(function () {
		var cat = $('#laundry_category_id').val(),
			_weight = $('#weight').val();
		if (cat == '' || _weight == '') {
			alert_toast('Fill the category and weight fields first.', 'warning')
			return false;
		}
		if ($('#list tr[data-id="' + cat + '"]').length > 0) {
			alert_toast('Category already exist.', 'warning')
			return false;
		}
		var price = $('#laundry_category_id option[value="' + cat + '"]').attr('data-price');
		var productPrice = $('#detergent_20ml_price').val();
		var cname = $('#laundry_category_id option[value="' + cat + '"]').html();
		var amount = parseFloat(price) * parseFloat(_weight);
		var productBrand = $('#inventory_id option[value="' + $('#inventory_id').val() + '"]').html(); // get the product brand
		var tr = $('<tr></tr>');
		tr.attr('data-id', cat)
		tr.append('<input type="hidden" name="item_id[]" id="" value=""><td class="text-center"><input type="hidden" name="laundry_category_id[]" id="" value="' + cat + '">' + cname + '</td>')
		tr.append('<td><input type="number" class="text-center" name="weight[]" id="" value="' + _weight + '"></td>')
		tr.append('<td class="text-center"><input type="hidden" name="inventory_id[]" id="" value="' + $('#inventory_id').val() + '">' + productBrand + '</td>')
		tr.append('<td class="text-center"><input type="hidden" name="product_price[]" id="" value="' + $('#detergent_20ml_price').val() + '">' + $('#detergent_20ml_price').val() + '</td>')
		tr.append('<td class="text-right"><input type="hidden" name="unit_price[]" id="" value="' + price + '">' + (parseFloat(price).toLocaleString('en-US', { style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2 })) + '</td>')
		tr.append('<td class="text-right"><input type="hidden" name="amount[]" id="" value="' + amount + '"><p>' + (parseFloat(amount).toLocaleString('en-US', { style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2 })) + '</p></td>')
		tr.append('<td><button class="btn btn-sm btn-danger" type="button" onclick="rem_list($(this))"><i class="fa fa-times"></i></button></td>')
		$('#list tbody').append(tr)
		calc()
		$('[name="weight[]"]').on('keyup keydown keypress change', function () {
			calc();
		})
		$('[name="tendered"]').trigger('keypress')

		$('#laundry_category_id').val('')
		$('#weight').val('')
	})
	function rem_list(_this) {
		_this.closest('tr').remove()
		calc()
		$('[name="tendered"]').trigger('keypress')


	}
	function calc() {
		var total = 0;
		$('#list tbody tr').each(function () {
			var _this = $(this)
			var weight = _this.find('[name="weight[]"]').val()
			var unit_price = _this.find('[name="unit_price[]"]').val()
			var product_price = _this.find('[name="product_price[]"]').val()
			var product_calculations = parseFloat(product_price) * parseFloat(weight)
			var amount = parseFloat(weight) * parseFloat(unit_price) + product_calculations
			_this.find('[name="amount[]"]').val(amount)
			_this.find('[name="amount[]"]').siblings('p').html(parseFloat(amount).toLocaleString('en-US', { style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2 }))
			total += amount;

		})
		$('[name="tamount"]').val(total)
		$('#tamount').html(parseFloat(total).toLocaleString('en-US', { style: 'decimal', maximumFractionDigits: 2, minimumFractionDigits: 2 }))


	}
	$('#manage-laundry').submit(function (e) {
		e.preventDefault()
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_laundry',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully added", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
				else if (resp == 2) {
					alert_toast("Data successfully updated", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)

				}
			}
		});
	});

	$(document).ready(function () {
		// When a detergent brand is selected
		$('#inventory_id').change(function () {
			var selectedOption = $(this).find(':selected');
			var size = selectedOption.data('size'); // Get size (e.g., "20 L" or "5 gal")
			var price = parseFloat(selectedOption.data('price')); // Get price
			var sizeInMl = 0;

			console.log('Selected Size:', size);
			console.log('Selected Price:', price);

			// Parse size to get volume in milliliters
			if (size) {
				var sizeParts = size.split(' ');
				var value = parseFloat(sizeParts[0]);
				var unit = sizeParts[1]?.toLowerCase();

				if (unit === 'l') {
					sizeInMl = value * 1000; // Convert liters to milliliters
				} else if (unit === 'ml') {
					sizeInMl = value;
				} else if (unit === 'gal') {
					sizeInMl = value * 3785.41; // Convert gallons to milliliters
				}
			}

			console.log('Size in Milliliters:', sizeInMl);

			// Calculate price for 20ml in pesos
			var pricePer20Ml = (price / sizeInMl) * 20 + 3;

			console.log('Price Per 20ml:', pricePer20Ml);

			// Display price per 20ml
			if (!isNaN(pricePer20Ml)) {
				$('#detergent_20ml_price').val(pricePer20Ml.toFixed(2)); // Update unit price field in pesos
			} else {
				$('#detergent_20ml_price').val('');
			}
		});
	});




</script>
