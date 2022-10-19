<?php
namespace Catalog\model;

class CatalogStoresModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;
    private $precio;
    private $categoria_array;
    private $excludeId;
    private $search_ids;
    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_stores');
    }


    
    public function setBusca($busca){
        $this->busca=$busca;
    }

    
    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["catalog_stores.id","catalog_stores.nombre","catalog_stores.idioma","catalog_stores.url","catalog_stores.moneda","createdAt","updateAt","catalog_monedas.nombre as moneda_nombre","abreviatura","simbolo","status"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_stores.".$k,$v,'=');
        }

        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('nombre','%'.$this->busca.'%','like');
          $this->where()->concat(')');
        }

        $this->from()->addInner('catalog_monedas','catalog_stores.moneda','catalog_monedas.id');
     

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

    function existe($url,$idioma,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('url',$url,'=');
        $this->where()->addAnd('idioma',$idioma,'=');
        if(!empty($id))
        {
            $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
