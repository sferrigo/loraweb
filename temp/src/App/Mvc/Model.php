<?php
   	namespace App\Mvc;
   	class Model
   	{
      	public function consulta(){
			$con = mysqli_connect('localhost','phpmyadmin','lora', 'lora');
			if (!$con) {
				echo "Error: Unable to connect to MySQL." . PHP_EOL;
				echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
				echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
				exit;
			}
			$consulta = "SELECT `aplicacao`.`app_id`, `aplicacao`.`dev_id`, `aplicacao`.`counter`, `aplicacao`.`port`, 
					`aplicacao`.`payload_raw`, `aplicacao`.`datastamp` as hora_gravacao, `metadata`.`time` as hora_recepcao, 
					`metadata`.`frequency`, `metadata`.`data_rate`, `metadata`.`aplicacao_id`, `gateway_record`.`gtw_id`, 
					`gateway`.`nome`, `gateway_record`.`channel`, `gateway_record`.`rssi`, `gateway_record`.`snr`, 
					`gateway_record`.`metadata_id`
				FROM `aplicacao`
				LEFT JOIN `metadata` ON `aplicacao`.`id` = `metadata`.`aplicacao_id` 
				LEFT JOIN `gateway_record` ON `metadata`.`id` = `gateway_record`.`metadata_id` 
				LEFT JOIN gateway ON gateway.gtw_id = gateway_record.gtw_id
				WHERE (( `datastamp` >= '2020-02-28'))
				ORDER BY aplicacao.id DESC LIMIT 300";

			if(mysqli_query($con, $consulta)){
				error_log("Aplicacao Records returned successfully.");
			} else{
				echo "ERROR: Could not able to execute $consulta. " . mysqli_error($con);
			}

			$resultado = mysqli_query($con, $consulta);
			return $resultado;
		}
   }
?>
