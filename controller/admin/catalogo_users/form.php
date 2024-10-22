<?php
use Catalog\Form\CatalogUsersForm;
use Catalog\model\CatalogUsersModel;
use Catalog\entity\CatalogUsersEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogUsersModel  = new CatalogUsersModel();
$CatalogUsersEntity = new CatalogUsersEntity();


$callback	= $MyRequest->getRequest('callback');
$id	= $Tokenizer->decode($MyRequest->getRequest('id'));
$data = $MyFlashMessage->getResponse();

$adminForm = new CatalogUsersForm("frmusername");
$adminForm->setAtributo("action", "admin/catalogo-users/submit.php");

$title = "Editar tienda";

$CatalogUsersEntity->id($id);
if($CatalogUsersModel->getData($CatalogUsersEntity->getArrayCopy()) == REGISTRO_SUCCESS) {
    $data = $CatalogUsersModel->getRows();

    if(!empty($data["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/marketplace/".$data["image"]))
    {
        $data['image'] = imageResize($MyConfigure->getUploadDir()."/catalog/marketplace/".$data["image"],150,150, true);
        
    }
    if(!empty($data["logo"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/marketplace/".$data["logo"]))
    {
        $data['logo'] = imageResize($MyConfigure->getUploadDir()."/catalog/marketplace/".$data["logo"],150,150, true);
        
    }
    $horarios = json_decode($data['horario'], true);
    foreach ($horarios as $dia => $horario) {
        $data[$dia] = 1;
        $data['hora_i_'.$dia] = $horario['open'];
        $data['hora_f_'.$dia] = $horario['close'];
    }
    $data['id'] = $Tokenizer->token('catalog_users',$data["id"]);
} else{
    $MyRequest->redirect($MyRequest->url(ADMIN_CATALOG_LIST_USERS),"301");
}

$adminForm->setMobile($Mobile_detect->isMobile());
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));
$title_form = $title;