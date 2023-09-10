<?php

require_once 'functions.php';

$status = $_POST['status'];
$id = $_GET['id'];

set_status($status, $id);
set_flash_message("success", "Статус обновлен");
redirect_to('/users.php');
