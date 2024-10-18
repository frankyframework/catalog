<?php
namespace Catalog\model;

class CustomattributesvaluesModel  extends \Franky\Database\Mysql\objectOperations
{

    private $ids;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_custom_attributes_values');
      $this->ids = [];
    }


    public function setIds($ids)
    {
        $this->ids = $ids;
    }
    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id_attribute","id_ref","value","entity"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_custom_attributes_values.".$k,$v,'=');
        }

        if(!empty($this->ids))
        {
            $this->where()->concat('AND catalog_custom_attributes_values.id_ref in ('.implode(',',$this->ids).') ');
          
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

        return $this->guardarRegistro($data);
    	

    }

    public function remove($data)
    {
        $data = $this->optimizeEntity($data);
        if (!empty($data))
    	{
            foreach($data as $k => $v)
            {
                $this->where()->addAnd("catalog_custom_attributes_values.".$k,$v,'=');
            }

            return $this->eliminarRegistro();
    	}
    	return false;
    }
}
?>
