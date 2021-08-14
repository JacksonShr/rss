<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueryLogs extends Model
{
    //Таблица для хранения логов запросов
    protected $table = "query_logs";
    
     function logQuery( $logArr ){
    
        //Берем необходимые значения из переданного массива, сохраняем в таблицу
        
        $this->requestUrl = $logArr['url'];
        
        $this->requestMethod = $logArr['method'];
        
        $this->responseHttpCode = $logArr['httpCode'];
        
        $this->timestamp = date('H:i:s d-m-y');
        
        $this->save();
    
    }
   
}
