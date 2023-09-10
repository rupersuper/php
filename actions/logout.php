<?php
require_once 'functions.php';
unset($_SESSION['user']);
redirect_to('/page_login.php');
