<?php
use Base\Form\filtrosForm;
use Franky\Core\paginacion;
use Catalog\model\CatalogsetattributesModel;
use Catalog\entity\CatalogsetattributesEntity;
use Franky\Haxor\Tokenizer;



$CatalogsetattributesModel = new CatalogsetattributesModel();
$CatalogsetattributesEntity = new CatalogsetattributesEntity();
$Tokenizer = new Tokenizer();

$MyPaginacion = new paginacion();


$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"catalog_set_attributes.createdAt"));
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



$CatalogsetattributesModel->setPage($MyPaginacion->getPage());
$CatalogsetattributesModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogsetattributesModel->setOrdensql($orden." ".$MyPaginacion->getOrden());
$CatalogsetattributesModel->setBusca($busca_b);
$result	 		= $CatalogsetattributesModel->getData($CatalogsetattributesEntity->getArrayCopy());
$MyPaginacion->setTotal($CatalogsetattributesModel->getTotal());
$lista_admin_data = array();


if($CatalogsetattributesModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $CatalogsetattributesModel->getRows())
    {
        $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");

        
       
       
        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "id" => $Tokenizer->token('catalog_products',$registro["id"]),
                "_id" => $registro["id"],
                "callback" => $Tokenizer->token('catalog_products',$MyRequest->getURI()),
                "nuevo_estado"  => ($registro["status"] == 1 ?"desactivar" : "activar")
        ));


        $iRow++;
    }
}
$MyFrankyMonster->setPHPFile(getVista("admin/template/grid.phtml"));
$title_grid = _catalog("Set de atributos");
$class_grid = "set_attr_products";
$error_grid = _catalog("No hay sets registrados");
$deleteFunction = "EliminarCatalogSetAttribute";

$frm_constante_link = ADMIN_FRM_CATALOG_SET_CUSTOM_ATTRIBUTES;

$titulo_columnas_grid = array("_id" => _catalog("ID"),"name" =>  _catalog("Nombre"),"description" => _catalog("Descripcion"));
$value_columnas_grid = array("_id" , "name","description");

$css_columnas_grid = array("_id" => "w-xxxx-1", "name" => "w-xxxx-4", "description" => "w-xxxx-4");


$permisos_grid = "administrar_catalogo_custom_attributes";

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>
