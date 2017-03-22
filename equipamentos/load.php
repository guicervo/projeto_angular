<?php 

include ("../config/db_connect.php");

$stm = $pdo->prepare("
	SELECT e.id, e.id_cliente, e.id_func_responsavel, e.marca, e.tipo, e.problema, e.data, c.nome AS cliente, c.telefone, se.status, f.nome AS funcionario
		FROM equipamento e INNER JOIN cliente c ON (e.id_cliente = c.id)
			INNER JOIN status_equipamento se ON (e.id = se.id_equipamento)
				LEFT JOIN funcionario f ON (e.id_func_responsavel = f.id)
					ORDER BY e.id DESC");
$stm->execute();

echo json_encode($stm->fetchALL());