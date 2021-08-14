<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Item;
use App\Enclosure;
use App\QueryLogs;

class RssReaderController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
   
    function enclosureCreate( $object, $item_id ){
        
        
        
        $enclosure = new Enclosure;
           
           $enclosure->url = $object['url'];
           
           $enclosure->type = $object['type'];
           
           $enclosure->length = $object['length'];
           
           $enclosure->item_id = $item_id;
           
           $enclosure->save();           
           
    
    }
    
    
    
    function logQuery( $logArr ){
    
    
        $newLogRow = new QueryLogs;
        
        $newLogRow->requestUrl = $logArr['url'];
        
        $newLogRow->requestMethod = $logArr['method'];
        
        $newLogRow->responseHttpCode = $logArr['httpCode'];
        
        $newLogRow->timestamp = date('H:i:s d-m-y');
        
        $newLogRow->save();
    
    }
    
    
    function getNews( $url ){
    
        $logArr['url'] = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
        
        $logArr['method'] = 'GET';
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 1);
        
        curl_setopt($curl, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
        curl_setopt($curl, CURLOPT_AUTOREFERER, true); 
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 1);
        curl_setopt_array($curl, array(
        CURLOPT_CUSTOMREQUEST => $logArr['method'],
        CURLOPT_URL => $logArr['url'],
        CURLOPT_RETURNTRANSFER => true
        ));
        $response = curl_exec($curl);
        
        if ($response !== false)
            {
              $curlInfo = curl_getinfo($curl);
              $logArr['httpCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
              $logArr['header'] = substr($response, 0, $curlInfo['header_size']);
              $response = substr($response, $curlInfo['header_size']);
            }
            
        curl_close($curl);
        
        
        
        $xml = simplexml_load_string( $response );
        
                
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
           
           
           if ( !isset( $value->enclosure['type']) ) continue;
           
                      
           if ( !is_array($value->enclosure) ){
           
                 $this->enclosureCreate($value->enclosure, $value->guid);
           
           } else {
           
                for( $i = 0; $i < count( $value->enclosure ); $i++ ){
                
                 $this->enclosureCreate($value->enclosure[$i], $value->guid); 
                
                }
           
           }
           
           
           
           
        
        }
        
        
       
    $this->logQuery( $logArr );
    }
    
    
}
