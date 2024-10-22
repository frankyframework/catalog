<?php
use Base\Form\filtrosForm;
use Catalog\model\CatalogUsersModel;
use Catalog\entity\CatalogUsersEntity;
use Franky\Core\paginacion;


$MyPaginacion = new paginacion();
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por','catalog_users.id'));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"ASC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));		
$busca_b	= $MyRequest->getRequest('busca_b');	


$alias = ['_id' => "catalog_users.id"];
if(isset($alias[$MyRequest->getRequest('por')]))
{

  $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}


$MyPaginacion->setCampoOrden($orden);

$CatalogUsersModel = new CatalogUsersModel();
$CatalogUsersEntity = new CatalogUsersEntity();

$CatalogUsersModel->setPage($MyPaginacion->getPage());
$CatalogUsersModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogUsersModel->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());


$CatalogUsersModel->setBusca($busca_b);

$result	 = $CatalogUsersModel->getData([]);
$MyPaginacion->setTotal($CatalogUsersModel->getTotal());

$lista_admin_data = array();
if($CatalogUsersModel->getTotal() > 0)
{
	$iRow = 0;	

	while($registro = $CatalogUsersModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
	
		$lista_admin_data[] = array_merge($registro,array(
                "id" => $Tokenizer->token('catalog_users',$registro["id"]),

                "_id" =>$registro["id"],
                "callback" => $Tokenizer->token('catalog_users',$MyRequest->getURI()),
                "thisClass"     => $thisClass,
                "nuevo_estado"  => ($registro["verificado"] == 1 ?"desactivar" : "activar"),
                "tipo_persona"  => ($registro["tipo_persona"] == 1 ?"Moral" : "Fisica"),
                ));
                $iRow++;
        }
}



//$MyFrankyMonster->setPHPFile(getVista("admin/template/grid.phtml"));
$title_grid = _catalog("Tiendas");
$class_grid = "cont_tiendas";
$error_grid = _catalog("No hay tiendas registradas");
$deleteFunction = "verificarTienda";
$frm_constante_link = ADMIN_CATALOG_FORM_USERS;
$titulo_columnas_grid = array("_id" => _catalog("ID"),"username" => _catalog("Usuario"),"sector" => _catalog("Sector"),"tipo_persona" => _catalog("Tipo"),"descripcion" => _catalog("Descripcion"));
$value_columnas_grid = array("_id", "username","sector","tipo_persona","descripcion");

$css_columnas_grid = array("_id" => "w-xxxx-2" ,"username" => "w-xxxx-3" ,"sector" => "w-xxxx-1" ,"tipo_persona" => "w-xxxx-2","descripcion" => "w-xxxx-2" );

$permisos_grid = "administrar_solicitud_user_marketplace";
$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());


$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>