<?php
use Franky\Core\ObserverManager;
$ObserverManager = new ObserverManager;

include 'lca.php';
include 'util.php';
__bindtextdomain("catalog", "catalog");


if (function_exists('bind_textdomain_codeset'))
{
    bind_textdomain_codeset("catalog", 'UTF-8');
}

$modulos = getModulos();
if(in_array('ecommerce',$modulos))
{
    $ObserverManager->addObserver('save_catalog_product','catalog_setPriceEcommerce');
    $ObserverManager->addObserver('edit_catalog_product','catalog_setPriceEcommerce');
    $ObserverManager->addObserver('prepara_producto_carrito','catalog_validaStockCarrito');
    $ObserverManager->addObserver('prepara_orden_ajax_ecommerce','catalog_validaStockCompra');
    $ObserverManager->addObserver('prepara_orden_ecommerce','catalog_validaStockCompras');
    $ObserverManager->addObserver('finalizar_orden_ecommerce','catalog_restaStock');
    $ObserverManager->addObserver('change_status_pago','catalog_addStock');
    
    
}

$MyMetatag->setCss("/modulos/catalog/web/css/catalog.css");
$MyMetatag->setJs("/modulos/catalog/web/js/catalog.js");
$MyMetatag->setJs("/modulos/catalog/web/js/ajax.catalog.js");
?>
