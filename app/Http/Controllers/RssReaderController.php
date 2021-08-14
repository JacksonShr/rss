<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Item;
use App\Enclosure;

class RssReaderController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
   
    function enclosureCreate( $object, $item_id ){
        
        /*
        var_dump( $object);
        echo "<hr />";
        echo "$item_id";
        echo "<hr />";
        //if( !isset($value->enclosure) ) return;*/
        
        
        $enclosure = new Enclosure;
           
           $enclosure->url = $object['url'];
           
           $enclosure->type = $object['type'];
           
           $enclosure->length = $object['length'];
           
           $enclosure->item_id = $item_id;1;//$object->item_id;
           
           $enclosure->save();           
           
    
    }
    
    
    
    function getNews( $adress ){
    
        $adress = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
        
        ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0');
    
        $xml = simplexml_load_file( $adress );
        
        
        foreach( $xml->channel->item as $key => $value ){
        
           $res = Item::where('guid', $value->guid)->get();
        
           if( $res->isEmpty()===false ) continue;
           
           
           $item  = new Item;
           
           $item->title = $value->title;
           
           $item->description = $value->description;
           
           $item->guid = $value->guid;
           
           $item->link = $value->link;
           
           $item->pubDate = $value->pubDate;
           
           $item->save();
           
           
           //if ( !property_exists('enclosure', $value ) ) continue;
           if ( !isset( $value->enclosure['type']) ) continue;
           
                      
           if ( !is_array($value->enclosure) ){
           
                 $this->enclosureCreate($value->enclosure, $value->guid);
           
           } else {
           
                for( $i = 0; $i < count( $value->enclosure ); $i++ ){
                
                 $this->enclosureCreate($value->enclosure[$i], $value->guid); 
                
                }
           
           }
           
           
           
           
        
        }
        
        
        
    
    }
    
    
}
