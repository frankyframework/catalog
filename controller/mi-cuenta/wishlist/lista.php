<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Wishlist\model\WishlistModel;
use Wishlist\entity\WishlistEntity;
use Franky\Haxor\Tokenizer;

$permisos_grid = "administrar_catalog_wishlist";
$campo_item = "name";
$campo_item_id = "id";
$campo_item_urlkey = getCoreConfig('catalog/product/urlkey');
$campo_item_image = "images";
$tabla = "catalog_products";
$seccion = "products";
$urlView = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' =>  "{campo_item_urlkey}"]);
$pathImage = $MyConfigure->getServerUploadDir()."/catalog/products/{campo_item_id}/{campo_item_image}";
$thumbW = 100;
$thumbH = 100;

$Tokenizer = new Tokenizer();
$MyPaginacion = new paginacion();


$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"wishlist.createdAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');
$rango_inicial  = $MyRequest->getRequest("rango_inicial","");
$rango_final    = $MyRequest->getRequest("rango_final","");

$rango = array();

if(!empty($rango_inicial) && !empty($rango_final))
{
    $rango = [$rango_inicial,$rango_final];
}
if(!empty($rango_inicial) && empty($rango_final))
{
    $rango = [$rango_inicial,date('Y-m-d')];
}
if(empty($rango_inicial) && !empty($rango_final))
{
    $rango = ['1900-01-01',$rango_final];
}

$WishlistModel = new WishlistModel();
$WishlistEntity = new WishlistEntity();
$WishlistModel->setRango($rango);
$WishlistModel->setBusca($busca_b);

$WishlistEntity->tabla($tabla);
$WishlistModel->setCampoItem($campo_item);
$WishlistModel->setTablaItem($tabla);
$WishlistModel->setCampoItemId($campo_item_id);
if (isset($campo_item_urlkey) && !empty($campo_item_urlkey) ) {
    $WishlistModel->setCampoItemUrl($campo_item_urlkey);
 }     
 if (isset($campo_item_image) && !empty($campo_item_image) ) {
    $WishlistModel->setCampoItemImage($campo_item_image);
 }  

$WishlistModel->setPage($MyPaginacion->getPage());
$WishlistModel->setTampag($MyPaginacion->getTampageDefault());
$WishlistModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());


$WishlistEntity->uid($MySession->GetVar('id'));

$result	 = $WishlistModel->getFullData($WishlistEntity->getArrayCopy());
$MyPaginacion->setTotal($WishlistModel->getTotal());


$lista_admin_data = array();
$data_new_group = array();
if($WishlistModel->getTotal() > 0)
{

	$iRow = 0;

	while($registro = $WishlistModel->getRows())
	{
		  $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

        if (isset($campo_item_urlkey) && !empty($campo_item_urlkey) && isset($urlView)) {
            $registro["item"] = '<a href="'.str_replace("{campo_item_urlkey}",$registro["item_url_key"],$urlView).'" target="_blank">'.$registro["item"]."</a>" ;
        }    
        if (isset($campo_item_image) && !empty($campo_item_image) && isset($pathImage)) {
         
            $images = json_decode($registro["item_image"],true);
            $img = "";
            $_img = getCoreConfig('catalog/product/placeholder');
            if($_img != "" && file_exists(PROJECT_DIR.$_img))
            {
              $registro['item_image'] = makeHTMLImg(imageResize($_img,500,500, false),100,100,strip_tags($registro["item"]));
            }
            
  
            if(!empty($images))
            {
                foreach($images as $foto)
                {
  
                    if($foto['principal'] == 1)
                    {
                        if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id_item"].'/'.$foto['img']))
                        {
  
                              $registro['item_image'] = makeHTMLImg(imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id_item"].'/'.$foto['img'],500,500, true),100,100,strip_tags($registro["item"]));
  
                        }
                    }
  
                }
            }

        }     

      $lista_admin_data[] = array_merge($registro,array(
      "id" => $Tokenizer->token("whishist", $registro["id"]),
      "createdAt"         => getFechaUI($registro["createdAt"]),
      "thisClass"     => $thisClass,
      "nuevo_estado"  =>  "desactivar"
      ));

      $iRow++;
    }

}

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();

$frm_constante_link = "";
$MyFrankyMonster->setPHPFile(PROJECT_DIR."/modulos/wishlist/diseno/admin/wishlist/lista.phtml");
$permisos_grid = $permisos_grid;
$deleteFunction ="wishlist_EliminarWhislist";
$MyFiltrosForm->addFecha('rango_inicial');
$MyFiltrosForm->addFecha('rango_final');
$MyFiltrosForm->addSubmit();
$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
$MyFiltrosForm->setAtributoInput("rango_inicial", "value",$rango_inicial);
$MyFiltrosForm->setAtributoInput("rango_final", "value",$rango_final);
$MyFiltrosForm->setAtributoInput("rango_inicial", "placeholder","Desde");
$MyFiltrosForm->setAtributoInput("rango_final", "placeholder","Hasta");

?>

?>
