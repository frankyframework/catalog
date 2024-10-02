<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Catalog\model\CatalogUsersModel;
use Catalog\entity\CatalogUsersEntity;
use Franky\Haxor\Tokenizer;
use Franky\Core\paginacion;
use Catalog\schema\productSchema;
use Catalog\schema\offerSchema;
use Catalog\schema\itemListSchema;

$itemListSchema =  new itemListSchema();
$username      = $MyRequest->getUrlParam('username');

if(getCoreConfig('catalog/marketplace/enabled') == 0 || empty($username))
{
    $MyRequest->redirect($MyRequest->url(CATALOG_SEARCH),"301");
}

$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$CatalogcategoryModel = new CatalogcategoryModel();
$CatalogcategoryEntity = new CatalogcategoryEntity();
$CatalogUsersModel  = new CatalogUsersModel();
$CatalogUsersEntity = new CatalogUsersEntity();
$MyPaginacion = new paginacion();
$Tokenizer = new Tokenizer();


$CatalogUsersEntity->username($username);
if($CatalogUsersModel->getData($CatalogUsersEntity->getArrayCopy()) == REGISTRO_SUCCESS) {
    $dataUser = $CatalogUsersModel->getRows();
    $imgMArketplace = "";
    if(!empty($dataUser["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/marketplace/".$dataUser["image"]))
    {
        $imgMArketplace  =  imageResize($MyConfigure->getUploadDir()."/catalog/marketplace/".$dataUser["image"],1920,822, true);
    }

    $MyMetatag->setTitulo($dataUser['meta_title']);
    $MyMetatag->setDescripcion($dataUser['meta_description']);
    $MyMetatag->setKeywords($dataUser['meta_keywords']);

} else{
    $MyRequest->redirect($MyRequest->url(CATALOG_SEARCH),"301");
}


$CatalogcategoryEntity->store(DATA_STORE_CONFIG['id']);


$por = $MyRequest->getRequest('por',"catalog_products.name");
if(empty($por))
{
  $por= "catalog_products.name";
}

$order = $MyRequest->getRequest('order',"ASC");
if(empty($por))
{
  $order= "ASC";
}
$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($por);
$MyPaginacion->setOrden($MyRequest->getRequest('order',$order));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',12));
$MyPaginacion->setTamanosValidos([12,24,48]);

$CatalogproductsModel->setPage($MyPaginacion->getPage());
$CatalogproductsModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogproductsModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());


$CatalogproductsEntity->status(1);
$CatalogproductsEntity->in_validation(0);
$CatalogproductsEntity->validate(1);

$CatalogproductsEntity->uid($dataUser['id_user']);
$CatalogproductsEntity->visible_in_search(1);
$CatalogproductsEntity->store(DATA_STORE_CONFIG['id']);
$resultados_pagina = array();
if($CatalogproductsModel->getDataSearch($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{
    $MyPaginacion->setTotal($CatalogproductsModel->getTotal());
    $itemListSchema->setNumberOfItems($CatalogproductsModel->getTotal());
    $itemListSchema->setUrl($MyRequest->link($MyRequest->getURI().'?'.$MyRequest->getQuery(),false,true));
    if($CatalogproductsModel->getTotal() > 0)
    {
    	while($registro = $CatalogproductsModel->getRows())
    	{


          $registro['link'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $registro['url_key']]);

       
          $registro['thumb_resize'] =  "";
          $img = "";
          $_img = getCoreConfig('catalog/product/placeholder');
          if($_img != "" && file_exists(PROJECT_DIR.$_img))
          {
            $registro['thumb_resize'] = imageResize($_img,500,500, false);
          }
          $registro["images"] = json_decode($registro["images"],true);

          if(!empty($registro['images']))
          {
              foreach($registro["images"] as $foto)
              {

                  if($foto['principal'] == 1)
                  {

                      if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img']))
                      {

                            $registro['thumb_resize'] = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img'],500,500, false);

                      }
                  }

              }
          }
         
           $registro['id_wishlist'] = $Tokenizer->token('wishlist',$registro["id"]);

            $registro['id'] = $Tokenizer->token('catalog_products',$registro["id"]);

          $resultados_pagina[] = $registro;

          $offerSchema =  new offerSchema();
          $productSchema = new productSchema();

          $offerSchema->setPriceCurrency('MXN');
          $offerSchema->setPrice($registro['price']);
          $productSchema->setName($registro['name']);
          $productSchema->setUrl($MyRequest->link($registro['link'],false,true));
          $productSchema->setImage($MyRequest->link($registro['thumb_resize'],false,true));
          $productSchema->setOffers(json_decode($offerSchema->get(false),true));
          $productSchema->setDescription($registro['meta_description']);
          $productSchema->setSku($registro['sku']);
          $itemListSchema->setItemListElement(json_decode($productSchema->get(false),true));

          
      }
  }
}
if($MyRequest->isAjax())
{
  echo render(PROJECT_DIR.'/modulos/catalog/diseno/marketplace/lista.phtml');
  die;
}

?>
