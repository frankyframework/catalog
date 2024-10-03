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
        $status = "En validacion";
        $declinar = '<a class="btn_adm_eliminar" data-id="'.$Tokenizer->token('catalog',$registro["id"]).'" href="#desactivar" ></a>';
        $aprovar = '<a class="btn_adm_aceptar" data-id="'.$Tokenizer->token('catalog',$registro["id"]).'" href="#activar" ><i class="icon icon-valido"></i></a>';
        switch($registro["status"]) {
            case 1:
                $status = "Aprovado";
                $aprovar = "";
                $declinar = "";
                break;
            case 2:
                $status = "Declinado";
                $aprovar = "";
                $declinar = "";
                break;
        }
        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "id" => $Tokenizer->token('catalog',$registro["id"]),
                "status" => $status,
                "declinar" => $declinar,
                "aprovar" => $aprovar,
                "name" => "<a href=\"".$MyRequest->url(CATALOG_PREVIEW,["friendly" => $registro['url_key'],"id" =>$registro["id"]])."\" target='blank'>".$registro["name"]."</a>"
        ));


        $iRow++;
    }
}
$title_grid = _catalog("Moderacion de catalogo");
$class_grid = "catalog_reviews";
$error_grid = _catalog("No hay productos registrados");


$titulo_columnas_grid = array("updateAt" => _("Fecha"),'name' => _("Producto"), "message" =>  _("Mensaje"), "status" =>  _("Status"));
$value_columnas_grid = array("updateAt" ,'name', "message","status");
$css_columnas_grid = array("updateAt" => "w-xxxx-1" ,'name' => "w-xxxx-3" ,"message" => "w-xxxx-4","status" => "w-xxxx-1");


$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addStatusCatalogMarketplace();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
$MyFiltrosForm->setAtributoInput("status_b", "value",$status_b);
