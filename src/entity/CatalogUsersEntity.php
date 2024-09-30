<?php
namespace Catalog\entity;

class CatalogUsersEntity
{
    private $id;
    private $id_user;
    private $username;
    private $image;
    private $meta_description;
    private $meta_keywords;
    private $meta_title;
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
        $this->id_user = (isset($data["id_user"]) ? $data["id_user"] : null);
        $this->username = (isset($data["username"]) ? $data["username"] : null);
        $this->image = (isset($data["image"]) ? $data["image"] : null);
        $this->meta_title = (isset($data["meta_title"]) ? $data["meta_title"] : null);
        $this->meta_description = (isset($data["meta_description"]) ? $data["meta_description"] : null);
        $this->meta_keywords = (isset($data["meta_keywords"]) ? $data["meta_keywords"] : null);
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
            );
    }

    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function id_user($id_user = null){ if($id_user !== null){ $this->id_user=$id_user; }else{ return $this->id_user; } }

    public function username($username = null){ if($username !== null){ $this->username=$username; }else{ return $this->username; } }

    public function meta_title($meta_title = null){ if($meta_title != null){ $this->meta_title=$meta_title; }else{ return $this->meta_title; } }

    public function meta_description($meta_description = null){ if($meta_description != null){ $this->meta_description=$meta_description; }else{ return $this->meta_description; } }

    public function meta_keywords($meta_keywords = null){ if($meta_keywords != null){ $this->meta_keywords=$meta_keywords; }else{ return $this->meta_keywords; } }

    public function image($image = null){ if($image != null){ $this->image=$image; }else{ return $this->image; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt !== null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }
}
?>
