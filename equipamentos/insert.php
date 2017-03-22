<?php
include("../config/db_connect.php");
$dados = json_decode(file_get_contents("php://input"));

if ($dados)
{
	$data_cadastro = date("Y-m-d");
	$sql = "INSERT INTO equipamento (id_cliente, 
								id_func_responsavel, 
								marca, 
								tipo, 
								problema, 
								data) 
					VALUES (:id_cliente,
							:id_func_responsavel,
							:marca,
							:tipo,
							:problema,
							:data
	)";

	if (isset($dados->selectFunc))
		$idFuncResponsavel = $dados->selectFunc;
	else
		$idFuncResponsavel = null;

	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id_cliente', $dados->selectClient, PDO::PARAM_INT);
	$stmt->bindParam(':id_func_responsavel', $idFuncResponsavel, PDO::PARAM_INT);
	$stmt->bindParam(':marca', $dados->marca, PDO::PARAM_STR);
	$stmt->bindParam(':tipo', $dados->tipo, PDO::PARAM_STR);
	$stmt->bindParam(':problema', $dados->problema, PDO::PARAM_STR);
	$stmt->bindParam(':data', $data_cadastro, PDO::PARAM_STR);
	$stmt->execute();	

	$lastIdInserted = $pdo->lastInsertId();

	$sql = "INSERT INTO status_equipamento (id_equipamento) VALUES (:id_equipamento)";
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id_equipamento', $lastIdInserted, PDO::PARAM_INT);

	$stmt->execute();
}
else
{
	echo FALSE;
}