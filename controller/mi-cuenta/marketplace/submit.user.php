<?php
use Franky\Filesystem\File;
use Franky\Core\validaciones;
use Catalog\model\CatalogUsersReviewsModel;
use Catalog\entity\CatalogUsersReviewsEntity;
use Franky\Haxor\Tokenizer;

$CatalogUsersReviewsModel = new CatalogUsersReviewsModel();
$CatalogUsersReviewsEntity = new CatalogUsersReviewsEntity($MyRequest->getRequest());
$Tokenizer = new Tokenizer();


$CatalogUsersReviewsEntity->id($Tokenizer->decode($MyRequest->getRequest('id')));
$id = $CatalogUsersReviewsEntity->id();
$error = false;


$validaciones =  new validaciones();


 $valid = $validaciones->validRules($CatalogUsersReviewsEntity->setValidation());


if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar("solicitud_user_marketplace"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

$File = new File();

$handle = new \Franky\Filesystem\Upload($_FILES['ine_anverso']);
if ($handle->uploaded)
{
    $name = utf8_encode(strtr(utf8_decode($_FILES['ine_anverso']['name']), utf8_decode('ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ'), 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'));
    $name = strtr($name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
    $name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $name);
            
    $dir = $MyConfigure->getServerUploadDir()."/marketplace/users/".$name[0].'/'.$name[1];
    $File->mkdir($dir);

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
            $CatalogUsersReviewsEntity->ine_anverso($handle->file_dst_name[0].'/'.$handle->file_dst_name[1].'/'.$handle->file_dst_name);
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


$handle = new \Franky\Filesystem\Upload($_FILES['ine_reverso']);
if ($handle->uploaded)
{
    $name = utf8_encode(strtr(utf8_decode($_FILES['ine_reverso']['name']), utf8_decode('ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ'), 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'));
    $name = strtr($name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
    $name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $name);
            
    $dir = $MyConfigure->getServerUploadDir()."/marketplace/users/".$name[0].'/'.$name[1];
    $File->mkdir($dir);

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
            $CatalogUsersReviewsEntity->ine_reverso($handle->file_dst_name[0].'/'.$handle->file_dst_name[1].'/'.$handle->file_dst_name);
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


$handle = new \Franky\Filesystem\Upload($_FILES['comprobante']);
if ($handle->uploaded)
{
    $name = utf8_encode(strtr(utf8_decode($_FILES['comprobante']['name']), utf8_decode('ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ'), 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'));
    $name = strtr($name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
    $name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $name);
            
    $dir = $MyConfigure->getServerUploadDir()."/marketplace/users/".$name[0].'/'.$name[1];
    $File->mkdir($dir);

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
            $CatalogUsersReviewsEntity->comprobante($handle->file_dst_name[0].'/'.$handle->file_dst_name[1].'/'.$handle->file_dst_name);
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




if(!$error)
{

    $CatalogUsersReviewsEntity->status(0);
    $CatalogUsersReviewsEntity->message("");
    $CatalogUsersReviewsEntity->parent_id($MySession->GetVar('id'));
    if(empty($id))
    {
        $CatalogUsersReviewsEntity->createdAt(date('Y-m-d H:i:s'));
        $CatalogUsersReviewsEntity->updateAt(date('Y-m-d H:i:s'));
    }
    else
    {
        $CatalogUsersReviewsEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogUsersReviewsModel->save($CatalogUsersReviewsEntity->getArrayCopy());
       
    if($result == REGISTRO_SUCCESS)
    {

            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("catalog_success_user_marketplace"));
            $location = $MyRequest->url(MI_CUENTA);
    }
    elseif($result == REGISTRO_ERROR)
    {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_error_user_marketplace"));
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
