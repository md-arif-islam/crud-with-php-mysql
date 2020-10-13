<?php
session_start();
$_SESSION['id'] = 0;
$_SESSION['username'] = false;
session_destroy();
header( "location:index.php" );
