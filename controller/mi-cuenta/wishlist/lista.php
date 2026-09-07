<?php
use Wishlist\model\WishlistModel;
use Wishlist\entity\WishlistEntity;
use Franky\Haxor\Tokenizer;

if ($MyRequest->isAjax()) {

    $campo_item = "name";
    $campo_item_id = "id";
    $campo_item_urlkey = getCoreConfig('catalog/product/urlkey');
    $campo_item_image = "images";
    $tabla = "catalog_products";
    $seccion = "products";
    $urlView = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' =>  "{campo_item_urlkey}"]);
    $pathImage = $MyConfigure->getServerUploadDir()."/catalog/products/{campo_item_id}/{campo_item_image}";
    $thumbW = 100;
    $thumbH = 100;

    $callback	= $MyRequest->getRequest('callback');
    $filters = $MyRequest->getRequest('filters');
    $dataPost = json_decode(stripslashes($filters),true);
    $dataPost = $dataPost['rules'];
    $request = [];
    foreach($dataPost as $data) {
        $request[$data['field']] = $MyRequest->Sanitizacion($data['data']);
    }

    $rango = [];

    if(isset($request['createdAt']) && !empty($request['createdAt']))
    {
        $rango = [$request['createdAt'],$request['createdAt']];
    }


    $Tokenizer = new Tokenizer();
    $WishlistModel = new WishlistModel();
    $WishlistEntity = new WishlistEntity($request);
    $WishlistModel->setRango($rango);
    $sortInput  = (!empty($MyRequest->getRequest('sidx',"wishlist.createdAt")) ? : "wishlist.createdAt");
  
    $WishlistEntity->tabla($tabla);
    $WishlistModel->setCampoItem($campo_item);
    $WishlistModel->setTablaItem($tabla);
    $WishlistModel->setCampoItemId($campo_item_id);
    if (isset($campo_item_urlkey) && !empty($campo_item_urlkey) ) {
        $WishlistModel->setCampoItemUrl($campo_item_urlkey);
    }     
    if (isset($campo_item_image) && !empty($campo_item_image) ) {
        $WishlistModel->setCampoItemImage($campo_item_image);
    }  

    $WishlistModel->setPage($MyRequest->getRequest('page',1));
    $WishlistModel->setTampag($MyRequest->getRequest('rows',12));
    $WishlistModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));

    $WishlistEntity->uid($MySession->GetVar('id'));
    $result	 = $WishlistModel->getFullData($WishlistEntity->getArrayCopy());
  
    $dataRows = ["rows" => [], "total" => ceil($WishlistModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $WishlistModel->getTotal()];
 
    if($WishlistModel->getTotal() > 0)
    {
        while($registro = $WishlistModel->getRows())
        {
            $registro = array_filter($registro, function($llave) {
                return !is_numeric($llave);
            }, ARRAY_FILTER_USE_KEY);
        
            if (isset($campo_item_urlkey) && !empty($campo_item_urlkey) && isset($urlView)) {
                $registro["item"] = '<a href="'.str_replace("{campo_item_urlkey}",$registro["item_url_key"],$urlView).'" target="_blank">'.$registro["item"]."</a>" ;
            }    
            if (isset($campo_item_image) && !empty($campo_item_image) && isset($pathImage)) {
            
                $images = json_decode($registro["item_image"],true);
                $img = "";
                $_img = getCoreConfig('catalog/product/placeholder');
                if($_img != "" && file_exists(PROJECT_DIR.$_img))
                {
                $registro['item_image'] = makeHTMLImg(imageResize($_img,500,500, false),100,100,strip_tags($registro["item"]));
                }
                
    
                if(!empty($images))
                {
                    foreach($images as $foto)
                    {
    
                        if($foto['principal'] == 1)
                        {
                            if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id_item"].'/'.$foto['img']))
                            {
    
                                $registro['item_image'] = makeHTMLImg(imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id_item"].'/'.$foto['img'],500,500, true),100,100,strip_tags($registro["item"]));
    
                            }
                        }
    
                    }
                }

            }     

            $dataRows['rows'][] =  array_merge($registro,array(
            "id" => $Tokenizer->token("whishist", $registro["id"]),
            "createdAt"         => getFechaUI($registro["createdAt"]),
            "stado"  =>  "desactivar"
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
    $MyFrankyMonster->setPHPFile(PROJECT_DIR."/modulos/wishlist/diseno/admin/wishlist/lista.phtml");

}


?>
