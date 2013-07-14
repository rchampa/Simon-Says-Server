<?php

$name = $_POST['name'];
$password = $_POST['password'];
$gcm_id = $_POST['gcm_id'];

require_once 'db_functions.php';
$db = new DB_Functions();

	if($db->register($name, $password, $gcm_id)){
		echo(" The user not registered.");
	}
	else{

		if($db->adduser($id,$password,$nombre,$apellido)){	
			$resultado[]=array("estado"=>"0", "mensaje"=>"El usuario fue agregado a la Base de Datos correctamente.");
		}else{
			$resultado[]=array("estado"=>"1", "mensaje"=>"Ha ocurrido un error.");
		}		

	}

echo json_encode($resultado);
	
?>



