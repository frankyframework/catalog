<?php
use Base\Form\filtrosForm;
use Catalog\model\CatalogcategoryModel;
use Franky\Core\paginacion;
use Franky\Haxor\Tokenizer;

$MyPaginacion = new paginacion();
$Tokenizer = new Tokenizer();
		
$busca_b	= $MyRequest->getRequest('busca_b');	

$CatalogCategoryModel = new CatalogcategoryModel();

$CatalogCategoryModel->setPage(1);
$CatalogCategoryModel->setTampag(1000);
$CatalogCategoryModel->setOrdensql("catalog_category.orden ASC");

$CatalogCategoryModel->setBusca($busca_b);
$result	 = $CatalogCategoryModel->getData([]);
$MyPaginacion->setTotal($CatalogCategoryModel->getTotal());

$lista_admin_data = array();
if($CatalogCategoryModel->getTotal() > 0)
{
	$iRow = 0;	

	while($registro = $CatalogCategoryModel->getRows())
	{
		$thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");
                $img = "";
                if(!empty($registro["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$registro["image"]))
                {
                    $img = imageResize($MyConfigure->getUploadDir()."/catalog/category/".$registro["image"],50,50, true);
                    $img = makeHTMLImg($img,50,50,$registro['name']);
                }


		$lista_admin_data[] = array_merge($registro,array(
                "id_category"=>$registro["id"],
                "id" => $Tokenizer->token("category", $registro["id"]),
                "callback" => $Tokenizer->token("category", $MyRequest->getURI()),    
                "createdAt" 	=> getFechaUI($registro["createdAt"]),
                "thisClass"     => $thisClass,
                "image"     => $img,
                "nuevo_estado"  => ($registro["status"] == 1 ?"desactivar" : "activar"),
                ));
                $iRow++;
        }
}



$MyFrankyMonster->setPHPFile(getVista("admin/template/grid.orden.phtml"));
$ordenfunction = "catalog_setOrdenCategoria";
$title_grid = "Categorias";
$class_grid = "cont_categorias_catalog";
$error_grid = "No hay categorias registradas";
$deleteFunction = "DeleteCatalogCategory";
$frm_constante_link = FRM_CATALOG_CATEGORY;
$titulo_columnas_grid = array("id_category" => "ID","createdAt" => "Fecha","name" => "Nombre","image" => "Imagen");
$value_columnas_grid = array("id_category","createdAt", "name","image" );

$css_columnas_grid = array("id_category" => "w-xxxx-1","createdAt" => "w-xxxx-3" ,"name" => "w-xxxx-3" ,"image" => "w-xxxx-3" );

$permisos_grid = ADMINISTRAR_CATEGORY_CATALOG;
$MyFiltrosForm = new filtrosForm('paginar');
$MyFiltrosForm->setMobile($Mobile_detect->isMobile());
$MyFiltrosForm->addBusca();
$MyFiltrosForm->addSubmit();

$MyFiltrosForm->setAtributoInput("busca_b", "value",$busca_b);
?>