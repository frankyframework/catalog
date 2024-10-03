<?php
namespace Catalog\model;

class CatalogUsersReviewsModel  extends \Franky\Database\Mysql\objectOperations
{

    private $busca;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_users_reviews');
    }


    public function setBusca($busca){
        $this->busca=$busca;
    }


    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = ["catalog_users_reviews.id","catalog_users_reviews.parent_id","message","ine_anverso","ine_reverso","comprobante","rfc","catalog_users_reviews.status","catalog_users_reviews.updateAt","catalog_users_reviews.createdAt","nombre","email"];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_users_reviews.".$k,$v,'=');
        }

        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('message','%'.$this->busca.'%','like');
          $this->where()->concat(')');
        }

        $this->from()->addInner('users','catalog_users_reviews.parent_id','users.id');
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
