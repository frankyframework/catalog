<?php

function _catalog($txt)
{
    return dgettext("catalog",$txt);
}

function getImageCategorys($id)
{
    global $MyConfigure;
    $CatalogcategoryModel = new Catalog\model\CatalogcategoryModel();
    $CatalogcategoryEntity = new Catalog\entity\CatalogcategoryEntity();
    $CatalogcategoryEntity->url_key($id);
    $CatalogcategoryModel->setTampag(1);
    $CatalogcategoryModel->setOrdensql("name ASC");
    $CatalogcategoryModel->getData($CatalogcategoryEntity->getArrayCopy());
    $total	= $CatalogcategoryModel->getTotal();

    if($total > 0)
    {

        $data = $CatalogcategoryModel->getRows();
        $img = '';
        if(!empty($data["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$data["image"]))
        {
           $img  =  imageResize($MyConfigure->getUploadDir()."/catalog/category/".$data["image"],1920,822, true);
        }

        return ['img' =>$img,'description' => $data['description']];


    }
    return '';
}


function getCategoryMenu()
{


    global $MyRequest;
    $CatalogcategoryModel = new Catalog\model\CatalogcategoryModel();
    $CatalogcategoryEntity = new Catalog\entity\CatalogcategoryEntity();
    
    $CatalogsubcategoryModel = new Catalog\model\CatalogsubcategoryModel();
    $CatalogsubcategoryEntity = new Catalog\entity\CatalogsubcategoryEntity();
    
    
    $CatalogcategoryModel->setTampag(1000);
    $CatalogcategoryModel->setOrdensql("orden ASC");
    $CatalogcategoryEntity->status(1);
    $CatalogcategoryEntity->visible_in_search(1);
    $CatalogcategoryModel->getData($CatalogcategoryEntity->getArrayCopy());
    $total = $CatalogcategoryModel->getTotal();
    
    $categorias= [];
    if($total > 0)
    {
        while($registro = $CatalogcategoryModel->getRows())
        {
            $categorias[$registro['id']] = $registro;
        }
    }
    
    $CatalogsubcategoryModel->setTampag(1000);
    $CatalogsubcategoryModel->setOrdensql("catalog_subcategory.orden ASC");
    $CatalogsubcategoryEntity->status(1);
    $CatalogsubcategoryEntity->visible_in_search(1);
    $CatalogsubcategoryModel->getData($CatalogsubcategoryEntity->getArrayCopy());
    $total = $CatalogsubcategoryModel->getTotal();
    
    $subcategorias= [];
    if($total > 0)
    {
        while($registro = $CatalogsubcategoryModel->getRows())
        {
            $subcategorias[$registro['id_category']][] = $registro;
        }
    }
    
    
    $menu = '<li class="_nav_catalog">
        <ul class="_ul_nav_catalog">';

    if(!empty($categorias))
    {
        foreach ($categorias as $id => $data)
        {
            $menu .= '<li class="'.$data['url_key'].'"><a href="'. $MyRequest->url(CATALOG_SEARCH_CATEGORY,[ 'friendly' => $data['url_key']]).'">'.$data['name'].'</a>';
            if(!empty($subcategorias))
            {
                if(isset($subcategorias[$id]) && !empty($subcategorias[$id]))
                {
                    $menu .= '<ul class="sub_ul_nav_catalog '.$data['url_key'].'">';
                    foreach ($subcategorias[$id] as $key => $data_sub)
                    {
                        $menu .= '<li  class="'.$data_sub['url_key'].'"><a href="'. $MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['categoria'  =>$data['url_key'],  'friendly' => $data_sub['url_key']]).'">'.$data_sub['name'].'</a></li>';
                    }
                    $menu .= '</ul>';
                }
            }

            $menu .= '</li>';
	}
    }

    $menu .= '</ul>
    </li>';

    return $menu;
}



function getCatalogCategorys( $type="interface", $search = [])
{
    $CatalogcategoryModel = new Catalog\model\CatalogcategoryModel();
    $CatalogcategoryModel->setTampag(1000);
    $CatalogcategoryModel->setOrdensql("name ASC");
    $CatalogcategoryModel->getData($search);
    $total			= $CatalogcategoryModel->getTotal();
    $categorias = array();

    if($total > 0)
    {

        while($registro = $CatalogcategoryModel->getRows())
        {
            $categorias[($type == "interface" ? $registro["url_key"] : $registro['id'])] = $registro["name"];
	}
    }
    return $categorias;
}

function getImageSubcategorys($id)
{
    global $MyConfigure;
    $CatalogsubcategoryModel = new Catalog\model\CatalogsubcategoryModel();
    $CatalogsubcategoryEntity = new Catalog\entity\CatalogsubcategoryEntity();
    $CatalogsubcategoryModel->setTampag(1);
    $CatalogsubcategoryModel->setOrdensql("catalog_subcategory.name ASC");
    $CatalogsubcategoryEntity->url_key($id);
    $CatalogsubcategoryModel->getData($CatalogsubcategoryEntity->getArrayCopy());
    $total	= $CatalogsubcategoryModel->getTotal();


    if($total > 0)
    {
        $data = $CatalogsubcategoryModel->getRows();
        $img = '';
        if(!empty($data["image"]) && file_exists($MyConfigure->getServerUploadDir()."/catalog/category/".$data["image"]))
        {
           $img  =  imageResize($MyConfigure->getUploadDir()."/catalog/category/".$data["image"],1920,822, true);
        }

        return ['img' =>$img,'description' => $data['description']];

    }
    return '';
}

function getCatalogSubcategorys($id=null, $type="interface")
{
    $CatalogsubcategoryModel = new Catalog\model\CatalogsubcategoryModel();
    $CatalogsubcategoryEntity = new Catalog\entity\CatalogsubcategoryEntity();
    $CatalogcategoryEntity = new Catalog\entity\CatalogcategoryEntity();
    
    $CatalogsubcategoryModel->setTampag(1000);
    $CatalogsubcategoryModel->setOrdensql("catalog_subcategory.name ASC");
    if(!empty($id)):
        if(is_numeric($id)):
            $CatalogsubcategoryEntity->id_category($id);
        else:
            $CatalogcategoryEntity->url_key($id);
        endif;
    endif;
    $CatalogsubcategoryEntity->status(1);
    $CatalogsubcategoryModel->setDataCategoria($CatalogcategoryEntity->getArrayCopy());
    $CatalogsubcategoryModel->getData($CatalogsubcategoryEntity->getArrayCopy());
    $total	= $CatalogsubcategoryModel->getTotal();
    $subcategorias = array();

    if($total > 0)
    {

        while($registro = $CatalogsubcategoryModel->getRows())
        {
            if($id != null)
            {
                $subcategorias[($type == "interface" ? $registro["url_key"] : $registro['id'])] = $registro["name"];
            }
            else
            {
                 $subcategorias[$registro["id_category"]][($type == "interface" ? $registro["url_key"] : $registro['id'])] = $registro["name"];

            }
	}
    }
    return $subcategorias;
}


function getFotoCatalogProduct($album,$foto,$token,$principal)
{

    global $MyConfigure;
    $Tokenizer = new \Franky\Haxor\Tokenizer();
    $html = "";
    $html .= "<div class='w-xxxx-4 w-xxx-4 w-xx-4 w-x-4 align_center img_foto_clientes foto_".$token."' id='foto_".$token."'>"
            ."<div class=\"w-xxxx-6 w-xxx-6 w-xx-6 w-x-6\">"
            ."<input type=\"radio\" value=\"$token\" name=\"principal\" ".($principal == 1 ? "checked='checked'" :'')." />Principal"
            ."</div>"
            ."<div class=\"w-xxxx-6 w-xxx-6 w-xx-6 w-x-6\">"
            ."<button type='button' onclick=\"eliminarFotoCatalogProduct('$token')\"><i class='icon icon-r-eliminar'></i></button>"

            . "</div><div>".  makeHTMLImg(imageResize($MyConfigure->getUploadDir()."/catalog/products/$album/$foto",220,220,true), "", "", "")."</div>"
            . "</div>";
    return $html;
}


function getCatalogBuscadorPrincipal()
{
    global $MyRequest;
    $BuscadorPrincipalForm =  new \Catalog\Form\BuscadorPrincipalForm('buscadorPrincipal');
    $BuscadorPrincipalForm->setAtributo('action',$MyRequest->url(CATALOG_SEARCH));

    return render(PROJECT_DIR.'/modulos/catalog/diseno/widget.buscador.phtml',['BuscadorPrincipalForm' => $BuscadorPrincipalForm]);
}


function catalog_getBuscadorLateral()
{
    global $MyRequest;
    global $MyFrankyMonster;
    $CatalogcategoryEntity = new Catalog\entity\CatalogcategoryEntity();
    $BuscadorLateralForm =  new \Catalog\Form\BuscadorLateralForm('buscadorLateral');
    $BuscadorLateralForm->setAtributo('action',$MyRequest->url(CATALOG_SEARCH));
    $CatalogcategoryEntity->status(1);
    $categorias = getCatalogCategorys('interface',$CatalogcategoryEntity->getArrayCopy());
    $_categorias = getCatalogCategorys('sql',$CatalogcategoryEntity->getArrayCopy());
    $subcategorias = getCatalogSubcategorys(null,'interface');
    $BuscadorLateralForm->setOptionsInput("categoria[]", $categorias);



    return render('widget.buscador.lateral.phtml',[
    'MyFrankyMonster' => $MyFrankyMonster,
    'MyRequest'  => $MyRequest,
    'BuscadorLateralForm' => $BuscadorLateralForm,
    'categorias' => $categorias,
    '_categorias' => $_categorias,
    'subcategorias' => $subcategorias
    ]);
}




function catalog_getPriceMaxMinProduct()
{
    $CatalogproductsModel = new \Catalog\model\CatalogproductsModel;
    $CatalogproductsEntity = new \Catalog\entity\CatalogproductsEntity;
    $CatalogproductsModel->setOrdensql("price ASC");
    $precio = [0,0];
    if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
            $registro = $CatalogproductsModel->getRows();
            $precio[0] = ($registro['price'] > 0 ? $registro['price'] : 0);
            
    }
    $CatalogproductsModel->setOrdensql("price DESC");
    if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    { 
            $registro = $CatalogproductsModel->getRows();
            $precio[1] = ($registro['price']> 0 ? $registro['price'] : 0);

    }

    return $precio;
}

function catalog_setPriceEcommerce($data)
{


    $PreciosModel   = new \Ecommerce\model\PreciosModel();
    $PreciosEntity  = new \Ecommerce\entity\PreciosEntity();
    $PreciosEntity2  = new \Ecommerce\entity\PreciosEntity();

    if($data['saleable'] == 1)
    {
        $PreciosEntity->precio($data['price']);
        $PreciosEntity->iva($data['iva']);
        $PreciosEntity->incluye_iva($data['incluye_iva']);
        $PreciosEntity2->id_producto($data['id']);
        $PreciosEntity->id_moneda(1);
        $PreciosEntity->id_producto($data['id']);

        if($PreciosModel->getData($PreciosEntity2->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            $result2 = $PreciosModel->updateByIdProdcuto($PreciosEntity->getArrayCopy());
        }
        else {
            $result2 = $PreciosModel->save($PreciosEntity->getArrayCopy());
        }

    }
}

function catalog_validaStockCarrito($id,$n)
{
    global $MyRequest;
    global $MyMessageAlert;
    $CatalogproductsModel = new \Catalog\model\CatalogproductsModel;
    $CatalogproductsModel->getInfoProducto($id);
    $registro = $CatalogproductsModel->getRows();

    if($registro['in_stock'] == 0 || $registro['saleable'] == 0)
    {

        echo json_encode(array("error" => true,"message" => $MyMessageAlert->Message("catalog_produt_no_saleable",$registro['nombre'])));


        die;
    }

    if($registro['stock_infinito'] ==  0 && $n > $registro['stock'])
    {

        echo json_encode(array("error" => true,"message" => $MyMessageAlert->Message("catalog_stock_no_disponible",$registro['nombre'])));


        die;
    }
    if( $n < $registro['min_qty'])
    {

        echo json_encode(array("error" => true,"message" => $MyMessageAlert->Message("catalog_stock_minimo",$registro['nombre'])));


        die;
    }
}

function catalog_validaStockCompra()
{
    global $MyRequest;
    global $MyMessageAlert;
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $productos_comprados = getCarrito();

    $CatalogproductsModel = new \Catalog\model\CatalogproductsModel;
    if(!empty($productos_comprados)){
        foreach($productos_comprados['productos'] as $producto)
        {
            $CatalogproductsModel->getInfoProducto($producto['id']);
            $registro = $CatalogproductsModel->getRows();
            if($registro['in_stock'] ==  0 || $registro["saleable"] == 0)
            {
                eliminarProductoCarrito($Tokenizer->token('catalog_products',$producto['id']));
                if(!$MyRequest->isAjax())
                {
                    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_produt_no_saleable",$registro['nombre']));
                    $MyRequest->redirect($MyRequest->url(CATALOG_VIEW_SUBCAT,['friendly' => $registro['url_key']]));

                }else{
                    echo json_encode(array("error" => true,"message" => $MyMessageAlert->Message("catalog_produt_no_saleables",$registro['nombre'])));
                }

                die;
            }
            if($registro['stock_infinito'] ==  0 && $producto["qty"] > $registro['stock'])
            {
                eliminarProductoCarrito($Tokenizer->token('catalog_products',$producto['id']));
                if(!$MyRequest->isAjax())
                {
                    $MyFlashMessage->setMsg("error",$MyMessageAlert->Message("catalog_stock_no_disponible",$registro['nombre']));
                    $MyRequest->redirect($MyRequest->url(CATALOG_VIEW_SUBCAT,['friendly' => $registro['url_key']]));

                }else{
                    echo json_encode(array("error" => true,"message" => $MyMessageAlert->Message("catalog_stock_no_disponible",$registro['nombre'])));
                }

                die;
            }
        }
    }


}

function catalog_restaStock($pedido)
{
    global $MySession;
    $CatalogproductsModel          = new \Catalog\model\CatalogproductsModel();
    $CatalogproductsEntity         = new \Catalog\entity\CatalogproductsEntity();
    $USERS =  new \Base\model\USERS;
    $entityUser = new \Base\entity\users;

    $detalle_pedido = getPedido($pedido);

    foreach($detalle_pedido['productos'] as $producto)
    {
        $CatalogproductsEntity->exchangeArray([]);

        $CatalogproductsModel->getInfoProducto($producto['id']);
        $registro = $CatalogproductsModel->getRows();

        if($registro['stock_infinito'] == 0)
        {
            $stock = $registro['stock'] - $producto['qty'];
            $CatalogproductsEntity->stock($stock);
            if($stock == 0)
            {
                $CatalogproductsEntity->in_stock(0);
            }
            $CatalogproductsEntity->id($producto['id']);

            $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
        }


    }
}

function catalog_addStock($pedido)
{
    global $MySession;
    $CatalogproductsModel          = new \Catalog\model\CatalogproductsModel();
    $CatalogproductsEntity         = new \Catalog\entity\CatalogproductsEntity();
    $USERS =  new \Base\model\USERS;
    $entityUser = new \Base\entity\users;

    $detalle_pedido = getPedido($pedido);
    if($detalle_pedido['status'] == 'canceled')
    {
        if(!empty($detalle_pedido['productos']))
        {
            foreach($detalle_pedido['productos'] as $producto)
            {
                $CatalogproductsEntity->exchangeArray([]);

                $CatalogproductsModel->getInfoProducto($producto['id']);
                $registro = $CatalogproductsModel->getRows();

                if($registro['stock_infinito'] == 0)
                {
                    $stock = $registro['stock'] + $producto['qty'];
                    $CatalogproductsEntity->stock($stock);
                    if($stock > 0)
                    {
                        $CatalogproductsEntity->in_stock(1);
                    }
                    $CatalogproductsEntity->id($producto['id']);

                    $CatalogproductsModel->save($CatalogproductsEntity->getArrayCopy());
                }

            }
        }
    }
}




function getCatalogVitrina($clave)
{
   
    global $MyConfigure;
    global $MyFrankyMonster;
    global $MyMetatag;
    global $MyRequest;

    $uiCommand = $MyFrankyMonster->getUiCommand($MyFrankyMonster->MySeccion());
  
    if (is_array($uiCommand[3])) {
        if (!in_array('slick',$uiCommand[3])) 
        {
            $MyMetatag->setJs("/public/jquery/slick/js/slick.min.js");
            $MyMetatag->setCss("/public/jquery/slick/css/slick-theme.css");
            $MyMetatag->setCss("/public/jquery/slick/css/slick.css");
        }     
    }
    else{
        $MyMetatag->setJs("/public/jquery/slick/js/slick.min.js");
        $MyMetatag->setCss("/public/jquery/slick/css/slick-theme.css");
        $MyMetatag->setCss("/public/jquery/slick/css/slick.css");
    }
      


    $CatalogvitrinaModel = new \Catalog\model\CatalogvitrinaModel();
    $CatalogvitrinaEntity = new \Catalog\entity\CatalogvitrinaEntity();
    $CatalogproductsModel = new \Catalog\model\CatalogproductsModel();
    $CatalogproductsEntity = new \Catalog\entity\CatalogproductsEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $CatalogproductsEntity->status(1);
    $CatalogvitrinaEntity->status(1);
    $CatalogvitrinaEntity->clave($clave);
    $result	 = $CatalogvitrinaModel->getData($CatalogvitrinaEntity->getArrayCopy());
    
    if($result == REGISTRO_SUCCESS){
        
        $vitrina = $CatalogvitrinaModel->getRows();
        
        
        $CatalogproductsModel->setTampag($vitrina['numero']);
        if($vitrina['random'] ==1)
        {
            $CatalogproductsModel->setOrdensql("RAND()");
        }
        else{
            $CatalogproductsModel->setOrdensql("name ASC");
        }
        
        $filtro_items = json_decode($vitrina['items'],true);
        $categorias = [];
        $subcategorias = [];
        if(!empty($filtro_items['category']))
        {
            foreach ($filtro_items['category'] as $cat => $sub)
            {
                $categorias[] = $cat;
                if(!empty($sub))
                {
                    foreach($sub as $_sub)
                    {
                        $subcategorias[] = $_sub;
                    }
                }
            }
        }
        
        $CatalogproductsModel->setCategoriaArray($categorias);
        $CatalogproductsModel->setSubCategoriaArray($subcategorias);
        
        
        if(isset($filtro_items['productos'])):
            $CatalogproductsModel->setsearchIds($filtro_items['productos']);
        endif;
        
        $resultados_pagina = [];
       
        if( $CatalogproductsModel->getDataVitrina($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
        
            while($registro = $CatalogproductsModel->getRows())
            {
                $registro['link'] = $MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $registro['url_key']]);

                $registro['thumb_resize'] =  "";
                $img = "";
                $_img = getCoreConfig('catalog/product/placeholder');
                if($_img != "" && file_exists(PROJECT_DIR.$_img))
                {
                  $registro['thumb_resize'] = imageResize($_img,500,500, false);
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

                                  $registro['thumb_resize'] = imageResize($MyConfigure->getUploadDir()."/catalog/products/".$registro["id"].'/'.$foto['img'],500,500, false);

                            }
                        }

                    }
                }

                $registro['id_wishlist'] = $Tokenizer->token('wishlist',$registro["id"]);

                $registro['id'] = $Tokenizer->token('catalog_products',$registro["id"]);

                $resultados_pagina[] = $registro;
            }  
            
            return render(PROJECT_DIR.'/modulos/catalog/diseno/widget.vitrina.phtml',['resultados_pagina' => $resultados_pagina,'titulo'=>$vitrina['titulo'],'clave'=>$clave['titulo']]);
        }
      
        
    }
    return  '';
}



