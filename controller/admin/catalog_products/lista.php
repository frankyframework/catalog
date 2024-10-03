<?php
use Catalog\Form\filtrosForm;
use Franky\Core\paginacion;
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Franky\Haxor\Tokenizer;



$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity();
$Tokenizer = new Tokenizer();

$MyPaginacion = new paginacion();

$tiendas = getCatalogStores();
$store_b	= $MyRequest->getRequest('store_b');	
if(empty($store_b)){
        foreach($tiendas as $k => $v)
        {
                $store_b = $k;
                break;
        }
        
}


$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"catalog_products.createdAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');


$alias = ['_id' => "catalog_products.id"];
if(isset($alias[$MyRequest->getRequest('por')]))
{

  $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

if(getCoreConfig('catalog/product/showdelete') == 0){
    $CatalogproductsEntity->status(1);
}
$CatalogproductsEntity->store($store_b);
$CatalogproductsModel->setPage($MyPaginacion->getPage());
$CatalogproductsModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogproductsModel->setOrdensql($orden." ".$MyPaginacion->getOrden());
$CatalogproductsModel->setBusca($busca_b);

if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
{
        $CatalogproductsEntity->uid($MySession->getVar('id'));
}

$result	 		= $CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy());
$MyPaginacion->setTotal($CatalogproductsModel->getTotal());
$lista_admin_data = array();


if($CatalogproductsModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $CatalogproductsModel->getRows())
    {
        $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

        
        $img = "";
        $_img = getCoreConfig('catalog/product/placeholder');
        if($_img != "" && file_exists(PROJECT_DIR.$_img))
        {
            $img = makeHTMLImg(imageResize($_img,50,50, true),50,50,$registro['name']);
        }
        $registro["images"] = json_decode($registro["images"],true);
        if(!empty($registro['images']))
        {
            foreach($registro["images"] as $foto)
            {
                if($foto['principal'] == 1)
                {
                     if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img']))
                    {
                        $img = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img'],50,50, true);
                        $img = makeHTMLImg($img,50,50,$registro['name']);
                    }
                }

            }
        }
        $configurableUrl = ADMIN_CATALOG_PRODUCTS_CONFIGURABLES;
        if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
        {
            $configurableUrl = ADMIN_CATALOG_PRODUCTS_CONFIGURABLES_MARKETPLACE;
        }
        $statusV = "En validacion";
        if($registro["in_validation"] == 0 &&  $registro["validate"] == 0 )
        {
            $statusV = "No autorizado";
        }
        if($registro["in_validation"] == 0 &&  $registro["validate"] == 1 )
        {
            $statusV = "Autorizado";
        }
        $registro['link'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $registro['url_key']]);
        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "id" => $Tokenizer->token('catalog_products',$registro["id"]),
                "_id" => $registro["id"],
                "callback" => $Tokenizer->token('catalog_products',$MyRequest->getURI()),
                "nuevo_estado"  => ($registro["status"] == 1 ?"desactivar" : "activar"),
                "status"  => $statusV,
                "images"     => "<a href=\"".$registro['link']."\" target='_blank'>".$img."</a>",
                "name"     => "<a href=\"".$registro['link']."\" target='_blank'>".$registro['name']."</a>",
                "type"     => ($registro['type'] == 'configurable' ? '<a href="'.$MyRequest->link($configurableUrl."?id=".$Tokenizer->token('catalog_products',$registro["id"])).'&amp;callback='.$Tokenizer->token('catalog_products',$MyRequest->getURI()).'&amp;store='.$store_b.'">'.$registro['type'].'</a>' : $registro['type'])
        ));


        $iRow++;
    }
}
//$MyFrankyMonster->setPHPFile(getVista("admin/template/grid.phtml"));
$title_grid = _catalog("Productos");
$class_grid = "products";
$error_grid = _catalog("No hay productos registrados");
$deleteFunction = "DeleteCatalogProduct";

$frm_constante_link = FRM_CATALOG_PRODUCTS;
$permisos_grid = "administrar_products_catalog";

if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
{
    $frm_constante_link = FRM_CATALOG_PRODUCTS_MARKETPLACE;
    $permisos_grid = "administrar_products_catalog_marketplace";
}
$titulo_columnas_grid = array("_id" => _catalog("ID"),"images" => _catalog("Thumb"), "name" =>  _catalog("Nombre"),"sku" => _catalog("SKU"),"type" => _catalog("Tipo"));
$value_columnas_grid = array("_id" ,"images", "name","sku","type");

$css_columnas_grid = array("_id" => "w-xxxx-1" ,"images" => "w-xxxx-2" , "name" => "w-xxxx-3", "sku" => "w-xxxx-1", "type" => "w-xxxx-1");



$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addStore();
$MyFiltrosForm->addSubmit();
$MyFiltrosForm->setOptionsInput("store_b", $tiendas);
$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
$MyFiltrosForm->setAtributoInput("store_b", "value",$store_b);
?>
