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
        $campos = ["catalog_vitrinas.id","store","catalog_vitrinas.nombre","titulo","clave","random","numero","items","catalog_vitrinas.createdAt",
        "catalog_vitrinas.updateAt","catalog_vitrinas.status","catalog_stores.nombre as store_nombre"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_vitrinas.".$k,$v,'=');
        }

        $this->from()->addInner('catalog_stores','catalog_stores.id','catalog_vitrinas.store');
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

    function existeClave($clave,$store,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('clave',$clave,'=');
        $this->where()->addAnd('store',$store,'=');
        if(!empty($id))
        {
            $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
