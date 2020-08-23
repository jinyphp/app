<?php

namespace Jiny\App\MiddleWare;
/**
 * 컨트롤러 호출전 실행
 */
class Before extends Chain
{
    public function __construct()
    {
        //echo __CLASS__."<br>";
        $str = \file_get_contents("../config/site.json");
        $this->site = \json_decode($str);
    }

    public function execute($req, $res)
    {
        // 라우트 최초 객체 생성
        if ($route = \jiny\route()->main()) {
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

            if ($name = $route->theme()) {
                $Theme = \jiny\theme()->setName($name)->setPath();
            }


    
            // next chain
            return $this->Next->execute($req, $res);
        } 
    }

}