function CatalogBreadcrumbs($name =null)
{
    global $MyRequest;
    global $MyFrankyMonster;
    global $MySession;
    $link = "";
    $html = '<div class="w-xxxx-12 cont_breadcrumb">
    <div class="content">
    <ul class="breadcrumb">';

    $uiCommand =  $MyFrankyMonster->getUiCommand($MyFrankyMonster->getSeccion(CATALOG_SEARCH));

    $html .='<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH).'" data-transition="back">'.$uiCommand[8].'</a></li>';

    $categorias = getCatalogCategorys();
    $_categorias = array_keys($categorias);
    if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_CATEGORY)
    {
        $categoria      = $MyRequest->getUrlParam('friendly');
        
    
        if(in_array($categoria, $_categorias))
        {
        
            $html .='<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $categoria]).'" data-transition="back">'.$categorias[$categoria].'</a></li>';
        }
        else{
            
            $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $categoria]).'" data-transition="back">'.$name.'</a></li>';
        }
        
    }
    if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_SUBCATEGORY)
    {
    
        $categoria      = $MyRequest->getUrlParam('categoria');
        $subcategorias = getCatalogSubcategorys($categoria);
        $_subcategorias = array_keys(getCatalogSubcategorys($categoria));
        $subcategoria      = $MyRequest->getUrlParam('friendly');

        if(in_array($subcategoria, $_subcategorias))
        {
            $html .= '
            <li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $categoria]).'" data-transition="back">'.$categorias[$categoria].'</a> </li>
            <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['categoria' => $categoria,'friendly' => $subcategoria]).'" data-transition="back">'.$subcategorias[$subcategoria].'</a> </li>';
        }
        else{
            $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $categoria]).'" data-transition="back">'.$categorias[$categoria].'</a> </li>
            <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['categoria' => $categoria,'friendly' => $subcategoria]).'" data-transition="back">'.$name.'</a></li>';
        }
        
    }
    if($MyFrankyMonster->MySeccion() == CATALOG_VIEW_SUBCAT)
    {
    
        $categoria      = $MyRequest->getUrlParam('categoria');
        $subcategorias = getCatalogSubcategorys($categoria);
        $_subcategorias = array_keys(getCatalogSubcategorys($categoria));
        $subcategoria      = $MyRequest->getUrlParam('subcategoria');
        $friendly      = $MyRequest->getUrlParam('friendly');

    
        $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $categoria]).'" data-transition="back">'.$categorias[$categoria].'</a> </li>
        <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['categoria' => $categoria,'friendly' => $subcategoria]).'" data-transition="back">'.$subcategorias[$subcategoria].'</a> </li>
        <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_VIEW_SUBCAT,['categoria' => $categoria,'subcategoria' => $subcategoria,'friendly' => $friendly]).'" data-transition="back">'.$name.'</a></li>';
    
        
    }


    
    $html .= '  </ul>
    </div>
