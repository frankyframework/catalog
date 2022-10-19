<?php
namespace Catalog\entity;


class CatalogproductsEntity
{
    private $id;
    private $parent_id;
    private $store;
    private $set_attribute;
    private $type;
    private $name;
    private $sku;
    private $category;
    private $visible_in_search;
    private $description;
    private $images;
    private $videos;
    private $url_key;
    private $meta_title;
    private $meta_keyword;
    private $meta_description;
    private $price;
    private $stock;
    private $iva;
    private $incluye_iva;
    private $createdAt;
    private $updateAt;
    private $status;
    private $in_stock;
    private $saleable;
    private $min_qty;
    private $stock_infinito;
    private $envio_requerido;
    private $configurable;


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
        $this->set_attribute = (isset($data["set_attribute"]) ? $data["set_attribute"] : null);
        $this->type = (isset($data["type"]) ? $data["type"] : null);
        $this->name = (isset($data["name"]) ? $data["name"] : null);
        $this->sku = (isset($data["sku"]) ? $data["sku"] : null);
        $this->category = (isset($data["category"]) ? $data["category"] : null);
        $this->visible_in_search = (isset($data["visible_in_search"]) ? $data["visible_in_search"] : null);
        $this->description = (isset($data["description"]) ? $data["description"] : null);
        $this->images = (isset($data["images"]) ? $data["images"] : null);
        $this->videos = (isset($data["videos"]) ? $data["videos"] : null);
        $this->url_key = (isset($data["url_key"]) ? $data["url_key"] : null);
        $this->meta_title = (isset($data["meta_title"]) ? $data["meta_title"] : null);
        $this->meta_keyword = (isset($data["meta_keyword"]) ? $data["meta_keyword"] : null);
        $this->meta_description = (isset($data["meta_description"]) ? $data["meta_description"] : null);
        $this->price = (isset($data["price"]) ? $data["price"] : null);
        $this->stock = (isset($data["stock"]) ? $data["stock"] : null);
        $this->iva = (isset($data["iva"]) ? $data["iva"] : null);
        $this->incluye_iva = (isset($data["incluye_iva"]) ? $data["incluye_iva"] : null);
        $this->createdAt = (isset($data["createdAt"]) ? $data["createdAt"] : null);
        $this->updateAt = (isset($data["updateAt"]) ? $data["updateAt"] : null);
        $this->status = (isset($data["status"]) ? $data["status"] : null);
        $this->in_stock = (isset($data["in_stock"]) ? $data["in_stock"] : null);
        $this->saleable = (isset($data["saleable"]) ? $data["saleable"] : null);
        $this->min_qty = (isset($data["min_qty"]) ? $data["min_qty"] : null);
        $this->stock_infinito = (isset($data["stock_infinito"]) ? $data["stock_infinito"] : null);
        $this->envio_requerido = (isset($data["envio_requerido"]) ? $data["envio_requerido"] : null);
        $this->configurable = (isset($data["configurable"]) ? $data["configurable"] : null);
        $this->store = (isset($data["store"]) ? $data["store"] : null);
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function setValidation()
    {
        return array( 
            "Nombre producto" => array("valor" => $this->name,"required"),
            "SKU" => array("valor" => $this->sku,"required")
        );
    }

    

    public function id($id = null){ if($id !== null){ $this->id=$id; }else{ return $this->id; } }

    public function parent_id($parent_id = null){ if($parent_id !== null){ $this->parent_id=$parent_id; }else{ return $this->parent_id; } }
    
    public function set_attribute($set_attribute = null){ if($set_attribute !== null){ $this->set_attribute=$set_attribute; }else{ return $this->set_attribute; } }
    
    public function type($type = null){ if($type !== null){ $this->type=$type; }else{ return $this->type; } }

    public function name($name = null){ if($name !== null){ $this->name=$name; }else{ return $this->name; } }

    public function sku($sku = null){ if($sku !== null){ $this->sku=$sku; }else{ return $this->sku; } }

    public function category($category = null){ if($category !== null){ $this->category=$category; }else{ return $this->category; } }

    public function visible_in_search($visible_in_search = null){ if($visible_in_search !== null){ $this->visible_in_search=$visible_in_search; }else{ return $this->visible_in_search; } }

    public function description($description = null){ if($description !== null){ $this->description=$description; }else{ return $this->description; } }

    public function images($images = null){ if($images !== null){ $this->images=$images; }else{ return $this->images; } }

    public function videos($videos = null){ if($videos !== null){ $this->videos=$videos; }else{ return $this->videos; } }

    public function url_key($url_key = null){ if($url_key !== null){ $this->url_key=$url_key; }else{ return $this->url_key; } }

    public function meta_title($meta_title = null){ if($meta_title !== null){ $this->meta_title=$meta_title; }else{ return $this->meta_title; } }

    public function meta_keyword($meta_keyword = null){ if($meta_keyword !== null){ $this->meta_keyword=$meta_keyword; }else{ return $this->meta_keyword; } }

    public function meta_description($meta_description = null){ if($meta_description !== null){ $this->meta_description=$meta_description; }else{ return $this->meta_description; } }

    public function price($price = null){ if($price !== null){ $this->price=$price; }else{ return $this->price; } }

    public function stock($stock = null){ if($stock !== null){ $this->stock=$stock; }else{ return $this->stock; } }

    public function iva($iva = null){ if($iva !== null){ $this->iva=$iva; }else{ return $this->iva; } }

    public function incluye_iva($incluye_iva = null){ if($incluye_iva !== null){ $this->incluye_iva=$incluye_iva; }else{ return $this->incluye_iva; } }

    public function createdAt($createdAt = null){ if($createdAt !== null){ $this->createdAt=$createdAt; }else{ return $this->createdAt; } }

    public function updateAt($updateAt = null){ if($updateAt !== null){ $this->updateAt=$updateAt; }else{ return $this->updateAt; } }

    public function status($status = null){ if($status !== null){ $this->status=$status; }else{ return $this->status; } }
   
    public function in_stock($in_stock = null){ if($in_stock !== null){ $this->in_stock=$in_stock; }else{ return $this->in_stock; } }

    public function saleable($saleable = null){ if($saleable !== null){ $this->saleable=$saleable; }else{ return $this->saleable; } }

    public function min_qty($min_qty = null){ if($min_qty !== null){ $this->min_qty=$min_qty; }else{ return $this->min_qty; } }

    public function stock_infinito($stock_infinito = null){ if($stock_infinito !== null){ $this->stock_infinito=$stock_infinito; }else{ return $this->stock_infinito; } }

    public function envio_requerido($envio_requerido = null){ if($envio_requerido !== null){ $this->envio_requerido=$envio_requerido; }else{ return $this->envio_requerido; } }

    public function configurable($configurable = null){ if($configurable !== null){ $this->configurable=$configurable; }else{ return $this->configurable; } }

    public function store($store = null){ if($store != null){ $this->store=$store; }else{ return $this->store; } }
}
?>
