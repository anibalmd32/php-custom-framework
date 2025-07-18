<?php 

namespace Tests\attributes;

use App\attributes\Route;
use App\enums\HTTP_METHODS;
use Attribute;
use ReflectionClass;

describe('Route Attribute', function () {
    it('should create a route with GET method and path', function () {
        $route = new Route(
            method: HTTP_METHODS::GET,
            path: '/test'
        );

        expect($route->method)->toBe(HTTP_METHODS::GET);
        expect($route->path)->toBe('/test');
    });

    it('should create a route with POST method and path', function () {
        $route = new Route(
            method: HTTP_METHODS::POST,
            path: '/test'
        );

        expect($route->method)->toBe(HTTP_METHODS::POST);
        expect($route->path)->toBe('/test');
    });

    it('should create a route with PUT method and path', function () {
        $route = new Route(
            method: HTTP_METHODS::PUT,
            path: '/test'
        );

        expect($route->method)->toBe(HTTP_METHODS::PUT);
        expect($route->path)->toBe('/test');
    });

    it('should create a route with DELETE method and path', function () {
        $route = new Route(
            method: HTTP_METHODS::DELETE,
            path: '/test'
        );

        expect($route->method)->toBe(HTTP_METHODS::DELETE);
        expect($route->path)->toBe('/test');
    });

    it('should create a route with PATCH method and path', function () {
        $route = new Route(
            method: HTTP_METHODS::PATCH,
            path: '/test'
        );

        expect($route->method)->toBe(HTTP_METHODS::PATCH);
        expect($route->path)->toBe('/test');
    });

    it('should has the correct attributes target', function () {
        $reflectionClass = new ReflectionClass(Route::class);
        $attributes = $reflectionClass->getAttributes(Attribute::class)[0] ?? null;

        expect($attributes)->not()->toBeNull();
        expect($attributes?->getArguments())->toBeArray();
        expect($attributes?->getArguments()[0])->toBe(Attribute::TARGET_METHOD);
    });
});
