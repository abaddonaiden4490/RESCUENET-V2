<?php
session_start();
include('../includes/config.php');
$id = $_GET['id'];
$conn->query("DELETE FROM shifts WHERE shift_id = $id");
header("Location: index.php");
?>