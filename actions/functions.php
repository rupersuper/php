<?php
session_start();

// REGISTER

function get_user_by_email($email)
{
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "SELECT * FROM `client` WHERE `email` = :email";
	$statement = $pdo->prepare($sql);
	$statement->execute(['email' => $email]);
	$user = $statement->fetch(PDO::FETCH_ASSOC);
	return $user;
}

function get_users()
{
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "SELECT * FROM `client`";
	$statement = $pdo->query($sql);
	return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function is_logged_in()
{
	if (isset($_SESSION['user'])) {
		return true;
	}
	return false;
}

function is_admin($user)
{
	if (is_logged_in()) {
		if ($user['role'] === 'admin') {
			return true;
		}
		return false;
	}
}


function is_not_logged_in()
{
	return !is_logged_in();
}

function get_authenticated_user()
{
	if (is_logged_in()) {
		return $_SESSION['user'];
	}
	return false;
}

function is_equal($user, $current_user)
{
	if ($user['id'] == $current_user['id']) {
		return true;
	}
	return false;
}

function set_flash_message($name, $message)
{
	$_SESSION["$name"] = "$message";
}

function display_flash_message($name)
{
	if (isset($_SESSION[$name])) {
		echo "<div class=\"alert alert-{$name} text-dark\" role=\"alert\">
		<strong>Уведомление! </strong>{$_SESSION[$name]}</div>";
		unset($_SESSION[$name]);
	}
}

function redirect_to($path)
{
	header("Location: $path");
	exit;
}

// LOGIN

function login($email, $password)
{
	$user = get_user_by_email($email);

	if (empty($user)) {
		set_flash_message("danger", "Неверный логин либо пароль");
		redirect_to('/page_login.php');
	}

	if (!password_verify($password, $user['password'])) {
		set_flash_message("danger", "Неверный логин либо пароль");
		redirect_to('/page_login.php');
	}

	$_SESSION['user'] = [
		"id" => $user['id'],
		"email" => $user['email'],
		"role" => $user['role']
	];

	redirect_to('/users.php');
}

// CREATE

function add_user($email, $password)
{
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "INSERT INTO `client` (`id`, `email`, `password`) VALUES (NULL, :email, :password)";
	$statement = $pdo->prepare($sql);
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	$result = $statement->execute(['email' => $email, 'password' => $hashed_password]);
	if ($result) {
		$user_id = $pdo->lastInsertId(); // Получаем идентификатор последней вставленной записи
		return $user_id;
	} else {
		return false;
	}
}

function edit($name, $position, $phone, $address, $id)
{
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "UPDATE `client` SET `name` = '$name', `position` = '$position', `phone` = '$phone', `address` = '$address' WHERE `client`.`id` = $id;";
	$statement = $pdo->prepare($sql);
	$result = $statement->execute();
}

function upload_image($image, $id)
{
	$types = ['image/jpeg', 'image/png', 'image/gif', 'image/tiff', 'image/svg+xml'];

	if (!in_array($image['type'], $types)) {
		set_flash_message('danger', 'Ошибка формата');
		redirect_to('/create_user.php');
		exit;
	}

	$file_size = $image['size'] / 1000000;
	$max_size = 15;

	if ($file_size > $max_size) {
		set_flash_message('danger', 'Слишком большой файл');
		redirect_to('/create_user.php');
		exit;
	}

	$image_name = time() . $image['name'];
	$path = 'assets/' . $image_name;

	move_uploaded_file($image['tmp_name'], '../' . $path);

	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "UPDATE `client` SET `image` = '$path' WHERE `client`.`id` = $id;";
	$statement = $pdo->prepare($sql);
	$statement->execute();
}

function set_status($status, $id)
{
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "UPDATE `client` SET `status` = '$status' WHERE `client`.`id` = $id;";
	$statement = $pdo->prepare($sql);
	$result = $statement->execute();
}

function add_social_links($telega, $insta, $vk, $id)
{
}


// EDIT

function get_user_by_id()
{
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "SELECT * FROM `client` WHERE `id` = :id";
	$statement = $pdo->prepare($sql);
	$statement->execute($_GET);
	$user = $statement->fetch(PDO::FETCH_ASSOC);
	return $user;
}

// SECURITY

function edit_credentials($email, $password, $id)
{
	$user = get_user_by_email($email);
	if (!empty($user && $id != $user['id'])) {
		set_flash_message("danger", "Этот эл. адрес уже занят другим пользователем!");
		redirect_to('/security.php?id=' . $id);
		exit;
	}
	$pdo = new PDO('mysql:host=localhost;dbname=CRUD', 'root', '');
	$sql = "UPDATE `client` SET `email` = :email, `password` = :password WHERE `client`.`id` = $id;";
	$statement = $pdo->prepare($sql);
	$hashed_password = password_hash($password, PASSWORD_DEFAULT);
	$result = $statement->execute(['email' => $email, 'password' => $hashed_password]);
	set_flash_message("success", "Обновлено");
	redirect_to('/users.php');
	exit;
}

// STATUS


function has_image ($id, $image) {

}