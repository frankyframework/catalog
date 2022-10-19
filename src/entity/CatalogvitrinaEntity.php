<?php
namespace Catalog\entity;


class CatalogvitrinaEntity
{
    private $id;
    private $store;
    private $nombre;
    private $titulo;
    private $clave;
    private $random;
    private $numero;
    private $items;
    private $createdAt;
    private $updateAt;
    private $status;
    


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->store = (isset($data["store"]) ? $data["store"] : null);
        $this->nombre = (isset($data["nombre"]) ? $data["nombre"] : null);
        $this->titulo = (isset($data["titulo"]) ? $data["titulo"] : null);
        $this->clave = (isset($data["clave"]) ? $data["clave"] : null);
        $this->random = (isset($data["random"]) ? $data["random"] : null);
        $this->numero = (isset($data["numero"]) ? $data["numero"] : null);
        $this->items = (isset($data["items"]) ? $data["items"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( 
            "Tienda" => array("valor" => $this->store,"required"),
            "Nombre de la vitrina" => array("valor" => $this->nombre,"required"),
            "Titulo de la vitrina" => array("valor" => $this->titulo,"required"),
            "Numero de productos" => array("valor" => $this->numero,"required","numeric")
        );
    }

    

    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function store($store = null){ if($store !== null){ $this->store=$store; }else{ return $this->store; } }

    public function nombre($nombre = null){ if($nombre !== null){ $this->nombre=$nombre; }else{ return $this->nombre; } }

    public function titulo($titulo = null){ if($titulo !== null){ $this->titulo=$titulo; }else{ return $this->titulo; } }

    public function clave($clave = null){ if($clave !== null){ $this->clave=$clave; }else{ return $this->clave; } }

    public function random($random = null){ if($random !== null){ $this->random=$random; }else{ return $this->random; } }

    public function numero($numero = null){ if($numero !== null){ $this->numero=$numero; }else{ return $this->numero; } }

    public function items($items = null){ if($items !== null){ $this->items=$items; }else{ return $this->items; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt !== null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }
   
}
?>
