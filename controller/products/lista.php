<?php
$categorias = array_keys(getCatalogCategorys());
if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_CATEGORY)
{
    $categoria      = $MyRequest->getUrlParam('friendly');
    
  
    if(in_array($categoria, $categorias))
    {
       
        include(PROJECT_DIR.'/modulos/catalog/controller/products/_lista.php');
    }
    else{
         
        include(PROJECT_DIR.'/modulos/catalog/controller/products/view.php');
    }
    
}
if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_SUBCATEGORY)
{
   
    $categoria      = $MyRequest->getUrlParam('categoria');
    $subcategorias = array_keys(getCatalogSubcategorys($categoria));
    $subcategoria      = $MyRequest->getUrlParam('friendly');

    if(in_array($subcategoria, $subcategorias))
    {
        include(PROJECT_DIR.'/modulos/catalog/controller/products/_lista.php');
    }
    else{
        include(PROJECT_DIR.'/modulos/catalog/controller/products/view.php');
    }
    
}