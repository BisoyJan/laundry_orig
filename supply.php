<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-12 col-lg-4">
				<form action="" id="manage-supply">
					<div class="card">
						<div class="card-header">
							Laundry Supply Form
						</div>
						<div class="card-body">
							<input type="hidden" name="id" id="id">
							<div class="form-group">
								<label class="control-label">Brand Name</label>
								<input type="text" name="brand_name" id="brand_name" class="form-control" required>
							</div>
							<div class="form-group">
								<label class="control-label">Category</label>
								<select name="category" id="category" class="custom-select" required>
									<option value="Laundry Soaps">Laundry Soaps</option>
									<option value="Alkaline Builder Detergent">Alkaline Builder Detergent</option>
									<option value="Laundry Destainer">Laundry Destainer</option>
									<option value="Bleach">Bleach</option>
									<option value="Laundry Neutralizer">Laundry Neutralizer</option>
									<option value="Fabric Starch">Fabric Starch</option>
									<option value="Fabric Softener">Fabric Softener</option>
									<option value="Laundry Emulsifier">Laundry Emulsifier</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Classification</label>
								<select name="classification" id="classification" class="custom-select" required>
									<option value="Fragrance">Fragrance</option>
									<option value="Cleaning Agent">Cleaning Agent</option>
									<option value="Sanitizer">Sanitizer</option>
								</select>
							</div>
							<div class="form-group">
								<label class="control-label">Size (Volume)</label>
								<div class="row">
									<div class="col-6">
										<select name="size_unit" id="size_unit" class="custom-select" required>
											<option value="L">Liter (L)</option>
											<option value="ml">Milliliter (mL)</option>
											<option value="gal">Gallon (gal)</option>
										</select>
									</div>
									<div class="col-6">
										<input type="number" step="any" name="size_value" id="size_value"
											class="form-control" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" step="any" name="price" id="price" class="form-control" required>
							</div>

							<div class="card-footer">
								<div class="row">
									<div class="col-6">
										<button class="btn btn-sm btn-primary btn-block"> Save</button>
									</div>
									<div class="col-6">
										<button class="btn btn-sm btn-default btn-block" type="button"
											onclick="$('#manage-supply').get(0).reset()"> Cancel</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-12 col-md-8">
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th class="text-center">Brand Name</th>
										<th class="text-center">Category</th>
										<th class="text-center">Classification</th>
										<th class="text-center">Size</th>
										<th class="text-center">Price</th>
										<th class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$i = 1;
									$inventory = $conn->query("SELECT id, brand, category, classification, size, price FROM supply_list;");
									while ($row = $inventory->fetch_assoc()):
										?>
										<tr>
											<td class="text-center"><?php echo $i++ ?></td>
											<td><?php echo $row['brand'] ?></td>
											<td><?php echo $row['category'] ?></td>
											<td><?php echo $row['classification'] ?></td>
											<td><?php echo $row['size'] ?></td>
											<td class="text-right"><?php echo $row['price'] ?></td>
											<td class="text-center">
												<button type="button" class="btn btn-sm btn-outline-primary edit_supply"
													data-id="<?php echo $row['id'] ?>"
													data-name="<?php echo $row['brand'] ?>"
													data-category="<?php echo $row['category'] ?>"
													data-price="<?php echo $row['price'] ?>"><i class=" fa
													fa-edit"></i></button>
												<button type="button" class="btn btn-sm btn-outline-danger delete_stock"
													data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>


</div>
<style>
	td {
		vertical-align: middle !important;
	}

	td p {
		margin: unset
	}
</style>
<script>

	$('#manage-supply').submit(function (e) {
		e.preventDefault()
		start_load()
		var id = $('#id').val();
		var brand_name = $('#brand_name').val();
		var category = $('#category').val();
		var classification = $('#classification').val();
		var size_unit = $('#size_unit').val();
		var size_value = $('#size_value').val();
		var price = $('#price').val();
		$.ajax({
			url: 'ajax.php?action=save_supply',
			data: {
				id: id,
				brand_name: brand_name,
				category: category,
				classification: classification,
				size_unit: size_unit,
				size_value: size_value,
				price: price
			},
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
		})
	})
	$('.edit_supply').click(function () {
		start_load()
		var cat = $('#manage-supply')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='brand_name']").val($(this).attr('data-name'))
		cat.find("[name='category']").val($(this).attr('data-category'))
		cat.find("[name='classification']").val($(this).attr('data-classification'))
		cat.find("[name='size_unit']").val($(this).attr('data-size-unit'))
		cat.find("[name='size_value']").val($(this).attr('data-size-value'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		end_load()
	})
	$('.delete_supply').click(function () {
		_conf("Are you sure to delete this supply?", "delete_supply", [$(this).attr('data-id')])
	})
	function delete_supply($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_supply',
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
