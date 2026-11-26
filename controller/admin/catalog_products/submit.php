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
use Catalog\model\CatalogproductrelatedModel;
use Catalog\entity\CatalogproductrelatedEntity;

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
$stock_infinito  = $MyRequest->getRequest('stock_infinito');
$price  = $MyRequest->getRequest('price');
$envio_requerido  = $MyRequest->getRequest('envio_requerido');
$error = false;
$hasRelatedField = array_key_exists('related_products', $_POST);
$relatedRaw = $hasRelatedField && is_string($_POST['related_products'])
    ? json_decode($_POST['related_products'], true)
    : ($hasRelatedField ? null : []);
$relatedIds = [];
if(!$hasRelatedField || is_array($relatedRaw)) {
    if(is_array($relatedRaw)) {
        foreach($relatedRaw as $relatedId) {
            if(is_numeric($relatedId) && (int)$relatedId > 0 && (int)$relatedId != (int)$id) $relatedIds[] = (int)$relatedId;
        }
    }
} else {
    $MyFlashMessage->setMsg("error", "No se pudo leer la selección de productos relacionados.");
    $error = true;
}
$relatedIds = array_values(array_unique($relatedIds));
$visible_in_search  = $MyRequest->getRequest('visible_in_search');
$CatalogproductsEntity->description($description);
$CatalogproductsEntity->sku(getFriendly($CatalogproductsEntity->sku()));

$CatalogproductsEntity->in_validation(0);
$CatalogproductsEntity->validate(1);
if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
{
    $CatalogproductsEntity->uid($MySession->getVar('id'));
    $CatalogproductsEntity->in_validation(1);
    $CatalogproductsEntity->validate(0);

}

if(empty($price))
{
    $CatalogproductsEntity->price(0);
}
if(empty($stock))
{
    $CatalogproductsEntity->stock(0);
}
if(empty($stock_infinito))
{
    $CatalogproductsEntity->stock_infinito(0);
}

if(empty($visible_in_search))
{
    $CatalogproductsEntity->visible_in_search(0);
}
if(empty($envio_requerido))
{
    $CatalogproductsEntity->envio_requerido(0);
}
if($CatalogproductsEntity->url_key() === "")
{
    $CatalogproductsEntity->url_key(getFriendly($CatalogproductsEntity->name()));
}
else{
    $CatalogproductsEntity->url_key(getFriendly($CatalogproductsEntity->url_key()));
}

$tiendas = getCatalogStores();	

$album = $MySession->GetVar('addProduct');
if(empty($id))
{
    $album =  $MySession->GetVar('addProduct');

    if(empty($CatalogproductsEntity->store()) || !isset($tiendas[$CatalogproductsEntity->store()])){
        foreach($tiendas as $k => $v)
        {
            $CatalogproductsEntity->store($k);
            break;
        }   
    }

} else {
    $album = $id;
}
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

// Revalidar en servidor los IDs enviados por el campo oculto.
if(!empty($relatedIds)) {
    $validRelatedIds = [];
    foreach($relatedIds as $relatedId) {
        $relatedProductsModel = new CatalogproductsModel();
        $relatedProductsEntity = new CatalogproductsEntity();
        $relatedProductsEntity->id($relatedId);
        if($relatedProductsModel->getData($relatedProductsEntity->getArrayCopy()) == REGISTRO_SUCCESS && $relatedProductsModel->getTotal() > 0) {
            $validRelatedIds[] = (int)$relatedId;
        }
    }
    if(count($validRelatedIds) != count($relatedIds)) {
        $MyFlashMessage->setMsg("error", "Uno o más productos relacionados ya no están disponibles.");
        $error = true;
    } else {
        $relatedIds = $validRelatedIds;
    }
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
        $CatalogproductsEntity->custom_attributes("");
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

        saveDataCatalogCustomAttribute($id,'catalog_products',$CatalogproductsEntity->set_attribute());

        // Reemplazar relaciones únicamente si el formulario envió su selección.
        if($hasRelatedField) {
            $savedRelatedIds = [];
            $savedRelatedModel = new CatalogproductrelatedModel();
            $savedRelatedEntity = new CatalogproductrelatedEntity();
            $savedRelatedEntity->id_parent($id);
            $savedRelatedModel->setTampag(10000);
            if($savedRelatedModel->getData($savedRelatedEntity->getArrayCopy()) == REGISTRO_SUCCESS) {
                while($savedRelated = $savedRelatedModel->getRows()) {
                    $savedRelatedIds[] = (int)$savedRelated['id_product'];
                }
            }

            $relatedResult = REGISTRO_SUCCESS;
            foreach(array_diff($savedRelatedIds, $relatedIds) as $relatedId) {
                $relatedEntity = new CatalogproductrelatedEntity();
                $relatedEntity->id_parent($id);
                $relatedEntity->id_product($relatedId);
                $relatedModel = new CatalogproductrelatedModel();
                if($relatedModel->eliminar($relatedEntity->getArrayCopy()) != REGISTRO_SUCCESS) {
                    $relatedResult = REGISTRO_ERROR;
                }
            }
            foreach(array_diff($relatedIds, $savedRelatedIds) as $relatedId) {
                $relatedEntity = new CatalogproductrelatedEntity();
                $relatedEntity->id_parent($id);
                $relatedEntity->id_product($relatedId);
                $relatedModel = new CatalogproductrelatedModel();
                if($relatedModel->save($relatedEntity->getArrayCopy()) != REGISTRO_SUCCESS) {
                    $relatedResult = REGISTRO_ERROR;
                }
            }
            if($relatedResult != REGISTRO_SUCCESS) {
                $MyFlashMessage->setMsg("error", "El producto se guardó, pero ocurrió un error al guardar sus productos relacionados.");
            }
        }

        $CatalogproductsEntity->exchangeArray([]);
        $CatalogproductsEntity->id($id);
        $CatalogproductsEntity->custom_attributes(json_encode(getCustomAttrsResolver($id)));
        $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
        
        $MySession->UnsetVar('album_'.$album);
        unset($_SESSION['related_products_'.$album]);
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
                else {
                    $CatalogCatalogReviewsEntity->createdAt(date('Y-m-d H:i:s'));
                }
                
                $CatalogCatalogReviewsEntity->updateAt(date('Y-m-d H:i:s'));
                $CatalogCatalogReviewsEntity->message("");
                $CatalogproductsModel->getData(["id" => $id]);
                $productData = $CatalogproductsModel->getRows();

                $productData['custom_attr'] = getDataCatalogCustomAttribute($id,'catalog_products');

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
