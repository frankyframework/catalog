<?php
include(PROJECT_DIR."/modulos/catalog/controller/admin/catalog_products/lista.php");

$titulo_columnas_grid = array("_id" => _catalog("ID"),"images" => _catalog("Thumb"), "name" =>  _catalog("Nombre"),"sku" => _catalog("SKU"),"type" => _catalog("Tipo"),"status" => _catalog("Status"));
$value_columnas_grid = array("_id" ,"images", "name","sku","type","status");

$css_columnas_grid = array("_id" => "w-xxxx-1" ,"images" => "w-xxxx-1" , "name" => "w-xxxx-3", "sku" => "w-xxxx-1", "type" => "w-xxxx-1", "status" => "w-xxxx-1");


