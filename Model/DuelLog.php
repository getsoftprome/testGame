<?php

namespace Model;
use Core\Cache;

class DuelLog
{
    private static $cacheTime = 60;

    public static function add($duelId, $log){
        $cacheKey = 'log'.$duelId;

        $logs = self::get($duelId);

        $logs[] = $log;

        return Cache::getMemcache()->set($cacheKey,$logs,false,self::$cacheTime);
    }

    public static function get($duelId){
        $cacheKey = 'log'.$duelId;
        $logs = Cache::getMemcache()->get($cacheKey);
        if(empty($logs)){
            $logs = [];
        }
        return $logs;
    }
}