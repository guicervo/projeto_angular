<?php
include("../config/db_connect.php");
$id = json_decode(file_get_contents("php://input"));
if ($id)
{
	$sql = "DELETE FROM fotos WHERE id =  :id";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);   
	$stmt->execute();	
}
else
{
	echo FALSE;
}