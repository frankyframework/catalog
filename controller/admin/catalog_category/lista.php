<?php
use Catalog\Form\filtrosForm;
use Catalog\model\CatalogcategoryModel;
use Catalog\entity\CatalogcategoryEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$tiendas = getCatalogStores();
$store_b	= $MyRequest->getRequest('store_b');	
if(empty($store_b)){
        foreach($tiendas as $k => $v)
        {
                $store_b = $k;
                break;
        }
        
}
$CatalogCategoryModel = new CatalogcategoryModel();
$CatalogcategoryEntity = new CatalogcategoryEntity();
$CatalogCategoryModel->setPage(1);
$CatalogCategoryModel->setTampag(1000);
$CatalogCategoryModel->setOrdensql("catalog_category.orden ASC");
$CatalogcategoryEntity->store($store_b);

$result	 = $CatalogCategoryModel->getData($CatalogcategoryEntity->getArrayCopy());



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

$permisos_grid = "administrar_category_catalog";

$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());

$MyFiltrosForm->addStore();
$MyFiltrosForm->addSubmit();


$MyFiltrosForm->setOptionsInput("store_b", $tiendas);
$MyFiltrosForm->setAtributoInput("store_b", "value",$store_b);
?>