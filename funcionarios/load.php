<?php
include('../config/db_connect.php');

$stm = $pdo->prepare("SELECT * FROM funcionario");
$stm->execute();

echo json_encode($stm->fetchALL());