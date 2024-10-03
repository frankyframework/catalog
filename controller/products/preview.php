<?php
use Catalog\model\CatalogCatalogReviewsModel;
use Catalog\entity\CatalogCatalogReviewsEntity;
use Franky\Haxor\Tokenizer;
$CatalogCatalogReviewsModel = new CatalogCatalogReviewsModel();
$CatalogCatalogReviewsEntity = new CatalogCatalogReviewsEntity();
$Tokenizer = new Tokenizer();

$id = $MyRequest->getUrlParam('id');

$CatalogCatalogReviewsEntity->id($id);
if($CatalogCatalogReviewsModel->getData($CatalogCatalogReviewsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
{

  $viewData = $CatalogCatalogReviewsModel->getRows();
  $data_detalle = json_decode($viewData['data'],true);
  $data_detalle["videos"] = json_decode($data_detalle["videos"],true);
  $data_detalle["images"] = json_decode($data_detalle["images"],true);
  $data_detalle["tags"] = explode(",",$data_detalle["meta_keyword"]);
  $data_detalle["id_categoria"] = json_decode($data_detalle["category"],true);
  
    $data_detalle['thumb_resize'] =  "";
    $img = "";
    $_img = getCoreConfig('catalog/product/placeholder');
    if($_img != "" && file_exists(PROJECT_DIR.$_img))
    {
        $data_detalle['thumb_resize'] = imageResize($_img,500,500, false);
    }
    

    if(!empty($data_detalle['images']))
    {
        foreach($data_detalle["images"] as $foto)
        {

            if($foto['principal'] == 1)
            {

                if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$data_detalle["id"].'/'.$foto['img']))
                {

                        $data_detalle['thumb_resize'] = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$data_detalle["id_"].'/'.$foto['img'],500,500, false);

                }
            }

        }
    }

    $custom_attr = $data_detalle["custom_attr"];
}
else{
    
    $MyRequest->redirect($MyRequest->url(ADMIN_MODERAR_PUBLICACIONES_CATALOG),"301");
}
$data_detalle['link'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $data_detalle['url_key']]);
  
$data_detalle['id_ori'] =$data_detalle['id'];
$data_detalle['id_wishlist'] = $Tokenizer->token('wishlist',$data_detalle["id"]);

$data_detalle['id'] = $Tokenizer->token('catalog_products', $data_detalle['id']);

$MyFrankyMonster->setPHPFile(getVista("products/preview.phtml"));
//print_r($data_detalle);
