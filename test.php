<?php

require_once 'database/connection.php';

$result = $db->query("SELECT DATABASE() as db");

$row = $result->fetch_assoc();

echo "Connected to: " . $row['db'];