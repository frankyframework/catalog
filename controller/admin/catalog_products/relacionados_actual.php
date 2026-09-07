<?php
use Franky\Haxor\Tokenizer;
use Catalog\model\CatalogproductrelatedModel;
use Catalog\entity\CatalogproductrelatedEntity;

$Tokenizer = new Tokenizer();
$id		= $MyRequest->getRequest('id');

$callback	= $MyRequest->getRequest('callback');
if(empty($Tokenizer->decode($id)))
{
    $dataRows = ["rows" => [], "total" => 0, "page" => 1,"records" => 0];

    header('Content-Type: application/json; charset=utf-8');
    echo $callback . '(' . json_encode($dataRows). ');';
    die;
}


$alias = ['_id' => "catalog_products.id"];
if(isset($alias[$MyRequest->getRequest('sidx')]))
{
    $sortInput = $alias[$MyRequest->getRequest('sidx')];
}
else{
    $sortInput  = (!empty($MyRequest->getRequest('sidx',"id_product")) ? : "id_product");
}


$CatalogproductrelatedModel =  new CatalogproductrelatedModel();
$CatalogproductrelatedEntity =  new CatalogproductrelatedEntity();


$CatalogproductrelatedModel->setPage($MyRequest->getRequest('page',1));
$CatalogproductrelatedModel->setTampag($MyRequest->getRequest('rows',12));
$CatalogproductrelatedModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));
$CatalogproductrelatedEntity->id_parent($Tokenizer->decode($id));

$CatalogproductrelatedEntity->id_parent($Tokenizer->decode($id));
$result	 		= $CatalogproductrelatedModel->getData($CatalogproductrelatedEntity->getArrayCopy());
$dataRows = ["rows" => [], "total" => ceil($CatalogproductrelatedModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $CatalogproductrelatedModel->getTotal()];


if($CatalogproductrelatedModel->getTotal() > 0)
{

    while($registro = $CatalogproductrelatedModel->getRows())
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
                    if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id_product"].'/'.$foto['img']))
                    {
                        $img = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id_product"].'/'.$foto['img'],50,50, true);
                        $img = makeHTMLImg($img,50,50,$registro['name']);
                    }
                }

            }
        }
    
        $dataRows['rows'][] = array_merge($registro,array(
                "id" => $Tokenizer->token('catalog_products',$registro["id_product"]),
                "_id" => $registro["id_product"],
                "images"     => "<a href=\"".$registro['link']."\" target='_blank'>".$img."</a>",
                "status"     => 'desactivar',
        ));
    }
}

header('Content-Type: application/json; charset=utf-8');
echo $callback . '(' . json_encode($dataRows). ');';
die;
