<?php
session_start();
session_unset();
session_destroy();
header('Location: ../user/auth.php');
exit;
