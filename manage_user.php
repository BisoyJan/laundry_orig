<?php
include('db_connect.php');
if (isset($_GET['id'])) {
	$user = $conn->query("SELECT * FROM users where id =" . $_GET['id']);
	foreach ($user->fetch_array() as $k => $v) {
		$meta[$k] = $v;
	}
}
?>
<div class="container-fluid">

	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
		<div class="form-group">
			<label for="name">Name</label>
			<input type="text" name="name" id="name" class="form-control"
				value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>"
				oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" required>
		</div>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" class="form-control"
				value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>"
				oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '')" required>
		</div>
		<div class="form-group">
			<label for="password">Password</label>
			<input type="password" name="password" id="password" class="form-control"
				value="<?php echo isset($meta['password']) ? $meta['password'] : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="cpassword">Confirm Password</label>
			<input type="password" name="cpassword" id="cpassword" class="form-control"
				value="<?php echo isset($meta['password']) ? $meta['password'] : '' ?>" required>
			<span id="cpassword-msg"></span>
		</div>
		<?php if (!isset($meta['type']) || $meta['type'] != 1): ?>
			<div class="form-group">
				<label for="type">User Type</label>
				<select name="type" id="type" class="custom-select">
					<option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin
					</option>
					<option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Staff
					</option>
				</select>
			</div>
		<?php endif; ?>
	</form>
</div>

<script>
	$('#manage-user').submit(function (e) {
		e.preventDefault();
		if ($('#password').val() != $('#cpassword').val()) {
			$('#cpassword-msg').html('<span class="text-danger">Password does not match.</span>');
			return false;
		}
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_user',
			method: 'POST',
			data: $(this).serialize(),
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				}
			}
		})
	})
</script>
