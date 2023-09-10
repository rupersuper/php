<?php
require_once 'functions.php';

$email = $_POST['email'];
$password = $_POST['password'];
$name = $_POST['name'];
$position = $_POST['position'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$status = $_POST['status'];
$image = $_FILES['image'];

$user = get_user_by_email($email);

if (!empty($user)) {
	set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем!");
	redirect_to('/create_user.php');
}

$id = add_user($email, $password);

edit($status, $name, $position, $phone, $address, $id);
set_status($status, $id);
upload_image($image, $id);
set_flash_message("success", "Пользователь добавлен");
redirect_to('/users.php');

