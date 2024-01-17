<?php 
include('../includes/connect.php');
include('../functions/common_function.php'); 
//  include_once('../functions/auth.php'); 

session_start();
// include_once('../functions/auth.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
     
     <style>
        body{
            overflow-x:hidden;
        }
     </style>
</head>
<body>
    
    <div class="container-fluid my-3">
         <h2 class="text-center">Please login to continue</h2>
         <div class="row d-flex align-items-center justify-content-center">
            <div class="col-lg-12 col-xl-6">
                <form action="" method="post">
                    <div class="form-outline mb-4">
                        <label for="user_username" class="form-label">Username</label>
                        <input type="text" id="user_username" class="form-control" placeholer="Enter your username" autocomplete="off" required="required"
                        name="user_username"/>

                    </div>
                   
                    <!-- password  -->
                    <div class="form-outline mb-4">
                        <label for="user_password" class="form-label">Password</label>
                        <input type="password" id="user_password" class="form-control" placeholer="Enter your password" autocomplete="off" required="required"
                        name="user_password"/>
                        
                    </div> 
                    
                   
                    <div class="mt-4 pt-2">
                        <input type="submit" value="Login" class="bg-info py-2 px-3 border-0" name="user_login">
                        <p class="small fw-bold mt-2 pt-1 mb-0">Don't have an account? <a href="user_registration.php">Register</a></p>
                    </div>
                </form>
            </div>
         </div>
    </div>
</body>
</html>
  <?php
    if(isset($_POST['user_login'])){
        
        $user_username=$_POST['user_username'];
        $password=$_POST['user_password'];
        $select_query="Select * from `user_table` where username='$user_username'";
        $result=mysqli_query($con,$select_query);
        $row_count=mysqli_num_rows($result);
        //cart items 
        
        $select_query_cart="Select * from `cart_details` where username='$user_username'";
        $result_cart=mysqli_query($con,$select_query_cart);
        $row_count_cart=mysqli_num_rows($result_cart);
         if($row_count==1){
            
            while( $row=mysqli_fetch_assoc($result)){
             if (password_verify($password, $row['user_password'])) {
                 $_SESSION['username']= $user_username;
               echo "<script>alert('You are Successfully Logged in...')</script>";
               if($user_username=='saw'){
                echo "<script>window.open('../admin_area/index.php','_self')</script>";
               }
               else if($row_count_cart==0){
                echo "<script>window.open('../index.php','_self')</script>";
                }else{
                echo "<script>alert('You have items in your cart')</script>";
                echo "<script>window.open('../cart.php','_self')</script>";
                }
            }else{
                echo "<script>alert('Invalid Password...')</script>";
            }
         }
         } else {
               echo "<script>alert('Invalid Username...')</script>";
            }
         
        
       
            

        }
    

    ?>
