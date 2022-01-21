<?php
namespace Catalog\model;

class CatalogcategoryproductModel  extends \Franky\Database\Mysql\objectOperations
{

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_category_product');
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id_category","id_product"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_category_product.".$k,$v,'=');
        }

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
    
    public function remove($data)
    {
        $data = $this->optimizeEntity($data);
        if (!empty($data))
    	{
            foreach($data as $k => $v)
            {
                $this->where()->addAnd("catalog_category_product.".$k,$v,'=');
            }

            return $this->eliminarRegistro();
    	}
    	return false;
    }
}
?>
