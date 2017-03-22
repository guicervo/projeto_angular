<?php
include('../config/db_connect.php');

$id = intval($_GET['idUser']);
if ($id)
{
	$stm = $pdo->prepare("SELECT * FROM cliente WHERE id = :id");
	$stm->bindParam(':id', $id, PDO::PARAM_INT);
	$stm->execute();	
	echo json_encode($stm->fetchAll());
}
else
{
	echo false;
}