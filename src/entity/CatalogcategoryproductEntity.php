<?php
namespace Catalog\entity;


class CatalogcategoryproductEntity
{
    private $id_subcategory;
    private $id_product;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id_subcategory = (isset($data["id_category"]) ? $data["id_category"] : null);
        $this->id_product = (isset($data["id_product"]) ? $data["id_product"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( "id_category" => array("valor" => $this->id_subcategory,"required"),"id_product" => array("valor" => $this->id_product,"required"),);
    }

    

    public function id_category($id_category = null){ if($id_category != null){ $this->id_category=$id_category; }else{ return $this->id_category; } }

    public function id_product($id_product = null){ if($id_product != null){ $this->id_product=$id_product; }else{ return $this->id_product; } }



}
?>
