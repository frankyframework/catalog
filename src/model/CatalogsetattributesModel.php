<?php
namespace Catalog\model;

class CatalogsetattributesModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_set_attributes');
    }


    public function setBusca($busca){
        $this->busca=$busca;
    }


    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id","uid","name","description","attributes","status","orden","createdAt"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_set_attributes.".$k,$v,'=');
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
            $this->where()->addAnd("catalog_set_attributes.".$k,$v,'=');
        }
        
        return $this->eliminarRegistro();
    	

    }

    function existe($category,$id='',$uid='')
    {
        $campos = array("id");
        $this->where()->addAnd('name',$category,'=');
        if(!empty($id))
        {
            $this->where()->addAnd('id',$id,'<>');
        }
        if(!empty($uid))
        {
            $this->where()->addAnd('uid',$uid,'=');
        }
        return $this->getColeccion($campos);
    }
}
?>
