#!/usr/bin/env php
<?php
// bin/react.php


use React\EventLoop\Factory;
use React\Socket\Server;
use React\Http\Response;
use React\Http\Request;
//use Psr\Http\Message\ServerRequestInterface;
//use Symfony\Component\Debug\Debug;

require __DIR__.'/../app/autoload.php';

$kernel = new AppKernel('prod', false);


$callback = function (React\Http\Request $request, React\Http\Response $response) use ($kernel) {

    $method = $request->getMethod();
    $headers = $request->getHeaders();
    $query = $request->getQueryParams();

    //Debug::enable();
    //$kernel = new AppKernel('dev', true);
    //$kernel->loadClassCache();

    $sfRequest = new Symfony\Component\HttpFoundation\Request(
        $query
    );
    $sfRequest->setMethod($method);
    $sfRequest->headers->replace($headers);
    $sfRequest->server->set('REQUEST_URI', $request->getPath());
    if (isset($headers['Host'])) {
        $sfRequest->server->set('SERVER_NAME', explode(':', $headers['Host'][0]));
    }

    $sfResponse = $kernel->handle($sfRequest);

    $response->writeHead(
        $sfResponse->getStatusCode(),
        $sfResponse->headers->all()
    );
    $response->end($sfResponse->getContent());
    $kernel->terminate($sfRequest, $sfResponse);
};

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server(isset($argv[1]) ? $argv[1] : '127.0.0.1:33000', $loop);
$http = new React\Http\Server($socket);

$http->on('request', $callback);
echo 'Listening on http://' . $socket->getAddress() . PHP_EOL;
$loop->run();



//$loop = Factory::create();
//$socket = new Server(isset($argv[1]) ? $argv[1] : '0.0.0.0:0', $loop);
//$server = new \React\Http\Server($socket, function (ServerRequestInterface $request) {
//    return new Response(
//        200,
//        array(
//            'Content-Type' => 'text/plain'
//        ),
//        "Hello world\n"
//    );
//});
//echo 'Listening on http://' . $socket->getAddress() . PHP_EOL;
//$loop->run();


$loop = React\EventLoop\Factory::create();
$socket = new Server(isset($argv[1]) ? $argv[1] : '127.0.0.1:33000', $loop);
$http = new React\Http\Server($socket);

$callback = function ($request, $response) {
    $statusCode = 200;
    $headers = array(
        'Content-Type: text/plain'
    );
    $content = 'Hello World!';

    $response->writeHead($statusCode, $headers);
    $response->end($content);
};

$http->on('request', $callback);
echo 'Listening on http://' . $socket->getAddress() . PHP_EOL;
$loop->run();