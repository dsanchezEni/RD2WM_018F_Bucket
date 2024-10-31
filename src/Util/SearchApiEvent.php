<?php

namespace App\Util;

use App\Models\SearchEvent;

class SearchApiEvent
{
    private readonly string $BASE_URL;

    /**
     * @param string $BASE_URL
     */
    public function __construct()
    {
        $this->BASE_URL = "https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda";
    }


    public function search(SearchEvent $searchEvent):array{
        $url=$this->BASE_URL;
        if($searchEvent->getCity())
        {
            $url.='&refine.location_city='.$searchEvent->getCity();
        }
        if($searchEvent->getDateEvent())
        {
            $url.='&refine.firstdate_begin='.$searchEvent->getDateEvent()->format('Y-m-d');
        }
        //dd($url);
        $url="https://public.opendatasoft.com/api/records/1.0/search/?dataset=evenements-publics-openagenda&refine.location_city=Rennes&refine.firstdate_begin=2023-05-16";
        $content=file_get_contents($url);
        //dd($content);
        $json=json_decode($content,true)['records'];
       //dd($json);
       // if($json===false){
       //     return [];
        //}else{
            return $json;
        //}
    }
}