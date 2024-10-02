<?php
namespace Catalog\entity;

class CatalogComentariosEntity
{
    public $id;
    public $parent_id;
    public $nombre;
    public $email;
    public $asunto;
    public $telefono;
    public $comentario;
    public $fecha;
    public $ip;

    public function __construct($data = null)
    {
        if (null != $data) {
            $this->exchangeArray($data);
        }
    }


    public function exchangeArray($data)
    {
        $this->id           = (isset($data['id']))          ? $data['id']           : null;
        $this->parent_id    = (isset($data['parent_id']))          ? $data['parent_id']           : null;
        $this->nombre       = (isset($data['nombre']))      ? $data['nombre']       : null;
        $this->email        = (isset($data['email']))       ? $data['email']        : null;
        $this->asunto       = (isset($data['asunto']))      ? $data['asunto']       : null;
        $this->telefono     = (isset($data['telefono']))    ? $data['telefono']     : null;
        $this->comentario   = (isset($data['comentario']))  ? $data['comentario']   : null;
        $this->fecha        = (isset($data['fecha']))       ? $data['fecha']        : null;
        $this->ip           = (isset($data['ip']))          ? $data['ip']           : null;
        
    }
    
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array(
        "Nombre" => array("valor" => $this->nombre,"required","apropiado","length"=> array("max" => "200")),
        "Producto" => array("valor" => $this->parent_id,"required"),
        "E-mail" => array("valor" => $this->email,"email","required"),
        "Asunto" =>array ("valor" => $this->asunto,"required","apropiado","length"=> array ("max"=>"200")),
        "Telefono" => array ("valor"=> $this->telefono,"length" =>array ("max"=>"21")),
        "Comentario" => array("valor" => $this->comentario,"required","boot","apropiado","length" => array("max" => "1000"))
        );
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getEmail()
    {
        return $this->email;
    }

    public function getAsunto()
    {
        return $this->asunto;
    }
    public function getTelefono()
    {
        return $this->telefono;
    }
    public function getComentario()
    {
        return $this->comentario;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getIp()
    {
        return $this->ip;
    }
    public function getParentId()
    {
        return $this->parent_id;
    }
   
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setAsunto($asunto)
    {
        $this->asunto = $asunto;
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

   public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }
    
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }
 }

