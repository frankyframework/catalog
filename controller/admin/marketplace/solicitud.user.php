<?php
use Catalog\Form\CancelForm;
use Catalog\Form\filtrosForm;
use Franky\Core\paginacion;
use Catalog\model\CatalogUsersReviewsModel;
use Catalog\entity\CatalogUsersReviewsEntity;
use Franky\Haxor\Tokenizer;

$CatalogUsersReviewsModel = new CatalogUsersReviewsModel();
$CatalogUsersReviewsEntity = new CatalogUsersReviewsEntity();
$Tokenizer = new Tokenizer();
$MyPaginacion = new paginacion();  
$cancelForm = new CancelForm("frmdecline"); 

$MyPaginacion->setPage($MyRequest->getRequest('page',1));
$MyPaginacion->setCampoOrden($MyRequest->getRequest('por',"catalog_users_reviews.updateAt"));
$MyPaginacion->setOrden($MyRequest->getRequest('order',"ASC"));
$MyPaginacion->setTampageDefault($MyRequest->getRequest('tampag',25));
$busca_b	= $MyRequest->getRequest('busca_b');
$status_b	= $MyRequest->getRequest('status_b',0);
    
$alias = ['updateAt' => "catalog_users_reviews.updateAt"];
if(isset($alias[$MyRequest->getRequest('por')]))
{
    $orden = $alias[$MyRequest->getRequest('por')];
}
else{
    $orden = $MyPaginacion->getCampoOrden();
}

$CatalogUsersReviewsModel->setPage($MyPaginacion->getPage());
$CatalogUsersReviewsModel->setTampag($MyPaginacion->getTampageDefault());
$CatalogUsersReviewsModel->setOrdensql($orden." ".$MyPaginacion->getOrden());
$CatalogUsersReviewsEntity->status($status_b);
$CatalogUsersReviewsModel->setBusca($busca_b);
$result	 = $CatalogUsersReviewsModel->getData($CatalogUsersReviewsEntity->getArrayCopy());
$MyPaginacion->setTotal($CatalogUsersReviewsModel->getTotal());
$lista_admin_data = array();


if($CatalogUsersReviewsModel->getTotal() > 0)
{

    $iRow = 0;

    while($registro = $CatalogUsersReviewsModel->getRows())
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
        $ine_anverso = "";
        $ine_reverso = "";
        $comprobante = "";
        if( file_exists($MyConfigure->getServerUploadDir()."/marketplace/users/".$registro['ine_anverso']))
        {
            $ine_anverso = imageResize($MyConfigure->getUploadDir()."/marketplace/users/".$registro['ine_anverso'],400,400, false);
            $ine_anverso = makeHTMLImg($ine_anverso,400,"",$registro['nombre']);
        }
        if( file_exists($MyConfigure->getServerUploadDir()."/marketplace/users/".$registro['ine_reverso']))
        {
            $ine_reverso = imageResize($MyConfigure->getUploadDir()."/marketplace/users/".$registro['ine_reverso'],400,400, false);
            $ine_reverso = makeHTMLImg($ine_reverso,400,"",$registro['nombre']);
        }
        if( file_exists($MyConfigure->getServerUploadDir()."/marketplace/users/".$registro['comprobante']))
        {
            $comprobante = imageResize($MyConfigure->getUploadDir()."/marketplace/users/".$registro['comprobante'],400,400, false);
            $comprobante = makeHTMLImg($comprobante,400,"",$registro['nombre']);
        }

        $lista_admin_data[$iRow] = array_merge($registro,array(
                "thisClass"     => $thisClass,
                "id" => $Tokenizer->token('catalog',$registro["id"]),
                "status" => $status,
                "declinar" => $declinar,
                "aprovar" => $aprovar,
                "ine_anverso" => $ine_anverso,
                "ine_reverso" => $ine_reverso,
                "comprobante" => $comprobante,
        ));


        $iRow++;
    }
}
$title_grid = _catalog("Moderacion de usuarios Marketplace");
$class_grid = "marketplace_users_reviews";
$error_grid = _catalog("No hay solicitudes registradas");
$deleteFunction = "Catalog_AutorizarDatosUserMarketplace";

$frm_constante_link = "";

$titulo_columnas_grid = array("updateAt" => _("Fecha"),'nombre' => _("Nombre"), "message" =>  _("Mensaje"), "status" =>  _("Status"));
$value_columnas_grid = array("updateAt" ,'nombre',"message","status");
$css_columnas_grid = array("updateAt" => "w-xxxx-1" ,'nombre' => "w-xxxx-3" ,"message" => "w-xxxx-3","status" => "w-xxxx-1");


$permisos_grid = "administrar_solicitud_user_marketplace";

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addStatusCatalogMarketplace();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
$MyFiltrosForm->setAtributoInput("status_b", "value",$status_b);
