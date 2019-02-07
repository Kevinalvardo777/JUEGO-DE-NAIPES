<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$p = new OAuthProvider();

$llave1 = "Ju3605";
$llave2 = "3xtr";

$t = $p->generateToken($llave1.'robertmacias1986@gmail.com'.$llave2);

echo strlen($t),  PHP_EOL;
echo bin2hex($t), PHP_EOL;