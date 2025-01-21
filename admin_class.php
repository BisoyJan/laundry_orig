<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
class Action
{
	private $db;

	public function __construct()
	{
		ob_start();
		include 'db_connect.php';

		$this->db = $conn;
	}
	function __destruct()
	{
		$this->db->close();
		ob_end_flush();
	}

	function login()
	{
		extract($_POST);

		// Hash the input password
		$hashed_password = md5($password);

		$qry = $this->db->query("SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			return 1;
		} else {
			return 3;
		}
	}
	function login2()
	{
		extract($_POST);
		$qry = $this->db->query("SELECT * FROM user_info where email = '" . $email . "' and password = '" . md5($password) . "' ");
		if ($qry->num_rows > 0) {
			foreach ($qry->fetch_array() as $key => $value) {
				if ($key != 'password' && !is_numeric($key))
					$_SESSION['login_' . $key] = $value;
			}
			$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
			$this->db->query("UPDATE cart set user_id = '" . $_SESSION['login_user_id'] . "' where client_ip ='$ip' ");
			return 1;
		} else {
			return 3;
		}
	}
	function logout()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2()
	{
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user()
	{
		extract($_POST);

		// Ensure all required fields are present
		if (empty($name) || empty($username) || empty($type)) {
			return "Error: Missing required fields.";
		}

		// Prepare the data for the query
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		$data .= ", type = '$type' ";

		// Check if the old password is provided and correct (for password change)
		if (!empty($old_password)) {
			$user = $this->db->query("SELECT * FROM users WHERE id = '$id'");
			if ($user->num_rows > 0) {
				$user_data = $user->fetch_assoc();

				// Hash the input old password before comparing
				$hashed_old_password = md5($old_password);

				// Debugging: Print the stored password and hashed input password
				echo "Stored Password: " . $user_data['password'] . "<br>";
				echo "Hashed Input Password: " . $hashed_old_password . "<br>";

				if ($user_data['password'] != $hashed_old_password) {
					return 2; // Old password is incorrect
				}
			} else {
				return 3; // User not found
			}
		}

		// Update the password if a new one is provided
		if (!empty($password)) {
			$data .= ", password = '" . md5($password) . "' "; // Hash the new password
		}

		// Insert or update the user
		if (empty($id)) {
			$query = "INSERT INTO users SET " . $data;
		} else {
			$query = "UPDATE users SET " . $data . " WHERE id = " . $id;
		}

		// Execute the query
		$save = $this->db->query($query);

		if ($save) {
			return 1; // Success
		} else {
			return "Error: " . $this->db->error; // SQL error
		}
	}

	function console_log($message, $data = [])
	{
		echo "<script>console.log('" . $message . "', " . json_encode($data) . ")</script>";
	}
	function signup()
	{
		extract($_POST);
		$data = " first_name = '$first_name' ";
		$data .= ", last_name = '$last_name' ";
		$data .= ", mobile = '$mobile' ";
		$data .= ", address = '$address' ";
		$data .= ", email = '$email' ";
		$data .= ", password = '" . md5($password) . "' ";
		$chk = $this->db->query("SELECT * FROM user_info where email = '$email' ")->num_rows;
		if ($chk > 0) {
			return 2;
		}
		$save = $this->db->query("INSERT INTO user_info set " . $data);
		if ($save) {
			$login = $this->login2();
			return 1;
		}
	}

	function save_settings()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '" . htmlentities(str_replace("'", "&#x2019;", $about)) . "' ";
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = strtotime(date('y-m-d H:i')) . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], '../assets/img/' . $fname);
			$data .= ", cover_img = '$fname' ";

		}

		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if ($chk->num_rows > 0) {
			$save = $this->db->query("UPDATE system_settings set " . $data . " where id =" . $chk->fetch_array()['id']);
		} else {
			$save = $this->db->query("INSERT INTO system_settings set " . $data);
		}
		if ($save) {
			$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
			foreach ($query as $key => $value) {
				if (!is_numeric($key))
					$_SESSION['setting_' . $key] = $value;
			}

			return 1;
		}
	}


	function save_category()
	{
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", price = '$price' ";
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO laundry_categories set " . $data);
		} else {
			$save = $this->db->query("UPDATE laundry_categories set " . $data . " where id=" . $id);
		}
		if ($save)
			return 1;
	}
	function delete_category()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM laundry_categories where id = " . $id);
		if ($delete)
			return 1;

	}
	function save_supply()
	{
		extract($_POST);
		$data = " brand = '$brand_name' ";
		$data .= ", category = '$category' ";
		$data .= ", classification = '$classification' ";
		$data .= ", size = '$size_value $size_unit' ";
		$data .= ", price = '$price' ";

		if (isset($id) && !empty($id)) {
			$save = $this->db->query("UPDATE supply_list set " . $data . " where id=" . $id);
		} else {
			$save = $this->db->query("INSERT INTO supply_list set " . $data);
		}
		return $save;
	}


	function delete_supply()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM supply_list where id = " . $id);
		if ($delete)
			return 1;
	}


	function save_laundry()
	{
		extract($_POST);

		// Define default values for variables if not set
		$customer_name = isset($customer_name) ? $customer_name : '';
		$phone = isset($phone) ? $phone : '';
		$remarks = isset($remarks) ? $remarks : '';
		$tamount = isset($tamount) ? $tamount : 0;
		$tendered = isset($tendered) ? $tendered : 0;
		$change = isset($change) ? $change : 0;
		$weight = isset($weight) ? $weight : [];

		$data = " customer_name = '$customer_name' ";
		$data .= ", phone = '$phone' ";
		$data .= ", remarks = '$remarks' ";
		$data .= ", total_amount = '$tamount' ";
		$data .= ", amount_tendered = '$tendered' ";
		$data .= ", amount_change = '$change' ";

		if (isset($pay)) {
			$data .= ", pay_status = '1' ";
		}
		if (isset($status)) {
			$data .= ", status = '$status' ";
		}

		if (empty($id)) {
			// Get today's date
			$today_date = date('Y-m-d');

			// Check if there are records with today's date
			$check_today = $this->db->query("SELECT `queue` FROM laundry_list WHERE DATE(date_created) = '$today_date' AND status != 3 ORDER BY id DESC LIMIT 1");

			// If records exist with today's date, increment the queue, else reset to 1
			if ($check_today->num_rows > 0) {
				$queue = $check_today->fetch_array()['queue'] + 1;
			} else {
				$queue = 1;
			}

			$data .= ", queue = '$queue' ";

			// Insert the new record
			$save = $this->db->query("INSERT INTO laundry_list SET " . $data);
			if ($save) {
				$id = $this->db->insert_id;
				foreach ($weight as $key => $value) {
					$items = " laundry_id = '$id' ";
					$items .= ", laundry_category_id = '$laundry_category_id[$key]' ";
					$items .= ", weight = '$weight[$key]' ";
					$items .= ", unit_price = '$unit_price[$key]' ";
					$items .= ", supply_list_id = '$inventory_id[$key]' ";
					$items .= ", product_price = '$product_price[$key]' ";
					$items .= ", amount = '$amount[$key]' ";
					$save2 = $this->db->query("INSERT INTO laundry_items SET " . $items);
				}
				if (!$save) {
					echo "SQL Error: " . $conn->error;
					return $save2;
				}
				return 1;
			}
		} else {
			// Update existing record
			$save = $this->db->query("UPDATE laundry_list SET " . $data . " WHERE id=" . $id);
			if ($save) {
				$this->db->query("DELETE FROM laundry_items WHERE laundry_id = " . $id . " AND id NOT IN (" . implode(',', array_filter($item_id)) . ")");

				foreach ($weight as $key => $value) {
					$items = " laundry_id = '$id' ";
					$items .= ", laundry_category_id = '$laundry_category_id[$key]' ";
					$items .= ", weight = '$weight[$key]' ";
					$items .= ", unit_price = '$unit_price[$key]' ";
					$items .= ", supply_list_id = '$inventory_id[$key]' ";
					$items .= ", product_price = '$product_price[$key]' ";
					$items .= ", amount = '$amount[$key]' ";

					if (empty($item_id[$key])) {
						$save2 = $this->db->query("INSERT INTO laundry_items SET " . $items);
					} else {
						$save2 = $this->db->query("UPDATE laundry_items SET " . $items . " WHERE id=" . $item_id[$key]);

						if (!$save || !$save2) {
							echo "SQL Error: " . $this->db->error;
							return false;
						}
					}
				}
				return 2;
			}
		}
	}


	// function save_laundry()
	// {
	// 	extract($_POST);
	// 	$data = " customer_name = '$customer_name' ";
	// 	$data .= ", remarks = '$remarks' ";
	// 	$data .= ", total_amount = '$tamount' ";
	// 	$data .= ", amount_tendered = '$tendered' ";
	// 	$data .= ", amount_change = '$change' ";
	// 	if (isset($pay)) {
	// 		$data .= ", pay_status = '1' ";
	// 	}
	// 	if (isset($status))
	// 		$data .= ", status = '$status' ";
	// 	if (empty($id)) {
	// 		$queue = $this->db->query("SELECT `queue` FROM laundry_list where status != 3 order by id desc limit 1");
	// 		$queue = $queue->num_rows > 0 ? $queue->fetch_array()['queue'] + 1 : 1;
	// 		$data .= ", queue = '$queue' ";
	// 		$save = $this->db->query("INSERT INTO laundry_list set " . $data);
	// 		if ($save) {
	// 			$id = $this->db->insert_id;
	// 			foreach ($weight as $key => $value) {
	// 				$items = " laundry_id = '$id' ";
	// 				$items .= ", laundry_category_id = '$laundry_category_id[$key]' ";
	// 				$items .= ", weight = '$weight[$key]' ";
	// 				$items .= ", unit_price = '$unit_price[$key]' ";
	// 				$items .= ", amount = '$amount[$key]' ";
	// 				$save2 = $this->db->query("INSERT INTO laundry_items set " . $items);
	// 			}
	// 			return 1;
	// 		}
	// 	} else {
	// 		$save = $this->db->query("UPDATE laundry_list set " . $data . " where id=" . $id);
	// 		if ($save) {
	// 			$this->db->query("DELETE FROM laundry_items where id not in (" . implode(',', $item_id) . ") ");
	// 			foreach ($weight as $key => $value) {
	// 				$items = " laundry_id = '$id' ";
	// 				$items .= ", laundry_category_id = '$laundry_category_id[$key]' ";
	// 				$items .= ", weight = '$weight[$key]' ";
	// 				$items .= ", unit_price = '$unit_price[$key]' ";
	// 				$items .= ", amount = '$amount[$key]' ";
	// 				if (empty($item_id[$key]))
	// 					$save2 = $this->db->query("INSERT INTO laundry_items set " . $items);
	// 				else
	// 					$save2 = $this->db->query("UPDATE laundry_items set " . $items . " where id=" . $item_id[$key]);
	// 			}
	// 			return 1;
	// 		}

	// 	}
	// }

	function delete_laundry()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM laundry_list where id = " . $id);
		$delete2 = $this->db->query("DELETE FROM laundry_items where laundry_id = " . $id);
		if ($delete && $delete2)
			return 1;
	}

	function save_inv()
	{
		extract($_POST);

		// Validate required fields
		if (empty($supply_id) || empty($stock_type) || (!isset($qty) && !isset($used))) {
			return "Error: Missing required fields.";
		}

		// Escape variables for safety
		$supply_id = $this->db->real_escape_string($supply_id);
		$stock_type = $this->db->real_escape_string($stock_type);
		$qty = isset($qty) ? $this->db->real_escape_string($qty) : 0;
		$used = isset($used) ? $this->db->real_escape_string($used) : 0;

		// Escape `id` if provided
		$id = $this->db->real_escape_string($id);

		// Check if the inventory record exists
		$checkResult = $this->db->query("SELECT * FROM inventory WHERE id = '$id' or supply_id = '$supply_id'");
		if (!$checkResult) {
			return "Error: " . $this->db->error;
		}

		$checkRow = $checkResult->fetch_assoc();

		if ($checkRow) {
			// Update existing record
			if ($stock_type == 2) { // Stock out
				$newUsed = $checkRow['used'] + $used;
				$newQty = $checkRow['qty'] - $used;
			} else { // Stock in
				$newQty = $checkRow['qty'] + $qty;
				$newUsed = $checkRow['used'];
			}

			$query = "UPDATE inventory SET 
                        supply_id = '$supply_id',
                        qty = '$newQty',
                        used = '$newUsed',
                        date_updated = NOW() 
                      WHERE id = '$id'";
		} else {
			// Insert new data if `id` does not exist
			$query = "INSERT INTO inventory (supply_id, qty, used, date_updated) 
                      VALUES ('$supply_id', '$qty', '$used', NOW())";
		}

		// Execute query and return result
		if ($this->db->query($query)) {
			return 1;
		} else {
			return "Error: " . $this->db->error;
		}
	}


	function delete_inv()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM inventory where id = " . $id);
		if ($delete)
			return 1;
	}

	function delete_user()
	{
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = " . $id);
		if ($delete)
			return 1;
	}


}
