<?php
include('../config/db_connect.php');

$dados = json_decode(file_get_contents("php://input"));
if ($dados)
{
	$stm = $pdo->prepare("SELECT * FROM cliente WHERE id = :id");
	$stm->bindParam(':id', $dados->id, PDO::PARAM_INT);
	$stm->execute();	
	if ($stm->fetchAll())
	{
		$sql = "UPDATE cliente SET nome = :nome, 
		            endereco = :endereco, 
		            cidade = :cidade,  
		            estado = :estado,  
		            telefone = :telefone,
		            email = :email  
		            WHERE id = :id";
		$stmt = $pdo->prepare($sql);                                  
		$stmt->bindParam(':nome', $dados->nome, PDO::PARAM_STR);       
		$stmt->bindParam(':endereco', $dados->endereco, PDO::PARAM_STR);    
		$stmt->bindParam(':cidade', $dados->cidade, PDO::PARAM_STR);
		$stmt->bindParam(':estado', $dados->estado, PDO::PARAM_STR); 
		$stmt->bindParam(':telefone', $dados->telefone, PDO::PARAM_STR);   
		$stmt->bindParam(':email', $dados->email, PDO::PARAM_STR);   
		$stmt->bindParam(':id', $dados->id, PDO::PARAM_INT);   
		$stmt->execute(); 		
	}
	else
	{
		echo FALSE;
	}
}
else
{
	echo FALSE;
}
