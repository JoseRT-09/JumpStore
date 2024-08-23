<?php
session_start();

$esAdmin = false;
if (isset($_SESSION['esAdmin'])) {
    $esAdmin = $_SESSION['esAdmin'];
}

require_once 'carrito.php';