<?php

namespace Jiny\App;

class Builder
{
    protected $conf;

    public function __construct()
    {
        
    }

    protected function init($controller)
    {
        $name = get_class($controller);
        $this->conf = \jiny\json_get_object("../".$name.".json");
    }

    public function methodConf($name)
    {
        $arr = \explode("::",$name);
        $key = $arr[1];
        if(isset($this->conf->$key)) {
            return $this->conf->$key;
        }
    }
}