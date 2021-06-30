<?php
use Catalog\Form\ProductsForm;
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$CatalogproductsModel  = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();



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
   
    foreach($data["category"] as $cat => $sub)
    {
        $data_category[] = $cat;
        foreach($sub as $_sub)
        {
            $data_subcategory[] = $_sub;
        }
    }
    
 
   
    

}
//print_r($data); exit;

$adminForm->setData($data);
$categorias = getCatalogCategorys('sql',['status' => 1]);
$subcategorias = getCatalogSubcategorys(null,'sql');

$adminForm->setAtributoInput("callback","value", urldecode($callback));
$adminForm->setOptionsInput("category", $categorias);
$adminForm->setOptionsInput("subcategory", $subcategorias);

$title_form = "$title";
$MySession->SetVar('addProduct',$album);

$MyMetatag->setCode("<script  src='/public/plugins/tinymce/tinymce.min.js'></script>");
