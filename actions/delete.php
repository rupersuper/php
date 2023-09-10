<?php
require_once 'functions.php';

if (is_not_logged_in() && !is_admin(get_authenticated_user())) {
	redirect_to('/page_login.php');
}

$user = get_user_by_id();

if (!is_admin(get_authenticated_user()) && !is_equal($user, get_authenticated_user())) {
	set_flash_message("danger", "Нет доступа");
	redirect_to('/users.php');
}

$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
$sql = "DELETE FROM `client` WHERE `client`.`id` = $id;";
$statement->execute();
set_flash_message("success", "Обновлено");
redirect_to('/users.php');
exit;

