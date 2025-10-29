<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\model\CatalogUsersModel;
use Catalog\entity\CatalogUsersEntity;

$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$CatalogUsersModel  = new CatalogUsersModel();
$CatalogUsersEntity = new CatalogUsersEntity();

$CatalogproductsModel->setPage(1);
$CatalogproductsModel->setTampag(100000);
$CatalogproductsModel->setOrdensql("catalog_products.name ASC");


$CatalogproductsEntity->status(1);
$CatalogproductsEntity->in_validation(0);
$CatalogproductsEntity->validate(1);
$catalogo = array();
if($CatalogproductsModel->getDataSearch($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
  
    if($CatalogproductsModel->getTotal() > 0)
    {
    	while($registro = $CatalogproductsModel->getRows())
    	{

            $catalogo[] = ["loc" => CATALOG_SEARCH_DEPARTAMENTO, "vars" =>['departamento' => $registro['url_key']],"priority" => "0.8","changefreq" => "daily"];  

        }
  }
}

$CatalogUsersModel->setPage(1);
$CatalogUsersModel->setTampag(100000);
$CatalogUsersModel->setOrdensql("catalog_users.username ASC");



$CatalogUsersEntity->verificado(1);
if($CatalogUsersModel->getData($CatalogUsersEntity->getArrayCopy()) == REGISTRO_SUCCESS) {
  while ($dataUser = $CatalogUsersModel->getRows()){
    $catalogo[] = ["loc" => MARKETPLACE, "vars" =>['username' => $dataUser['username']],"priority" => "0.8","changefreq" => "daily"];  
  }

}
return $catalogo;