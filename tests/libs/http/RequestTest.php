<?php

namespace Tests\libs\http;

use App\libs\http\Request;
use phpmock\MockBuilder;

beforeEach(function () {
    $_SERVER = [];
    $_GET    = [];
    $_POST   = [];
    $_FILES  = [];
});

describe('Request Lib', function () {
    it('should parse a JSON body correctly', function () {
        $jsonBody = '{"test": "test"}';

        $libNamespace = (new \ReflectionClass(Request::class))->getNamespaceName();

        $mock = new MockBuilder();

        $mock->setNamespace($libNamespace)
            ->setName('file_get_contents')
            ->setFunction(
                function ($arg) use ($jsonBody) {
                    if ($arg === 'php://input') {
                        return $jsonBody;
                    }
                    return \file_get_contents($arg);
                }
            );

        $fileGetContentsMock = $mock->build();
        $fileGetContentsMock->enable();

        $request = new Request('/test');

        $fileGetContentsMock->disable();

        expect($request->body)->toEqual(['test' => 'test']);
    })->group('unit');

    it('should populate the files property from $_FILES', function () {
        $_FILES = [
            'my_file' => [
                'name'     => 'test.txt',
                'type'     => 'text/plain',
                'tmp_name' => '/tmp/phpYzdqkD',
                'error'    => 0,
                'size'     => 123,
            ],
        ];

        $request = new Request('/test');

        expect($request->files)->toEqual($_FILES);
    })->group('unit');

    it('should extract route parameters correctly', function () {
        $definedRoute = '/users/:userId/books/:bookId';
        $actualUri    = '/users/42/books/7';

        $_SERVER['REQUEST_URI'] = $actualUri;

        $request = new Request($definedRoute);

        expect($request->params)->toEqual([
            'userId'     => '42',
            'bookId'     => '7',
        ]);
    })->group('unit');

    it('should extract query parameters correctly', function () {
        $actualUri = '/search?query=phpunit&sort=asc';

        $_SERVER['REQUEST_URI'] = $actualUri;

        $request = new Request('/search');

        expect($request->queries)->toEqual([
            'query' => 'phpunit',
            'sort'  => 'asc',
        ]);
    })->group('unit');
});
