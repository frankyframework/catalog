<?php
use Franky\Core\validaciones;
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;
use Catalog\entity\CatalogcategoryproductEntity;
use Catalog\model\CatalogcategoryproductModel;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$CatalogcategoryproductEntity    = new CatalogcategoryproductEntity();
$CatalogcategoryproductModel     = new CatalogcategoryproductModel();
$CatalogvitrinaModel               = new CatalogvitrinaModel();
$CatalogvitrinaEntity              = new CatalogvitrinaEntity($MyRequest->getRequest());


$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$CatalogvitrinaEntity->id($Tokenizer->decode($MyRequest->getRequest('id')));
$id = $CatalogvitrinaEntity->id();
$category  = $MyRequest->getRequest('category');

$random  = $MyRequest->getRequest('random');
$error = false;

if(empty($random))
{
    $CatalogvitrinaEntity->random(0);
}

$CatalogvitrinaEntity->clave(getFriendly($CatalogvitrinaEntity->nombre()));

$album = $MySession->GetVar('addProduct');

$validaciones =  new validaciones();


 $valid = $validaciones->validRules($CatalogvitrinaEntity->setValidation());


if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar("administrar_products_catalog"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if($CatalogvitrinaModel->existeClave($CatalogvitrinaEntity->clave(),$CatalogvitrinaEntity->store(),$CatalogvitrinaEntity->id()) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_vitrina_duplicada"));
    $error = true;
}

if(!$error)
{
    
    if($MySession->GetVar('vitrina'))
    {
        $items['productos'] = $MySession->GetVar('vitrina');
    }
    else{
        $items['category'] = $category;
    }
    
    $CatalogvitrinaEntity->items(json_encode($items));


    if(empty($id))
    {

        $CatalogvitrinaEntity->createdAt(date('Y-m-d H:i:s'));
        $CatalogvitrinaEntity->status(1);
    }
    else
    {
        $CatalogvitrinaEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogvitrinaModel->save($CatalogvitrinaEntity->getArrayCopy());
    
   
    if($result == REGISTRO_SUCCESS)
    {

        
        if(empty($id))
        {
         
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("guardar_generico_success"));
        }
        else
        {
            
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        }
        $location = (!empty($callback) ? ($callback) : $MyRequest->url(LISTA_CATALOG_VITRINA));
        

        $MySession->UnsetVar('vitrina');


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
