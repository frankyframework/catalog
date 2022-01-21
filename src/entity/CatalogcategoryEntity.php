<?php
namespace Catalog\entity;


class CatalogcategoryEntity
{
    private $id;
    private $parent_id;
    private $name;
    private $description;
    private $image;
    private $visible_in_search;
    private $users;
    private $meta_title;
    private $meta_description;
    private $meta_keywords;
    private $url_key;
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
        $this->parent_id = (isset($data["parent_id"]) ? $data["parent_id"] : null);
        $this->name = (isset($data["name"]) ? $data["name"] : null);
        $this->description = (isset($data["description"]) ? $data["description"] : null);
        $this->image = (isset($data["image"]) ? $data["image"] : null);
        $this->visible_in_search = (isset($data["visible_in_search"]) ? $data["visible_in_search"] : null);
        $this->users = (isset($data["users"]) ? $data["users"] : null);
        $this->meta_title = (isset($data["meta_title"]) ? $data["meta_title"] : null);
        $this->meta_description = (isset($data["meta_description"]) ? $data["meta_description"] : null);
        $this->meta_keywords = (isset($data["meta_keywords"]) ? $data["meta_keywords"] : null);
        $this->url_key = (isset($data["url_key"]) ? $data["url_key"] : null);
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

    public function parent_id($parent_id = null){ if($parent_id != null){ $this->parent_id=$parent_id; }else{ return $this->parent_id; } }

    public function name($name = null){ if($name != null){ $this->name=$name; }else{ return $this->name; } }

    public function description($description = null){ if($description != null){ $this->description=$description; }else{ return $this->description; } }

    public function image($image = null){ if($image != null){ $this->image=$image; }else{ return $this->image; } }

    public function visible_in_search($visible_in_search = null){ if($visible_in_search !== null){ $this->visible_in_search=$visible_in_search; }else{ return $this->visible_in_search; } }

    public function users($users = null){ if($users != null){ $this->users=$users; }else{ return $this->users; } }

    public function meta_title($meta_title = null){ if($meta_title != null){ $this->meta_title=$meta_title; }else{ return $this->meta_title; } }

    public function meta_description($meta_description = null){ if($meta_description != null){ $this->meta_description=$meta_description; }else{ return $this->meta_description; } }

    public function meta_keywords($meta_keywords = null){ if($meta_keywords != null){ $this->meta_keywords=$meta_keywords; }else{ return $this->meta_keywords; } }

    public function url_key($url_key = null){ if($url_key != null){ $this->url_key=$url_key; }else{ return $this->url_key; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function orden($orden = null){ if($orden != null){ $this->orden=$orden; }else{ return $this->orden; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt != null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }
}
?>
