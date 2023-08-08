<?php
use Base\Form\filtrosForm;
use Catalog\model\CatalogStoresModel;
use Franky\Core\paginacion;
$MyPaginacion = new paginacion();
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por','catalog_stores.id'));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"ASC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));		
$busca_b	= $MyRequest->getRequest('busca_b');	


$alias = ['_id' => "catalog_stores.id",'nombre' => "catalog_stores.nombre",'idioma' => "catalog_stores.idioma",'moneda_nombre' => "catalog_monedas.nombre"];
if(isset($alias[$MyRequest->getRequest('por')]))
{

  $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}


$MyPaginacion->setCampoOrden($orden);

$CatalogStoresModel = new CatalogStoresModel();


$CatalogStoresModel->setPage($MyPaginacion->getPage());
$CatalogStoresModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogStoresModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());


$CatalogStoresModel->setBusca($busca_b);

$result	 = $CatalogStoresModel->getData([]);
$MyPaginacion->setTotal($CatalogStoresModel->getTotal());

$lista_admin_data = array();
if($CatalogStoresModel->getTotal() > 0)
{
	$iRow = 0;	

	while($registro = $CatalogStoresModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
	
		$lista_admin_data[] = array_merge($registro,array(
                "id" => $Tokenizer->token('catalog_stores',$registro["id"]),

                "_id" =>$registro["id"],
                "callback" => $Tokenizer->token('catalog_stores',$MyRequest->getURI()),
                "thisClass"     => $thisClass,
                "nuevo_estado"  => ($registro["status"] == 1 ?"desactivar" : "activar"),
                ));
                $iRow++;
        }
}



$MyFrankyMonster->setPHPFile(getVista("admin/template/grid.phtml"));
$title_grid = _catalog("Tiendas");
$class_grid = "cont_categorias_blog";
$error_grid = _catalog("No hay tiendas registradas");
$deleteFunction = "EliminarTienda";
$frm_constante_link = ADMIN_FRM_CATALOG_STORES;
$titulo_columnas_grid = array("_id" => _catalog("ID"),"nombre" => _catalog("Nombre"),"idioma" => _catalog("Idioma"),"moneda_nombre" => _catalog("Moneda"),"url" => _catalog("URL"));
$value_columnas_grid = array("_id", "nombre","idioma","moneda_nombre","url");

$css_columnas_grid = array("_id" => "w-xxxx-2" ,"nombre" => "w-xxxx-3" ,"idioma" => "w-xxxx-1" ,"moneda_nombre" => "w-xxxx-2","url" => "w-xxxx-2" );

$permisos_grid = "administrar_stores_catalog";
$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());


$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>