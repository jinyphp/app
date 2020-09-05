<?php

namespace Jiny\App;

class Boot extends MiddleWare\Chain
{
    public $params=[];
    private $route=null;
    public function __construct()
    {
        //echo __CLASS__."<br>";
        // 라우터객체 생성 및 설정
        $this->route = $this->routerFactory();
    }

    /**
     * 라우터 객체를 생성하는 팩토리
     */
    private function routerFactory()
    {
        try {
            // MiddleWare before에서 생성된 route 공유받음(싱글턴)
            return \Jiny\Router\Route::instance();
        } catch (\Throwable $ex) {
            return null; // 객체생성 실패
        }
    }

    public function execute($req, $res)
    {
        if ($this->route) {
            if ($r = $this->route->main()) {
                // 라우터 설정에 따른 컨트롤러 생성
                $res->body = $this->route($r); //jsonRoute
            } else {
                // 라우터 정보가 없는 경우
                echo "라우터 정보파일이 없습니다.";
                exit;
            }
        } else {
            // url 매칭
        }

        // next chain
        return $this->Next->execute($req, $res);
    }

    

    
    /**
     * 라우터 정보에 의한 컨트롤러 생성
     */
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
            // 컨트롤러 생성     
            return $this->controller($name, $r);            
        } else {
            // 설정에 라우터 정보가 없는 경우
            if($type = $r->actionType()) {
                return $this->typeAction($type, $r);    
            } else {
                // 라우터 정보에 컨트롤러 이름 없음.
                return $this->errorNoController();
            }            
        }
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
     * 객체를 생성합니다.
     */
    private function factory($name, $args=null)
    {
        try {
            if($args) {
                $obj = new $name ($args);
            } else {
                $obj = new $name;
            }
        } catch (\Throwable $ex) {
            echo "컨트롤러 ".$name."을 생성할 수 없습니다.";
            exit;
        }
        return $obj;
    }

}