<?php
use Franky\Filesystem\File;
use Franky\Core\validaciones; 
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogCategoryModel = new CatalogcategoryModel();
$CatalogCategoryEntity = new CatalogcategoryEntity($MyRequest->getRequest());

$id       = $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$status = $MyRequest->getRequest('status',0);
$visible_in_search = $MyRequest->getRequest('visible_in_search',0);
$CatalogCategoryEntity->users(json_encode($MyRequest->getRequest('users',array())));

if($Tokenizer->decode($MyRequest->getRequest('id')) != false)
{
    $CatalogCategoryEntity->id($id);
}


$error = false;

if($CatalogCategoryEntity->url_key() === "")
{
    $CatalogCategoryEntity->url_key(getFriendly($CatalogCategoryEntity->name()));
}
else{
    $CatalogCategoryEntity->url_key(getFriendly($CatalogCategoryEntity->url_key()));
}

$validaciones =  new validaciones();
$valid = $validaciones->validRules($CatalogCategoryEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}

if($CatalogCategoryModel->existe($nombre,$id) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("project_categoria_duplicado"));
    $error = true;
}

if(!$MyAccessList->MeDasChancePasar("administrar_category_catalog") && (getCoreConfig('catalog/marketplace/enabled') == 0 || !$MyAccessList->MeDasChancePasar("administrar_category_catalog_marketplace")))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}


$dir = $MyConfigure->getServerUploadDir()."/catalog/category/";
$File = new File();
$File->mkdir($dir);


$handle = new \Franky\Filesystem\Upload($_FILES['image']);
if ($handle->uploaded)
{
    if($handle->file_is_image)
    {
        $handle->file_max_size = "2024288"; //1k(1024) x 512

        if($handle->image_src_x > 1600 || $handle->image_src_y > 1600)
        {
            $handle->image_resize = true;
        }
        $handle->image_x = 1600;
        $handle->image_y = 1600;
        $handle->image_ratio           = true;
    //    $handle->image_ratio_fill = true;
        $handle->file_auto_rename = true;
        $handle->file_overwrite = false;
        $handle->image_background_color = '#FFFFFF';


        $handle->Process($dir);

        if ($handle->processed)
        {
            $CatalogCategoryEntity->image($handle->file_dst_name);

        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("imagen_error",$handle->error));
            $error = true;
        }
    }
    else
    {
        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("solo_imagen"));
        $error = true;

    }
}

if($error == false)        
{
    $CatalogCategoryEntity->status($status);
    $CatalogCategoryEntity->visible_in_search($visible_in_search);
    if(empty($id))
    {

        $CatalogCategoryEntity->createdAt(date('Y-m-d H:i:s'));
       
    }
    else
    {
        $CatalogCategoryEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogCategoryModel->save($CatalogCategoryEntity->getArrayCopy());
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
        
        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_CATEGORY)."?store_b=".$CatalogCategoryEntity->store());
        

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