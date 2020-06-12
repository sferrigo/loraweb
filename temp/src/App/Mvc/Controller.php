<?php
   namespace App\Mvc;
   class Controller
   {
      public function index() {
         $model = new Model();
         $view = new View();
         //$model->conexao();
         $result = $model->consulta();
         if ($result){
         //   while($row = mysqli_fetch_array($result)){
         //      $name = $row['payload_raw'];
               //echo "Dados: ".$name."br/>";
               //$view->render("Dados: ".$name);
               $view->render($result);
         //   }
         }
         //$view->render($model->consulta());
         //$view->render("66");
      }
   }
?>
