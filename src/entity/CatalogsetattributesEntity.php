<?php
namespace Catalog\entity;

class CatalogsetattributesEntity
{
    private $id;
    private $uid;
    private $parent_id;
    private $name;
    private $description;
    private $attributes;
    private $status;
    private $orden;
    private $createdAt;
    private $updateAt;

    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data["id"]) ? $data["id"] : null);
        $this->uid = (isset($data["uid"]) ? $data["uid"] : null);
        $this->parent_id = (isset($data["parent_id"]) ? $data["parent_id"] : null);
        $this->name = (isset($data["name"]) ? $data["name"] : null);
        $this->description = (isset($data["description"]) ? $data["description"] : null);
        $this->attributes = (isset($data["attributes"]) ? $data["attributes"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->orden = (isset($data["orden"]) ? $data["orden"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
            "name" => array("valor" => $this->name,"required"),
        );
    }

    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function uid($uid = null){ if($uid != null){ $this->uid=$uid; }else{ return $this->uid; } }

    public function parent_id($parent_id = null){ if($parent_id != null){ $this->parent_id=$parent_id; }else{ return $this->parent_id; } }

    public function name($name = null){ if($name != null){ $this->name=$name; }else{ return $this->name; } }

    public function description($description = null){ if($description != null){ $this->description=$description; }else{ return $this->description; } }

    public function attributes($attributes = null){ if($attributes != null){ $this->attributes=$attributes; }else{ return $this->attributes; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function orden($orden = null){ if($orden != null){ $this->orden=$orden; }else{ return $this->orden; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt != null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }
}
?>
