<?php
use Catalog\Form\CatalogStoresForm;
use Catalog\model\CatalogStoresModel;
use Catalog\entity\CatalogStoresEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$id         = $Tokenizer->decode($MyRequest->getRequest('id'));
$callback           = $MyRequest->getRequest('callback');
$data             = $MyFlashMessage->getResponse();

if(!empty($id))
{
    $CatalogStoresModel = new CatalogStoresModel();
    $CatalogStoresEntity = new CatalogStoresEntity();
    $CatalogStoresEntity->id($id);
    $result	 = $CatalogStoresModel->getData($CatalogStoresEntity->getArrayCopy());
    $data    = $CatalogStoresModel->getRows();
    $data['id'] = $Tokenizer->token('catalog_stores', $data['id']);;
}

$adminForm = new CatalogStoresForm("frmstores");

$idiomas_disponibles = getCoreConfig('base/theme/langs');
$idiomas = array();
foreach($idiomas_disponibles as $idioma)
{
    $idiomas[$idioma] = $idioma;
}
$adminForm->setOptionsInput("idioma", $idiomas);

$monedas = getMonedas();
$adminForm->setOptionsInput("moneda", $monedas);
$adminForm->setData($data);
$adminForm->setAtributoInput("callback","value", urldecode($callback));
$title_form = _catalog("Tiendas");
