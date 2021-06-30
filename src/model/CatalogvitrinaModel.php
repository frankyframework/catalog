<?php
namespace Catalog\model;

class CatalogvitrinaModel  extends \Franky\Database\Mysql\objectOperations
{
    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_vitrinas');
    }

    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["id","nombre","titulo","clave","random","numero","items","createdAt",
        "updateAt","status"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_vitrinas.".$k,$v,'=');
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

    function existeClave($clave,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('clave',$clave,'=');
        if(!empty($id))
        {
                        $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
