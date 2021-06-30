<?php
namespace Catalog\entity;


class CatalogproductconfigurablesEntity
{
    private $id_parent;
    private $id_product;
    private $id_attribute;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }

    public function exchangeArray($data)
    {
        $this->id_parent = (isset($data["id_parent"]) ? $data["id_parent"] : null);
        $this->id_product = (isset($data["id_product"]) ? $data["id_product"] : null);
        $this->id_attribute = (isset($data["id_attribute"]) ? $data["id_attribute"] : null);
        
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( 
        "id_parent" => array("valor" => $this->id_parent,"required"),
        "id_product" => array("valor" => $this->id_product,"required"),
        "id_attribute" => array("valor" => $this->id_attribute,"required")
        );
    }

    public function id_parent($id_parent = null){ if($id_parent !== null){ $this->id_parent=$id_parent; }else{ return $this->id_parent; } }

    public function id_product($id_product = null){ if($id_product !== null){ $this->id_product=$id_product; }else{ return $this->id_product; } }

    public function id_attribute($id_attribute = null){ if($id_attribute !== null){ $this->id_attribute=$id_attribute; }else{ return $this->id_attribute; } }
}
?>
