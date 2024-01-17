<?php    // check login
    function checkLoggedIn() {
      if (!isset($_SESSION['username'])) {
          // Redirect to the login page or show an error message
          header("Location: ./users_area/login.php");
          exit();
      }
  }
  checkLoggedIn();
  ?>