<?php

namespace Jiny\App;

class Application
{
    public $type = "www"; // www 또는 api 
    
    public function __construct()
    {
        // echo __CLASS__."<br>";
    }

    public function main($req, $res)
    {
        $this->middleware($req, $res);
        $res->send();
    }

    /**
     * 미들웨어 처리
     */
    private function middleware($req, $res)
    {
        // 체인 목록
        $chain = [];
        $list = [
            "\Jiny\App\Middleware\Before",
            "\App\MiddleWare\AppBefore",
            "\Jiny\App\Boot",
            "\App\MiddleWare\AppAfter",
            "\Jiny\App\Middleware\After"
        ];
        foreach ($list as $name) {
            if($obj = $this->factory($name)) {
                $chain []= $obj;
            }
        }

        // 체인 결합
        for($i=1; $i<count($chain); $i++) {
            $chain[$i-1]->setNext($chain[$i]);
        }

        $chain[0]->execute($req, $res);
    }

    /**
     * 미들웨어 객체 생성
     */
    private function factory($classname)
    {
        try {
            return new $classname;
        } catch (\Throwable $ex) {
            return null; // 객체생성 실패
        }
    }

    /**
     * 
     */
}