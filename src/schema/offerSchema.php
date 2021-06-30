<?php
//URL: https://schema.org/BlogPosting

namespace Catalog\schema;

class offerSchema extends \Franky\Schema\schema
{  
    function __construct() {
       
        parent::__construct("Offer");
    }
    
    public function setPrice($val)
    {
        
       $this->json["price"] = $val;   
        
    }
    
    public function setPriceCurrency($val)
    {
        $this->json["priceCurrency"] = $val;   
    }
}
?>