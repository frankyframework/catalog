<?php
function DeleteCatalogCategory($id,$status)
{
    global $MySession;
    $CatalogcategoryModel =  new \Catalog\model\CatalogcategoryModel();
    $CatalogcategoryEntity =  new \Catalog\entity\CatalogcategoryEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_CATEGORY_CATALOG))
    {
        $CatalogcategoryEntity->id(addslashes($Tokenizer->decode($id)));

        if($CatalogcategoryModel->eliminar($CatalogcategoryEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("catalog_category_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function DeleteCatalogProduct($id,$status)
{
    global $MySession;
    $CatalogproductsModel =  new \Catalog\model\CatalogproductsModel();
    $CatalogproductsEntity =  new \Catalog\entity\CatalogproductsEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductsEntity->id(addslashes($Tokenizer->decode($id)));
        $CatalogproductsEntity->status($status);

        if($CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("catalog_products_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}



function setOrdenImagesProducts($album, $orden)
{
	
	
        global $MyAccessList;
        global $MyMessageAlert;
        $respuesta =null;
        if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
        {
           
        
            $orden = explode(",",str_replace("foto_","",$orden));

            $v = "";
            $new_order = [];
          
            if(isset($_SESSION['album_'.$album]) && !empty($_SESSION['album_'.$album]))
            {
        
                
            
                foreach($orden as $key => $val)
                {
                    $v .= ($key)." -> $val,";
                    
                    foreach($_SESSION['album_'.$album]  as $foto)
                    {
                        if(md5($foto['img']) == $val)
                        {
                            $new_order[$key] = $foto;
                        }
                    
                    }
                }
               
                $_SESSION['album_'.$album] = $new_order;
            }
        }
        else
        {
             $respuesta[] = array("message" => $MyMessageAlert->Message("sin_privilegios"));
        }
	
	return $respuesta;
}



function eliminarFotoCatalogProduct($token,$status)
{

        global $MySession;
        global $MyConfigure;
        global $MyMessageAlert;
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $respuesta =null;

        $album = $MySession->GetVar('addProduct');
        foreach($_SESSION['album_'.$album] as $k => $image)
        {
            if($image['token'] == $token)
            {
              $id = $k;
              $img = $image['img'];
            }
        }

        $dir = $MyConfigure->getServerUploadDir()."/catalog/products/".$album."/$img";
        if(file_exists($dir))
        {
            //unlink($dir);

            //if(!file_exists($dir))
            //{
              unset($_SESSION['album_'.$album][$id]);
              $respuesta[] = array("message" => "success","id" => $token);
            //}

        }
        else{
            $respuesta[] = array("message" =>  $MyMessageAlert->Message("eliminar_generico_error"));
        }

	return $respuesta;
}

function ajax_products_agregarProductoRelacionadoVitrina($id_parent,$id){
    
    global $MyAccessList;
    global $MyMessageAlert;
    global $MySession;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        
        $vitrina = $MySession->GetVar('vitrina');
        if(!in_array($Tokenizer->decode($id), $vitrina))
        {
            $vitrina[] = $Tokenizer->decode($id);
        }
        $MySession->SetVar('vitrina',$vitrina);
        
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}


function ajax_products_quitarProductoRelacionadoVitrina($id_parent,$id){
    
    global $MyAccessList;
    global $MyMessageAlert;
    global $MySession;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
       $Tokenizer = new \Franky\Haxor\Tokenizer;
       
        $vitrina = $MySession->GetVar('vitrina');
        foreach($vitrina as $k => $v){
            if($v ==  $Tokenizer->decode($id))
            {
                unset($vitrina[$k]);
            }
        }
       
        $MySession->SetVar('vitrina',$vitrina);
        

    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}

function ajax_products_cargarProductosRelacionadosVitrina($id)
{
    global $MyAccessList;
    global $MyMessageAlert;
    global $MyConfigure;
    global $MySession;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        
        
        $vitrina = $MySession->GetVar('vitrina');
        if($vitrina === false){
            
            $CatalogvitrinaModel = new \Catalog\model\CatalogvitrinaModel;
            $CatalogvitrinaEntity = new \Catalog\entity\CatalogvitrinaEntity;
            
            $CatalogvitrinaEntity->id($Tokenizer->decode($id));
            $CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy());

            $data = $CatalogvitrinaModel->getRows();

            $data["items"] = json_decode($data["items"],true);
    
            $vitrina = $data['items']['productos'];
            $MySession->SetVar('vitrina',$vitrina);
        }
        
        
        $lista_admin_data =[];
        if(!empty($vitrina))
        {
            $CatalogproductsModel = new Catalog\model\CatalogproductsModel;
            $CatalogproductsEntity = new Catalog\entity\CatalogproductsEntity;
            $CatalogproductsModel->setsearchIds($vitrina);
            $CatalogproductsModel->setTampag(10000);
            if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
            {
                $iRow = 0;
                while($registro = $CatalogproductsModel->getRows())
                {
                    $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");


                    $img = "";
                    $_img = getCoreConfig('catalog/product/placeholder');
                    if($_img != "" && file_exists(PROJECT_DIR.$_img))
                    {
                        $img = makeHTMLImg(imageResize($_img,50,50, true),50,50,$registro['name']);
                    }
                    $registro["images"] = json_decode($registro["images"],true);
                    if(!empty($registro['images']))
                    {
                        foreach($registro["images"] as $foto)
                        {
                            if($foto['principal'] == 1)
                            {
                                if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img']))
                                {
                                    $img = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img'],50,50, true);
                                    $img = makeHTMLImg($img,50,50,$registro['name']);
                                }
                            }

                        }
                    }

                    $lista_admin_data[$iRow] = array_merge($registro,array(
                            "thisClass"     => $thisClass,
                            "id" => $Tokenizer->token('catalog_products',$registro["id"]),
                            "_id" => $registro["id"],
                            "images"     => $img,
                    ));


                    $iRow++;
                }

            }
        }
        $respuesta['lista_admin_data_relacionados'] = ($lista_admin_data);
        $titulo_columnas_grid = array("_id" => "ID","images" => "Thumb", "name" =>  "Nombre","sku" => "SKU");
        $value_columnas_grid = array("_id" ,"images", "name","sku");

        $css_columnas_grid = array("_id" => "w-xxxx-2" ,"images" => "w-xxxx-2" , "name" => "w-xxxx-4", "sku" => "w-xxxx-2");


       

        $respuesta['html'] = render(PROJECT_DIR.'/modulos/catalog/diseno/admin/catalog_vitrinas/widget.relacionados.phtml',
                ['titulo_columnas_grid' =>$titulo_columnas_grid,
                 'value_columnas_grid' => $value_columnas_grid,
                 'css_columnas_grid' => $css_columnas_grid
                ]
        );
        
        
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}


function ajax_products_agregarProductoRelacionado($id_parent,$id){
    
    global $MyAccessList;
    global $MyMessageAlert;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductrelatedModel =  new \Catalog\model\CatalogproductrelatedModel();
        $CatalogproductrelatedEntity =  new \Catalog\entity\CatalogproductrelatedEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $CatalogproductrelatedEntity->id_parent($Tokenizer->decode($id_parent));
        $CatalogproductrelatedEntity->id_product($Tokenizer->decode($id));
        
        
        if($CatalogproductrelatedModel->save($CatalogproductrelatedEntity->getArrayCopy()) != REGISTRO_SUCCESS)
        {
            $respuesta["error"] = true;
            $respuesta["message"] = $MyMessageAlert->Message("catalog_products_error_relacionar");
        }
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}


function ajax_products_quitarProductoRelacionado($id_parent,$id){
    
    global $MyAccessList;
    global $MyMessageAlert;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductrelatedModel =  new \Catalog\model\CatalogproductrelatedModel();
        $CatalogproductrelatedEntity =  new \Catalog\entity\CatalogproductrelatedEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $CatalogproductrelatedEntity->id_parent($Tokenizer->decode($id_parent));
        $CatalogproductrelatedEntity->id_product($Tokenizer->decode($id));
        
        
        if($CatalogproductrelatedModel->eliminar($CatalogproductrelatedEntity->getArrayCopy()) != REGISTRO_SUCCESS)
        {
            $respuesta["error"] = true;
            $respuesta["message"] = $MyMessageAlert->Message("catalog_products_error_relacionar_eliminar");
        }
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}

function ajax_products_cargarProductosRelacionados($id)
{
    global $MyAccessList;
    global $MyMessageAlert;
    global $MyConfigure;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductrelatedModel =  new \Catalog\model\CatalogproductrelatedModel();
        $CatalogproductrelatedEntity =  new \Catalog\entity\CatalogproductrelatedEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $CatalogproductrelatedEntity->id_parent($Tokenizer->decode($id));
        $CatalogproductrelatedModel->setTampag(10000);
        $lista_admin_data =[];
        if($CatalogproductrelatedModel->getData($CatalogproductrelatedEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            $iRow = 0;
            while($registro = $CatalogproductrelatedModel->getRows())
            {
                $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");


                $img = "";
                $_img = getCoreConfig('catalog/product/placeholder');
                if($_img != "" && file_exists(PROJECT_DIR.$_img))
                {
                    $img = makeHTMLImg(imageResize($_img,50,50, true),50,50,$registro['name']);
                }
                $registro["images"] = json_decode($registro["images"],true);
                if(!empty($registro['images']))
                {
                    foreach($registro["images"] as $foto)
                    {
                        if($foto['principal'] == 1)
                        {
                            if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id_product"].'/'.$foto['img']))
                            {
                                $img = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id_product"].'/'.$foto['img'],50,50, true);
                                $img = makeHTMLImg($img,50,50,$registro['name']);
                            }
                        }

                    }
                }

                $lista_admin_data[$iRow] = array_merge($registro,array(
                        "thisClass"     => $thisClass,
                        "id" => $Tokenizer->token('catalog_products',$registro["id_product"]),
                        "_id" => $registro["id_product"],
                        "images"     => $img,
                ));


                $iRow++;
            }
            
        }
        $respuesta['lista_admin_data_relacionados'] = ($lista_admin_data);
        $titulo_columnas_grid = array("_id" => "ID","images" => "Thumb", "name" =>  "Nombre","sku" => "SKU");
        $value_columnas_grid = array("_id" ,"images", "name","sku");

        $css_columnas_grid = array("_id" => "w-xxxx-2" ,"images" => "w-xxxx-2" , "name" => "w-xxxx-4", "sku" => "w-xxxx-2");


       

        $respuesta['html'] = render(PROJECT_DIR.'/modulos/catalog/diseno/admin/catalog_products/widget.relacionados.phtml',
                ['titulo_columnas_grid' =>$titulo_columnas_grid,
                 'value_columnas_grid' => $value_columnas_grid,
                 'css_columnas_grid' => $css_columnas_grid
                ]
        );
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}


function DeleteCatalogVitrina($id,$status)
{
    global $MySession;
    $CatalogvitrinaModel = new \Catalog\model\CatalogvitrinaModel;
    $CatalogvitrinaEntity = new \Catalog\entity\CatalogvitrinaEntity;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_CATEGORY_CATALOG))
    {
        $CatalogvitrinaEntity->id(addslashes($Tokenizer->decode($id)));
        $CatalogvitrinaEntity->status($status);

        if($CatalogvitrinaModel->save($CatalogvitrinaEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("catalog_vitrina_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}


function ajax_products_agregarProductoConfigurable($id_parent,$id){
    
    global $MyAccessList;
    global $MyMessageAlert;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductsModel =  new \Catalog\model\CatalogproductsModel();
        $CatalogproductsEntity =  new \Catalog\entity\CatalogproductsEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $CatalogproductsEntity->parent_id($Tokenizer->decode($id_parent));
        $CatalogproductsEntity->visible_in_search(0);
        $CatalogproductsEntity->id($Tokenizer->decode($id));
        
        
        if($CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy()) != REGISTRO_SUCCESS)
        {
            $respuesta["error"] = true;
            $respuesta["message"] = $MyMessageAlert->Message("catalog_products_error_configurar");
        }
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}




function ajax_products_quitarProductoConfigurable($id_parent,$id){
    
    global $MyAccessList;
    global $MyMessageAlert;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductsModel =  new \Catalog\model\CatalogproductsModel();
        $CatalogproductsEntity =  new \Catalog\entity\CatalogproductsEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $CatalogproductsEntity->parent_id(0);
        $CatalogproductsEntity->id($Tokenizer->decode($id));
        $CatalogproductsEntity->visible_in_search(1);
        
        if($CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy()) != REGISTRO_SUCCESS)
        {
            $respuesta["error"] = true;
            $respuesta["message"] = $MyMessageAlert->Message("catalog_products_error_configurar_eliminar");
        }
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}

function ajax_products_cargarProductosConfigurables($id)
{
    global $MyAccessList;
    global $MyMessageAlert;
    global $MyConfigure;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductsModel =  new \Catalog\model\CatalogproductsModel();
        $CatalogproductsEntity =  new \Catalog\entity\CatalogproductsEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
        $CatalogproductsEntity->parent_id($Tokenizer->decode($id));
        $CatalogproductsModel->setTampag(10000);
        $lista_admin_data =[];
        if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            $iRow = 0;
            while($registro = $CatalogproductsModel->getRows())
            {
                $thisClass  = ((($iRow % 2) == 0) ? "formFieldDk" : "formFieldLt");


                $img = "";
                $_img = getCoreConfig('catalog/product/placeholder');
                if($_img != "" && file_exists(PROJECT_DIR.$_img))
                {
                    $img = makeHTMLImg(imageResize($_img,50,50, true),50,50,$registro['name']);
                }
                $registro["images"] = json_decode($registro["images"],true);
                if(!empty($registro['images']))
                {
                    foreach($registro["images"] as $foto)
                    {
                        if($foto['principal'] == 1)
                        {
                            if(!empty($foto["img"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/products/".$registro["id_product"].'/'.$foto['img']))
                            {
                                $img = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id_product"].'/'.$foto['img'],50,50, true);
                                $img = makeHTMLImg($img,50,50,$registro['name']);
                            }
                        }

                    }
                }

                $lista_admin_data[$iRow] = array_merge($registro,array(
                        "thisClass"     => $thisClass,
                        "id" => $Tokenizer->token('catalog_products',$registro["id"]),
                        "_id" => $registro["id"],
                        "images"     => $img,
                ));


                $iRow++;
            }
            
        }
        $respuesta['lista_admin_data_configurables'] = ($lista_admin_data);
        $titulo_columnas_grid = array("_id" => "ID","images" => "Thumb", "name" =>  "Nombre","sku" => "SKU");
        $value_columnas_grid = array("_id" ,"images", "name","sku");

        $css_columnas_grid = array("_id" => "w-xxxx-2" ,"images" => "w-xxxx-2" , "name" => "w-xxxx-4", "sku" => "w-xxxx-2");


       

        $respuesta['html'] = render(PROJECT_DIR.'/modulos/catalog/diseno/admin/catalog_products/widget.configurables.phtml',
                ['titulo_columnas_grid' =>$titulo_columnas_grid,
                 'value_columnas_grid' => $value_columnas_grid,
                 'css_columnas_grid' => $css_columnas_grid
                ]
        );
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}


function ajax_products_setAttrConfigurable($id,$attr){
    
    global $MyAccessList;
    global $MyMessageAlert;
    $respuesta =[];
    
    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_PRODUCTS_CATALOG))
    {
        $CatalogproductsModel =  new \Catalog\model\CatalogproductsModel();
        $CatalogproductsEntity =  new \Catalog\entity\CatalogproductsEntity();
        $Tokenizer = new \Franky\Haxor\Tokenizer;
     
        $CatalogproductsEntity->id($Tokenizer->decode($id));
        $CatalogproductsEntity->configurable(json_encode(explode(",",$attr)));


        if($CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy()) != REGISTRO_SUCCESS)
        {
            $respuesta["error"] = true;
            $respuesta["message"] = $MyMessageAlert->Message("catalog_products_error_change_attr");
        }
    }
    else
    {
        $respuesta["error"] = true;
        $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
    }
    return $respuesta;
}



function catalog_setOrdenCategoria($orden)
{

    $CatalogcategoryModel =  new \Catalog\model\CatalogcategoryModel();
    $CatalogcategoryEntity =  new \Catalog\entity\CatalogcategoryEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;
        $respuesta =null;
        if($MyAccessList->MeDasChancePasar(ADMINISTRAR_CATEGORY_CATALOG))
        {
           
        
            $orden = explode(",",str_replace("cat_","",$orden));

            


           
            $v = "";
            foreach($orden as $key => $val)
            {
                $v .= ($key)." -> $val,";
                $CatalogcategoryEntity->id($Tokenizer->decode($val));
                $CatalogcategoryEntity->orden($key);
                $CatalogcategoryModel->save($CatalogcategoryEntity->getArrayCopy());
            }
          //  echo $v;
        }
        else
        {
             $respuesta[] = array("message" => $MyMessageAlert->Message("sin_privilegios"));
        }
	
	return $respuesta;
}


function ajax_catalog_importar_producto($id)
{
    global $MySession;
    global $MyConfigure;
    global $MyMessageAlert;
    $respuesta = [];
    $time_execute = 30;
    $sql_time = 0;
    $file_xls = $MyConfigure->getServerUploadDir()."/catalog/importar/".$MySession->GetVar('importar-productos-file');


    if ( $xls = SimpleXLS::parse($file_xls) ) {
      
        $atributos_xls = [
            "name","sku","category","description","visible_in_search","stock","in_stock","stock_infinito","saleable","min_qty","price",
            "iva","incluye_iva","envio_requerido","meta_title","meta_description","meta_keyword","url_key","status","new_images"
        ];

        $_xls = $xls->rows();
        $count = 0;
        $sql_time_i = explode(" ",microtime());
        while($_xls[$id] && $sql_time < $time_execute)
        {

            
            foreach($_xls[$id] as $_key => $_val){
                $_POST[$atributos_xls[$_key]] = utf8_encode($_val); 
            }
            $_POST['sku'] = getFriendly($_POST['sku']);
            $_POST['url_key'] = getFriendly($_POST['url_key']);
            if(empty($_POST['url_key']))
            {
                $_POST['url_key'] = getFriendly($_POST['name']);
            }
            $respuesta['data'][$count]['sku'] = $_POST['sku'];
            $respuesta['data'][$count]['id'] = $id;

            $CatalogcategoryproductEntity    = new Catalog\entity\CatalogcategoryproductEntity();
            $CatalogcategoryproductModel     = new Catalog\model\CatalogcategoryproductModel();
            $CatalogproductsModel               = new Catalog\model\CatalogproductsModel();    
            $CatalogproductsEntity              = new Catalog\entity\CatalogproductsEntity($_POST);
            $ObserverManager                    = new \Franky\Core\ObserverManager;
            $validaciones                       = new Franky\Core\validaciones;

            $valid = $validaciones->validRules($CatalogproductsEntity->setValidation());


            if(!$valid)
            {
                $respuesta['data'][$count]['operacion'] = $validaciones->getMsg();
                $respuesta['data'][$count]["status"] ="error";
            }
            else{

                if($CatalogproductsModel->existeSKU($_POST['sku']) == REGISTRO_SUCCESS)
                {
                    $data = $CatalogproductsModel->getRows();
                    $CatalogproductsEntity->updateAt(date('Y-m-d H:i:s'));
                    $CatalogproductsEntity->id($data['id']);

                    $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
                    $respuesta['data'][$count]["operacion"] ="update";

                    $ObserverManager->dispatch('edit_catalog_product',['data' => $CatalogproductsEntity->getArrayCopy()]);
                    
                }
                else{
                    $CatalogproductsEntity->createdAt(date('Y-m-d H:i:s'));
                    $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
                    $respuesta['data'][$count]["operacion"] ="add";
                    $data['id'] = $CatalogproductsModel->getUltimoID();


                    $CatalogproductsEntity->id($data['id']);
                    
                    $ObserverManager->dispatch('save_catalog_product',['data' => $CatalogproductsEntity->getArrayCopy()]);
                }
                


                $CatalogcategoryproductEntity->id_product($data['id']);
                $CatalogcategoryproductModel->remove($CatalogcategoryproductEntity->getArrayCopy());   
                $category = json_decode($CatalogproductsEntity->category(),true);

                foreach($category as $cat)
                {
               
                    $CatalogcategoryproductEntity->id_category($cat);
                    $CatalogcategoryproductModel->save($CatalogcategoryproductEntity->getArrayCopy());   
                    
                }

            

                $custom_attr = getDataCustomAttribute(0,'catalog_products');



                if(!empty($custom_attr['custom_imputs']))
                {

                    foreach($custom_attr['custom_imputs'] as $key => $data_attrs)
                    {
                        if(!in_array($data_attrs['type'],['file','multifile']))
                        {
                            $atributos_xls[] = $data_attrs['name'];
                            
                        }

                        
                    }

                    
                    foreach($_xls[$id] as $_key => $_val){
                        $_POST[$atributos_xls[$_key]] = utf8_encode($_val); 
                    }
                    
                    // print_r($_POST); die;
                    
                    saveDataCustomAttributeImport($data['id'],'catalog_products',$_POST);
                }


                if(!empty($_POST['new_images']))
                {
                    $new_images = explode(",",$_POST['new_images']);
                    $images = [];
                    $principal = 1;
                
                    if(isset($data['images']) && !empty($data['images'])){
                        $principal = 0;
                        $images = json_decode($data['images'],true);
                    }

                    if(!empty($new_images))
                    {

                        $dir = $MyConfigure->getServerUploadDir()."/catalog/products/".$data['id']."/";
                        $dir2 = $MyConfigure->getServerUploadDir()."/catalog/importar/images/";
                        $File = new \Franky\Filesystem\File();
                        $File->mkdir($dir);

                        foreach($new_images as $image)
                        {
                            $image = trim($image);
                        
                            if(!file_exists($dir2.$image))
                            {
                                continue;
                            }
                        

                            $handle = new \Franky\Filesystem\Upload($dir2.$image);


                            if ($handle->uploaded)
                            {
                            
                                if  (!in_array(strtolower(pathinfo($dir2.$image, PATHINFO_EXTENSION)),array("jpg","png","gif","bmp","jpe","jpeg")))//($handle->file_is_image)
                                {
                                    continue;
                                }
                                
                                $handle->file_max_size = 1024*1024*100; //1k(1024) x 512
                            
                                if($handle->image_src_x > 2000 || $handle->image_src_y > 2000)
                                {
                                    $handle->image_resize = true;
                                    $handle->image_x = 2000;
                                    $handle->image_y = 2000;
                                }
                                $handle->file_auto_rename = true;
                                $handle->file_overwrite = false;
                                

                                $handle->Process($dir);

                                if ($handle->processed)
                                {
                                    $images[] = array("token" => md5($handle->file_dst_name), "img" => $handle->file_dst_name,'principal' => $principal);
                                    $principal = 0;
                                }
                                else
                                {
                                    $respuesta["msg"].=  $MyMessageAlert->Message("imagen_error",$image);
                                }
                            
                            }
                            else
                            {
                                $respuesta["msg"].=  $MyMessageAlert->Message("imagen_error",$image);
                            }

                            

                        }
                        $CatalogproductsEntity->exchangeArray([]);
                        $CatalogproductsEntity->id($data['id']);
                        $CatalogproductsEntity->updateAt(date('Y-m-d H:i:s'));
                        $CatalogproductsEntity->images(json_encode($images));
                        $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
                    }
                }
                $respuesta['data'][$count]["status"] ="success";
            }
            
            $sql_time_f = explode(" ",microtime());
            $sql_time +=  round(((float)$sql_time_f[0]+(float)$sql_time_f[1])-((float)$sql_time_i[0]+(float)$sql_time_i[1]),3);
            $respuesta['time'] = $sql_time;
            $id++;
            $count++;
        }
       
       

    } else {
        $respuesta["status"] ="error";
        $respuesta["msg"] = SimpleXLS::parseError();
    }

    return $respuesta;
}


function EliminarCatalogCustomAttribute($id,$status)
{
    global $MySession;
    $CustomattributesModel =  new \Base\model\CustomattributesModel();
    $CustomattributesEntity =  new \Base\entity\CustomattributesEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_CATALOG_CUSTOM_ATTRIBUTES))
    {
        $CustomattributesEntity->id(addslashes($Tokenizer->decode($id)));
        $CustomattributesEntity->status($status);

        if($CustomattributesModel->save($CustomattributesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("custom_attribute_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}

function ajax_getCatalogCustomAttrFrm($id,$set)
{
    
    $respuesta = null;
    $custom_attr = getDataCustomAttribute($id,'catalog_products');


    if(!empty($custom_attr['custom_imputs']))
    {

        $adminForm = new \Catalog\Form\ProductsForm("frmproduct");
        $CatalogproductsModel = new \Catalog\model\CatalogproductsModel;
        $CatalogproductsEntity = new \Catalog\entity\CatalogproductsEntity;


        $CatalogproductsEntity->id($id);
        $CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy());

        $data = $CatalogproductsModel->getRows();
       
        if(!empty($set))
        {
            $CatalogsetattributesModel = new \Catalog\model\CatalogsetattributesModel;
            $CatalogsetattributesEntity = new \Catalog\entity\CatalogsetattributesEntity;

            $CatalogsetattributesEntity->id($set);
            $CatalogsetattributesModel->getData($CatalogsetattributesEntity->getArrayCopy());
        
            $sets_data = $CatalogsetattributesModel->getRows();
            $attr_use=json_decode($sets_data['attributes']);
            foreach($custom_attr['custom_imputs'] as $key => $data_attrs)
            {
                $pass = false;


                if(!empty($attr_use))
                {
                    if(in_array($key,$attr_use))
                    {
                        $pass = true;
                    }
                }

                
                if(!$pass)
                {  
                    unset($custom_attr['custom_imputs'][$key]);
                    continue;
                }
            
                if(in_array($data_attrs['type'],['file']))
                {
                    
                    $adminForm->add(array(
                        'name' => $data_attrs['name'],
                        'label' => $data_attrs['label'],
                        'type'  => $data_attrs['type'],
                        'required'  => $data_attrs['required'],
                        'atributos' => array(
                            'class'       => ($data_attrs['required'] && empty($custom_attr['custom_values'][$data_attrs['name']]) ? 'required' : ''),
                        //   'maxlength' => 60
                        ),
                        'label_atributos' => array(
                            'class'       => ($data_attrs['required'] ? 'desc_form_obligatorio' : 'desc_form_no_obligatorio')
                        )
                        )
                    );
                }
                if(in_array($data_attrs['type'],['multifile']))
                {
                    
                    $adminForm->add(array(
                        'name' => $data_attrs['name'],
                        'label' => $data_attrs['label'],
                        'type'  => 'file',
                        'required'  => $data_attrs['required'],
                        'atributos' => array(
                            'class'       => ($data_attrs['required'] && empty($custom_attr['custom_values'][$data_attrs['name']]) ? 'required' : ''),
                        'multiple' => true
                        ),
                        'label_atributos' => array(
                            'class'       => ($data_attrs['required'] ? 'desc_form_obligatorio' : 'desc_form_no_obligatorio')
                        )
                        )
                    );
                }
                if(in_array($data_attrs['type'],['text','textarea']))
                {
                    
                    $adminForm->add(array(
                        'name' => $data_attrs['name'],
                        'label' => $data_attrs['label'],
                        'type'  => $data_attrs['type'],
                        'required'  => $data_attrs['required'],
                        'atributos' => array(
                            'class'       => ($data_attrs['required'] ? 'required' : '').' '.($data_attrs['type'] == 'textarea'? 'editor_html' : ''),
                        //   'maxlength' => 60
                        ),
                        'label_atributos' => array(
                            'class'       => ($data_attrs['required'] ? 'desc_form_obligatorio' : 'desc_form_no_obligatorio')
                        )
                        )
                    );
                }
                if(in_array($data_attrs['type'],['radio','select','checkbox']))
                {
                    $adminForm->add(array(
                        'name' => $data_attrs['name'],
                        'label' => $data_attrs['label'],
                        'type'  => $data_attrs['type'],
                        'required'  => $data_attrs['required'],
                        'atributos' => array(
                            'class'       =>  ($data_attrs['required'] ? 'required' : ''),
                        //   'maxlength' => 60
                        ),
                        'options' => $data_attrs['data'],
                        'label_atributos' => array(
                            'class'       => ($data_attrs['required'] ? 'desc_form_obligatorio' : 'desc_form_no_obligatorio')
                    
                        )
                        )
                    );
                }

            }
        }
        if(!empty($custom_attr['custom_values']))
        {
            $data = array_merge($data,$custom_attr['custom_values']);
        }
    
        
        $adminForm->setData($data);

        $respuesta['html'] = render(PROJECT_DIR.'/modulos/catalog/diseno/admin/custom_attributes/customform.phtml',['custom_attr' => $custom_attr,'adminForm' => $adminForm]);
    }




  
    return $respuesta;
}


function ajax_getFrmCategpry($id,$parent)
{
    global $_Niveles_usuarios;
    global $MyConfigure;
    $respuesta = null;
    $Tokenizer = new Franky\Haxor\Tokenizer();
   
    $id = $Tokenizer->decode($id);
    $parent = $Tokenizer->decode($parent);
    $data = ['parent_id' => $parent];
    if(!empty($id))
    {
        $CatalogCategoryModel = new Catalog\model\CatalogcategoryModel();
        $CatalogCategoryEntity = new Catalog\entity\CatalogcategoryEntity();
        $CatalogCategoryEntity->id($id);
        $result	 = $CatalogCategoryModel->getData($CatalogCategoryEntity->getArrayCopy());
        $data           = $CatalogCategoryModel->getRows();
        $data['users'] = json_decode($data['users'],true);
    
        if(!empty($data["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$data["image"]))
        {
            $data['image'] = imageResize($MyConfigure->getUploadDir()."/catalog/category/".$data["image"],150,150, true);
            
        }

        $data['id'] = $Tokenizer->token('category', $data['id']);
    }
    $adminForm = new Catalog\Form\CatalogCategoryForm("frmcategoria");
    $adminForm->setOptionsInput("users[]", $_Niveles_usuarios);
    $adminForm->setData($data);
     
    $respuesta['html'] = render(PROJECT_DIR.'/modulos/catalog/diseno/admin/catalog_category/form.phtml',['id' => $id,'adminForm' => $adminForm,'data'=> $data]);
    




  
    return $respuesta;
}


function EliminarCatalogSetAttribute($id,$status)
{
    global $MySession;
    $CatalogsetattributesModel =  new \Catalog\model\CatalogsetattributesModel();
    $CatalogsetattributesEntity =  new \Catalog\entity\CatalogsetattributesEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    global $MyAccessList;
    global $MyMessageAlert;

    $respuesta = null;

    if($MyAccessList->MeDasChancePasar(ADMINISTRAR_CATALOG_CUSTOM_ATTRIBUTES))
    {
        $CatalogsetattributesEntity->id(addslashes($Tokenizer->decode($id)));
        $CatalogsetattributesEntity->status($status);

        if($CatalogsetattributesModel->save($CatalogsetattributesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {

        }
        else
        {
              $respuesta["message"] = $MyMessageAlert->Message("custom_attribute_error_delete");
              $respuesta["error"] = true;
        }
    }
    else
    {
         $respuesta["message"] = $MyMessageAlert->Message("sin_privilegios");
         $respuesta["error"] = true;
    }

    return $respuesta;
}

/******************************** EJECUTA *************************/

$MyAjax->register("EliminarCatalogCustomAttribute");
$MyAjax->register("DeleteCatalogCategory");
$MyAjax->register("DeleteCatalogProduct");
$MyAjax->register("setOrdenImagesProducts");
$MyAjax->register("eliminarFotoCatalogProduct");
$MyAjax->register("ajax_products_agregarProductoRelacionado");
$MyAjax->register("ajax_products_quitarProductoRelacionado");
$MyAjax->register("ajax_products_cargarProductosRelacionados");
$MyAjax->register("ajax_products_agregarProductoRelacionadoVitrina");
$MyAjax->register("ajax_products_quitarProductoRelacionadoVitrina");
$MyAjax->register("ajax_products_cargarProductosRelacionadosVitrina");
$MyAjax->register("DeleteCatalogVitrina");
$MyAjax->register("ajax_products_cargarProductosConfigurables");
$MyAjax->register("ajax_products_agregarProductoConfigurable");
$MyAjax->register("ajax_products_quitarProductoConfigurable");
$MyAjax->register("ajax_products_setAttrConfigurable");
$MyAjax->register("catalog_setOrdenCategoria");
$MyAjax->register("ajax_catalog_importar_producto");
$MyAjax->register("ajax_getCatalogCustomAttrFrm");
$MyAjax->register("ajax_getFrmCategpry");
$MyAjax->register("EliminarCatalogSetAttribute");
?>
