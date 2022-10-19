<?php
namespace Catalog\entity;

 
class CatalogMonedasEntity
{
    private $id;
    private $nombre;
    private $simbolo;
    private $abreviatura;

    
    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->nombre = (isset($data["nombre"]) ? $data["nombre"] : null);
        $this->simbolo = (isset($data["simbolo"]) ? $data["simbolo"] : null);
        $this->abreviatura = (isset($data["abreviatura"]) ? $data["abreviatura"] : null);

    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array();
    }

    
    
    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function nombre($nombre = null){ if($nombre != null){ $this->nombre=$nombre; }else{ return $this->nombre; } }

    public function simbolo($simbolo = null){ if($simbolo != null){ $this->simbolo=$simbolo; }else{ return $this->simbolo; } }

    public function abreviatura($abreviatura = null){ if($abreviatura != null){ $this->abreviatura=$abreviatura; }else{ return $this->abreviatura; } }


  
}
?>