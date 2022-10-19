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
    
   
    $CatalogcategoryModel->setTampag(1000);
    $CatalogcategoryModel->setOrdensql("orden ASC");
    $CatalogcategoryEntity->status(1);
    $CatalogcategoryEntity->store(DATA_STORE_CONFIG['id']);
    $CatalogcategoryEntity->visible_in_search(1);
    $CatalogcategoryModel->getData($CatalogcategoryEntity->getArrayCopy());
    $total = $CatalogcategoryModel->getTotal();
    

    if($total > 0)
    {
        while($registro = $CatalogcategoryModel->getRows())
        {
            if(empty($registro['parent_id']))
            {
                $data['cat_0'][] = $registro;
            }
            else{
                $data['cat_'.$registro['parent_id']][] = $registro;
            }
            
        }
    }

    
    
    $menu = '<li class="_nav_catalog">
        <ul class="_ul_nav_catalog">';

    if(!empty($data))
    {
        foreach ($data['cat_0'] as $id => $departamento)
        {
            $menu .= '<li class="'.$departamento['url_key'].'"><a href="'. $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,[ 'departamento' => $departamento['url_key']]).'">'.$departamento['name'].'</a>';
            if(!empty($data['cat_'.$departamento['id']]))
            {
                if(isset($data['cat_'.$departamento['id']]) && !empty($data['cat_'.$departamento['id']]))
                {
                    $menu .= '<ul class="sub_ul_nav_catalog '.$departamento['url_key'].'">';
                    foreach ($data['cat_'.$departamento['id']] as $key => $categoria)
                    {
                        $menu .= '<li  class="'.$categoria['url_key'].'"><a href="'. $MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento'  =>$departamento['url_key'],  'categoria' => $categoria['url_key']]).'">'.$categoria['name'].'</a></li>';
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



function getCatalogCategorys($search = [])
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
            $parent_id = (empty($registro['parent_id']) ? 0: $registro['parent_id'] );
            $categorias['cat_'.$parent_id][] = $registro;
	    }
    }
    return $categorias;
}



function getAttributesSet()
{
    $CatalogsetattributesModel = new Catalog\model\CatalogsetattributesModel();
    $CatalogsetattributesEntity = new Catalog\entity\CatalogsetattributesEntity();
    $CatalogsetattributesModel->setTampag(1000);
    $CatalogsetattributesModel->setOrdensql("name ASC");
    $CatalogsetattributesEntity->status(1);
    $CatalogsetattributesModel->getData($CatalogsetattributesEntity->getArrayCopy());
    $total			= $CatalogsetattributesModel->getTotal();
    $data = array();

    if($total > 0)
    {

        while($registro = $CatalogsetattributesModel->getRows())
        {
            $data[$registro['id']] = $registro['name'];
	    }
    }
    return $data;
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
    $CatalogcategoryEntity->store(DATA_STORE_CONFIG['id']);
    $categorias = getCatalogCategorys($CatalogcategoryEntity->getArrayCopy());
    
    $BuscadorLateralForm->setOptionsInput("categoria[]", $categorias);



    return render('widget.buscador.lateral.phtml',[
    'MyFrankyMonster' => $MyFrankyMonster,
    'MyRequest'  => $MyRequest,
    'BuscadorLateralForm' => $BuscadorLateralForm,
    'categorias' => $categorias
    ]);
}




function catalog_getPriceMaxMinProduct()
{
    $CatalogproductsModel = new \Catalog\model\CatalogproductsModel;
    $CatalogproductsEntity = new \Catalog\entity\CatalogproductsEntity;
    $CatalogproductsModel->setOrdensql("price ASC");
 
    $precio = [0,0];
    $CatalogproductsEntity->status(1);
    $CatalogproductsEntity->store(DATA_STORE_CONFIG['id']);
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
            $MyFrankyMonster->addJquery('slick');
        }     
    }
    else{
        $MyFrankyMonster->addJquery('slick');
    }
      

    $CatalogvitrinaModel = new \Catalog\model\CatalogvitrinaModel();
    $CatalogvitrinaEntity = new \Catalog\entity\CatalogvitrinaEntity();
    $CatalogproductsModel = new \Catalog\model\CatalogproductsModel();
    $CatalogproductsEntity = new \Catalog\entity\CatalogproductsEntity();
    $Tokenizer = new \Franky\Haxor\Tokenizer;
    $CatalogproductsEntity->status(1);
    $CatalogproductsEntity->visible_in_search(1);
    $CatalogvitrinaEntity->status(1);
    $CatalogvitrinaEntity->store(DATA_STORE_CONFIG['id']);
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
       
        if(!empty($filtro_items['category']))
        {
            foreach ($filtro_items['category'] as $cat)
            {
                $categorias[] = $cat;
                
            }
        }
        
        $CatalogproductsModel->setCategoriaArray($categorias);
        
        
        if(isset($filtro_items['productos'])):
            $CatalogproductsModel->setsearchIds($filtro_items['productos']);
        endif;
        
        $resultados_pagina = [];
       
        if( $CatalogproductsModel->getDataVitrina($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
        {
        
            while($registro = $CatalogproductsModel->getRows())
            {
                $registro['link'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $registro['url_key']]);

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
    $_categorias = [];
    $name_categorias = [];
    foreach($categorias as $parent => $cat)
    {
        foreach($cat as $key => $_cat)
        {
            $name_categorias[$_cat['url_key']] = $_cat['name'];
            $_categorias[] = $_cat['url_key'];
        }
    }

    if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_DEPARTAMENTO)
    {
        $departamento      = $MyRequest->getUrlParam('departamento');
        
    
        if(in_array($departamento, $_categorias))
        {
        
            $html .='<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name_categorias[$departamento].'</a></li>';
        }
        else{
            
            $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name.'</a></li>';
        }
        
    }
    if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_CATEGORY)
    {
    
        $departamento      = $MyRequest->getUrlParam('departamento');
        $categoria      = $MyRequest->getUrlParam('categoria');
      

        if(in_array($categoria, $_categorias))
        {
            $html .= '
            <li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name_categorias[$departamento].'</a> </li>
            <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento' => $departamento,'categoria' => $categoria]).'" data-transition="back">'.$name_categorias[$categoria].'</a> </li>';
        }
        else{
            $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name_categorias[$departamento].'</a> </li>
            <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento' => $categoria,'categoria' => $categoria]).'" data-transition="back">'.$name.'</a></li>';
        }
        
    }
    if($MyFrankyMonster->MySeccion() == CATALOG_SEARCH_SUBCATEGORY)
    {
    
        $departamento      = $MyRequest->getUrlParam('departamento');
        $categoria      = $MyRequest->getUrlParam('categoria');
        $subcategoria      = $MyRequest->getUrlParam('subcategoria');
      

        if(in_array($subcategoria, $_categorias))
        {
            $html .= '
            <li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name_categorias[$departamento].'</a> </li>
            <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento' => $departamento,'categoria' => $categoria]).'" data-transition="back">'.$name_categorias[$categoria].'</a> </li>
            <li class="nivel_4"><a href="'.$MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['departamento' => $departamento,'categoria' => $categoria,'subcategoria' => $subcategoria]).'" data-transition="back">'.$name_categorias[$subcategoria].'</a> </li>';
        }
        else{
            $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name_categorias[$departamento].'</a> </li>
            <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento' => $categoria,'categoria' => $categoria]).'" data-transition="back">'.$name_categorias[$categoria].'</a></li>
            <li class="nivel_4"><a href="'.$MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['departamento' => $categoria,'categoria' => $categoria,'subcategoria' => $subcategoria]).'" data-transition="back">'.$name.'</a></li>';
        }
        
    }
    if($MyFrankyMonster->MySeccion() == CATALOG_VIEW_SUBCAT)
    {
    
        $departamento      = $MyRequest->getUrlParam('departamento');
        $categoria      = $MyRequest->getUrlParam('categoria');
        $subcategoria      = $MyRequest->getUrlParam('subcategoria');
        $friendly      = $MyRequest->getUrlParam('friendly');

    
        $html .= '<li class="nivel_2"><a href="'.$MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $departamento]).'" data-transition="back">'.$name_categorias[$departamento].'</a> </li>
        <li class="nivel_3"><a href="'.$MyRequest->url(CATALOG_SEARCH_CATEGORY,['departamento' => $departamento,'categoria' => $categoria]).'" data-transition="back">'.$name_categorias[$categoria].'</a> </li>
        <li class="nivel_4"><a href="'.$MyRequest->url(CATALOG_SEARCH_SUBCATEGORY,['departamento' => $departamento,'categoria' => $categoria,'subcategoria' => $subcategoria]).'" data-transition="back">'.$name_categorias[$subcategoria].'</a> </li>
        <li class="nivel_5"><a href="'.$MyRequest->url(CATALOG_VIEW_SUBCAT,['departamento' => $departamento,'categoria' => $categoria,'subcategoria' => $subcategoria,'friendly' => $friendly]).'" data-transition="back">'.$name.'</a></li>';
    
        
    }


    
    $html .= '  </ul>
    </div>
