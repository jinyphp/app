<?php
namespace Jiny\App;

class Error
{
    private $message;
    public function __construct($msg)
    {
        $this->message = $msg;
    }

    public function main()
    {
        $args = [
            'error_message' => $this->message
        ];

        // 화면출력
        $file = "../resource/members/error.html";
        if(file_exists($file)) {
            $body = \jiny\html_get_contents($file, $args);
            return $body;
        } else {
            return $this->message;
        }
    }
    
}