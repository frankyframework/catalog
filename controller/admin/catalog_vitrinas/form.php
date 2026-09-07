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

$data = $MyFlashMessage->getResponse();
$galeria_frm = "";
$_callback = $Tokenizer->token('catalog_vitrina',$MyRequest->getURI());


$tiendas = getCatalogStores();	

$data['category'] = [];

$data_category = [];
$data_subcategory = [];
$adminForm = new CatalogVitrinaForm("frmvitrina");

$MySession->SetVar('productsVitrina', []);
$title = "Nuevo producto";
if(!empty($id))
{
    $CatalogvitrinaEntity->id($id);
    $CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy());

    $data = $CatalogvitrinaModel->getRows();

    $data['id'] = $Tokenizer->token('catalog_vitrina', $data['id']);;
  
    $title = "Editar vitrina";

    $data["items"] = json_decode($data["items"],true);
   
    
    $data['category'] =  !empty($data["items"]["category"])  ? $data["items"]["category"] : [];
    $data['productos'] =  !empty($data["items"]["productos"])  ? $data["items"]["productos"] : [];
       
    $MySession->SetVar('productsVitrina', $data['productos']);
    
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
$MySession->SetVar('vitrina',[]);

$adminForm->setOptionsInput("category[]", $_categorias);
$adminForm->setOptionsInput("store",$tiendas);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));

$title_form = "$title";

$MyMetatag->setJs("/public/plugins/jqGrid/js/jquery.jqGrid.js");
$MyMetatag->setJs("/public/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
$MyMetatag->setCSS("/public/plugins/jqGrid/css/ui.jqgrid.css");
