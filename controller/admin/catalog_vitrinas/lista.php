<?php
use Catalog\Form\filtrosForm;
use Franky\Core\paginacion;
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;
use Franky\Haxor\Tokenizer;

$CatalogvitrinaModel = new CatalogvitrinaModel();
$CatalogvitrinaEntity = new CatalogvitrinaEntity();
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

$MySession->UnsetVar('vitrina');
$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"catalog_vitrinas.id"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"DESC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');

$alias = ['_id' => "catalog_vitrinas.id",'store_nombre' => "catalog_vitrinas.store"];
if(isset($alias[$MyRequest->getRequest('por')]))
{

  $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

$CatalogvitrinaEntity->store($store_b);
$CatalogvitrinaModel->setPage($MyPaginacion->getPage());
$CatalogvitrinaModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogvitrinaModel->setOrdensql($orden." ".$MyPaginacion->getOrden());
$result	 		= $CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy(),$busca_b);
$MyPaginacion->setTotal($CatalogvitrinaModel->getTotal());
$lista_admin_data = array();


if($CatalogvitrinaModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $CatalogvitrinaModel->getRows())
    {
        $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "_id" =>$registro["id"],
                "id" => $Tokenizer->token('catalog_vitrina',$registro["id"]),
                "callback" => $Tokenizer->token('catalog_vitrina',$MyRequest->getURI()),
                "nuevo_estado"  => ($registro["status"] == 1 ?"desactivar" : "activar"),
              
        ));


        $iRow++;
    }
}

$title_grid = _catalog("Vitrinas");
$class_grid = "vitrinas";
$error_grid = _catalog("No hay vitrinas registradas");
$deleteFunction = "DeleteCatalogVitrina";

$frm_constante_link = FRM_CATALOG_VITRINA;

$titulo_columnas_grid = array("_id" => _catalog("ID"),"titulo" =>  _catalog("Titulo"),"nombre" =>  _catalog("Nombre"),"store" => _catalog("Tienda"),"clave" => _catalog("Clave"));
$value_columnas_grid = array("_id" ,'titulo', "nombre","store","clave");

$css_columnas_grid = array("_id" => "w-xxxx-1" , "titulo" => "w-xxxx-3","nombre" => "w-xxxx-2", "store" => "w-xxxx-2", "clave" => "w-xxxx-2");


$permisos_grid = ADMINISTRAR_PRODUCTS_CATALOG;

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addStore();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setOptionsInput("store_b", $tiendas);
$MyFiltrosForm->setAtributoInput("store_b", "value",$store_b);
?>
