<?php
//URL: https://schema.org/BlogPosting

namespace Catalog\schema;

class productSchema extends \Franky\Schema\schema
{  
    function __construct() {
       
        parent::__construct("Product");
    }
    
    public function setName($val)
    {
        
       $this->json["name"] = $val;   
        
    }
    
    public function setUrl($val)
    {
        $this->json["url"] = $val;   
    }
    
    public function setImage($val)
    {
        $this->json["image"] = $val;   
    }
    
    public function setOffers($val)
    {
        $this->json["offers"] = $val;   
    }

    public function setSku($val)
    {
        $this->json["sku"] = $val;   
    }
    
    public function setDescription($val)
    {
        $this->json["description"] = $val;   
    }
}
?>