# jinyPHP Web Application boot
web 서비스 개발은 위한 boot 입니다. 라우트 정보에 따른 Controller를 생성하고 이를 호출합니다.

## 설치방법
컴포저를 이용하여 패키지를 설치할 수 있습니다.

```
composer require jiny/app
```

## 동작설명
패키지의 시작 클래스는 Application 입니다.  
Application 클래스는 미들웨어를 설정하고, 이를 실행합니다.

## 미들웨어
미들웨어는 5단계로 생성됩니다.

```
$list = [
            "\Jiny\App\Middleware\Before",
            "\App\MiddleWare\AppBefore",
            "\Jiny\App\Boot",
            "\App\MiddleWare\AppAfter",
            "\Jiny\App\Middleware\After"
        ];
```

Before는 메인 동작이 실행되기 전에 동작하는 미들웨어이며,
After 는 메인 동작이 실행된 후에 동작하는 미들웨어 입니다.

## boot
boot 클래스는 라우터 정보에 따라서 컨트롤러 클래스를 호출합니다.