</div>';

   
    return $html;
}




function getDataConfigurables($id_product)
{
    global $MyRequest;
    $CatalogproductsModel = new Catalog\model\CatalogproductsModel;
    $CatalogproductsEntity = new Catalog\entity\CatalogproductsEntity;



    $configurables = [];
    $CatalogproductsEntity->id($id_product);

    if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
       
        $registro = $CatalogproductsModel->getRows();
        
        $attrs = json_decode($registro['configurable'],true);
 
    }


    $CatalogproductsModel->setPage(1);
    $CatalogproductsModel->setTampag(100);

    $CatalogproductsEntity->exchangeArray([]);
    $CatalogproductsEntity->status(1);
    $CatalogproductsEntity->parent_id($id_product);
    
    if($CatalogproductsModel->getData($CatalogproductsEntity->getArrayCopy()) == REGISTRO_SUCCESS)
    {
       $i = 0;
        while($registro = $CatalogproductsModel->getRows())
        {
            $custom_attr = getDataCustomAttribute($registro['id'],'catalog_products');
            
            foreach($attrs as $key => $input_id)
            {
                foreach($custom_attr['custom_imputs'] as $_key => $attr)
                {
                    if($attr['id'] == $input_id)
                    {
                        $configurables['config'][$key] = ['name' => $attr['name'],'label' => $attr['label']];
                        $configurables['productos'][$i][$attr['name']] =$custom_attr['custom_values'][$attr['name']];
                    }
                }
            }
        
        
                $configurables['productos'][$i]['url_key'] = $registro['url_key'];
                $configurables['productos'][$i]['url'] = $MyRequest->url(CATALOG_SEARCH_DEPARTAMENTO,['departamento' => $registro['url_key']]);
            $i++;
        }
 
    }
    
 
    return $configurables;
}


function getCatalogStores()
{
    $CatalogStoresModel = new Catalog\model\CatalogStoresModel();
    $CatalogStoresEntity = new Catalog\entity\CatalogStoresEntity();
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
            $stores[$registro['id']] =$registro['nombre'];
	    }
    }
    return $stores;
}

function getMonedas()
{
    $CatalogMonedasModel = new Catalog\model\CatalogMonedasModel();
    $CatalogMonedasModel->setTampag(1000);
    $CatalogMonedasModel->setOrdensql("id ASC");
    $CatalogMonedasModel->getData();
    $total			= $CatalogMonedasModel->getTotal();
    $monedas = array();

    if($total > 0)
    {

        while($registro = $CatalogMonedasModel->getRows())
        {
            $monedas[$registro['id']] =$registro['nombre'];
	    }
    }
    return $monedas;
}
?>
