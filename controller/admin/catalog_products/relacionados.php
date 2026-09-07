<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Franky\Haxor\Tokenizer;
use Catalog\model\CatalogproductrelatedModel;
use Catalog\entity\CatalogproductrelatedEntity;

$Tokenizer = new Tokenizer();
$CatalogproductsModel = new CatalogproductsModel();
$id		= $MyRequest->getRequest('id');
$_callback	= $MyRequest->getRequest('_callback');


$producto_actual = [];
if(!empty($Tokenizer->decode($id)))
{
    $CatalogproductsEntity = new CatalogproductsEntity();
    $CatalogproductsModel->setExcludeId('');
    $CatalogproductsEntity->exchangeArray([]);
    $CatalogproductsEntity->id($Tokenizer->decode($id));
    $CatalogproductsModel->setBusca("");
    if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        $producto_actual = $CatalogproductsModel->getRows();
    }
}

if ($MyRequest->isAjax()) {

    $callback	= $MyRequest->getRequest('callback');
    if(empty($producto_actual)) {

        $dataRows = ["rows" => [], "total" => 0, "page" => 1,"records" => 0];

        header('Content-Type: application/json; charset=utf-8');
        echo $callback . '(' . json_encode($dataRows). ');';
        die;
    }
    $CatalogproductrelatedModel =  new CatalogproductrelatedModel();
    $CatalogproductrelatedEntity =  new CatalogproductrelatedEntity();
    $CatalogproductrelatedEntity->id_parent($Tokenizer->decode($id));
    $CatalogproductrelatedModel->setTampag(10000);
    $relacionados =[];
    if($CatalogproductrelatedModel->getData($CatalogproductrelatedEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        while($registro = $CatalogproductrelatedModel->getRows())
        {
            $relacionados[] = $registro['id_product'];
        }
    }
   
    $filters = $MyRequest->getRequest('filters');
    $dataPost = json_decode(stripslashes($filters),true);
    if(isset($dataPost['rules'])) {
        $dataPost = $dataPost['rules'];
    }
    $request = [];
    if(!empty($dataPost)) {
        foreach($dataPost as $data) {
            $request[$data['field']] = $MyRequest->Sanitizacion($data['data']);
          }
    }


    $alias = ['_id' => "catalog_products.id"];
    if(isset($alias[$MyRequest->getRequest('sidx')]))
    {
        $sortInput = $alias[$MyRequest->getRequest('sidx')];
    }
    else{
        $sortInput  = (!empty($MyRequest->getRequest('sidx',"catalog_products.id")) ? : "catalog_products.id");
    }
    if(isset($request['_id']))
    {
        $request['id'] = $request['_id'];

    }

   
    $CatalogproductsEntity = new CatalogproductsEntity($request);
    

    $tiendas = getCatalogStores();

    if(empty($store)) {
        foreach($tiendas as $k => $v) {
            $store =  $k;
            break;
        }
    }

    $CatalogproductsModel->setExcludeId($Tokenizer->decode($id));
    $CatalogproductsModel->setPage($MyRequest->getRequest('page',1));
    $CatalogproductsModel->setTampag($MyRequest->getRequest('rows',12));
    $CatalogproductsModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));
    $CatalogproductsEntity->status(1);
    $CatalogproductsEntity->store($producto_actual['store']);
    $CatalogproductsEntity->visible_in_search(1);

    if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
    {
        $CatalogproductsEntity->uid($MySession->getVar('id'));
    }
    if(!empty($producto_actual['uid'])) {
        $CatalogproductsEntity->uid($producto_actual['uid']);
    }

    $result	 		= $CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy());
    $dataRows = ["rows" => [], "total" => ceil($CatalogproductsModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $CatalogproductsModel->getTotal()];


    if($CatalogproductsModel->getTotal() > 0)
    {

        while($registro = $CatalogproductsModel->getRows())
        {
            $registro = array_filter($registro, function($llave) {
                return !is_numeric($llave);
            }, ARRAY_FILTER_USE_KEY);
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
        
            $dataRows['rows'][] = array_merge($registro,array(
                    "id" => $Tokenizer->token('catalog_products',$registro["id"]),
                    "_id" => $registro["id"],
                    "images"     => $img,
                    "status"     => in_array($registro['id'],$relacionados) ? 'desactivar':'activar',
            ));
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo $callback . '(' . json_encode($dataRows). ');';
    die;
} else {
   
    if(empty($producto_actual)) {
        $MyRequest->redirect($Tokenizer->decode($_callback));
    }

    $MyMetatag->setJs("/public/plugins/jqGrid/js/jquery.jqGrid.js");
    $MyMetatag->setJs("/public/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
    $MyMetatag->setCSS("/public/plugins/jqGrid/css/ui.jqgrid.css");

    $permisos_grid = "";
    $path = "";
    if($MyAccessList->MeDasChancePasar("administrar_products_catalog"))
    {
        $permisos_grid = "administrar_products_catalog";
        $path = ADMIN_CATALOG_PRODUCTS_RELATED;
    }
    if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
    {
        $permisos_grid = "administrar_products_catalog_marketplace";
        $path = ADMIN_CATALOG_PRODUCTS_RELATED_MARKETPLACE;
    }
  
}
?>





