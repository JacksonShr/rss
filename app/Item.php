<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Enclosure;

class Item extends Model
{
    //
    protected $table = "items";
    public $timestamps = false;
    
    
    
    function setItem( $resourse ){
    
        
           $res = Item::where('guid', $resourse->guid)->get();//Выполняем запрос по ключу guid, нет ли уже этой записи в таблице
        
           if( $res->isEmpty()===false ) return;//Если такая запись есть, выходим из функции
           
           
           $this->title = $resourse->title;
           
           $this->description = $resourse->description;
           
           $this->guid = $resourse->guid;
           
           $this->link = $resourse->link;
           
           $this->pubDate = $resourse->pubDate;
           
           $this->save();
           
           
           
           $enclosure = new Enclosure;//Создаем модель для добавления доп данных (изображения и видео)
           
           if ( !isset( $resourse->enclosure['type']) ) return;//В том случае, если доп данных нет вообще, выходим
           
                      
           if ( !is_array($resourse->enclosure) ){
           
                 $enclosure->enclosureCreate($resourse->enclosure, $resourse->guid);//В случае одного экземпляра доп данных сразу его передаем для записи в таблице
           
           } else {
           
                for( $i = 0; $i < count( $resourse->enclosure ); $i++ ){
                
                 $enclosure->enclosureCreate($resourse->enclosure[$i], $resourse->guid);//В случае нескольких экземпляров доп данных перебираем их в цикле
                
                }
           
           }
           
    
    
    }
    
    
    
}
