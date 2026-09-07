<?php
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;
use Franky\Haxor\Tokenizer;

if ($MyRequest->isAjax()) {
    $callback	= $MyRequest->getRequest('callback');
    $filters = $MyRequest->getRequest('filters');
    $dataPost = json_decode(stripslashes($filters),true);
    if(isset($dataPost['rules'])) {
        $dataPost = $dataPost['rules'];
    }
    $requestFranky = [];
    $request = [];
    if(!empty($dataPost)) {
        foreach($dataPost as $data) {
            $request[$data['field']] = $MyRequest->Sanitizacion($data['data']);
          }
    }
   
    $tiendas = getCatalogStores();

    $alias = ['_id' => "catalog_vitrinas.id",'store_nombre' => "catalog_vitrinas.store"];
    if(isset($alias[$MyRequest->getRequest('sidx')]))
    {
        $sortInput = $alias[$MyRequest->getRequest('sidx')];
    }
    else{
        $sortInput  = (!empty($MyRequest->getRequest('sidx',"catalog_vitrinas.id")) ? : "catalog_vitrinas.id");
    }
    if(isset($request['_id']))
    {
        $request['id'] = $request['_id']; 
    }
    if(isset($request['store_nombre']))
    {
        $request['store'] = $request['store_nombre'];
    }
    $CatalogvitrinaModel = new CatalogvitrinaModel();
    $CatalogvitrinaEntity = new CatalogvitrinaEntity($request);
    $Tokenizer = new Tokenizer();

    if(empty($request['store'])) {
        foreach($tiendas as $k => $v) {
            $request['store'] = $k;
            $CatalogvitrinaEntity->store($request['store']);
            break;
        }
    }

    $MySession->UnsetVar('vitrina');
  
    $CatalogvitrinaModel->setPage($MyRequest->getRequest('page',1));
    $CatalogvitrinaModel->setTampag($MyRequest->getRequest('rows',12));
    $CatalogvitrinaModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));
    $result	 		= $CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy());

    $dataRows = ["rows" => [], "total" => ceil($CatalogvitrinaModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $CatalogvitrinaModel->getTotal()];


    if($CatalogvitrinaModel->getTotal() > 0)
    {

        while($registro = $CatalogvitrinaModel->getRows())
        {
            $registro = array_filter($registro, function($llave) {
                return !is_numeric($llave);
            }, ARRAY_FILTER_USE_KEY);

            $dataRows['rows'][] =   array_merge($registro,array(
                    "store"     => $tiendas[$registro['store']],
                    "store_id"     => $registro['store'],
                    "_id" =>$registro["id"],
                    "id" => $Tokenizer->token('catalog_vitrina',$registro["id"]),
                    "callback" => $Tokenizer->token('catalog_vitrina',$MyRequest->getURI()),
                    "status"  => ($registro["status"] == 1 ?"desactivar" : "activar"),  
            ));

        }
    }
    header('Content-Type: application/json; charset=utf-8');
    echo $callback . '(' . json_encode($dataRows). ');';
    die;
} else {

    $MyMetatag->setJs("/public/plugins/jqGrid/js/jquery.jqGrid.js");
    $MyMetatag->setJs("/public/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
    $MyMetatag->setCSS("/public/plugins/jqGrid/css/ui.jqgrid.css");

    $frm_constante_link = "";
    $permisos_grid = "";
    if($MyAccessList->MeDasChancePasar("administrar_products_catalog"))
    {
        $frm_constante_link = FRM_CATALOG_VITRINA;
        $permisos_grid = "administrar_products_catalog";
    }
}


?>