</div>';

   
    return $html;
}




function getDataConfigurables($id_product)
{
    global $MyRequest;
    $CatalogproductconfigurablesModel =  new \Catalog\model\CatalogproductconfigurablesModel();
    $CatalogproductconfigurablesEntity =  new \Catalog\entity\CatalogproductconfigurablesEntity();


    $CatalogproductconfigurablesEntity->id_product($id_product);
  
    
    if($CatalogproductconfigurablesModel->getData($CatalogproductconfigurablesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
      
        while($registro = $CatalogproductconfigurablesModel->getRows())
        {
            $id_product = $registro['id_parent'];
        }
    }

    $CatalogproductconfigurablesEntity->exchangeArray([]);
    $CatalogproductconfigurablesEntity->id_parent($id_product);
    $CatalogproductconfigurablesModel->setTampag(10000);
    $lista_configurables =[];

    if($CatalogproductconfigurablesModel->getData($CatalogproductconfigurablesEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
 
        $lista_configurables[] =$id_product;
        while($registro = $CatalogproductconfigurablesModel->getRows())
        {
            $lista_configurables[] = $registro['id_product'];
            $id_attr = $registro['id_attribute'];
        }

        //print_r($lista_configurables); 
    }

    if(!empty($lista_configurables))
    {
        $CustomattributesvaluesModel = new \Base\model\CustomattributesvaluesModel();
        $CustomattributesvaluesEntity = new \Base\entity\CustomattributesvaluesEntity();

 
        $CustomattributesvaluesModel->setTampag(100);
        $CustomattributesvaluesModel->setIds($lista_configurables);
        $CustomattributesvaluesEntity->id_attribute($id_attr);
        $CustomattributesvaluesModel->getData($CustomattributesvaluesEntity->getArrayCopy());
        $lista_configurables_values= [];
     
        if($CustomattributesvaluesModel->getTotal() > 0)
        {
            

            while($registro = $CustomattributesvaluesModel->getRows())
            {

                $lista_configurables_values[$registro['id_ref']] = $registro['value'];

            }

           // print_r($lista_configurables_values); 
        }
    }

    $configurables = [];
    if(!empty($lista_configurables))
    {
        $CatalogproductsModel = new Catalog\model\CatalogproductsModel;
        $CatalogproductsEntity = new Catalog\entity\CatalogproductsEntity;


        $CatalogproductsModel->setPage(1);
        $CatalogproductsModel->setTampag(100);


        $CatalogproductsEntity->status(1);
        $CatalogproductsModel->setsearchIds($lista_configurables);
  
        if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
            $configurables['id_attribute'] = $id_attr;
            while($registro = $CatalogproductsModel->getRows())
            {
                    $configurables['configurables'][] = ['url' => $MyRequest->url(CATALOG_SEARCH_CATEGORY,['friendly' => $registro['url_key']]),'url_key' => $registro['url_key'], 'value' => $lista_configurables_values[$registro['id']]];

            }

               
            
        }
    }
    
 
    return $configurables;
}
?>
