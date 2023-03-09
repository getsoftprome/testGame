<?php

namespace Core;

class Route
{
    private $routerLinks = [];

    public  function add($function,$uri){
        $this->routerLinks[$uri] = $function;
    }

    private function cleanUri($uri){
        return explode('?',$uri)[0];
    }
    public function get($uri){
        $uri = $this->cleanUri($uri);
        if(!isset($this->routerLinks[$uri])){
            return false;
        }
        return $this->routerLinks[$uri];
    }
}