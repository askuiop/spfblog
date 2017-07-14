#!/usr/bin/env php
<?php
// bin/react.php


use React\EventLoop\Factory;
use React\Socket\Server;
use React\Http\Response;
use React\Http\Request;
//use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Debug\Debug;

require __DIR__.'/../app/autoload.php';

$kernel = new AppKernel('prod', true);
$kernel->loadClassCache();

//ebug::enable();
//kernel = new AppKernel('dev', true);


$callback = function (React\Http\Request $request, React\Http\Response $response) use ($kernel) {

    //$kernel = new AppKernel('dev', true);
    //$kernel->loadClassCache();

    $method = $request->getMethod();
    $headers = $request->getHeaders();
    $query = $request->getQueryParams();

    echo $request->getPath(). PHP_EOL;


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

