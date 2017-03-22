<?php
include("../config/db_connect.php");
$id = intval($_GET['idUser']);
if ($id)
{
	$sql = "DELETE FROM cliente WHERE id =  :id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
	$stmt->execute();	
}
else
{
	echo FALSE;
}