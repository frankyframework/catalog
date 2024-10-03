<?php
use Franky\Filesystem\File;
use Franky\Core\validaciones;
use Mylines\model\MylinesCapitulosModel;
use Mylines\entity\MylinesCapitulosEntity;
use Franky\Haxor\Tokenizer;
use Franky\Core\ObserverManager;

$Tokenizer = new Tokenizer();
$MylinesCapitulosModel               = new MylinesCapitulosModel();
$MylinesCapitulosEntity              = new MylinesCapitulosEntity($MyRequest->getRequest());



$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$historia = $Tokenizer->decode($MyRequest->getRequest('id_obra'));
$MylinesCapitulosEntity->id($Tokenizer->decode($MyRequest->getRequest('id')));
$id = $MylinesCapitulosEntity->id();
$descripcion  = $MyRequest->getRequest('descripcion','',true);
$MylinesCapitulosEntity->descripcion($descripcion);
$error = false;


if($MylinesCapitulosEntity->url_key() === "")
{
    $MylinesCapitulosEntity->url_key(getFriendly($MylinesCapitulosEntity->titulo()));
}
else{
    $MylinesCapitulosEntity->url_key(getFriendly($MylinesCapitulosEntity->url_key()));
}



$validaciones =  new validaciones();


 $valid = $validaciones->validRules($MylinesCapitulosEntity->setValidation());


if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar("administrar_mylines_mis_obras"))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}





$File = new File();




$handle = new \Franky\Filesystem\Upload($_FILES['portada']);
if ($handle->uploaded)
{

    $name = utf8_encode(strtr(utf8_decode($_FILES['portada']['name']), utf8_decode('ŠŽšžŸÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿ'), 'SZszYAAAAAACEEEEIIIINOOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyy'));
    $name = strtr($name, array('Þ' => 'TH', 'þ' => 'th', 'Ð' => 'DH', 'ð' => 'dh', 'ß' => 'ss', 'Œ' => 'OE', 'œ' => 'oe', 'Æ' => 'AE', 'æ' => 'ae', 'µ' => 'u'));
    $name = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $name);
            
    $dir = $MyConfigure->getServerUploadDir()."/obras/".$name[0].'/'.$name[1];
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
            $MylinesCapitulosEntity->portada($handle->file_dst_name[0].'/'.$handle->file_dst_name[1].'/'.$handle->file_dst_name);

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


    $MylinesCapitulosEntity->id_obra($historia);
    if(empty($id))
    {
        
        $MylinesCapitulosEntity->createdAt(date('Y-m-d H:i:s'));
        $MylinesCapitulosEntity->status(1);
    }
    else
    {
        $MylinesCapitulosEntity->id($id);
        $MylinesCapitulosEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $MylinesCapitulosModel->save($MylinesCapitulosEntity->getArrayCopy());
    
   
    if($result == REGISTRO_SUCCESS)
    {

        $ObserverManager = new ObserverManager;

        if(empty($id))
        {
            $id = $MylinesCapitulosModel->getUltimoID();

          
            
            $MylinesCapitulosEntity->id($id);


            $ObserverManager->dispatch('save_mylines_obras',['data' => $MylinesCapitulosEntity->getArrayCopy()]);
        
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("guardar_generico_success"));


            
        }
        else
        {
            $ObserverManager->dispatch('edit_mylines_obras',['data' => $MylinesCapitulosEntity->getArrayCopy()]);
        
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        }
        $location = (!empty($callback) ? ($callback) : $MyRequest->url(ADMIN_MYLINES_MIS_CAPITULOS).'?historia='.$Tokenizer->token('mylines_obras',$historia));
       
       

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
