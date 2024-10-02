<?php
$menucatalog = array(
    array(
      'title'=> "Productos",
      'children' =>  []
    )
);
if(getCoreConfig('catalog/wishlist/enabled') == 1):
    $menucatalog[0]['children'][] = array(
        "permiso" =>   "administrar_catalog_wishlist",
        "url" => $MyRequest->url(ADMIN_CATALOG_WISHLIST),
        "etiqueta" => "Favoritos"
    );
endif;
if(getCoreConfig('catalog/calificaciones/enabled') == 1):
  $menucatalog[0]['children'][] = array(
    "permiso" =>   "administrar_catalog_mis_calificaciones",
    "url" => $MyRequest->url(ADMIN_MIS_CALIFICACIONES_CATALOG),
    "etiqueta" => "Mis Calificaciones y comentarios"
  );
endif;

if(getCoreConfig('catalog/marketplace/enabled') == 1){

  $menucatalog[1] = array(
    'title'=> "Marketplace",
    'children' =>  [
      [
        "permiso" =>   "administrar_products_catalog_marketplace",
        "url" => $MyRequest->url(ADMIN_CATALOG_PRODUCTS_MARKETPLACE),
        "etiqueta" => "Productos"
      ],
      [
         "permiso" =>   "administrar_catalogo_custom_attributes_marketplace",
         "url" => $MyRequest->url(ADMIN_CATALOG_CUSTOM_ATTRIBUTES_MARKETPLACE),
         "etiqueta" => _("Atributos customizados")
      ],
      [
         "permiso" =>   "administrar_catalogo_custom_attributes_marketplace",
         "url" => $MyRequest->url(ADMIN_CATALOG_SET_CUSTOM_ATTRIBUTES_MARKETPLACE),
         "etiqueta" => _("Set de atributos")
      ],
      array(
        "permiso" =>   "administrar_catalog_contactanos_marketplace",
        "url" => $MyRequest->url(CONTACTOS_CATALOG_LIST_MARKETPLACE),
        "etiqueta" => _("Contacto")
       )
    ]
  );


  if(getCoreConfig('catalog/marketplace/moderar-publicaciones') == 1):
    $menucatalog[1]['children'][] = array(
      "permiso" =>   "administrar_products_catalog_marketplace",
      "url" => $MyRequest->url(SOLICITUDES_PUBLICACIONES_CATALOG),
      "etiqueta" => "Mis aprovaciones pendientes"
    );
  endif;
  if(getCoreConfig('catalog/calificaciones/enabled') == 1){
    if(getCoreConfig('catalog/calificaciones/moderado') == 1){
      
      $menucatalog[1]['children'][] = array(
        "permiso" =>   "administrar_catalog_calificaciones_pendientes_marketplace",
        "url" => $MyRequest->url(ADMIN_CALIFICACIONES_PENDIENTES_CATALOG_MARKETPLACE),
        "etiqueta" => "Calificaciones y comentarios pendientes"
      );
    }

    $menucatalog[1]['children'][] = array(
      "permiso" =>   "administrar_catalog_calificaciones_marketplace",
      "url" => $MyRequest->url(ADMIN_CALIFICACIONES_CATALOG_MARKETPLACE),
      "etiqueta" => "Calificaciones y comentarios"
    );


  }
}

return $menucatalog;
?>
