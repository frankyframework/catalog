<?php
namespace Catalog\entity;


class CatalogCatalogReviewsEntity
{
    private $id;
    private $parent_id;
    private $message;
    private $data;
    private $status;
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
        $this->message = (isset($data["message"]) ? $data["message"] : null);
        $this->data = (isset($data["data"]) ? $data["data"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
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

    public function message($message = null){ if($message != null){ $this->message=$message; }else{ return $this->message; } }

    public function data($data = null){ if($data != null){ $this->data=$data; }else{ return $this->data; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt != null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

}
?>
