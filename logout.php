
<?php

require_once 'init.php';

$_SESSION = [];
session_destroy();
redirect('login.php');


