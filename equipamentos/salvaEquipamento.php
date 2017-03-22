<?php
include('../config/db_connect.php');
set_time_limit(0);
$dados = json_decode(file_get_contents("php://input"));


if (isset($dados->id_cliente))
{

	//verifica se tem indice na tabela relatorio_equipamento relacionado ao equipamento
	$sql = "SELECT * FROM relatorio_equipamento WHERE id_equipamento = :id";
	$stmSelectRE = $pdo->prepare($sql);
	$stmSelectRE->bindParam(':id', $dados->id, PDO::PARAM_INT);   
	$stmSelectRE->execute();
	$rSRE = $stmSelectRE->fetchAll();
	if (!$rSRE)
	{
		//caso não tenha, faz a inserção
		$sql = "INSERT INTO relatorio_equipamento (id_equipamento)
						VALUES (:id_equipamento)";		
		$sqlIRE = $pdo->prepare($sql);
		$sqlIRE->bindParam(':id_equipamento', $dados->id, PDO::PARAM_INT);					
		$sqlIRE->execute();

		$lastIdInserted = $pdo->lastInsertId();

		//insere na tabela retorno_cliente indice da tabela relatorio_equipamento
		$sql = "INSERT INTO retorno_cliente (id_relatorio_equipamento)
						VALUES (:id_relatorio_equipamento)";		
		$sqlIRC = $pdo->prepare($sql);
		$sqlIRC->bindParam(':id_relatorio_equipamento', $lastIdInserted, PDO::PARAM_INT);
		$sqlIRC->execute();		
	}

	// print_r($dados);
	$sql = "UPDATE equipamento, relatorio_equipamento, status_equipamento, retorno_cliente 
				SET equipamento.id_cliente = :id_cliente, 
					equipamento.id_func_responsavel = :id_func_responsavel, 
					equipamento.marca = :marca, 
					equipamento.tipo = :tipo, 
					equipamento.problema = :problema, 
					status_equipamento.status = :status, 
					relatorio_equipamento.descricao = :descricao_problema, 
					retorno_cliente.status = :status_cliente 
				WHERE equipamento.id = :id_equipamento 
				AND relatorio_equipamento.id_equipamento = equipamento.id 
				AND status_equipamento.id_equipamento = equipamento.id
				AND retorno_cliente.id_relatorio_equipamento = relatorio_equipamento.id";

	$stmt = $pdo->prepare($sql);                                  
	$stmt->bindParam(':id_cliente', $dados->id_cliente, PDO::PARAM_INT);       
	$stmt->bindParam(':id_func_responsavel', $dados->id_func_responsavel, PDO::PARAM_INT);    
	$stmt->bindParam(':marca', $dados->marca, PDO::PARAM_STR);
	$stmt->bindParam(':tipo', $dados->tipo, PDO::PARAM_STR); 
	$stmt->bindParam(':problema', $dados->problema, PDO::PARAM_STR);   
	$stmt->bindParam(':status', $dados->status);
	$stmt->bindParam(':descricao_problema', $dados->re_descricao);
	$stmt->bindParam(':status_cliente', $dados->rc_status);   
	$stmt->bindParam(':id_equipamento', $dados->id, PDO::PARAM_INT);   
	$stmt->execute();

	if (isset($dados->salvaEnvia))
	{
		//echo "Salva e envia";
		$stm = $pdo->prepare("SELECT * FROM cliente WHERE id = :id");
		$stm->bindParam(':id', $id, PDO::PARAM_INT);
		$stm->execute();	
		$r = $stm->fetchAll();
		if ($r)
		{
			envia_email($r[0]['nome'], "", $dados->marca, $dados->tipo, $dados->problema, $dados->re_descricao, $cc);
		}
	}
}
else
{
	//busca informações no db e envia
	//verifica se tem relatório do funcionário antes de enviar
	$stm = $pdo->prepare("
						SELECT e.id, e.id_cliente, e.id_func_responsavel, e.marca, e.tipo, e.problema, e.data, c.nome AS cliente, c.telefone, c.email, se.status, f.nome AS funcionario, re.descricao AS re_descricao, rc.status rc_status
									FROM equipamento e INNER JOIN cliente c ON (e.id_cliente = c.id)
										INNER JOIN status_equipamento se ON (e.id = se.id_equipamento)
											INNER JOIN funcionario f ON (e.id_func_responsavel = f.id)
						                    	INNER JOIN relatorio_equipamento re ON (e.id = re.id_equipamento)
						                        	LEFT JOIN retorno_cliente rc ON (re.id = rc.id_relatorio_equipamento)
														WHERE e.id = :id");
	$stm->bindParam(':id', $dados, PDO::PARAM_INT);
	$stm->execute();	
	$r = $stm->fetchAll();

	$texto_imagem = "";
	$cc = array();
	if ($r[0]['re_descricao'])
	{
		$stm_img = $pdo->prepare("SELECT nome_foto FROM fotos WHERE id_equipamento = :id");
		$stm_img->bindParam(':id', $dados, PDO::PARAM_INT);
		$stm_img->execute();	
		$i = $stm_img->fetchAll();
		if ($i)
		{
			//se tem img, \attach no email
			$texto_imagem .= "Também, em anexo, segue as fotos tiradas do problema.";
			foreach ($i as $key => $value) 
			{
				$cc[] = $value['nome_foto'];
			}
		}	
		//envia e-mail
		envia_email($r[0]['cliente'], $r[0]['email'], $texto_imagem, $r[0]['marca'], $r[0]['tipo'], $r[0]['problema'], $r[0]['re_descricao'], $cc);
	}
	else
	{
		echo FALSE;
	}	
}



function envia_email($cliente, $email, $texto_imagem, $marca, $tipo, $problema, $re_descricao, $cc)
{
	$mensagem = "";
	$mensagem .= "<p><h3>Relatório do Equipamento</h3></p>";
	$mensagem .= "<p>Prezado cliente ".$cliente.", estamos lhe enviando abaixo o relatório do equipamento deixado para conserto.</p>";
	$mensagem .= "<p>".$texto_imagem."</p>";
	$mensagem .= "<p><b>Marca: </b>".$marca."</p>";
	$mensagem .= "<p><b>Tipo: </b>".$tipo."</p>";
	$mensagem .= "<p><b>Problema: </b>".$problema."</p>";
	$mensagem .= "<p><b>Relatório do especialista: </b>".$re_descricao."</p>";
	$mensagem .= "<p>Aguardamos seu retorno para continuidade do processo.</p>";

	require '../PHPMailer/PHPMailerAutoload.php';

	$mail = new PHPMailer;

	//$mail->SMTPDebug = 3;                               

	$mail->isSMTP();    
	$mail->CharSet = 'UTF-8';                                  
	$mail->Host = 'ssl://smtp.gmail.com';  
	$mail->SMTPAuth = true;                               
	$mail->Username = 'egrdocs@assessoria24h.com.br';                 
	$mail->Password = '&grdoc$2016';                           
	$mail->SMTPSecure = false;                            
	$mail->Port = 465;                                    

	$mail->setFrom('contato@controleequipamento.com', 'Relatório da Revisão');
	$mail->addAddress($email, $cliente);     
	foreach ($cc as $key => $value) 
	{
		$mail->addAttachment($_SERVER["DOCUMENT_ROOT"] . "/projeto_betha/uploads/" . $value, $value);	
	}

	// die;
	$mail->isHTML(true);                                  

	$mail->Subject = 'Relatório do equipamento';
	$mail->Body    = $mensagem;

	if(!$mail->send()) {
		echo FALSE;
	} else {
	    echo json_encode(array("reposta" => 1));
	}	
}