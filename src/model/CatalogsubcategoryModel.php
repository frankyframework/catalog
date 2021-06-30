<?php
namespace Catalog\model;

class CatalogsubcategoryModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;
    private $data_categoria;
    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_subcategory');
    }

    public function setBusca($busca){
        $this->busca=$busca;
    }

    public function setDataCategoria($data){
        $this->data_categoria = $this->optimizeEntity($data);
    }

    
    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["catalog_subcategory.id","id_category","catalog_subcategory.name",
        "catalog_subcategory.description","catalog_subcategory.image",
        "catalog_subcategory.visible_in_search",
        "catalog_subcategory.users",
        "catalog_subcategory.meta_title",
        "catalog_subcategory.meta_description",
        "catalog_subcategory.meta_keywords",
        "catalog_subcategory.url_key",
        "catalog_subcategory.status",
        "catalog_subcategory.createdAt",
        "catalog_subcategory.orden",
        "catalog_category.name as categoria"];

        if(!empty($data))
        {
            foreach($data as $k => $v)
            {
                $this->where()->addAnd("catalog_subcategory.".$k,$v,'=');
            }
        }
        if(!empty($this->data_categoria))
        {
            foreach($this->data_categoria as $k => $v)
            {
                $this->where()->addAnd("catalog_category.".$k,$v,'=');
            }
        }
        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('catalog_subcategory.name','%'.$this->busca.'%','like');
          $this->where()->addOr('catalog_category.name','%'.$this->busca.'%','like');
          $this->where()->addOr('catalog_subcategory.description','%'.$this->busca.'%','like');
          $this->where()->concat(')');
        }

        $this->from()->addInner('catalog_category','catalog_category.id','catalog_subcategory.id_category');
          

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

    function existe($subcategory,$category,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('name',$category,'=');
        $this->where()->addAnd('id_category',$subcategory,'=');
        if(!empty($id))
        {
                $this->where()->addAnd('id',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
