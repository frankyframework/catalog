<?php
namespace Catalog\model;

class CatalogproductrelatedModel  extends \Franky\Database\Mysql\objectOperations
{
    private $dataProduct;
    
    
    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_product_related');
    }

    
    function setDataProduct($data)
    {
        $data = $this->optimizeEntity($data);
        $this->dataProduct = $data;
    }
    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id_parent","id_product","name","sku",'images','url_key',
            "price","stock","iva","incluye_iva","saleable","in_stock","min_qty","stock_infinito"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_product_related.".$k,$v,'=');
        }
        
        if(!empty($this->dataProduct))
        {
            foreach($this->dataProduct as $k => $v)
            {
                $this->where()->addAnd("catalog_products.".$k,$v,'=');
            }
        }
       
        
        $this->from()->addInner('catalog_products','catalog_products.id','catalog_product_related.id_product');

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

    function existe($parent,$product)
    {
        $campos = array("id_product");
        $this->where()->addAnd('id_parent',$parent,'=');
        $this->where()->addAnd('id_product',$product,'=');
        
        return $this->getColeccion($campos);
    }
    
    function eliminar($data)
    {
        $data = $this->optimizeEntity($data);
        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                $this->where()->addAnd("catalog_product_related.".$k,$v,'=');
            }
        }
      
        
        return $this->eliminarRegistro();
    }
    
    
    
}
?>
