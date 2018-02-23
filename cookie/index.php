<?php
session_start();
var_dump($_SESSION);
print(session_id());
$_SESSION['username'] = 'admin';
