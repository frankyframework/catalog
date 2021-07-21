<?php
$menucatalog = array(
     array('title'=> "Catalogo",
            'children' =>  array(
   
   
    )
  )
   
);
if(getCoreConfig('catalog/wishlist/enabled') == 1):
    $menucatalog[0]['children'][] = array(
        "permiso" =>   ADMINISTRAR_CATALOG_WISHLIST,
        "url" => $MyRequest->url(ADMIN_CATALOG_WISHLIST),
        "etiqueta" => "Favoritos"
    );
endif;
if(getCoreConfig('catalog/calificaciones/enabled') == 1):
 
  $menucatalog[0]['children'][] = array(
    "permiso" =>   ADMINISTRAR_CATALOG_MIS_CALIFICACIONES,
    "url" => $MyRequest->url(ADMIN_MIS_CALIFICACIONES_CATALOG),
    "etiqueta" => "Mis Calificaciones y comentarios"
  );
endif;

return $menucatalog;
?>
