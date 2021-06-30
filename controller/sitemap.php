<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;

$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();


$CatalogproductsModel->setPage(1);
$CatalogproductsModel->setTampag(100000);
$CatalogproductsModel->setOrdensql("catalog_products.name ASC");


$CatalogproductsEntity->status(1);
$catalogo = array();
if($CatalogproductsModel->getDataSearch($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
  
    if($CatalogproductsModel->getTotal() > 0)
    {
    	while($registro = $CatalogproductsModel->getRows())
    	{

            $catalogo[] = ["loc" => CATALOG_VIEW_SUBCAT, "vars" =>['friendly' => $registro['url_key']],"priority" => "0.8","changefreq" => "daily"];  

        }
  }
}
return $catalogo;