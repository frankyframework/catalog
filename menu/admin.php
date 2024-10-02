<?php
$menucatalog = array(
     array('title'=> "Catalogo",
            'children' =>  array(
    array(
     "permiso" =>   "administrar_products_catalog",
     "url" => $MyRequest->url(ADMIN_CATALOG_PRODUCTS),
     "etiqueta" => "Productos"
    ),
    array(
     "permiso" =>   "administrar_category_catalog",
     "url" => $MyRequest->url(ADMIN_CATALOG_CATEGORY),
     "etiqueta" => "Categorías"
    ),
    array(
     "permiso" =>   "administrar_products_catalog",
     "url" => $MyRequest->url(LISTA_CATALOG_VITRINA),
     "etiqueta" => "Vitrinas"
    ),
    array(
      "permiso" =>   "administrar_products_catalog",
      "url" => $MyRequest->url(IMPORTAR_CATALOGO),
      "etiqueta" => "Exportar/Importar"
    ),
    array(
      "permiso" =>   "administrar_catalogo_custom_attributes",
      "url" => $MyRequest->url(ADMIN_CATALOG_CUSTOM_ATTRIBUTES),
      "etiqueta" => _("Atributos customizados")
    ),
    array(
      "permiso" =>   "administrar_catalogo_custom_attributes",
      "url" => $MyRequest->url(ADMIN_CATALOG_SET_CUSTOM_ATTRIBUTES),
      "etiqueta" => _("Set de atributos")
    ),
    array(
      "permiso" =>   "administrar_stores_catalog",
      "url" => $MyRequest->url(ADMIN_LISTA_CATALOG_STORES),
      "etiqueta" => _("Tiendas")
    ),
    array(
      "permiso" =>   "administrar_catalog_contactanos",
      "url" => $MyRequest->url(CONTACTOS_CATALOG_LIST),
      "etiqueta" => _("Contacto")
    )
  )
  )
   
);
if(getCoreConfig('catalog/marketplace/enabled') == 1){

  $menucatalog[1] = array(
    'title'=> "Marketplace",
    'children' =>  [
      array(
        "permiso" =>   "administrar_solicitud_user_marketplace",
        "url" => $MyRequest->url(ADMIN_SOLICITUD_USER_MARKETPLACE),
        "etiqueta" => _("Solicitudes usuario marketplace")
       )
    ]
  );
  if(getCoreConfig('catalog/marketplace/moderar-publicaciones') == 1):
    $menucatalog[1]['children'][] = array(
      "permiso" =>   "moderar_publicaciones_catalog",
      "url" => $MyRequest->url(ADMIN_MODERAR_PUBLICACIONES_CATALOG),
      "etiqueta" => "Moderacion de publicaciones"
    );
  endif;
}

if(getCoreConfig('catalog/calificaciones/enabled') == 1):
  if(getCoreConfig('catalog/calificaciones/moderado') == 1):
      
    $menucatalog[0]['children'][] = array(
      "permiso" =>   "administrar_catalog_calificaciones_pendientes",
      "url" => $MyRequest->url(ADMIN_CALIFICACIONES_PENDIENTES_CATALOG),
      "etiqueta" => "Calificaciones y comentarios pendientes"
    );
  endif;

  $menucatalog[0]['children'][] = array(
    "permiso" =>   "administrar_catalog_calificaciones",
    "url" => $MyRequest->url(ADMIN_CALIFICACIONES_CATALOG),
    "etiqueta" => "Calificaciones y comentarios"
  );


endif;

return $menucatalog;
?>
