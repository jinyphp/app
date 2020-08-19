<?php

namespace Jiny\App;

class Application
{
    public $type = "www"; // www 또는 api 
    
    public function __construct()
    {
        // echo __CLASS__."<br>";
    }

    public function main()
    {
        // echo "main 호출<br>";
        $req = \jiny\http\request();
        $res = \jiny\http\response();

        // 체인 목록
        $chain = [
            new Middleware\Before(),
            new \App\MiddleWare\AppBefore(),
            new \Jiny\App\Boot(),
            new \App\MiddleWare\AppAfter(),
            new Middleware\After()
        ];

        // 체인 결합
        for($i=1; $i<count($chain); $i++) {
            $chain[$i-1]->setNext($chain[$i]);
        }

        $chain[0]->execute($req, $res);

        // end
        // exit;
        $res->send();
    }

    /**
     * 
     */
}