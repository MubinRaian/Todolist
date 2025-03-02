<?php
// logout.php - End Session
session_start();
session_destroy();
header("Location: index.html");
exit();
?>