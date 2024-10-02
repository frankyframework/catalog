<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Catalog\model\CatalogsubcategoryModel;
use Catalog\entity\CatalogsubcategoryEntity;
use Franky\Haxor\Tokenizer;
use Franky\Core\paginacion;
use Catalog\schema\productSchema;
use Catalog\schema\offerSchema;
use Catalog\schema\itemListSchema;



$itemListSchema =  new itemListSchema();

$q = $MyRequest->getRequest('q');

$precio	= $MyRequest->getRequest('precio');

$image_category = '';
if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_DEPARTAMENTO)
{
    $departamento      = $MyRequest->getUrlParam('departamento',$MyRequest->getRequest('departamento'));
    $categoria = $MyRequest->getRequest('categoria');
    $image_category = getImageCategorys($departamento);   
}
elseif($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_CATEGORY)
{
    $departamento      = $MyRequest->getUrlParam('departamento',$MyRequest->getRequest('departamento'));
    $categoria      = $MyRequest->getUrlParam('categoria',$MyRequest->getRequest('categoria'));
    $subcategoria = $MyRequest->getRequest('subcategoria');
    $image_category = getImageCategorys($categoria);   
}
elseif($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_SUBCATEGORY)
{
    $departamento      = $MyRequest->getUrlParam('departamento',$MyRequest->getRequest('departamento'));
    $categoria      = $MyRequest->getUrlParam('categoria',$MyRequest->getRequest('categoria'));
    $subcategoria      = $MyRequest->getUrlParam('subcategoria',$MyRequest->getRequest('subcategoria'));
    $image_category = getImageCategorys($subcategoria);   
}
else{
    $departamento = $MyRequest->getRequest('departamento');
    $categoria      = $MyRequest->getRequest('categoria');
    $subcategoria = $MyRequest->getRequest('subcategoria');
}




$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$CatalogcategoryModel = new CatalogcategoryModel();
$CatalogcategoryEntity = new CatalogcategoryEntity();
$MyPaginacion = new paginacion();
$Tokenizer = new Tokenizer();


$CatalogcategoryEntity->store(DATA_STORE_CONFIG['id']);
if(!empty($departamento))
{
  if(is_array($departamento))
  {
    $CatalogproductsModel->setCategoriaArray($departamento);

  }else {
    $CatalogproductsModel->setCategoriaArray([$departamento]);
 
    $CatalogcategoryEntity->url_key($departamento);
    $result	 = $CatalogcategoryModel->getData($CatalogcategoryEntity->getArrayCopy());
    $data           = $CatalogcategoryModel->getRows();
    $MyMetatag->setTitulo($data['meta_title']);
    $MyMetatag->setDescripcion($data['meta_description']);
    $MyMetatag->setKeywords($data['meta_keywords']);
  }

}


if(!empty($categoria))
{
  if(is_array($categoria))
  {
    $CatalogproductsModel->setCategoriaArray($categoria);

  }else {
    $CatalogproductsModel->setCategoriaArray([$categoria]);
 
    $CatalogcategoryEntity->url_key($categoria);
    $result	 = $CatalogcategoryModel->getData($CatalogcategoryEntity->getArrayCopy());
    $data           = $CatalogcategoryModel->getRows();
    $MyMetatag->setTitulo($data['meta_title']);
    $MyMetatag->setDescripcion($data['meta_description']);
    $MyMetatag->setKeywords($data['meta_keywords']);
  }

}


if(!empty($subcategoria))
{
  if(is_array($subcategoria))
  {
    $CatalogproductsModel->setCategoriaArray($subcategoria);

  }else {
    $CatalogproductsModel->setCategoriaArray([$subcategoria]);

    $CatalogcategoryEntity->url_key($subcategoria);
    $result	 = $CatalogcategoryModel->getData($CatalogcategoryEntity->getArrayCopy());
    $data           = $CatalogcategoryModel->getRows();
    $MyMetatag->setTitulo($data['meta_title']);
    $MyMetatag->setDescripcion($data['meta_description']);
    $MyMetatag->setKeywords($data['meta_keywords']);

  }

}



if(!empty($precio))
{
    $precio = explode("-", $precio);
    if(is_array($precio))
    {

        $precio[0] = intval(preg_replace('/[^0-9]+/', '',  $precio[0]), 10);
        $precio[1] = intval(preg_replace('/[^0-9]+/', '', $precio[1]), 10);

      $CatalogproductsModel->setPrecioArray($precio);
    }
}


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
$CatalogproductsEntity->visible_in_search(1);
$CatalogproductsEntity->store(DATA_STORE_CONFIG['id']);
$CatalogproductsModel->setBusca($q);
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

        if(in_array($MyFrankyMonster->MySeccion(),[CATALOG_SEARCH_DEPARTAMENTO])):

          $registro['link'] = $MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento' => $departamento,'categoria'   => $registro['url_key']]);
        
        elseif(in_array($MyFrankyMonster->MySeccion(),[CATALOG_SEARCH_CATEGORY])):

          $registro['link'] = $MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['departamento' => $departamento,'categoria'  =>$categoria,'subcategoria'  => $registro['url_key']]);
        elseif(in_array($MyFrankyMonster->MySeccion(),[CATALOG_SEARCH_SUBCATEGORY])):

            $registro['link'] = $MyRequest->url(CATALOG_VIEW_SUBCAT,['departamento' => $departamento,'categoria'  =>$categoria,'subcategoria'  =>$subcategoria, 'friendly' => $registro['url_key']]);

        else:
          $registro['link'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $registro['url_key']]);

        endif;
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
$MyFrankyMonster->setPHPFile(getVista("products/lista.phtml"));
if($MyRequest->isAjax())
{
  echo render(PROJECT_DIR.'/modulos/catalog/diseno/products/lista.phtml');
  die;
}

?>
