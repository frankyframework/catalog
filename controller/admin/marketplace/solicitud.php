<?php
use Catalog\Form\filtrosForm;
use Franky\Core\paginacion;
use Catalog\model\CatalogCatalogReviewsModel;
use Catalog\entity\CatalogCatalogReviewsEntity;
use Franky\Haxor\Tokenizer;

$CatalogCatalogReviewsModel = new CatalogCatalogReviewsModel();
$CatalogCatalogReviewsEntity = new CatalogCatalogReviewsEntity();
$Tokenizer = new Tokenizer();
$MyPaginacion = new paginacion();   

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"catalog_catalog_reviews.updateAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"ASC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');
$status_b	= $MyRequest->getRequest('status_b',0);
    
$alias = ['updateAt' => "catalog_catalog_reviews.updateAt"];
if(isset($alias[$MyRequest->getRequest('por')]))
{
    $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

$CatalogCatalogReviewsModel->setPage($MyPaginacion->getPage());
$CatalogCatalogReviewsModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogCatalogReviewsModel->setOrdensql($orden." ".$MyPaginacion->getOrden());
$CatalogCatalogReviewsEntity->status($status_b);
$CatalogCatalogReviewsModel->setBusca($busca_b);
$result	 = $CatalogCatalogReviewsModel->getData($CatalogCatalogReviewsEntity->getArrayCopy());
$MyPaginacion->setTotal($CatalogCatalogReviewsModel->getTotal());
$lista_admin_data = array();


if($CatalogCatalogReviewsModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $CatalogCatalogReviewsModel->getRows())
    {
        $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
        
        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "id" => $Tokenizer->token('catalog',$registro["id"]),
                "name" => "<a href=\"".$MyRequest->url(CATALOG_PREVIEW,["friendly" => $registro['url_key']])."\" target='blank'>".$registro["name"]."</a>"
        ));


        $iRow++;
    }
}
$title_grid = _catalog("Moderacion de catalogo");
$class_grid = "catalog_reviews";
$error_grid = _catalog("No hay productos registrados");
$deleteFunction = "Catalog_AprovarInformacion";

$frm_constante_link = "";

$titulo_columnas_grid = array("updateAt" => _("Fecha"),'name' => _("Producto"), "message" =>  _("Mensaje"));
$value_columnas_grid = array("updateAt" ,'name', "message");
$css_columnas_grid = array("updateAt" => "w-xxxx-1" ,'name' => "w-xxxx-3" ,"message" => "w-xxxx-4");


$permisos_grid = "moderar_publicaciones_catalog";

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
