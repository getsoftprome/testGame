<?php

namespace Core;

use Memcache;

class Cache
{
    private static $memcache = null;
    public static function init(){
        self::$memcache = new Memcache;
        self::$memcache->connect('127.0.0.1', 11211) or die("Could not connect");
    }

    public static function getMemcache(): Memcache{
        if (is_null(self::$memcache)) {
            throw new \Exception("Use init() at first call");
        }
        return self::$memcache;
    }
}