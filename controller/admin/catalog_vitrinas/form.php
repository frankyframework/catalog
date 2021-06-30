<?php
use Catalog\Form\CatalogVitrinaForm;
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;

use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogvitrinaModel = new CatalogvitrinaModel();
$CatalogvitrinaEntity = new CatalogvitrinaEntity();

$id         = $Tokenizer->decode($MyRequest->getRequest('id'));
$callback   = $MyRequest->getRequest('callback');
$data = $MyFlashMessage->getResponse();
$galeria_frm = "";
$_callback = $Tokenizer->token('catalog_vitrina',$MyRequest->getURI());





$data_category = [];
$data_subcategory = [];
$adminForm = new CatalogVitrinaForm("frmvitrina");


$title = "Nuevo producto";
if(!empty($id))
{
    $CatalogvitrinaEntity->id($id);
    $CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy());

    $data = $CatalogvitrinaModel->getRows();

    $data['id'] = $Tokenizer->token('catalog_vitrina', $data['id']);;
  
    $title = "Editar vitrina";

    $data["items"] = json_decode($data["items"],true);
   
    foreach($data['items']["category"] as $cat => $sub)
    {
        $data_category[] = $cat;
        foreach($sub as $_sub)
        {
            $data_subcategory[] = $_sub;
        }
    }
    
}
//print_r($data); exit;


$categorias = getCatalogCategorys('sql');
$subcategorias = getCatalogSubcategorys(null,'sql');


$adminForm->setOptionsInput("category", $categorias);
$adminForm->setOptionsInput("subcategory", $subcategorias);

$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "$title";
