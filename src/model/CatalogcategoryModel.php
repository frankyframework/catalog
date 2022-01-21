<?php
namespace Catalog\model;

class CatalogcategoryModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_category');
    }


    public function setBusca($busca){
        $this->busca=$busca;
    }


    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id","parent_id","name","description","image","visible_in_search","users","meta_title","meta_description","meta_keywords","url_key","status","orden","createdAt"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_category.".$k,$v,'=');
        }

        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('name','%'.$this->busca.'%','like');
          $this->where()->addOr('description','%'.$this->busca.'%','like');
          $this->where()->concat(')');
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

    public function eliminar($data)
    {
        $data = $this->optimizeEntity($data);


        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_category.".$k,$v,'=');
        }
        
        return $this->eliminarRegistro();
    	

    }

    function existe($category,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('name',$category,'=');
        if(!empty($id))
        {
                        $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
