<?php
use Catalog\Form\ProductsForm;
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Catalog\model\CatalogproductrelatedModel;
use Catalog\entity\CatalogproductrelatedEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogproductsModel  = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$CatalogCategoryModel = new CatalogcategoryModel();
$CatalogCategoryEntity = new CatalogcategoryEntity();


$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');
$store	= $MyRequest->getRequest('store');
$data = $MyFlashMessage->getResponse();

$galeria_frm = "";

$tiendas = getCatalogStores();	
if(empty($store) || !isset($tiendas[$store])){
        foreach($tiendas as $k => $v)
        {
                $store = $k;
                break;
        }   
}

$data['store'] = $store;

$album = md5(session_id().time());

$data_category = [];
$data_subcategory = [];
$adminForm = new ProductsForm("frmproduct");
$relatedProducts = [];



$data["category"] = [];
$title = "Nuevo producto";
if(!empty($id))
{
    if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
    {
        $CatalogproductsEntity->uid($MySession->getVar('id'));
    }
    $CatalogproductsEntity->id($id);
    $CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy());

    $data = $CatalogproductsModel->getRows();
    $galeria_frm = "";
    $album = $data['id'];
    $CatalogproductsEntity->id($id);
    $data['id'] = $Tokenizer->token('catalog_products', $data['id']);;
  
    $title = "Editar producto";
    //$data["videos"] = (!empty($data['videos']) ? implode(",",json_decode($data["videos"],true)) : '');
    $data["images"] = json_decode($data["images"],true);
    $data["category"] = json_decode($data["category"],true);

    // Relaciones actuales se cargan en el formulario como cambios pendientes.
    $relatedModel = new CatalogproductrelatedModel();
    $relatedEntity = new CatalogproductrelatedEntity();
    $relatedEntity->id_parent($id);
    $relatedModel->setTampag(10000);
    if($relatedModel->getData($relatedEntity->getArrayCopy()) == REGISTRO_SUCCESS) {
        while($related = $relatedModel->getRows()) {
            $relatedProducts[] = ['id' => (int)$related['id_product'], 'name' => $related['name'], 'sku' => $related['sku']];
        }
    }
    // Cada carga del formulario inicia un borrador limpio desde las relaciones persistidas.
    $_SESSION['related_products_'.$album] = array_column($relatedProducts, 'id');

    $_SESSION['album_'.$album] = $data["images"];
    if(!empty($data['images']))
    {
        foreach($data["images"] as $foto)
        {

            $galeria_frm .= getFotoCatalogProduct($id,$foto['img'],md5($foto['img']),$foto['principal']);
        }
    }
   
 
   
    

}
$relatedDraftIds = isset($_SESSION['related_products_'.$album]) && is_array($_SESSION['related_products_'.$album])
    ? array_values(array_unique(array_map('intval', $_SESSION['related_products_'.$album])))
    : array_column($relatedProducts, 'id');

//print_r($data); exit;


$CatalogCategoryModel->setPage(1);
$CatalogCategoryModel->setTampag(1000);
$CatalogCategoryModel->setOrdensql("catalog_category.name ASC");

$CatalogCategoryEntity->status(1);
$CatalogCategoryEntity->store($store);
$result	 = $CatalogCategoryModel->getData($CatalogCategoryEntity->getArrayCopy());

$categorias = array();
$categorys = array();

if($CatalogCategoryModel->getTotal() > 0)
{
	$iRow = 0;	

	while($registro = $CatalogCategoryModel->getRows())
	{
                
        $parent_id = (!empty($registro['parent_id']) ? $registro['parent_id'] : 0);
        $categorias['cat_'.$parent_id][] = $registro;
        $categorys[$registro['id']] = $registro['name'];
    }
}
$uid = '';
if(getCoreConfig('catalog/marketplace/enabled') == 1 && 
$MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace") && 
getCoreConfig('catalog/marketplace/set-global') == 0)
{
    $uid = $MySession->getVar('id');
}
$set_attribute = getAttributesSet($uid,$store);

$adminForm->setOptionsInput("set_attribute",$set_attribute);
$adminForm->setOptionsInput("category[]",$categorys);
$adminForm->setOptionsInput("store",$tiendas);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));
if(!empty($data['parent_id']))
{
    $adminForm->setAtributoInput("visible_in_search","disabled", 'true');

}


$title_form = "$title";
$MySession->SetVar('addProduct',$album);

$MyMetatag->setCode("<script  src='/plugins/tinymce/tinymce.min.js'></script>");
$MyMetatag->setJs("/plugins/jqGrid/js/jquery.jqGrid.js");
$MyMetatag->setJs("/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
$MyMetatag->setCSS("/plugins/jqGrid/css/ui.jqgrid.css");
