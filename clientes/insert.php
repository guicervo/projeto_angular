<?php
include("../config/db_connect.php");

$dados = json_decode(file_get_contents("php://input"));

if ($dados)
{
	$data_cadastro = date("Y-m-d");
	$sql = "INSERT INTO cliente(nome, 
								endereco, 
								cidade, 
								estado, 
								telefone, 
								email, 
								data_cadastro) 
					VALUES (:nome,
							:endereco,
							:cidade,
							:estado,
							:telefone,
							:email,
							:data_cadastro
	)";
	                                          
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':nome', $dados->nome, PDO::PARAM_STR);
	$stmt->bindParam(':endereco', $dados->endereco, PDO::PARAM_STR);
	$stmt->bindParam(':cidade', $dados->cidade, PDO::PARAM_STR);
	$stmt->bindParam(':estado', $dados->estado, PDO::PARAM_STR);
	$stmt->bindParam(':telefone', $dados->telefone, PDO::PARAM_STR);
	$stmt->bindParam(':email', $dados->email, PDO::PARAM_STR);
	$stmt->bindParam(':data_cadastro', $data_cadastro, PDO::PARAM_STR);
	$stmt->execute();
}
else
{
	echo FALSE;
}

