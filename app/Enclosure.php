<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enclosure extends Model
{
    //
    
    protected $table = "enclosures";
    public $timestamps = false;
    
    function enclosureCreate( $object, $item_id ){
        
           
           $this->url = $object['url'];
           
           $this->type = $object['type'];
           
           $this->length = $object['length'];
           
           $this->item_id = $item_id;
           
           $this->save();           
           
    
    }
    
    
}
