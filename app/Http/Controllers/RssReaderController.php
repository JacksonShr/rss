<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Item;

class RssReaderController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
   
    function itemParse( $item ){
    
        
        $guid = $item->guid[0];
        
        
        $res = Item::where('guid', '=', $item['guid']);
        //var_dump( $res );
        
    
    }
    
    
    
    function getNews( $adress ){
    
        $adress = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
        
        ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0');
    
        $xml = simplexml_load_file( $adress );
        
        
        foreach( $xml->channel->item as $key => $value ){
        
           $res = Item::where('guid', $value->guid)->get();
        
           if( $res->isEmpty()===false ) continue;
           var_dump( $res);
        
        }
        
        
        //var_dump($xml);
    
    }
    
    
}
