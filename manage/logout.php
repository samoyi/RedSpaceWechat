<?php
   session_start();

   unset($_SESSION["valid"]);

   header('location: login.php');
?>
