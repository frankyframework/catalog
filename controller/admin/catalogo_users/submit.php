<?php
use Franky\Filesystem\File;
use Franky\Core\validaciones; 
use Catalog\model\CatalogUsersModel;
use Catalog\entity\CatalogUsersEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogUsersModel = new CatalogUsersModel();
$CatalogUsersEntity = new CatalogUsersEntity($MyRequest->getRequest());
$id = $Tokenizer->decode($MyRequest->getRequest('id'));

$dias = [
    "lunes" => "Lunes", 
    "martes" => "Martes", 
    "miescoles" => "Miercoles", 
    "jueves" => "Jueves",
    "viernes" => "Viernes",
    "sabado" => "Sabado",
    "domingo" => "Domingo"
];
$horarios = [];

foreach ($dias as $dia => $diaF) {
    if($MyRequest->getRequest($dia) == 1) {
        $horarios[$dia]["open"] = $MyRequest->getRequest('hora_i_'.$dia);
        $horarios[$dia]["close"] = $MyRequest->getRequest('hora_f_'.$dia);
    }
}
if(!empty($horarios)) {
    $CatalogUsersEntity->horario(json_encode($horarios));

}

$error = false;
$CatalogUsersEntity->id($id);


$validaciones =  new validaciones();
$valid = $validaciones->validRules($CatalogUsersEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}

if(empty($id))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if(!$MyAccessList->MeDasChancePasar("administrar_solicitud_user_marketplace"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}


$dir = $MyConfigure->getServerUploadDir()."/catalog/marketplace/";
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
            $CatalogUsersEntity->image($handle->file_dst_name);

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


$handle = new \Franky\Filesystem\Upload($_FILES['logo']);
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
            $CatalogUsersEntity->logo($handle->file_dst_name);

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
 
    $CatalogUsersEntity->updateAt(date('Y-m-d H:i:s'));
    
    $result = $CatalogUsersModel->save($CatalogUsersEntity->getArrayCopy());
    if($result == REGISTRO_SUCCESS)
    {
        $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_CATALOG_LIST_USERS));
    }
    elseif($result == REGISTRO_ERROR)
    {

        $location = $MyRequest->getReferer();
        $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("editar_generico_error"));
    }
    else
    {
        $location = $MyRequest->getReferer();
        $MyFlashMessage->setMsg("error",$result);
    }
}


$MyRequest->redirect($location);
?>