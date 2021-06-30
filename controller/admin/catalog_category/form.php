<?php
use Catalog\Form\CatalogCategoryForm;
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback      = $MyRequest->getRequest('callback');
$data          = $MyFlashMessage->getResponse();

if(!empty($id))
{
    $CatalogCategoryModel = new CatalogcategoryModel();
    $CatalogCategoryEntity = new CatalogcategoryEntity();
    $CatalogCategoryEntity->id($id);
    $result	 = $CatalogCategoryModel->getData($CatalogCategoryEntity->getArrayCopy());
    $data           = $CatalogCategoryModel->getRows();
    $data['users'] = json_decode($data['users'],true);
    
    if(!empty($data["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$data["image"]))
    {
        $data['image'] = imageResize($MyConfigure->getUploadDir()."/catalog/category/".$data["image"],150,150, true);
        
    }

    $data['id'] = $Tokenizer->token('category', $data['id']);
}

$adminForm = new CatalogCategoryForm("frmcategoria");
$adminForm->setOptionsInput("users[]", $_Niveles_usuarios);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));
$title_form = "Categorias de catalogo";
