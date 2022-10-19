<?php
define("ADMINISTRAR_STORES_CATALOG",           "administrar_stores_catalog");
$MyAccessList->addRoll(NIVEL_USERADMIN,         ADMINISTRAR_STORES_CATALOG);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_STORES_CATALOG);


define("ADMINISTRAR_CATEGORY_CATALOG",           "administrar_category_catalog");
$MyAccessList->addRoll(NIVEL_USERADMIN,         ADMINISTRAR_CATEGORY_CATALOG);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_CATEGORY_CATALOG);

define("ADMINISTRAR_PRODUCTS_CATALOG",           "administrar_products_catalog");
$MyAccessList->addRoll(NIVEL_USERADMIN,         ADMINISTRAR_PRODUCTS_CATALOG);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_PRODUCTS_CATALOG);


define("ADMINISTRAR_CATALOG_WISHLIST",                         "administrar_catalog_wishlist");
$MyAccessList->addRoll(NIVEL_USERADMIN,                 ADMINISTRAR_CATALOG_WISHLIST);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,             ADMINISTRAR_CATALOG_WISHLIST);
$MyAccessList->addRoll(NIVEL_USERSEO,                   ADMINISTRAR_CATALOG_WISHLIST);
$MyAccessList->addRoll(NIVEL_USERSUSCRIPTOR,            ADMINISTRAR_CATALOG_WISHLIST);

define("ADMINISTRAR_CATALOG_CALIFICACIONES_PENDIENTES",           "administrar_catalog_calificaciones_pendientes");
$MyAccessList->addRoll(NIVEL_USERADMIN,         ADMINISTRAR_CATALOG_CALIFICACIONES_PENDIENTES);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_CATALOG_CALIFICACIONES_PENDIENTES);

define("ADMINISTRAR_CATALOG_CALIFICACIONES",           "administrar_catalog_calificaciones");
$MyAccessList->addRoll(NIVEL_USERADMIN,         ADMINISTRAR_CATALOG_CALIFICACIONES);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,     ADMINISTRAR_CATALOG_CALIFICACIONES);


define("ADMINISTRAR_CATALOG_MIS_CALIFICACIONES",       "administrar_catalog_mis_calificaciones");
$MyAccessList->addRoll(NIVEL_USERADMIN,                 ADMINISTRAR_CATALOG_MIS_CALIFICACIONES);
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,             ADMINISTRAR_CATALOG_MIS_CALIFICACIONES);
$MyAccessList->addRoll(NIVEL_USERSEO,                   ADMINISTRAR_CATALOG_MIS_CALIFICACIONES);
$MyAccessList->addRoll(NIVEL_USERSUSCRIPTOR,            ADMINISTRAR_CATALOG_MIS_CALIFICACIONES);


define("ADMINISTRAR_CATALOG_CUSTOM_ATTRIBUTES",      "administrar_catalogo_custom_attributes");
$MyAccessList->addRoll(NIVEL_USERDEVELOPER,ADMINISTRAR_CATALOG_CUSTOM_ATTRIBUTES);
?>