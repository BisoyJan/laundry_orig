<?php include('db_connect.php'); ?>

<div class="container-fluid">

	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
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
								<label class="control-label">Size (Volume)</label>
								<div class="row">
									<div class="col-md-6">
										<select name="size_unit" id="size_unit" class="custom-select" required>
											<option value="L">Liter (L)</option>
											<option value="ml">Milliliter (mL)</option>
											<option value="gal">Gallon (gal)</option>
										</select>
									</div>
									<div class="col-md-6">
										<input type="number" step="any" name="size_value" id="size_value"
											class="form-control" required>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" step="any" name="price" id="price" class="form-control" required>
							</div>
						</div>

						<div class="card-footer">
							<div class="row">
								<div class="col-md-12">
									<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
									<button class="btn btn-sm btn-default col-sm-3" type="button"
										onclick="$('#manage-supply').get(0).reset()"> Cancel</button>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Brand Name</th>
									<th class="text-center">Category</th>
									<th class="text-center">Size</th>
									<th class="text-center">Price</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<!-- Add your tbody content here -->
								<?php
								$i = 1;
								$cats = $conn->query("SELECT * FROM supply_list order by id asc");
								while ($row = $cats->fetch_assoc()):
									?>
									<tr>
										<td class="text-center"><?php echo $i++ ?></td>
										<td class="">
											<p><b><?php echo $row['brand'] ?></b></p>
										</td>
										<td class="">
											<p><b><?php echo $row['category'] ?></b></p>
										</td>
										<td class="">
											<p><b><?php echo $row['size'] ?></b></p>
										</td>
										<td class="">
											<p><b><?php echo $row['price'] ?></b></p>
										</td>
										<td class="text-center">
											<button class="btn btn-sm btn-primary edit_supply" type="button"
												data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['brand'] ?>"
												data-category="<?php echo $row['category'] ?>"
												data-price="<?php echo $row['price'] ?>">Edit</button>
											<button class="btn btn-sm btn-danger delete_supply" type="button"
												data-id="<?php echo $row['id'] ?>">Delete</button>
										</td>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
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
		var size_unit = $('#size_unit').val();
		var size_value = $('#size_value').val();
		var price = $('#price').val();
		$.ajax({
			url: 'ajax.php?action=save_supply',
			data: {
				id: id,
				brand_name: brand_name,
				category: category,
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
