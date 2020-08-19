<?php

namespace Jiny\App\MiddleWare;

class Before extends Chain
{
    public function __construct()
    {
        //echo __CLASS__."<br>";
    }

    public function execute($req, $res)
    {
        $str = \file_get_contents("../config/site.json");
        $this->site = \json_decode($str);
        
        // 라우트 최초 객체 생성
        $route = \jiny\route()->main();
        if(!$route->enable()) {
            echo "활성화 되지 않은 접속경로 입니다.";
            return false;
        }

        if($route->auth())
        {
            if(method_exists($this->Next, "isAuth")) {
                $this->Next->isAuth();
            }            
        }

        // next chain
        return $this->Next->execute($req, $res);
    }

}