<?php
include("../config/db_connect.php");

$id = intval($_GET['id_equipamento']);

if ($id)
{
	$stm = $pdo->prepare("SELECT id, id_equipamento, nome_foto FROM fotos WHERE id_equipamento = :id");
	$stm->bindParam(':id', $id, PDO::PARAM_INT);
	$stm->execute();	
	echo json_encode($stm->fetchAll());	
}