<?php
use Catalog\Form\CatalogImportForm;
use Franky\Filesystem\File;

$adminForm = new CatalogImportForm('frmimport');
$adminForm->setAtributoInput("my_url_friendly","value", $MyFrankyMonster->MySeccion());


$dir = $MyConfigure->getServerUploadDir()."/catalog/importar/";
$File = new File();
$File->mkdir($dir);




$handle = new \Franky\Filesystem\Upload($_FILES['archivo']);
if ($handle->uploaded)
{


        
        $handle->Process($dir);

        if ($handle->processed)
        {
            

            if ( $xls = SimpleXLS::parse($dir.'/'.$handle->file_dst_name) ) {
                $MySession->SetVar('importar-productos-file',$handle->file_dst_name);

                $atributos_xls = [
                    "name","sku"
                ];

               
            
                $data_productos = [];
          
                foreach($xls->rows() as $key => $val)
                {

                    if($key > 0)
                    {
                        $thisClass  = ((($key % 2) == 0) ? "formFieldDk" : "formFieldLt");
                        foreach($val as $_key => $_val){


                            $data_productos[$key-1][$atributos_xls[$_key]] = htmlentities(utf8_encode($_val)); 
                        }
                        $data_productos[$key-1]['thisClass'] = $thisClass;
                        $data_productos[$key-1]['id'] = $key;
                        $data_productos[$key-1]['sku'] = getFriendly($data_productos[$key-1]['sku']);
                     
                       
                    }

                }
                $titulo_columnas_grid = array("name" =>  "Nombre","sku" => "SKU");
                $value_columnas_grid = array("name","sku");

                $css_columnas_grid = array("name" => "w-xxxx-4", "sku" => "w-xxxx-4");



            } else {
                
                $MyFlashMessage->setMsg("error",SimpleXLS::parseError());
                $error = true;
            }
            
        }
        else
        {
            $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_upload_import_error",$handle->error));
            $error = true;
        }

}
