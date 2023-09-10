<?php
require_once 'functions.php';

$id = $_GET['id'];
$email = $_POST['email'];
$password = $_POST['password'];

edit_credentials($email, $password, $id);
