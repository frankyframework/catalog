<?php
$menucatalog = array(
     array('title'=> "Catalogo",
            'children' =>  array(
    array(
     "permiso" =>   ADMINISTRAR_PRODUCTS_CATALOG,
     "url" => $MyRequest->url(ADMIN_CATALOG_PRODUCTS),
     "etiqueta" => "Productos"
    ),
    array(
     "permiso" =>   ADMINISTRAR_CATEGORY_CATALOG,
     "url" => $MyRequest->url(ADMIN_CATALOG_CATEGORY),
     "etiqueta" => "Categorías"
    ),
    array(
     "permiso" =>   ADMINISTRAR_PRODUCTS_CATALOG,
     "url" => $MyRequest->url(LISTA_CATALOG_VITRINA),
     "etiqueta" => "Vitrinas"
    ),
    array(
      "permiso" =>   ADMINISTRAR_PRODUCTS_CATALOG,
      "url" => $MyRequest->url(IMPORTAR_CATALOGO),
      "etiqueta" => "Exportar/Importar"
    ),
    array(
      "permiso" =>   ADMINISTRAR_CATALOG_CUSTOM_ATTRIBUTES,
      "url" => $MyRequest->url(ADMIN_CATALOG_CUSTOM_ATTRIBUTES),
      "etiqueta" => _("Atributos customizados")
    ),
    array(
      "permiso" =>   ADMINISTRAR_CATALOG_CUSTOM_ATTRIBUTES,
      "url" => $MyRequest->url(ADMIN_CATALOG_SET_CUSTOM_ATTRIBUTES),
      "etiqueta" => _("Set de atributos")
     )
  )
  )
   
);

if(getCoreConfig('catalog/calificaciones/enabled') == 1):
  if(getCoreConfig('catalog/calificaciones/moderado') == 1):
      
    $menucatalog[0]['children'][] = array(
      "permiso" =>   ADMINISTRAR_CATALOG_CALIFICACIONES_PENDIENTES,
      "url" => $MyRequest->url(ADMIN_CALIFICACIONES_PENDIENTES_CATALOG),
      "etiqueta" => "Calificaciones y comentarios pendientes"
    );
  endif;

  $menucatalog[0]['children'][] = array(
    "permiso" =>   ADMINISTRAR_CATALOG_CALIFICACIONES,
    "url" => $MyRequest->url(ADMIN_CALIFICACIONES_CATALOG),
    "etiqueta" => "Calificaciones y comentarios"
  );


endif;

return $menucatalog;
?>
