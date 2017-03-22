<?php
include("../config/db_connect.php");

$id = intval($_GET['idEquipamento']);
if ($id)
{
	$stm = $pdo->prepare("
							SELECT e.id, e.id_cliente, e.id_func_responsavel, e.marca, e.tipo, e.problema, e.data, c.nome AS cliente, c.telefone, se.status, f.nome AS funcionario, re.descricao AS re_descricao, rc.status rc_status
										FROM equipamento e INNER JOIN cliente c ON (e.id_cliente = c.id)
											INNER JOIN status_equipamento se ON (e.id = se.id_equipamento)
												LEFT JOIN funcionario f ON (e.id_func_responsavel = f.id)
							                    	LEFT JOIN relatorio_equipamento re ON (e.id = re.id_equipamento)
							                        	LEFT JOIN retorno_cliente rc ON (re.id = rc.id_relatorio_equipamento)
															WHERE e.id = :id");
	$stm->bindParam(':id', $id, PDO::PARAM_INT);
	$stm->execute();	
	echo json_encode($stm->fetchAll());
}
else
{
	echo FALSE;
}