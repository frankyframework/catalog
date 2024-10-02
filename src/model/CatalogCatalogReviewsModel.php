<?php
namespace Catalog\model;

class CatalogCatalogReviewsModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_catalog_reviews');
    }


    public function setBusca($busca){
        $this->busca=$busca;
    }


    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["catalog_catalog_reviews.id","catalog_catalog_reviews.parent_id","message","data","catalog_catalog_reviews.status","catalog_catalog_reviews.updateAt","catalog_catalog_reviews.createdAt","name","url_key"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_catalog_reviews.".$k,$v,'=');
        }

        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('data','%'.$this->busca.'%','like');
          $this->where()->addOr('message','%'.$this->busca.'%','like');
          $this->where()->concat(')');
        }

        $this->from()->addInner('catalog_products','catalog_catalog_reviews.parent_id','catalog_products.id');
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
}
?>
