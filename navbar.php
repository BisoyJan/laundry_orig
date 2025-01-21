<style>
	/* Modern sidebar styling */
	#sidebar {
		position: fixed;
		width: 250px;
		height: 100%;
		background-color: rgb(36, 55, 72);
		/* Dark blue background */
		color: #fff;
		transition: all 0.3s;
		overflow-y: auto;
	}

	.sidebar-list {
		padding-top: 15px;
	}

	.nav-item {
		display: block;
		padding: 10px 15px;
		color: #fff;
		text-decoration: none;
		transition: background-color 0.3s, color 0.3s;
		background-color: rgba(255, 255, 255, 0.1);
		/* Semi-transparent white */
		margin: 5px 10px;
		border-radius: 5px;
	}

	.nav-item:hover {
		background-color: rgba(255, 255, 255, 0.2);
		/* Slightly more opaque on hover */
		color: rgb(15, 180, 240);
		/* Light yellow for hover text */
	}

	.nav-item.active {
		background-color: rgba(255, 221, 87, 0.2);
		/* Light yellow with transparency */
		color: rgb(240, 240, 240);
		/* Light yellow for active text */
	}

	.icon-field {
		margin-right: 10px;
	}

	/* Hide specific items based on login type */
	.nav-sales,
	.nav-users {
		display: none;
	}
</style>

<nav id="sidebar" style='height: 100%; background-color:rgb(36, 55, 72);'>
	<div class="sidebar-list">
		<a href="index.php?page=home" class="nav-item nav-home active"><span class="icon-field"><i
					class="fa fa-home"></i></span> Home</a>
		<a href="index.php?page=laundry" class="nav-item nav-laundry"><span class="icon-field"><i
					class="fa fa-water"></i></span> Laundry List</a>
		<a href="index.php?page=categories" class="nav-item nav-categories"><span class="icon-field"><i
					class="fa fa-list"></i></span> Laundry Category</a>
		<a href="index.php?page=supply" class="nav-item nav-supply"><span class="icon-field"><i
					class="fa fa-boxes"></i></span> Supply List</a>
		<a href="index.php?page=inventory" class="nav-item nav-inventory"><span class="icon-field"><i
					class="fa fa-list-alt"></i></span> Inventory</a>
		<a href="index.php?page=reports" class="nav-item nav-reports"><span class="icon-field"><i
					class="fa fa-th-list"></i></span> Reports</a>
		<?php if ($_SESSION['login_type'] == 1): ?>
			<a href="index.php?page=users" class="nav-item nav-users"><span class="icon-field"><i
						class="fa fa-users"></i></span> Users</a>
		<?php endif; ?>
	</div>
</nav>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	// JavaScript to add ' active' class based on current page $(document).ready(function () { var
	page = "<?php echo isset($_GET['page']) ? $_GET['page'] : 'home'; ?>"; $('.nav-item').removeClass('active');
	$('.nav-' + page).addClass('active'); </script>

<?php if ($_SESSION['login_type'] == 2): ?>
	<style>
		.nav-sales,
		.nav-users {
			display: none !important;
		}
	</style>
<?php endif; ?>

