<?php
use Catalog\model\CatalogproductsModel;
use Catalog\entity\CatalogproductsEntity;
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$id	= $MyRequest->getRequest('id');
$relacionados =[];
if(!empty($Tokenizer->decode($id)))
{
    $CatalogvitrinaModel = new CatalogvitrinaModel();
    $CatalogvitrinaEntity = new CatalogvitrinaEntity();

    $CatalogvitrinaEntity->id($Tokenizer->decode($id));
    $CatalogvitrinaModel->setTampag(10000);

    if($CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
        while($registro = $CatalogvitrinaModel->getRows())
        {
            $items = json_decode($registro['items'],true);
            $relacionados = isset($items['productos']) ? $items['productos'] : [];
        }
    }
}

$callback	= $MyRequest->getRequest('callback');
$vitrina = $MySession->GetVar('vitrina');
$relacionados = array_merge($relacionados,$vitrina);

$MySession->SetVar('productsVitrina', $relacionados);
if(empty($relacionados)) {
    $dataRows = ["rows" => [], "total" => 0, "page" => 1,"records" => 0];

    header('Content-Type: application/json; charset=utf-8');
    echo $callback . '(' . json_encode($dataRows). ');';
    die;
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

$tiendas = getCatalogStores();

$CatalogproductsModel = new CatalogproductsModel();
$CatalogproductsEntity = new CatalogproductsEntity($request);


$CatalogproductsModel->setPage($MyRequest->getRequest('page',1));
$CatalogproductsModel->setTampag($MyRequest->getRequest('rows',12));
$CatalogproductsModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));
$CatalogproductsEntity->status(1);
$CatalogproductsEntity->visible_in_search(1);
$CatalogproductsModel->setsearchIds($relacionados);
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
    
        $dataRows['rows'][] =array_merge($registro,array(
            "id" => $Tokenizer->token('catalog_products',$registro["id"]),
            "_id" => $registro["id"],
            "store"     => $tiendas[$registro['store']],
            "store_id"     => $registro['store'],
            "images"     => $img,
            "status"     => 'desactivar'
        ));

    }
}


header('Content-Type: application/json; charset=utf-8');
echo $callback . '(' . json_encode($dataRows). ');';
die;
