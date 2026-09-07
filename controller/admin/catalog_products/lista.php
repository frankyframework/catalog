<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
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
    $CatalogproductsModel = new CatalogproductsModel();
    $CatalogproductsEntity = new CatalogproductsEntity($request);
    $Tokenizer = new Tokenizer();

    if($MyAccessList->MeDasChancePasar("administrar_products_catalog"))
    {
        if(getCoreConfig('catalog/product/showdelete') == 0){
            $CatalogproductsEntity->status(1);
        }
    }
    if(empty($request['store'])) {
        foreach($tiendas as $k => $v) {
            $request['store'] = $k;
            $CatalogproductsEntity->store($request['store']);
            break;
        }
    }

    $CatalogproductsModel->setPage($MyRequest->getRequest('page',1));
    $CatalogproductsModel->setTampag($MyRequest->getRequest('rows',12));
    $CatalogproductsModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));

    if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
    {
            $CatalogproductsEntity->uid($MySession->getVar('id'));
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

            $img = "&nbsp;";
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
            $configurableUrl = ADMIN_CATALOG_PRODUCTS_CONFIGURABLES;
            if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
            {
                $configurableUrl = ADMIN_CATALOG_PRODUCTS_CONFIGURABLES_MARKETPLACE;
            }
            $statusV = "En validacion";
            if($registro["in_validation"] == 0 &&  $registro["validate"] == 0 )
            {
                $statusV = "No autorizado";
            }
            if($registro["in_validation"] == 0 &&  $registro["validate"] == 1 )
            {
                $statusV = "Autorizado";
            }
            $registro['link'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $registro['url_key']]);
            $dataRows['rows'][] = array_merge($registro,array(
                    "store"     => $tiendas[$registro['store']],
                    "store_id"     => $registro['store'],
                    "id" => $Tokenizer->token('catalog_products',$registro["id"]),
                    "_id" => $registro["id"],
                    "callback" => $Tokenizer->token('catalog_products',$MyRequest->getURI()),
                    "status"  => ($registro["status"] == 1 ?"desactivar" : "activar"),
                    "statusv"  => $statusV,
                    "images"     => "<a href=\"".$registro['link']."\" target='_blank'>".$img."</a>",
                    "name"     => "<a href=\"".$registro['link']."\" target='_blank'>".$registro['name']."</a>",
                    "type"     => ($registro['type'] == 'configurable' ? '<a href="'.$MyRequest->link($configurableUrl."?id=".$Tokenizer->token('catalog_products',$registro["id"])).'&amp;callback='.$Tokenizer->token('catalog_products',$MyRequest->getURI()).'&amp;store='.$registro['store'].'">'.$registro['type'].'</a>' : $registro['type'])
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
    $link_related="";
    $permisos_grid = "";
    if($MyAccessList->MeDasChancePasar("administrar_products_catalog"))
    {
        $frm_constante_link = FRM_CATALOG_PRODUCTS;
        $link_related=ADMIN_CATALOG_PRODUCTS_RELATED;
        $permisos_grid = "administrar_products_catalog";
    }
    if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_products_catalog_marketplace"))
    {
        $frm_constante_link = FRM_CATALOG_PRODUCTS_MARKETPLACE;
        $link_related=ADMIN_CATALOG_PRODUCTS_RELATED_MARKETPLACE;
        $permisos_grid = "administrar_products_catalog_marketplace";
    }
  
}

   
   
?>
