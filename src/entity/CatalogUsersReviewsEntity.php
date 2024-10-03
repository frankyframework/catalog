<?php
namespace Catalog\entity;


class CatalogUsersReviewsEntity
{
    private $id;
    private $parent_id;
    private $message;
    private $rfc;
    private $ine_anverso;
    private $ine_reverso;
    private $comprobante;
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
        $this->rfc = (isset($data["rfc"]) ? $data["rfc"] : null);
        $this->ine_anverso = (isset($data["ine_anverso"]) ? $data["ine_anverso"] : null);
        $this->ine_reverso = (isset($data["ine_reverso"]) ? $data["ine_reverso"] : null);
        $this->comprobante = (isset($data["comprobante"]) ? $data["comprobante"] : null);
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
        );
     }

    

    public function id($id = null){ if($id != null){ $this->id=$id; }else{ return $this->id; } }

    public function parent_id($parent_id = null){ if($parent_id != null){ $this->parent_id=$parent_id; }else{ return $this->parent_id; } }

    public function message($message = null){ if($message !== null){ $this->message=$message; }else{ return $this->message; } }

    public function ine_anverso($ine_anverso = null){ if($ine_anverso != null){ $this->ine_anverso=$ine_anverso; }else{ return $this->ine_anverso; } }

    public function ine_reverso($ine_reverso = null){ if($ine_reverso != null){ $this->ine_reverso=$ine_reverso; }else{ return $this->ine_reverso; } }

    public function comprobante($comprobante = null){ if($comprobante != null){ $this->comprobante=$comprobante; }else{ return $this->comprobante; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }

    public function rfc($rfc = null){ if($rfc !== null){ $this->rfc=$rfc; }else{ return $this->rfc; } }

    public function createdAt($createdAt = null){ if($createdAt != null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt != null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

}
?>
