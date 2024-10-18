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
    private $tipo_persona;
    private $empresa;
    private $rfc;
    private $sector;
    private $direccion;
    private $latitud;
    private $longitud;
    private $logo;
    private $descripcion;
    private $horario;
    private $web;
    private $email;
    private $tel;
    private $wa;
    private $fb;
    private $ins;
    private $x;
    private $ttk;

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
        $this->tipo_persona = (isset($data["tipo_persona"]) ? $data["tipo_persona"] : null);
        $this->empresa = (isset($data["empresa"]) ? $data["empresa"] : null);
        $this->rfc = (isset($data["rfc"]) ? $data["rfc"] : null);
        $this->sector = (isset($data["sector"]) ? $data["sector"] : null);
        $this->direccion = (isset($data["direccion"]) ? $data["direccion"] : null);
        $this->latitud = (isset($data["latitud"]) ? $data["latitud"] : null);
        $this->longitud = (isset($data["longitud"]) ? $data["longitud"] : null);
        $this->logo = (isset($data["logo"]) ? $data["logo"] : null);
        $this->descripcion = (isset($data["descripcion"]) ? $data["descripcion"] : null);
        $this->horario = (isset($data["horario"]) ? $data["updateAt"] : null);
        $this->web = (isset($data["web"]) ? $data["web"] : null);
        $this->email = (isset($data["email"]) ? $data["email"] : null);
        $this->tel = (isset($data["tel"]) ? $data["tel"] : null);
        $this->wa = (isset($data["wa"]) ? $data["wa"] : null);
        $this->fb = (isset($data["fb"]) ? $data["fb"] : null);
        $this->ins = (isset($data["ins"]) ? $data["ins"] : null);
        $this->x = (isset($data["x"]) ? $data["x"] : null);
        $this->ttk = (isset($data["ttk"]) ? $data["ttk"] : null);
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

    public function tipo_persona($tipo_persona = null){ if($tipo_persona !== null){ $this->tipo_persona=$tipo_persona; }else{ return $this->tipo_persona; } }

    public function empresa($empresa = null){ if($empresa !== null){ $this->empresa=$empresa; }else{ return $this->empresa; } }

    public function rfc($rfc = null){ if($rfc !== null){ $this->rfc=$rfc; }else{ return $this->rfc; } }

    public function sector($sector = null){ if($sector !== null){ $this->sector=$sector; }else{ return $this->sector; } }

    public function direccion($direccion = null){ if($direccion !== null){ $this->direccion=$direccion; }else{ return $this->direccion; } }

    public function latitud($latitud = null){ if($latitud !== null){ $this->latitud=$latitud; }else{ return $this->latitud; } }

    public function longitud($longitud = null){ if($longitud !== null){ $this->longitud=$longitud; }else{ return $this->longitud; } }

    public function logo($logo = null){ if($logo !== null){ $this->logo=$logo; }else{ return $this->logo; } }

    public function descripcion($descripcion = null){ if($descripcion !== null){ $this->descripcion=$descripcion; }else{ return $this->descripcion; } }

    public function horario($horario = null){ if($horario !== null){ $this->horario=$horario; }else{ return $this->horario; } }

    public function web($web = null){ if($web !== null){ $this->web=$web; }else{ return $this->web; } }

    public function email($email = null){ if($email !== null){ $this->email=$email; }else{ return $this->email; } }

    public function tel($tel = null){ if($tel !== null){ $this->tel=$tel; }else{ return $this->tel; } }

    public function wa($wa = null){ if($wa !== null){ $this->wa=$wa; }else{ return $this->wa; } }

    public function fb($fb = null){ if($fb !== null){ $this->fb=$fb; }else{ return $this->fb; } }

    public function ins($ins = null){ if($ins !== null){ $this->ins=$ins; }else{ return $this->ins; } }

    public function x($x = null){ if($x !== null){ $this->x=$x; }else{ return $this->x; } }

    public function ttk($ttk = null){ if($ttk !== null){ $this->ttk=$ttk; }else{ return $this->ttk; } }

    public function updateAt($updateAt = null){ if($updateAt !== null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }
}
?>
