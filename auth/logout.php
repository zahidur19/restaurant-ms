<?php
session_start();
session_destroy();
header("Location: /restaurant-ms/index.php");
exit;
