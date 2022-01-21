<?php
use Catalog\model\CatalogcategoryModel;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
		
$busca_b	= $MyRequest->getRequest('busca_b');	

$CatalogCategoryModel = new CatalogcategoryModel();

$CatalogCategoryModel->setPage(1);
$CatalogCategoryModel->setTampag(1000);
$CatalogCategoryModel->setOrdensql("catalog_category.orden ASC");

$CatalogCategoryModel->setBusca($busca_b);
$result	 = $CatalogCategoryModel->getData([]);

$lista_admin_data = array();
if($CatalogCategoryModel->getTotal() > 0)
{
	$iRow = 0;	

	while($registro = $CatalogCategoryModel->getRows())
	{
                $img = "";
        
                if(!empty($registro["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$registro["image"]))
                {
                    $img = imageResize($MyConfigure->getUploadDir()."/catalog/category/".$registro["image"],50,50, true);
                    $img = makeHTMLImg($img,50,50,$registro['name']);
                }

                $parent_id = (!empty($registro['parent_id']) ? $Tokenizer->token("category", $registro['parent_id']) : 0);
		$lista_admin_data['cat_'.$parent_id][] = array_merge($registro,array(
                "id_category"=>$registro["id"],
                "id" => $Tokenizer->token("category", $registro["id"]),
                "createdAt" 	=> getFechaUI($registro["createdAt"]),
                "image"     => $img
                ));
                $iRow++;
        }
}



$ordenfunction = "catalog_setOrdenCategoria";

$permisos_grid = ADMINISTRAR_CATEGORY_CATALOG;
?>