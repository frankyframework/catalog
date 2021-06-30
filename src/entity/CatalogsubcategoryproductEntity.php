<?php
namespace Catalog\entity;


class CatalogsubcategoryproductEntity
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
        $this->id_subcategory = (isset($data["id_subcategory"]) ? $data["id_subcategory"] : null);
        $this->id_product = (isset($data["id_product"]) ? $data["id_product"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( "id_subcategory" => array("valor" => $this->id_subcategory,"required"),"id_product" => array("valor" => $this->id_product,"required"),);
    }

    

    public function id_subcategory($id_subcategory = null){ if($id_subcategory != null){ $this->id_subcategory=$id_subcategory; }else{ return $this->id_subcategory; } }

    public function id_product($id_product = null){ if($id_product != null){ $this->id_product=$id_product; }else{ return $this->id_product; } }



}
?>
