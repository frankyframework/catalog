<?php
namespace Catalog\model;

class CatalogproductsModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;
    private $precio;
    private $categoria_array;
    private $excludeId;
    private $search_ids;
    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_products');
    }

    function setExcludeId($id)
    {
        $this->excludeId = $id;
    }
    
    public function setBusca($busca){
        $this->busca=$busca;
    }

    public function setPrecioArray($data)
    {
        $this->precio = $data;
    }
    public function setCategoriaArray($data)
    {
        $this->categoria_array = $data;
    }
    
    public function setsearchIds($data)
    {
        if(is_array($data)){
            if(!empty($data)){
            $this->search_ids = implode(',',$data);
            }
        }
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id","parent_id","type","set_attribute","name","sku","category","visible_in_search","description","images","videos","url_key","meta_title","meta_keyword","meta_description","price","stock","iva","incluye_iva","createdAt","updateAt","status",
        "in_stock","saleable","min_qty","stock_infinito","envio_requerido","configurable"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_products.".$k,$v,'=');
        }

        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('name','%'.$this->busca.'%','like');
          $this->where()->addOr('description','%'.$this->busca.'%','like');
          $this->where()->addOr('sku','%'.$this->busca.'%','like');
          $this->where()->concat(')');
        }
        
        if(!empty($this->excludeId))
        {
            $this->where()->addAnd("catalog_products.id",$this->excludeId,'!=');
        }
        
        if(!empty($this->search_ids))
        {
            $this->where()->concat(" AND catalog_products.id in (".$this->search_ids.") ");
        }

        return $this->getColeccion($campos);
    }

    function getDataSearch($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["catalog_products.id","catalog_products.parent_id","catalog_products.type","catalog_products.set_attribute","catalog_products.name","sku","category",
        "catalog_products.visible_in_search","catalog_products.description",
        "images","videos","catalog_products.url_key","catalog_products.meta_title",
        "catalog_products.meta_keyword","catalog_products.meta_description",
        "price","stock","iva","incluye_iva","catalog_products.createdAt",
        "catalog_products.updateAt","catalog_products.status",
        "in_stock","saleable","min_qty","stock_infinito","envio_requerido","configurable"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_products.".$k,$v,'=');
        }

        if(!empty($this->busca) )
        {

            $this->where()->concat("AND (MATCH(catalog_products.name) "
                . "AGAINST('".$this->busca."' in boolean mode) "
                . "or "
                . "MATCH(catalog_products.meta_keyword) "
                . "AGAINST('".$this->busca."' in boolean mode) ");
           
                $this->where()->addOr('catalog_products.name',"%".$this->busca."%",'like');
                $this->where()->addOr('catalog_products.meta_keyword',"%".$this->busca."%",'like');
        
                $this->where()->concat(')');

        }
      

          if(!empty($this->precio)){
                  if(is_array($this->precio))
                  {
                    $this->where()->concat("AND (");
                    $this->where()->addAnd("catalog_products.price ",trim($this->precio[0]),'>=');
                    $this->where()->addAnd("catalog_products.price ",trim($this->precio[1]),'<=');
                    $this->where()->concat(')');
                  }
                }


        if(!empty($this->categoria_array)){
          if(is_array($this->categoria_array))
          {
            $this->where()->concat("AND (");
            foreach($this->categoria_array as $id)
            {
                if(is_numeric($id))
                {
                    $this->where()->addOr("catalog_category.id",$id,'=');
                }
                else{
                    $this->where()->addOr("catalog_category.url_key",$id,'=');
                }
            }
              $this->where()->concat(')');
          }
         
            
           
          $this->from()->addInner('catalog_category_product','catalog_category_product.id_product','catalog_products.id');
          $this->from()->addInner('catalog_category','catalog_category_product.id_category','catalog_category.id');
          $this->setGrupo('catalog_products.id');
        }

        if(!empty($this->search_ids))
        {
            $this->where()->concat(" AND catalog_products.id in (".$this->search_ids.") ");
        }

        return $this->getColeccion($campos);
    }


    function getDataVitrina($data = array())
    {
        
        $data = $this->optimizeEntity($data);
        $campos = ["catalog_products.id","catalog_products.parent_id","catalog_products.type","catalog_products.set_attribute","catalog_products.name","sku","category",
        "catalog_products.visible_in_search","catalog_products.description",
        "images","videos","catalog_products.url_key","catalog_products.meta_title",
        "catalog_products.meta_keyword","catalog_products.meta_description",
        "price","stock","iva","incluye_iva","catalog_products.createdAt",
        "catalog_products.updateAt","catalog_products.status",
        "in_stock","saleable","min_qty","stock_infinito","envio_requerido","configurable"];

   
        if(!empty($this->categoria_array)){
            if(is_array($this->categoria_array))
            {
                $this->where()->concat("AND (");
                $this->where()->concat("(");
                foreach($this->categoria_array as $id)
                {
                    if(is_numeric($id))
                    {
                        $this->where()->addOr("catalog_category.id",$id,'=');
                    }
                    else{
                        $this->where()->addOr("catalog_category.url_key",$id,'=');
                    }
                }
                $this->where()->concat(')');
                $this->where()->addAnd("catalog_products.status",1,'=');
                $this->where()->addAnd("catalog_products.visible_in_search",1,'=');
                $this->where()->concat(')');
            }

            if(!empty($this->search_ids))
            {
                if(is_array($this->search_ids))
                {
                    $this->where()->concat("OR (");
                    $this->where()->concat("(");
                    foreach($this->search_ids as $id)
                    {
                        if(is_numeric($id))
                        {
                            
                            $this->where()->addOr("catalog_products.id",$id,'=');

                            
                        }
                        else{
                            $this->where()->addOr("catalog_products.url_key",$id,'=');
                        }
                        
                    }
                    $this->where()->concat(')');
                    $this->where()->addAnd("catalog_products.status",1,'=');
                    $this->where()->addAnd("catalog_products.visible_in_search",1,'=');
                    $this->where()->concat(')');
                }
    
            }

            
            
            $this->from()->addInner('catalog_category_product','catalog_category_product.id_product','catalog_products.id');
            $this->from()->addInner('catalog_category','catalog_category_product.id_category','catalog_category.id');
            $this->setGrupo('catalog_products.id');
        }
        else{
            if(!empty($this->search_ids))
            {
                $this->where()->concat(" AND catalog_products.id in (".$this->search_ids.") ");
                $this->where()->addAnd("catalog_products.status",1,'=');
                $this->where()->addAnd("catalog_products.visible_in_search",1,'=');
            }
            else{
                $this->where()->addAnd("catalog_products.status",1,'=');
                $this->where()->addAnd("catalog_products.visible_in_search",1,'=');
            }
        }

        

        return $this->getColeccion($campos);
    }

    function getInfoProducto($id)
    {

        $campos = ["catalog_products.id","precio","sku","ecommerce_precios.incluye_iva","ecommerce_precios.iva","name as nombre","images as imagen","stock","url_key","min_qty","stock_infinito","saleable","in_stock","min_qty","envio_requerido"];

        $this->where()->addAnd("catalog_products.id",$id,'=');

        $this->from()->addLeft('ecommerce_precios','catalog_products.id','ecommerce_precios.id_producto');

        return $this->getColeccion($campos);
    }



    private function optimizeEntity($array)
    {
        foreach ($array as $k => $v )
        {
            if (!isset($v)) {
                unset($array[$k]);
            }
        }
        return $array;
    }

    public function save($data)
    {
        $data = $this->optimizeEntity($data);


    	if (isset($data['id']))
    	{
            $this->where()->addAnd('id',$data['id'],'=');

            return $this->editarRegistro($data);
    	}
    	else {

            return $this->guardarRegistro($data);
    	}

    }

    function existeURLKEY($key,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('url_key',$key,'=');
        if(!empty($id))
        {
                        $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
    function existeSKU($sku,$id='')
    {
        $campos = array("id","images");
        $this->where()->addAnd('sku',$sku,'=');
        if(!empty($id))
        {
                        $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
