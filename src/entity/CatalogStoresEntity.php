<?php
namespace Catalog\entity;

 
class CatalogStoresEntity
{
    private $id;
    private $nombre;
    private $idioma;
    private $url;
    private $moneda;
    private $status;
    private $createdAt;
    private $updateAt;
    private $marketplace;

    
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
        $this->idioma = (isset($data["idioma"]) ? $data["idioma"] : null);
        $this->url = (isset($data["url"]) ? $data["url"] : null);
        $this->moneda = (isset($data["moneda"]) ? $data["moneda"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
        $this->marketplace = (isset($data["marketplace"]) ? $data["marketplace"] : null);

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

    public function idioma($idioma = null){ if($idioma != null){ $this->idioma=$idioma; }else{ return $this->idioma; } }

    public function status($status = null){ if($status != null){ $this->status=$status; }else{ return $this->status; } }

    public function moneda($moneda = null){ if($moneda != null){ $this->moneda=$moneda; }else{ return $this->moneda; } }
    
    public function url($url = null){ if($url != null){ $this->url=$url; }else{ return $this->url; } }
    
    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }
    
    public function updateAt($updateAt = null){ if($updateAt != null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

    public function marketplace($marketplace = null){ if($marketplace != null){ $this->marketplace=$marketplace; }else{ return $this->marketplace; } }
  
}
?>