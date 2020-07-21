<?php
   namespace App\Mvc;
   class View
   {
      public function render($resultado) {
         echo "<html>";
         echo "<head>";
         echo "<title> Clima LoRa </title>";
         echo "</head>";
         echo "<body>";
         echo "<h2> Temperatura e Umidade </h2>";
         echo "<table width = 950px border=1>";
         echo "<tr>";
         echo "<td> Dispositivo </td>";
         echo "<td> Cont. </td>";
         echo "<td> Horário </td>";
         echo "<td> Dados </td>";
         echo "<td> SF </td>";
         echo "<td> RSSI </td>";
         echo "<td> SNR </td>";
         echo "<td> Freq </td>";
         echo "<td> GW </td>";
         if ($resultado){
            while($row = mysqli_fetch_array($resultado)){
               echo "<tr>";
               echo "<td>";
               echo $row['dev_id'];
               echo "</td>";
               echo "<td>";
               echo $row['counter'];
               echo "</td>";
               echo "<td>";
               echo $row['hora_gravacao'];
               echo "</td>";
               echo "<td>";
               echo $row['payload_raw'];
               echo "</td>";
               echo "<td>";
               echo $row['data_rate'];
               echo "</td>";
               echo "<td>";
               echo $row['rssi'];
               echo "</td>";
               echo "<td>";
               echo $row['snr'];
               echo "</td>";
               echo "<td>";
               echo $row['frequency'];
               echo "</td>";
               echo "<td>";
               echo $row['nome'];
               echo "</td>";
               //$name = $row['payload_raw'];
               //echo "Dados: ".$name."br/>";
               //$view->render("Dados: ".$name);
            }
         }
         echo "</table>";
         echo "</body>";
         echo "</html>";
      }
   }
