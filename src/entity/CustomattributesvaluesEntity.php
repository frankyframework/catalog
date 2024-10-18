<?php
namespace Catalog\entity;


class CustomattributesvaluesEntity
{
    private $id_attribute;
    private $id_ref;
    private $value;
    private $entity;


    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id_attribute = (isset($data["id_attribute"]) ? $data["id_attribute"] : null);
        $this->id_ref = (isset($data["id_ref"]) ? $data["id_ref"] : null);
        $this->value = (isset($data["value"]) ? $data["value"] : null);
        $this->entity = (isset($data["entity"]) ? $data["entity"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( "Atributo" => array("valor" => $this->id_attribute,"required"),
        "id_ref" => array("valor" => $this->id_ref,"required"),
        "Valor" => array("valor" => $this->value,"required"),
        "entity" => array("valor" => $this->entity,"required"),
        );
    }

    
    public function id_attribute($id_attribute = null){ if($id_attribute != null){ $this->id_attribute=$id_attribute; }else{ return $this->id_attribute; } }

    public function id_ref($id_ref = null){ if($id_ref != null){ $this->id_ref=$id_ref; }else{ return $this->id_ref; } }

    public function value($value = null){ if($value != null){ $this->value=$value; }else{ return $this->value; } }

    public function entity($entity = null){ if($entity != null){ $this->entity=$entity; }else{ return $this->entity; } }



}
?>
