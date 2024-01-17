<?php
include('../functions/common_function.php');

    session_start();
    include_once('../functions/auth.php');
    session_unset();
    session_destroy();
    echo "<script>alert('You are Successfully Logged out...')</script>";
    echo "<script>window.open('../index.php','_self')</script>";
    ?><?php
include('../functions/common_function.php');

    session_start();
    include_once('../functions/auth.php');
    session_unset();
    session_destroy();
    echo "<script>alert('You are Successfully Logged out...')</script>";
    echo "<script>window.open('../index.php','_self')</script>";
    ?>