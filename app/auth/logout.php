<?php
// app/auth/logout.php
session_start();
session_destroy();
header("Location: /app/auth/login.php");
exit();
