<?php
//URL: https://schema.org/BlogPosting

namespace Catalog\schema;

class itemListSchema extends \Franky\Schema\schema
{  
    function __construct() {
       
        parent::__construct("ItemList");
    }
    
    public function setUrl($val)
    {
        
       $this->json["url"] = $val;   
        
    }
    
    public function setNumberOfItems($val)
    {
        
       $this->json["numberOfItems"] = $val;   
        
    } 
   
    public function setItemListElement($val)
    {
        $this->json["itemListElement"][] = $val;   
    }
    
}
?>