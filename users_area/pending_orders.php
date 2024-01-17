<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders</title>
    <style>
    body{
        width:fit-content;
    }
        .img{
             width:80px;
             height:80px;
             object-fit: contain;
}
</style>
</head>
<body>
    <h3 class="text-success">Pending Orders</h3>
    <table class="table table-bordered mt-5">
    <thead class="bg-info">
        <tr>
            <th>Order Id</th>
            <th>Item</th>
            <th>Image</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Net Amt</th>
            <th>Date</th>
            <th>Payment</th>
            <th>Delivered</th>
        </tr>
    </thead>
    <tbody class="bg-secondary text-light">

    <?php
    $username=$_SESSION['username'];
        $get_order_details="Select * from `orders_pending` where username='$username' and payment_status=1 and delivery_status=0 ORDER BY order_id DESC";
        $result_orders=mysqli_query($con,$get_order_details);
     


        
        while($row_data=mysqli_fetch_assoc($result_orders)){
            
            $invoice_number=$row_data['invoice_number'];
            $qty=$row_data['quantity'];

            // fetching product image
            $product_id=$row_data['product_id'];
            $select_products="Select * from `products` where product_id=$product_id";
            $result_products=mysqli_query($con,$select_products);
            $row_product=mysqli_fetch_assoc($result_products);
            $product_image1=$row_product['product_image1'];
            // details from user orders
            
            $get_total_amount="Select * from `user_orders` where invoice_number='$invoice_number'";
            $result_amt=mysqli_query($con,$get_total_amount);
            $row_amt=mysqli_fetch_assoc($result_amt);
            $amount=$row_amt['total_amount'];
            $order_date=$row_amt['order_date'];
            
        
            
            //product details
            $product_id=$row_data['product_id'];
            $get_product_details="Select * from `products` where product_id=$product_id";
            $result_product=mysqli_query($con,$get_product_details);
            $row_product=mysqli_fetch_assoc($result_product);
            $product_title=$row_product['product_title'];
            $product_price=$row_product['product_price'];


            
            // $invoice_number=$row_data['invoice_number'];
            
            
            
            echo "<tr>
            <td>$invoice_number</td>
            <td>$product_title</td>
            <td><img src='../admin_area/product_images/$product_image1' alt='' class='img'></td>
            <td>$qty</td>
            <td>$product_price</td>
            <td>$amount</td>
            <td>$order_date</td>
            <td>Done</td>
            <td>Pending</td>
            </tr>";
         
           
        }
    ?>
    </tbody>
   </table> 
</body>
</html>