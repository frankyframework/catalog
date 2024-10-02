<?php
use Franky\Core\validaciones;
use Catalog\model\CatalogComentariosModel;
use Catalog\entity\CatalogComentariosEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$MyContacto         = new CatalogComentariosModel();
$MyContactoEntity         = new CatalogComentariosEntity($MyRequest->getRequest());
$token	= $MyRequest->getRequest('token');
$error=false;

$MyContactoEntity->setParentId($Tokenizer->decode($MyRequest->getRequest('parent_id')));
if(!$Tokenizer->decode($MyRequest->getRequest('token_xsrf')))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("bad_request"));
    $error = true;
}

$validaciones =  new validaciones();
$valid = $validaciones->validRules($MyContactoEntity->setValidation());
if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}

if($error== false)
{


        $MyContactoEntity->setFecha(date("Y-m-d")." ".date("H:i:s"));
        $MyContactoEntity->setIp($MyRequest->getIP());

	$result = $MyContacto->save($MyContactoEntity->getArrayCopy());

	if($result == REGISTRO_SUCCESS)
	{


                $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("frm_success"));

                $location= $MyRequest->getReferer();

                $campos = $MyContactoEntity->getArrayCopy();


                $TemplateemailModel    = new \Base\model\TemplateemailModel;
                $TemplateemailEntity    = new \Base\entity\TemplateemailEntity;
                $TemplateemailEntity->id(getCoreConfig('catalog/contactanos/email-contactanos'));
                $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());

                $registro  = $TemplateemailModel->getRows();

                sendEmail($campos,$registro);

                if(getCoreConfig('catalog/contactanos/user-notification')==1):
                    $TemplateemailEntity->id(getCoreConfig('catalog/contactanos/email-user-contactanos'));
                    $TemplateemailModel->getData($TemplateemailEntity->getArrayCopy());
    
                    $registro  = $TemplateemailModel->getRows();
    
                    sendEmail($campos,$registro);
                endif;

	}
	else
	{
		$MyFlashMessage->setMsg("error",$MyMessageAlert->Message("frm_err"));
	        $location= $_SERVER['HTTP_REFERER'];
	}

}
else
{
	$location=$_SERVER['HTTP_REFERER'];
}

$MyRequest->redirect($location);
?>
