<?php
use Developer\Form\filtrosForm;
use Catalog\model\CustomattributesModel;
use Catalog\entity\CustomattributesEntity;
use Franky\Haxor\Tokenizer;

if ($MyRequest->isAjax()) {
        $callback	= $MyRequest->getRequest('callback');
        $filters = $MyRequest->getRequest('filters');
        $dataPost = json_decode(stripslashes($filters),true);
        if(isset($dataPost['rules'])) {
                $dataPost = $dataPost['rules'];
        }
        $requestFranky = [];
        $request = [];
        if(!empty($dataPost)) {
                foreach($dataPost as $data) {
                $request[$data['field']] = $MyRequest->Sanitizacion($data['data']);
                }
        }	
        $sortInput  = (!empty($MyRequest->getRequest('sidx',"catalog_products.id")) ? : "createdAt");

        $CustomattributesModel = new CustomattributesModel();
        $CustomattributesEntity = new CustomattributesEntity($request);
        $Tokenizer = new Tokenizer();
        $CustomattributesEntity->entity('catalog_products');  
        if(getCoreConfig('catalog/marketplace/enabled') == 1 && 
        $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
        {
                $CustomattributesEntity->uid($MySession->getVar('id'));
        }
        $CustomattributesModel->setPage($MyRequest->getRequest('page',1));
        $CustomattributesModel->setTampag($MyRequest->getRequest('rows',12));
        $CustomattributesModel->setOrdensql($sortInput." ".$MyRequest->getRequest('sord',"ASC"));

       
        $result	 = $CustomattributesModel->getData($CustomattributesEntity->getArrayCopy());
        $dataRows = ["rows" => [], "total" => ceil($CustomattributesModel->getTotal() / $MyRequest->getRequest('rows',12)), "page" => (int)$MyRequest->getRequest('page',1),"records" => $CustomattributesModel->getTotal()];

        if($CustomattributesModel->getTotal() > 0)
        {
	

                while($registro = $CustomattributesModel->getRows())
                {
                                $registro = array_filter($registro, function($llave) {
                                return !is_numeric($llave);
                                }, ARRAY_FILTER_USE_KEY);
                        
                                $dataRows['rows'][]  = array_merge($registro,array(
                                "id" => $Tokenizer->token("custom_attributes", $registro["id"]),
                                "callback" => $Tokenizer->token("custom_attributes", $MyRequest->getURI()),    
                                "createdAt" 	=> getFechaUI($registro["createdAt"]),
                            "   status"  => ($registro["status"] == 1 ?"desactivar" : "activar"),
                                ));
                        $iRow++;
                }
        }
        header('Content-Type: application/json; charset=utf-8');
        echo $callback . '(' . json_encode($dataRows). ');';
        die;
} else {

        $MyMetatag->setJs("/plugins/jqGrid/js/jquery.jqGrid.js");
        $MyMetatag->setJs("/plugins/jqGrid/js/i18n/grid.locale-$lang_root.js");
        $MyMetatag->setCSS("/plugins/jqGrid/css/ui.jqgrid.css");

        $frm_constante_link = FRM_CATALOG_CUSTOM_ATTRIBUTES;
        $permisos_grid = "administrar_catalogo_custom_attributes";

        if(getCoreConfig('catalog/marketplace/enabled') == 1 && $MyAccessList->MeDasChancePasar("administrar_catalogo_custom_attributes_marketplace"))
        {
                $frm_constante_link = FRM_CATALOG_CUSTOM_ATTRIBUTES_MARKETPLACE;
                $permisos_grid = "administrar_catalogo_custom_attributes_marketplace";
        }
}

?>