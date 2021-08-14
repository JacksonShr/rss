<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use App\Item;
use App\QueryLogs as Logger;

class RssReaderController extends BaseController
{
    //use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
       
    
    function getNews(string $url,string $method){
    
        $logArr['url'] = $url; //Сразу готовим массив для передачи в функцию создания логов
        
        $logArr['method'] = $method; //Добавляем в массив логов метод
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17',
        CURLOPT_AUTOREFERER => true,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_VERBOSE => 1,
        CURLOPT_HEADER => 1,
        CURLOPT_CUSTOMREQUEST => $logArr['method'],
        CURLOPT_URL => $logArr['url'],
        CURLOPT_RETURNTRANSFER => true
        ));//
        $response = curl_exec($curl);
        
        if ($response !== false)
            {
              $curlInfo = curl_getinfo($curl);
              $logArr['httpCode'] = curl_getinfo($curl, CURLINFO_HTTP_CODE);//Сохраняем код ответа сервера
              $logArr['header'] = substr($response, 0, $curlInfo['header_size']);//Отделяем заголовок
              $response = substr($response, $curlInfo['header_size']);//Отделяем тело ответа
            }
            
        curl_close($curl);
        
        
        
        $xml = simplexml_load_string( $response );
        
                
        foreach( $xml->channel->item as $key => $value ){
        
            //В цикле перебираем узел item xml файла рассылки
            
            $item = new Item;//Создаем экземпляр модели Item
        
            $item->setItem( $value );
        
        
        }
        
        
    $q = new Logger;//Создаем модель логгера
           
    $q->logQuery( $logArr );//Заносим данные о запросе в базу данных
    
    }
    
    
}
