<?php
namespace Catalog\model;

class CatalogUsersModel  extends \Franky\Database\Mysql\objectOperations
{
    private $busca;

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_users');
    }

    public function setBusca($busca){
        $this->busca=$busca;
    }


    function getData($data = array())
    {
        $data = $this->optimizeEntity($data);
        $campos = [
            "id",
            "username",
            "id_user",
            "image",
            "meta_title",
            "meta_description",
            "meta_keywords",
            "createdAt",
            "updateAt",
            "tipo_persona",
            "empresa",
            "rfc",
            "sector",
            "direccion",
            "latitud",
            "longitud",
            "logo",
            "descripcion",
            "horario",
            "web",
            "email",
            "tel",
            "wa",
            "fb",
            "ins",
            "x",
            "ttk",
            "verificado"
        ];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_users.".$k,$v,'=');
        }

        if($this->busca != "")
        {
          $this->where()->concat('AND (');
          $this->where()->addOr('empresa','%'.$this->busca.'%','like');
          $this->where()->addOr('descripcion','%'.$this->busca.'%','like');
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
    
    function existe($user,$id='')
    {
        $campos = array("id");
        $this->where()->addAnd('username',$user,'=');
        if(!empty($id))
        {
                        $this->where()->addAnd('id_user',$id,'<>');
        }
        return $this->getColeccion($campos);
    }
}
?>
