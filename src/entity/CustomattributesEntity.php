<?php
namespace Catalog\entity;


class CustomattributesEntity
{
    private $id;
    private $uid;
    private $name;
    private $label;
    private $type;
    private $data;
    private $source;
    private $entity;
    private $createdAt;
    private $updateAt;
    private $status;
    private $required;
    private $extra;
    private $icon;
    private $searchable;


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
        $this->name = (isset($data["name"]) ? $data["name"] : null);
        $this->label = (isset($data["label"]) ? $data["label"] : null);
        $this->type = (isset($data["type"]) ? $data["type"] : null);
        $this->data = (isset($data["data"]) ? $data["data"] : null);
        $this->icon = (isset($data["icon"]) ? $data["icon"] : null);
        $this->source = (isset($data["source"]) ? $data["source"] : null);
        $this->entity = (isset($data["entity"]) ? $data["entity"] : null);
        $this->searchable = (isset($data["searchable"]) ? $data["searchable"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->required = (isset($data["required"]) ? $data["required"] : null);
        $this->extra = (isset($data["extra"]) ? $data["extra"] : null);

    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( "Nombre" => array("valor" => $this->name,"required"),
        "Etiqueta" => array("valor" => $this->label,"required"),
        "Tipo campo" => array("valor" => $this->type,"required"),
        "Entidad" => array("valor" => $this->entity,"required")
        );
    }

    

    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function parent_id($parent_id = null){ if($parent_id != null){ $this->parent_id=$parent_id; }else{ return $this->parent_id; } }

    public function uid($uid = null){ if($uid != null){ $this->uid=$uid; }else{ return $this->uid; } }

    public function name($name = null){ if($name != null){ $this->name=$name; }else{ return $this->name; } }

    public function label($label = null){ if($label != null){ $this->label=$label; }else{ return $this->label; } }

    public function type($type = null){ if($type != null){ $this->type=$type; }else{ return $this->type; } }

    public function icon($icon = null){ if($icon != null){ $this->icon=$icon; }else{ return $this->icon; } }

    public function data($data = null){ if($data !== null){ $this->data=$data; }else{ return $this->data; } }

    public function source($source = null){ if($source !== null){ $this->source=$source; }else{ return $this->source; } }

    public function entity($entity = null){ if($entity != null){ $this->entity=$entity; }else{ return $this->entity; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt != null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function required($required = null){ if($required !== null){ $this->required=$required; }else{ return $this->required; } }

    public function extra($extra = null){ if($extra !== null){ $this->extra=$extra; }else{ return $this->extra; } }

    public function searchable($searchable = null){ if($searchable !== null){ $this->searchable=$searchable; }else{ return $this->searchable; } }
    
}
?>
