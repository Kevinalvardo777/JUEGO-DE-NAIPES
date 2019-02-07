<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function obtenerProbabilidad($probability=0.1, $length=10000){
   $test = mt_rand(1, $length);
   return $test<=$probability*$length;
}

function obtenerProbabilidadLista(array $set, $length=10000){
   $left = 0;
   foreach($set as $num=>$right){
      $set[$num] = $left + $right*$length;
      $left = $set[$num];
   }
   $test = mt_rand(1, $length);
   $left = 1;
   foreach($set as $num=>$right){
      if($test>=$left && $test<=$right)
      {
         return $num;
      }
      $left = $right;
   }
   return null;//debug, no event realized
}

echo (obtenerProbabilidad(1/10))? "Ganador":"Perdedor";
echo "<br/>";
$set = [
  1 => 0.4,
  2 => 0.1,
  3 => 0.25,
  4 => 0.05,
  5 => 0.1,
  6 => 0.1
];
for($i=0; $i<10; $i++){
   echo (obtenerProbabilidadLista($set))."<br/>";
}