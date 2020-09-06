<?php

namespace Jiny\App;

// 컨트롤러를 생성하고 이를 실행합니다.
class Run
{
    public $controller;
    public $params;
    public function __construct($name, $args=null)
    {
        $this->controller = $this->factory($name, $args);
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
            // print_r($ex);
            exit;
        }

        return $obj;
    }

    public function setParam($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * 컨트롤러를 호출합니다.
     */
    public function execute($method="main")
    {
        return call_user_func_array(
            [
                $this->controller, 
                $method
            ], 
            [
                $this->params
            ]);
    }


}