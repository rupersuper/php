<?php
require_once 'functions.php';

$image = $_FILES['image'];
$id = $_GET['id'];

upload_image($image, $id);
set_flash_message("success", "Фото обновлено");
redirect_to('/users.php');
