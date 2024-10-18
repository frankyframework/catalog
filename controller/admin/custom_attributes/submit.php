<?php
use Franky\Core\validaciones; 
use Catalog\model\CustomattributesModel;
use Catalog\entity\CustomattributesEntity;
use Franky\Haxor\Tokenizer;
use Franky\Filesystem\File;

$Tokenizer = new Tokenizer();

$CustomattributesModel = new CustomattributesModel();
$CustomattributesEntity = new CustomattributesEntity($MyRequest->getRequest());
$CustomattributesEntity->entity('catalog_products');
$id       = $Tokenizer->decode($MyRequest->getRequest('id'));
$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$required = $MyRequest->getRequest('required',0);
$searchable = $MyRequest->getRequest('searchable',0);
$type_option = $MyRequest->getRequest('type_option');

$CustomattributesEntity->required($required);

$CustomattributesEntity->searchable($searchable);

if($Tokenizer->decode($MyRequest->getRequest('id')) != false)
{
    $CustomattributesEntity->id($id);
}

$CustomattributesEntity->name(getFriendly($CustomattributesEntity->name()));

if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
{
    $CustomattributesEntity->uid($MySession->getVar('id'));
}

$error = false;



$validaciones =  new validaciones();
$valid = $validaciones->validRules($CustomattributesEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}
$uid = '';

if($CustomattributesModel->existe($nombre,$entity,$id, $CustomattributesEntity->uid()) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("developer_attr_duplicado"));
    $error = true;
}

if(!$MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes") && (getCoreConfig('catalog/marketplace/enabled') == 0 || !$MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace")))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}


if($type_option == "options_attr")
{
   
    $values = $MyRequest->getRequest('option_value',array());
    $label = $MyRequest->getRequest('option_label',array());
    $options = [];
    if(!empty($values))
    {
        foreach($values as $k => $v)
        {
            $options[$v] = $label[$k];
        }
    }
    $CustomattributesEntity->data(addslashes(json_encode($options)));
    $CustomattributesEntity->source('');
}
else
{
    
    $CustomattributesEntity->data('');
}


$dir = $MyConfigure->getServerUploadDir()."/catalog/customattr/";
$File = new File();
$File->mkdir($dir);


$handle = new \Franky\Filesystem\Upload($_FILES['icon']);
if ($handle->uploaded)
{
    if($handle->file_is_image)
    {
        $handle->file_max_size = "2024288"; //1k(1024) x 512

        if($handle->image_src_x > 100 || $handle->image_src_y > 100)
        {
            $handle->image_resize = true;
        }
        $handle->image_x = 100;
        $handle->image_y = 100;
        $handle->image_ratio           = true;
    //    $handle->image_ratio_fill = true;
        $handle->file_auto_rename = true;
        $handle->file_overwrite = false;
        $handle->image_background_color = '#FFFFFF';


        $handle->Process($dir);

        if ($handle->processed)
        {
            $CustomattributesEntity->icon($handle->file_dst_name);

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
    if(empty($id))
    {
        
        $CustomattributesEntity->createdAt(date('Y-m-d H:i:s'));
        $CustomattributesEntity->status(1);
        
    }
    else
    {
        $CustomattributesEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CustomattributesModel->save($CustomattributesEntity->getArrayCopy());
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

        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_CUSTOM_ATTRIBUTES));
        if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
        {
            $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_CUSTOM_ATTRIBUTES_MARKETPLACE));

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