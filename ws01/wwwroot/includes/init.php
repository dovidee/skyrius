<?php 
session_start(); 
$user = $_SESSION['user'] ?? "";
$groups = $_SESSION['groups'] ?? [];
?>