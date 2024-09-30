<?php
use Franky\Core\validaciones;
use Catalog\model\CatalogsetattributesModel;
use Catalog\entity\CatalogsetattributesEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$CatalogsetattributesModel = new CatalogsetattributesModel;
$CatalogsetattributesEntity = new CatalogsetattributesEntity($MyRequest->getRequest());



$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$CatalogsetattributesEntity->id($Tokenizer->decode($MyRequest->getRequest('id')));
$id = $CatalogsetattributesEntity->id();
$attributes  = $MyRequest->getRequest('attributes');
$description  = $MyRequest->getRequest('description','',true);

$CatalogsetattributesEntity->description($description);
$error = false;

if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
{
    $CatalogsetattributesEntity->uid($MySession->getVar('id'));
}


$validaciones =  new validaciones();


 $valid = $validaciones->validRules($CatalogsetattributesEntity->setValidation());


if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes") && (getCoreConfig('catalog/marketplace/enabled') == 0 || !$MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace")))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if(empty($attributes))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_set_empty_attributes"));
    $error = true;
}




if(!$error)
{
   
    $CatalogsetattributesEntity->attributes(json_encode($attributes));

    
  
    if(empty($id))
    {

        $CatalogsetattributesEntity->createdAt(date('Y-m-d H:i:s'));
        $CatalogsetattributesEntity->status(1);
    }
    else
    {
        $CatalogsetattributesEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogsetattributesModel->save($CatalogsetattributesEntity->getArrayCopy());
    
   
    if($result == REGISTRO_SUCCESS)
    {

     

        if(empty($id))
        {
            $id = $CatalogsetattributesModel->getUltimoID();
          
           
            
         
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("guardar_generico_success"));
        }
        else
        {
         
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        }
        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_SET_CUSTOM_ATTRIBUTES));
       
        if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
        {
            $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_SET_CUSTOM_ATTRIBUTES_MARKETPLACE));
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
