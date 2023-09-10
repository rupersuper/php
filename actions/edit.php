<?php
require_once 'functions.php';

$name = $_POST['name'];
$position = $_POST['position'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$id = $_GET['id'];

edit($name, $position, $phone, $address, $id);
set_flash_message("success", "Обновлено");
redirect_to('/users.php');