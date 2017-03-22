<?php
include("../config/db_connect.php");
$id = intval($_GET['idEquipamento']);
if ($id)
{
	$sql = "DELETE FROM equipamento WHERE id =  :id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
	$stmt->execute();	
}
else
{
	echo FALSE;
}