<?php

namespace Jiny\App;

class Boot extends MiddleWare\Chain
{
    public $params=[];

    public function __construct()
    {
        //echo __CLASS__."<br>";
    }

    public function execute($req, $res)
    {
        // MiddleWare before에서 생성된 route 공유받음(싱글턴)
        $r = \jiny\route()->main();
        $res->body = $this->route($r); //jsonRoute

        // next chain
        return $this->Next->execute($req, $res);
    }

    private function route($r)
    {
        if($r->get()) { 
            // 컨트롤러를 생성하고, 호출합니다.
            return $this->paerser($r);
        }

        // 라우터 정보가 없음
        return $this->errorNoRoute();
    }

    /**
     * 컨트롤러 생성, 호출
     */
    private function paerser($r)
    {
        if ($name = $r->controllerName()) {         
            /*   
            $controller = $this->factory($name); // 컨트롤러 객체 생성            
            $method = $r->method(); // 실행 메서드            
            $this->params = $r->params(); // uri 파라미터
            */
            return $this->controller($name, $r);
            
        } else {
            // 설정에 라이트 정보가 없는 경우
            if($type = $r->actionType()) {
                return $this->typeAction($type, $r);
    
            } else {
                // 라우터 정보에 컨트롤러 이름 없음.
                /*
                $msg = " 컨트롤러 이름이 설정되어 있지 않습니다.";
                // 기본값 404
                $controller = new \Jiny\App\Error($msg);
                $method = "main";
                */

                return $this->errorNoController();

            }
            
        }

        return $this->run($controller, $method);
    }

    private function errorNoController()
    {
        // 라우터 정보에 컨트롤러 이름 없음.
        $msg = " 컨트롤러 이름이 설정되어 있지 않습니다.";
        // 기본값 404
        $controller = new \Jiny\App\Error($msg);
        $method = "main";

        return $this->run($controller, $method);
    }


    private function controller($name, $r)
    {
        $controller = $this->factory($name); // 컨트롤러 객체 생성            
        $method = $r->method(); // 실행 메서드            
        $this->params = $r->params(); // uri 파라미터

        return $this->run($controller, $method);
    }

    private function typeAction($type, $r)
    {
        $path = $r->actionConf();
        switch($type) {
            case 'table' :
                $name = "\Jiny\\Board\\Controller";
                $controller = $this->factory($name); // 컨트롤러 객체 생성    
                $controller->init($path);
                $method = $r->method(); // 실행 메서드            
                $this->params = $r->params(); // uri 파라미터

        }

        return $this->run($controller, $method);
    }



    /**
     * 라우터 파일을 얻지 못한경우
     */
    private function errorNoRoute()
    {
        $msg = "라우터파일이 없습니다.";

        $controller = new \Jiny\App\Error($msg);
        $method = "main";

        return $this->run($controller, $method);
    }

    /**
     * 컨트롤러를 호출합니다.
     */
    private function run($obj, $method="main")
    {
        return call_user_func_array(
            [
                $obj, 
                $method
            ], 
            [
                $this->params
            ]);
    }

    /**
     * Simple Factory
     * 객체를 생성합니다.
     */
    private function factory($name, $args=null)
    {
        try {
            if($args) {
                // echo "args";
                $obj = new $name ($args);
            } else {
                $obj = new $name;
            }
        } catch (\Exception $e) {
            echo "오류";
            echo $e->getMessage();
            exit;
        }
        return $obj;
    }

}