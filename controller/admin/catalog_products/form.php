<?php
use Catalog\Form\ProductsForm;
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogproductsModel  = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$CatalogCategoryModel = new CatalogcategoryModel();
$CatalogCategoryEntity = new CatalogcategoryEntity();


$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback	= $MyRequest->getRequest('callback');
$data = $MyFlashMessage->getResponse();
$galeria_frm = "";
$album = $MySession->GetVar('addProduct');

if(empty($album))
{
   $album = md5(session_id().time());

}
else{
    if(isset($_SESSION['album_'.$album]) && !empty($_SESSION['album_'.$album]))
    {

        foreach($_SESSION['album_'.$album]  as $foto)
        {

            $galeria_frm .= getFotoCatalogProduct($album,$foto['img'],md5($foto['img']),$foto['principal']);
        }
    }
}

$data_category = [];
$data_subcategory = [];
$adminForm = new ProductsForm("frmproduct");




$title = "Nuevo producto";
if(!empty($id))
{
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

    $_SESSION['album_'.$album] = $data["images"];
    if(!empty($data['images']))
    {
        foreach($data["images"] as $foto)
        {

            $galeria_frm .= getFotoCatalogProduct($id,$foto['img'],md5($foto['img']),$foto['principal']);
        }
    }
   
 
   
    

}
//print_r($data); exit;


$CatalogCategoryModel->setPage(1);
$CatalogCategoryModel->setTampag(1000);
$CatalogCategoryModel->setOrdensql("catalog_category.orden ASC");

$CatalogCategoryEntity->status(1);
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
$set_attribute = getAttributesSet();

$adminForm->setOptionsInput("set_attribute",$set_attribute);
$adminForm->setOptionsInput("category[]",$categorys);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));


$title_form = "$title";
$MySession->SetVar('addProduct',$album);

$MyMetatag->setCode("<script  src='/public/plugins/tinymce/tinymce.min.js'></script>");
