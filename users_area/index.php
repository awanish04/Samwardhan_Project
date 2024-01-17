
<!DOCTYPE html>
<html>

<head>
	<title>UPI Gateway - Payment Test Demo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
     <!-- font awesome link  -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
	<div class="container p-5">
		<div class="row">
			<div class="col-md-7 mb-2">
				<?php
				if (isset($_POST['payment'])) {
					
					
					
						$username=$_POST['customerName'];
					  
						$total_price= total_cart_price();
						$invoice_number=(string) rand(100000, 999999); 
						$status= 0; 
                        
					
					
					$query="Select * from `cart_details` where username='$username'";
					$result=mysqli_query($con,$query);
					$count_products=mysqli_num_rows($result);
					while($row=mysqli_fetch_array($result)){
						$product_id=$row['product_id'];
						$product_quantity=$row['quantity'];
						//orders pending table
					$insert_pending_orders="Insert into `orders_pending`(username,invoice_number,product_id,quantity,payment_status,delivery_status,invoice_pid)
					values ('$username','$invoice_number',$product_id ,$product_quantity,0,0,'$invoice_number.$product_id')";
					$result_pending_query=mysqli_query($con,$insert_pending_orders);
					
					}
					
					
					
					//  user orders table
					
				
                    
					$insert_orders="Insert into `user_orders`(username,total_amount,invoice_number,total_products,order_date,payment_status,delivery_status)
					values ('$username',$total_price,'$invoice_number',$count_products,CURDATE(),0,0)";
					$result_query=mysqli_query($con,$insert_orders);
					if($result_query){
					   // echo "<script>alert('Orders are placed successfully')</script>";
						//echo "<script>window.open('profile.php','_self')</script>";
					}
					




					$key = "eb3b08e0-d212-4ca1-80d6-c9e9bcd06469";	// Your Api Token https://merchant.upigateway.com/user/api_credentials
					$post_data = new stdClass();
					$post_data->key = $key;
					$post_data->client_txn_id =$invoice_number ; // you can use this field to store order id;
					$post_data->amount = $_POST['txnAmount'];
					$post_data->p_info = "product_name";
					$post_data->customer_name = $_POST['customerName'];
					$post_data->customer_email = $_POST['customerEmail'];
					$post_data->customer_mobile = $_POST['customerMobile'];
					$post_data->redirect_url = "http://samvardhan.infinityfreeapp.com/users_area/redirect_page.php"; // automatically ?client_txn_id=xxxxxx&txn_id=xxxxx will be added on redirect_url
					// $post_data->udf1 = "extradata";
					// $post_data->udf2 = "extradata";
					// $post_data->udf3 = "extradata";

					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_URL => 'https://merchant.upigateway.com/api/create_order',
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
						echo '<script>location.href="' . $result['data']['payment_url'] . '"</script>';
						exit();
					}

					echo '<div class="alert alert-danger">' . $result['msg'] . '</div>';
				}
				?>
				<h2>Pay Online</h2>
				<span>Details</span>
				<hr>
				<?php
                    $username=$_SESSION['username'];
                    $total_price=total_cart_price();
            //          $con=mysqli_connect('sql203.infinityfree.com','if0_34379824','VwUE31yXfGEWTm','if0_34379824_samvardhan');
			// if(!$con){
			// 	die(mysqli_error($con));
			// }
           
            
			// mysqli_close($con);
                    $query="Select * from user_table where username='$username'";
                    $result=mysqli_query($con,$query);
                    $data=mysqli_fetch_array($result);
                    $phone=$data['user_mobile'];
                    $email=$data['user_email'];
                    echo "<form action='' method='post'>
					<h6>Txn Amount:</h6>
					<input type='text' name='txnAmount' value='$total_price' class='form-control' placeholder='Enter Txn Amount' readonly><br>
					<h6>Customer Name:</h6>
					<input type='text' name='customerName' value='$username' placeholder='Enter Customer Name' class='form-control' required readonly><br>
					<h6>Customer Mobile:</h6>
					<input type='text' name='customerMobile' value='$phone' placeholder='Enter Customer Mobile' maxlength='10' class='form-control' required readonly><br>
					<h6>Customer Email:</h6>
					<input type='email' name='customerEmail' value='$email' placeholder='Enter Customer Email' class='form-control' required readonly>
					<br>
					<input type='submit' name='payment' value='PAY NOW' class='btn btn-primary'>
				</form>";
                ?>
			</div>
		</div>

	</div>
</body>

</html>