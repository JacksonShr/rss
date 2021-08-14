<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QueryLogs extends Model
{
    //Таблица для хранения логов запросов
    protected $table = "query_logs";
}
