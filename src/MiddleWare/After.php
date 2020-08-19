<?php

namespace Jiny\App\MiddleWare;

class After extends Chain
{
    public function __construct()
    {
        // echo __CLASS__."<br>";
    }

    public function execute($req, $res)
    {
        //echo "실행 after <br>";
        // echo $res->body;
        // exit;
    
        // return $this->Next->execute($event);
    }
}