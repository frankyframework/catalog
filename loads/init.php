<?php
use Franky\Core\ObserverManager;
use Catalog\model\CatalogStoresModel;
use Catalog\entity\CatalogStoresEntity;
$ObserverManager = new ObserverManager;
$CatalogStoresModel = new CatalogStoresModel();
$CatalogStoresEntity = new CatalogStoresEntity();

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

$CatalogStoresModel->setTampag(1000);
$CatalogStoresModel->setOrdensql("id ASC");

$CatalogStoresEntity->status(1);
$CatalogStoresModel->getData($CatalogStoresEntity->getArrayCopy());
$total			= $CatalogStoresModel->getTotal();
$stores = array();

if($total > 0)
{
    while($registro = $CatalogStoresModel->getRows())
    {
        if($registro['idioma'] == $_SESSION['lang'] && $registro['url'] == $MyRequest->getSERVER()){
            define('DATA_STORE_CONFIG', $registro); 
        }

    }
}


$MyMetatag->setCss("/modulos/catalog/web/css/catalog.css");
$MyMetatag->setJs("/modulos/catalog/web/js/catalog.js");
$MyMetatag->setJs("/modulos/catalog/web/js/ajax.catalog.js");
?>