<?php
namespace Catalog\model;


class CatalogComentariosModel  extends \Franky\Database\Mysql\objectOperations
{


          public function __construct()
          {
            parent::__construct();
            $this->from()->addTable('catalog_comentarios');
          }

        function getData($uid='',$busca='',$rango=array())
        {
            $campos = array("catalog_comentarios.id","catalog_comentarios.parent_id","catalog_comentarios.nombre",
            "catalog_comentarios.email","catalog_comentarios.telefono","catalog_comentarios.asunto",
            "catalog_comentarios.comentario","catalog_comentarios.fecha","catalog_comentarios.ip",
            "catalog_products.name as nombre_producto"
            );
            if(!empty($uid)) {
                $this->where()->addAnd('catalog_products.uid',$uid,'=');
            }
           
            if(!empty($busca))
            {
                  $this->where()->concat('AND (');
                  $this->where()->addOr('catalog_comentarios.email','%'.$busca.'%','like');
                  $this->where()->addOr('catalog_comentarios.nombre','%'.$busca.'%','like');
                  $this->where()->addOr('catalog_comentarios.comentario','%'.$busca.'%','like');
                  $this->where()->concat(')');
                }
            if(!empty($rango))
            {
                  $this->where()->concat('AND (');
                  $this->where()->addAnd('catalog_comentarios.fecha',$rango[0].' 00:00:00','>=');
                  $this->where()->addAnd('catalog_comentarios.fecha',$rango[1].' 23:59:59','<=');
                  $this->where()->concat(')');
            }
            $this->from()->addInner('catalog_products','catalog_comentarios.parent_id','catalog_products.id');

            return $this->getColeccion($campos);

        }

        private function optimizeEntity($array)
        {
            foreach ($array as $k => $v )
            {
                if (!isset($v)) {
                    unset($array[$k]);
                }
            }
            return $array;
        }

        public function save($contacto)
        {
            $contacto = $this->optimizeEntity($contacto);

            if (isset($contacto['id']))
            {
                $this->where()->addAnd('id',$contacto['id'],'=');
                return $this->editarRegistro($contacto);
            }
            else {

                return $this->guardarRegistro($contacto);
            }

        }

        public function delete($id)
        {
            $this->where()->addAnd('id',$id,'=');
            return $this->eliminarRegistro();
        }
}

?>
