<?php
use Catalog\Form\CatalogsetattributesForm;
use Catalog\model\CatalogsetattributesModel;
use Catalog\entity\CatalogsetattributesEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogsetattributesModel = new CatalogsetattributesModel;
$CatalogsetattributesEntity = new CatalogsetattributesEntity;

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');
$data = $MyFlashMessage->getResponse();

$adminForm = new CatalogsetattributesForm("frmsetatributos");

$title = "Nuevo set de atributos";

$uid = '';
if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
{
    $uid = $MySession->GetVar('id');
}
$set_attribute = getAttributesSet($uid);
if(!empty($id)) {
    $CatalogsetattributesEntity->id($id);
    $CatalogsetattributesModel->getData($CatalogsetattributesEntity->getArrayCopy());

    $data = $CatalogsetattributesModel->getRows();
    $CatalogsetattributesEntity->id($id);

    unset($set_attribute[$id]);
    $title = "Editar set de atributos";
}
$adminForm->setOptionsInput("attributes[]",$_custom_attribtues);
$adminForm->setOptionsInput("parent_id",$set_attribute);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));


$title_form = "$title";