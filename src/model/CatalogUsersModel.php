<?php
namespace Catalog\model;

class CatalogUsersModel  extends \Franky\Database\Mysql\objectOperations
{

    public function __construct()
    {
      parent::__construct();
      $this->from()->addTable('catalog_users');
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
            "ttk"
        ];

        foreach($data as $k => $v)
        {
            $this->where()->addAnd("catalog_users.".$k,$v,'=');
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
