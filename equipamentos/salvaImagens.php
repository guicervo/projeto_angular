<?php

include("../config/db_connect.php");

$dir = "../uploads/";
$id = intval($_POST['id_equipamento']);
$arquivos = $_FILES;

if ($arquivos)
{
	if (checkImage($_FILES['file']['type']))
	{
		if (checkSize($_FILES['file']['size']))
		{
			$nome = geraNome(basename($_FILES["file"]["name"]));
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $dir.$nome))
			{
				$sql = "INSERT INTO fotos (id_equipamento, nome_foto) 
								VALUES (:id_equipamento, :nome_foto)";
				                                          
				$stmt = $pdo->prepare($sql);		
				$stmt->bindParam(':id_equipamento', $id, PDO::PARAM_INT);
				$stmt->bindParam(':nome_foto', $nome, PDO::PARAM_STR);		
				$stmt->execute();
			}			
		}
		else
		{
			echo json_encode(array("error" => 1));
		}
	}
	else
	{
		echo json_encode(array("error" => 1));
	}
}

function geraNome($nome)
{
    $extensao = strtolower(end(explode('.', $nome)));
    $data = date("H:i:s");
    $nome = md5($nome . $data . rand(0, 10000)) . '.' . $extensao;
    return $nome;	
}

function checkImage($imageType)
{
	if ($imageType == "image/jpeg")
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

function checkSize($size)
{
	if ($size < 1117113)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}