<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Catalog\model\CatalogComentariosModel;
use Franky\Haxor\Tokenizer;

$MyPaginacion = new paginacion();
$Tokenizer = new Tokenizer();

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"fecha"));
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


$MyContacto = new CatalogComentariosModel();
$MyContacto->setPage($MyPaginacion->getPage());
$MyContacto->setTampag($MyPaginacion->getTampageDefault());
$MyContacto->setOrdensql($MyPaginacion->getCampoOrden()." ".$MyPaginacion->getOrden());

$uid = "";
if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
{
        $uid = $MySession->getVar('id');
}

$result	 = $MyContacto->getData($uid, $busca_b,$rango);
$MyPaginacion->setTotal($MyContacto->getTotal());


$lista_admin_data = array();
if($MyContacto->getTotal() > 0)
{

	$iRow = 0;

	while($registro = $MyContacto->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

                $lista_admin_data[] = array_merge($registro,array(
                "fecha"         => getFechaUI($registro["fecha"]),
                "thisClass"     => $thisClass,
                "nuevo_estado"  =>  "desactivar",
                "id" => $Tokenizer->token('catalog_products',$registro["id"])
		));

                $iRow++;
        }
}


$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());

$MyFiltrosForm->addBusca();

$deleteFunction ="EliminarComentarioCatalog";
$MyFiltrosForm->addFecha('rango_inicial');
$MyFiltrosForm->addFecha('rango_final');
$MyFiltrosForm->addSubmit();
$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
$MyFiltrosForm->setAtributoInput("rango_inicial", "value",$rango_inicial);
$MyFiltrosForm->setAtributoInput("rango_final", "value",$rango_final);
$MyFiltrosForm->setAtributoInput("rango_inicial", "placeholder","Desde");
$MyFiltrosForm->setAtributoInput("rango_final", "placeholder","Hasta");
?>
