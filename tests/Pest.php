<?php

// ...
function mockGuzzleClient()
{
    $mock = new \GuzzleHttp\Handler\MockHandler();
    $handler = \GuzzleHttp\HandlerStack::create($mock);
    $client = new \GuzzleHttp\Client(['handler' => $handler]);

    return $client;
}

function mockGuzzleClientWithResponse($response)
{
    $mock = new \GuzzleHttp\Handler\MockHandler([
        new \GuzzleHttp\Psr7\Response(200, [], $response),
    ]);
    $handler = \GuzzleHttp\HandlerStack::create($mock);
    $client = new \GuzzleHttp\Client(['handler' => $handler]);

    return $client;
}

// skip this test if the environment variable is not set
// wip
function skipIfNotSet($env)
{
    return fn () => getenv($env) === null;
}
