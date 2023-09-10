<?php
require_once 'functions.php';

$email = $_POST['email'];
$password = $_POST['password'];

login($email, $password);

