<?php
use Franky\Core\validaciones;
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\entity\CatalogcategoryproductEntity;
use Catalog\model\CatalogcategoryproductModel;
use Franky\Haxor\Tokenizer;
use Franky\Core\ObserverManager;
use Catalog\model\CatalogCatalogReviewsModel;
use Catalog\entity\CatalogCatalogReviewsEntity;

$Tokenizer = new Tokenizer();
$CatalogcategoryproductEntity    = new CatalogcategoryproductEntity();
$CatalogcategoryproductModel     = new CatalogcategoryproductModel();
$CatalogproductsModel               = new CatalogproductsModel();
$CatalogproductsEntity              = new CatalogproductsEntity($MyRequest->getRequest());



$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$CatalogproductsEntity->id($Tokenizer->decode($MyRequest->getRequest('id')));
$id = $CatalogproductsEntity->id();
$category  = $MyRequest->getRequest('category');
$description  = $MyRequest->getRequest('description','',true);
$principal  = $MyRequest->getRequest('principal');
$stock  = $MyRequest->getRequest('stock');
$price  = $MyRequest->getRequest('price');
$iva  = $MyRequest->getRequest('iva');
$visible_in_search  = $MyRequest->getRequest('visible_in_search');
$CatalogproductsEntity->description($description);
$CatalogproductsEntity->sku(getFriendly($CatalogproductsEntity->sku()));
$error = false;

$CatalogproductsEntity->in_validation(0);
$CatalogproductsEntity->validate(1);
if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
{
    $CatalogproductsEntity->uid($MySession->getVar('id'));
    $CatalogproductsEntity->in_validation(1);
    $CatalogproductsEntity->validate(0);

}
if(empty($iva))
{
    $CatalogproductsEntity->iva(0);
}
if(empty($price))
{
    $CatalogproductsEntity->price(0);
}
if(empty($stock))
{
    $CatalogproductsEntity->stock(0);
}
if(empty($visible_in_search))
{
    $CatalogproductsEntity->visible_in_search(0);
}
if($CatalogproductsEntity->url_key() === "")
{
    $CatalogproductsEntity->url_key(getFriendly($CatalogproductsEntity->name()));
}
else{
    $CatalogproductsEntity->url_key(getFriendly($CatalogproductsEntity->url_key()));
}


$album = $MySession->GetVar('addProduct');

$validaciones =  new validaciones();


 $valid = $validaciones->validRules($CatalogproductsEntity->setValidation());


if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar("administrar_products_catalog")  && (getCoreConfig('catalog/marketplace/enabled') == 0 || !$MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace")))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if(empty($category))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_empty_category"));
    $error = true;
}




if(!$error)
{
   
    $CatalogproductsEntity->category(json_encode($category));

    
    if(isset($_SESSION['album_'.$album]) && !empty($_SESSION['album_'.$album]))
    {

        foreach($_SESSION['album_'.$album]  as $k => $foto)
        {
            if(md5($foto['img']) == $principal)
            {
                $_SESSION['album_'.$album][$k]['principal'] = 1;
            }
            else{
                $_SESSION['album_'.$album][$k]['principal'] = 0;
            }
        }
    }
    
 
    $CatalogproductsEntity->images(json_encode($_SESSION['album_'.$album]));
    
    if(empty($id))
    {

        $CatalogproductsEntity->createdAt(date('Y-m-d H:i:s'));
        $CatalogproductsEntity->status(1);
    }
    else
    {
        $CatalogproductsEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
    
   
    if($result == REGISTRO_SUCCESS)
    {

        $ObserverManager = new ObserverManager;

        if(empty($id))
        {
            $id = $CatalogproductsModel->getUltimoID();
          
            $dir = $MyConfigure->getServerUploadDir()."/catalog/products/$album/";
            rename($dir,str_replace($album,$id,$dir));
            $CatalogproductsEntity->id($id);
            
            $ObserverManager->dispatch('save_catalog_product',['data' => $CatalogproductsEntity->getArrayCopy()]);
        
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("guardar_generico_success"));
        }
        else
        {
            $ObserverManager->dispatch('edit_catalog_product',['data' => $CatalogproductsEntity->getArrayCopy()]);
        
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        }
        
       
        $CatalogcategoryproductEntity->id_product($id);
        $CatalogcategoryproductModel->remove($CatalogcategoryproductEntity->getArrayCopy());     
        foreach($category as $cat )
        {
           
                $CatalogcategoryproductEntity->id_category($cat);
                $CatalogcategoryproductModel->save($CatalogcategoryproductEntity->getArrayCopy());   
            
        }

        saveDataCustomAttribute($id,'catalog_products');


        
        $MySession->UnsetVar('album_'.$album);
        $MySession->UnsetVar('addProduct');

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_PRODUCTS)."?store_b=".$CatalogproductsEntity->store());
        if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
        {
            if(getCoreConfig('catalog/marketplace/moderar-publicaciones') == 1)
            {
                $CatalogCatalogReviewsEntity    = new CatalogCatalogReviewsEntity();
                $CatalogCatalogReviewsModel     = new CatalogCatalogReviewsModel();
                $CatalogCatalogReviewsEntity->status(0);
                $CatalogCatalogReviewsEntity->parent_id($id);
                if($CatalogCatalogReviewsModel->getData($CatalogCatalogReviewsEntity->getArrayCopy()) == REGISTRO_SUCCESS) {

                    $dataReview = $CatalogCatalogReviewsModel->getRows();
                    $CatalogCatalogReviewsEntity->id($dataReview['id']);
                }
                $CatalogCatalogReviewsEntity->createdAt(date('Y-m-d H:i:s'));
                $CatalogCatalogReviewsEntity->updateAt(date('Y-m-d H:i:s'));
                $CatalogCatalogReviewsEntity->message("");
                $CatalogproductsModel->getData(["id" => $id]);
                $productData = $CatalogproductsModel->getRows();

                $productData['custom_attr'] = getDataCustomAttribute($data_detalle['id'],'catalog_products');

                $CatalogCatalogReviewsEntity->data(addslashes(json_encode($productData)));
                $CatalogCatalogReviewsModel->save($CatalogCatalogReviewsEntity->getArrayCopy());
                $MyFlashMessage->free();
                $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("catalog_savemoderado_success"));
            }
            $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_PRODUCTS_MARKETPLACE)."?store_b=".$CatalogproductsEntity->store());
        }


    }
    elseif($result == REGISTRO_ERROR)
    {

        if(empty($id))
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("editar_generico_error"));
        }
        $location = $MyRequest->getReferer();
    }
    else
    {
        $MyFlashMessage->setMsg("error",$result);
        $location = $MyRequest->getReferer();
    }
}
else
{
    $location = $MyRequest->getReferer();
}


$MyRequest->redirect($location);
?>
