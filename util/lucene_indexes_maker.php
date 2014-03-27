<?php
$env = 'development';
include('app.php');

//Carga de indexes
$zl = new ZendLucene();
$zl->load_Indexes("beneficios");
printf("ZendLucene Indexes [OK]\n");

