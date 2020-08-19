<?php

namespace Jiny\App;

class Controller 
{
    /**
     * 컨트롤러 실행
     */
    public function controller($name)
    {
        $file = $this->appPath($name);
        if ($this->appExists($file)) {           
            $classname = $this->appName($name);
            return $this->factory($classname);
        }
    }

    /**
     * 컨트롤러 객체생성
     */
    private function factory($classname)
    {
        return new $classname ();
    }

    /**
     * 컨트롤러 이름조합
     */
    private function appName($name)
    {
        return "\App\Controllers\\".$name;
    }

    private function appPath($name)
    {
        $extention = ".php";

        // 컨트롤러 클래스 파일이 존재여부를 확인후에 처리함
        $filename = "../App/Controllers/";
        $filename = str_replace("/", DIRECTORY_SEPARATOR, $filename);
        $filename .= $name.$extention;
        // echo $filename."\n";

        return $filename;
    }

    /**
     * 컨트롤러 파일존재 여부 체크
     */
    private function appExists($name)
    {
        if (file_exists($name)) {           
            return true;
        } else {
            echo $name." 컨트롤러 파일이 존재하지 않습니다.<br>";
            exit;                             
        }
    }


}