<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class RssReaderController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
   
    function itemParse( $item ){
    
        $table = "ITEMS";
        $fieldStr = '';
        $valueStr = '';
        
        $guid = $item->guid[0];
        
        $res = DB::select('select * from ITEMS where guid = :guid', [ 'guid' => $guid ]);
        
        //var_dump( $res );
       // var_dump( $item->guid);
        //echo('<hr />');
        if( count($res) != 0 ) return;
        
        foreach( $item as $key => $value ){
            
            if( $key == 'enclosure' ) continue;
        
            $fieldStr = $fieldStr . $key . ', ';
            $valueStr = $valueStr . $value . ', ';
        
        }
        $fieldStr = substr($fieldStr, 0, -2);
        $valueStr = substr($valueStr, 0, -2);
        
        
        //var_dump( $valueStr . "<hr />");
        DB::insert('insert into ITEMS (id, title, link, description, guid, pubDate) values (NULL, :title, :link, :description, :guid, :pubDate)', [ 'title' => $item['title'], 'link' => $item['link'], 'description' => $item['description'], 'guid' => $item['guid'], 'pubDate' => 1 ]  );
    
    
    }
    
    
    
    function getNews( $adress ){
    
        $adress = "http://static.feed.rbc.ru/rbc/logical/footer/news.rss";
        
        ini_set('user_agent', 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:16.0) Gecko/20100101 Firefox/16.0');
    
        $xml = simplexml_load_file( $adress );
        
        
        foreach( $xml->channel->item as $key => $value ){
        
            $this->itemParse( $value);
            
            
           // var_dump( $value);
        
        }
        
        
        //var_dump($xml);
    
    }
    
    
}
