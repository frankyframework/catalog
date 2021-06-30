<?php
use Franky\Core\validaciones;
use Catalog\model\CatalogvitrinaModel;
use Catalog\entity\CatalogvitrinaEntity;
use Catalog\entity\CatalogsubcategoryproductEntity;
use Catalog\model\CatalogsubcategoryproductModel;
use Franky\Haxor\Tokenizer;

$Tokenizer = new Tokenizer();
$CatalogsubcategoryproductEntity    = new CatalogsubcategoryproductEntity();
$CatalogsubcategoryproductModel     = new CatalogsubcategoryproductModel();
$CatalogvitrinaModel               = new CatalogvitrinaModel();
$CatalogvitrinaEntity              = new CatalogvitrinaEntity($MyRequest->getRequest());


$callback = $Tokenizer->decode($MyRequest->getRequest('callback'));
$CatalogvitrinaEntity->id($Tokenizer->decode($MyRequest->getRequest('id')));
$id = $CatalogvitrinaEntity->id();
$category  = $MyRequest->getRequest('category');
$subcategory  = $MyRequest->getRequest('subcategory');

$random  = $MyRequest->getRequest('random');
$error = false;

if(empty($random))
{
    $CatalogvitrinaEntity->random(0);
}

$CatalogvitrinaEntity->clave(getFriendly($CatalogvitrinaEntity->nombre()));

$album = $MySession->GetVar('addProduct');

$validaciones =  new validaciones();


 $valid = $validaciones->validRules($CatalogvitrinaEntity->setValidation());


if(!$valid)
{
    $MyFlashMessage->setMsg("error",$validaciones->getMsg());
    $error = true;
}


if(!$MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("sin_privilegios"));
    $error = true;
}

if($CatalogvitrinaModel->existeClave($CatalogvitrinaEntity->clave(),$CatalogvitrinaEntity->id()) == REGISTRO_SUCCESS)
{
    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_vitrina_duplicada"));
    $error = true;
}

if(!$error)
{
    $subcategorias = getCatalogSubcategorys(null,'sql');
    $category_subcategory = [];
    foreach($subcategorias as $cat => $subcat)
    {
        if(in_array($cat,$category))
        {
            $category_subcategory['category'][$cat] = array(); 
            foreach($subcat as $id_sub => $label)
            {
                if(in_array($id_sub,$subcategory))
                {
                    $category_subcategory['category'][$cat][] = $id_sub; 
                }
            }
        }
        
    }
    if($MySession->GetVar('vitrina'))
    {
        $category_subcategory['productos'] = $MySession->GetVar('vitrina');
    }
    
    $CatalogvitrinaEntity->items(json_encode($category_subcategory));


    if(empty($id))
    {

        $CatalogvitrinaEntity->createdAt(date('Y-m-d H:i:s'));
        $CatalogvitrinaEntity->status(1);
    }
    else
    {
        $CatalogvitrinaEntity->updateAt(date('Y-m-d H:i:s'));
    }
    $result = $CatalogvitrinaModel->save($CatalogvitrinaEntity->getArrayCopy());
    
   
    if($result == REGISTRO_SUCCESS)
    {

        
        if(empty($id))
        {
         
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("guardar_generico_success"));
        }
        else
        {
            
            $MyFlashMessage->setMsg("success",$MyMessageAlert->Message("editar_generico_success"));
        }
        $location = (!empty($callback) ? ($callback) : $MyRequest->url(LISTA_CATALOG_VITRINA));
        

        $MySession->UnsetVar('vitrina');


    }
    elseif($result == REGISTRO_ERROR)
    {

        if(empty($id))
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("guardar_generico_error"));
        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("editar_generico_error"));
        }
        $location = $MyRequest->getReferer();
    }
    else
    {
        $MyFlashMessage->setMsg("error",$result);
        $location = $MyRequest->getReferer();
    }
}
else
{
    $location = $MyRequest->getReferer();
}


$MyRequest->redirect($location);
?>
