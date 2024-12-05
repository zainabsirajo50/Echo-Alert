<?php
session_start();
include "path.php";
require ROOT_PATH . "/app/database/connection.php";
session_destroy(); // Destroy the session
header('Location: ' . BASE_URL . '/home-page.php');  // Redirect to the login page
exit();
?>