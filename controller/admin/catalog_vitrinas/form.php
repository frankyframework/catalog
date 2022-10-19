<?php
use Catalog\Form\CatalogVitrinaForm;
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;
use Catalog\entity\CatalogcategoryEntity;


use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogvitrinaModel = new CatalogvitrinaModel();
$CatalogvitrinaEntity = new CatalogvitrinaEntity();
$CatalogcategoryEntity = new CatalogcategoryEntity();
$id         = $Tokenizer->decode($MyRequest->getRequest('id'));
$callback   = $MyRequest->getRequest('callback');
$store   = $MyRequest->getRequest('store');
$data = $MyFlashMessage->getResponse();
$galeria_frm = "";
$_callback = $Tokenizer->token('catalog_vitrina',$MyRequest->getURI());


$tiendas = getCatalogStores();	
if(empty($store)){
        foreach($tiendas as $k => $v)
        {
                $store = $k;
                break;
        }   
}

$data['store'] = $store;


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
   
    
    $data['category'] =  $data["items"]["category"];
       
    
    
}

$CatalogcategoryEntity->store($data['store']);
$categorias = getCatalogCategorys($CatalogcategoryEntity->getArrayCopy());
$_categorias = [];
foreach($categorias as $parent => $categoria){
    foreach($categoria as $cat)
    {
        $_categorias[$cat['id']] = $cat['name']; 
    }
   
}

$adminForm->setOptionsInput("category[]", $_categorias);

$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "$title";
