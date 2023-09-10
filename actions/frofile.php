<?php
$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

$values .= "?,";