<?php
use Franky\Core\validaciones; 
use Catalog\model\CatalogStoresModel;
use Catalog\entity\CatalogStoresEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogStoresModel = new CatalogStoresModel();
$CatalogStoresEntity = new CatalogStoresEntity($MyRequest->getRequest());

$id       = $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$CatalogStoresEntity->id($id);



$error = false;


$validaciones =  new validaciones();
$valid = $validaciones->validRules($CatalogStoresEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}

if($CatalogStoresModel->existe($CatalogStoresEntity->url(),$CatalogStoresEntity->idioma(),$id) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_store_exist"));
    $error = true;
}

if(!$MyAccessList->MeDasChancePasar(ADMINISTRAR_STORES_CATALOG))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}



if($error == false)        
{
    
    if(empty($id))
    {
        $CatalogStoresEntity->status(1);
        $CatalogStoresEntity->createdAt(date('Y-m-d H:i:s'));
       
    }
    else
    {
        $CatalogStoresEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogStoresModel->save($CatalogStoresEntity->getArrayCopy());
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

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_LISTA_CATALOG_STORES));

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