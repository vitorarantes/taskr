<?php

use hiqdev\composer\config\Builder;
use Yiisoft\Di\Container;
use Yiisoft\Http\Method;
use Yiisoft\Yii\Web\Application;
use Yiisoft\Yii\Web\SapiEmitter;
use Yiisoft\Yii\Web\ServerRequestFactory;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotEnv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
$dotEnv->load();

// Don't do it in production, assembling takes it's time
Builder::rebuild();

$container = new Container(require Builder::path('web'));

require_once dirname(__DIR__) . '/src/globals.php';

$application = $container->get(Application::class);

$request = $container->get(ServerRequestFactory::class)->createFromGlobals();

try {
    $application->start();
    $response = $application->handle($request);
    $emitter = new SapiEmitter();
    $emitter->emit($response, $request->getMethod() === Method::HEAD);
} finally {
    $application->shutdown();
}
