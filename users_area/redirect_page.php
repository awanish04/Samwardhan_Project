
<?php
error_reporting(E_ERROR | E_PARSE);


$echo = "";
if (isset($_GET['client_txn_id'])) {
	$key = "eb3b08e0-d212-4ca1-80d6-c9e9bcd06469";	// Your Api Token https://merchant.upigateway.com/user/api_credentials
	$post_data = new stdClass();
	$post_data->key = $key;
	$post_data->client_txn_id = $_GET['client_txn_id']; // you will get client_txn_id in GET Method
	$post_data->txn_date = date("d-m-Y"); // date of transaction

	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://merchant.upigateway.com/api/check_order_status',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($post_data),
		CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json'
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);

	$result = json_decode($response, true);
	if ($result['status'] == true) {
		// Txn Status = 'created', 'scanning', 'success','failure'

		if ($result['data']['status'] == 'success') {
			$echo = '<div class="alert alert-danger"> Transaction Status : Success</div>';
			$txn_data = $result['data'];
			// All the Process you want to do after successfull payment
			// Please also check the txn is already success in your database.
			//echo "<script>alert('Orders are placed successfully')</script>";
			// $con=mysqli_connect('localhost','root','','samvardhan');
			 $con=mysqli_connect('sql203.infinityfree.com','if0_34379824','VwUE31yXfGEWTm','if0_34379824_samvardhan');
			if(!$con){
				die(mysqli_error($con));
			}
            $client_txn=$_GET['client_txn_id'];
			$update_payment_status="UPDATE `orders_pending` SET payment_status=1 WHERE invoice_number='$client_txn'";
			$result_qty=mysqli_query($con,$update_payment_status);

            // $update_user_orders="UPDATE `user_orders` SET payment_status=1 WHERE invoice_number='$client_txn'";
			// $result=mysqli_query($con,$update_user_orders);
            session_start();
            $username=$_SESSION['username'];
            $delete_cart="DELETE FROM cart_details WHERE username='$username'";
            $result_delete=mysqli_query($con,$delete_cart);
            
			mysqli_close($con);
			// $query="UPDATE user_orders SET order_status='success' WHERE username='saw'";
			// $result=mysqli_query($con,$query);


			
		}
		$txn_data = $result['data'];
		$echo = '<div class="alert alert-danger"> Transaction Status : ' . $result['data']['status'] . '</div>';
		
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Payment Gateway - Response</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
	<div class="container p-5">
		<div class="row">
			<div class="col-md-8 mb-2">
				<h2>Response</h2>
				<p>Payment Gateway - Response</p>
                <a href="http://samvardhan.infinityfreeapp.com/"> <strong><u> << Back to site</u></strong></a>
				<?php echo $echo;
				 // show table of response
				 if (isset($txn_data)) {
					echo '<table class="table table-bordered">
					<thead>
					  <tr>
						<th>Key</th>
						<th>Value</th>
					  </tr>
					</thead>
					<tbody>';
					foreach ($txn_data as $key => $value) {
                        if($key=='customer_vpa' || $key=='udf1' || $key=='udf2' || $key=='udf3' || $key=='upi_txn_id' || $key=='p_info' || $key=='Merchant' ){
                            continue;
                        }
						echo '<tr>
						<td>' . $key . '</td>
						<td>' . @$value . '</td>
					  </tr>';
					
                    }
					echo '</tbody>
				  </table>';
				 }
				?>

			</div>
		</div>

	</div>
</body>

</html>