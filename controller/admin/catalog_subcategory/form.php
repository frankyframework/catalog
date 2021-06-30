<?php
use Catalog\Form\CatalogSubcategoryForm;
use Catalog\model\CatalogsubcategoryModel;
use Catalog\entity\CatalogsubcategoryEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback      = $MyRequest->getRequest('callback');
$data          = $MyFlashMessage->getResponse();
$catalog_categorias = getCatalogCategorys('sql');
if(!empty($id))
{
    $CatalogsubcategoryModel = new CatalogsubcategoryModel();
    $CatalogsubcategoryEntity = new CatalogsubcategoryEntity();
    $CatalogsubcategoryEntity->id($id);
    $result	 = $CatalogsubcategoryModel->getData($CatalogsubcategoryEntity->getArrayCopy());
    $data           = $CatalogsubcategoryModel->getRows();
    $data['users'] = json_decode($data['users'],true);
    
    if(!empty($data["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$data["image"]))
    {
        $data['image'] = imageResize($MyConfigure->getUploadDir()."/catalog/category/".$data["image"],150,150, true);
        
    }

    $data['id'] = $Tokenizer->token('subcategory', $data['id']);
}


$adminForm = new CatalogSubcategoryForm("frmsubcategoria");
$adminForm->setOptionsInput("users[]", $_Niveles_usuarios);
$adminForm->setOptionsInput("id_category", $catalog_categorias);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));
$title_form = "Subcategorias de catalogo";
