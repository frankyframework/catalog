<?php
use Catalog\Form\CustomAttributesForm;
use Base\model\CustomattributesModel;
use Base\entity\CustomattributesEntity;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();

$id		= $Tokenizer->decode($MyRequest->getRequest('id'));
$callback      = $MyRequest->getRequest('callback');
$data          = $MyFlashMessage->getResponse();
$type_option = "options_attr";
$adminForm = new CustomAttributesForm("frmattributes");

$data_category = [];
$data_subcategory = [];

if(!empty($id))
{
    $CustomattributesModel = new CustomattributesModel();
    $CustomattributesEntity = new CustomattributesEntity();
    $CustomattributesEntity->id($id);
    $result	 = $CustomattributesModel->getData($CustomattributesEntity->getArrayCopy());
    $data           = $CustomattributesModel->getRows();
    $data['data'] = json_decode($data['data'],true);
    $data['required'] = json_decode($data['required'],true);
    $data["extra"] = json_decode($data["extra"],true);
    
    if(!empty($data["extra"]))
    {
        foreach($data["extra"] as $cat => $sub)
        {
            $data_category[] = $cat;
            foreach($sub as $_sub)
            {
                $data_subcategory[] = $_sub;
            }
        }
    }

    $data['id'] = $Tokenizer->token('custom_attributes', $data['id']);
    $type_option = "class_attr";
    if(empty($data['source']))
    {
        $type_option = "options_attr";
    }
    $adminForm->addId();
}


$adminForm->setData($data);
$categorias = getCatalogCategorys('sql',['status' => 1]);
$subcategorias = getCatalogSubcategorys(null,'sql');
$adminForm->setAtributoInput("callback","value", urldecode($callback));
$adminForm->setOptionsInput("category", $categorias);
$adminForm->setOptionsInput("subcategory", $subcategorias);
$adminForm->setAtributoInput("type_option","value", $type_option);

$title_form = "Atributos personalizados";
