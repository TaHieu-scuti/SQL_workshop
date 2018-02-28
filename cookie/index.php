<?php
//TODO Please delete this file and cookie directory after we finish automatic login function
session_start();
var_dump($_SESSION);
print(session_id());
$_SESSION['account_id'] = '4';
