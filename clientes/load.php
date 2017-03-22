<?php
include("../config/db_connect.php");

$stm = $pdo->prepare("SELECT * FROM cliente");
$stm->execute();

echo json_encode($stm->fetchAll());

?>
