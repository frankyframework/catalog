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
if(!empty($id))
{
    $CatalogsetattributesEntity->id($id);
    $CatalogsetattributesModel->getData($CatalogsetattributesEntity->getArrayCopy());

    $data = $CatalogsetattributesModel->getRows();
   
    $CatalogsetattributesEntity->id($id);
    $data['id'] = $Tokenizer->token('catalog_products', $data['id']);;
  
    $title = "Editar set de atributos";
    
    $data["attributes"] = json_decode($data["attributes"],true);

  
   
    

}
//print_r($data); exit;

$custom_attribtues = getDataCustomAttribute(0,'catalog_products');
$_custom_attribtues = [];
foreach($custom_attribtues['custom_imputs'] as $attr)
{
    $_custom_attribtues[$attr['id']] = $attr['name'];
}


$adminForm->setOptionsInput("attributes[]",$_custom_attribtues);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));


$title_form = "$title";