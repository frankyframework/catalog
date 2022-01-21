<?php
$categorias = getCatalogCategorys();
$_categorias = [];
foreach($categorias as $parent => $cat)
{
    foreach($cat as $key => $_cat)
    {
        $_categorias[] = $_cat['url_key'];
    }
}

if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_DEPARTAMENTO)
{
    $departamento      = $MyRequest->getUrlParam('departamento');
    
  
    if(in_array($departamento, $_categorias))
    {
       
        include(PROJECT_DIR.'/modulos/catalog/controller/products/_lista.php');
    }
    else{
         
        include(PROJECT_DIR.'/modulos/catalog/controller/products/view.php');
    }
    
}
if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_CATEGORY)
{
    $categoria      = $MyRequest->getUrlParam('categoria');
    
 
    if(in_array($categoria, $_categorias))
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
    $subcategoria      = $MyRequest->getUrlParam('subcategoria');

    if(in_array($subcategoria, $_categorias))
    {
        include(PROJECT_DIR.'/modulos/catalog/controller/products/_lista.php');
    }
    else{
        include(PROJECT_DIR.'/modulos/catalog/controller/products/view.php');
    }
    
}