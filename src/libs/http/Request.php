<?php

namespace App\libs\http;

class Request
{
    /**
     * Body of the request
     * @var array<string, string|int|float|bool|null>
     */
    public array $body    = [];

    /**
     * Files uploaded with the request
     * @var array<string, array<string>>
     */
    public array $files   = [];

    /**
     * Route parameters
     * @var array<string, string>
     */
    public array $params  = [];

    /**
     * Query parameters
     * @var array<string, string>
     */
    public array $queries = [];

    /**
     * Constructor
     * @param string $definedRoute
     */
    public function __construct(string $definedRoute)
    {
        $this->getBody();
        $this->getFiles();
        $this->getParams($definedRoute);
        $this->getQueries();
    }

    /**
     * Get the body of the request
     * @return void
     */
    private function getBody()
    {
        try {
            $rawBody = file_get_contents('php://input');

            if ($rawBody) {
                $parsedBody = json_decode($rawBody, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON body');
                }

                $this->body = $parsedBody;
            }
        } catch (\Throwable $th) {
            $this->body = [];
        }
    }

    /**
     * Get the files uploaded with the request
     * @return void
     */
    private function getFiles()
    {
        $this->files = $_FILES;
    }

    /**
     * Get the route parameters
     * @param string $definedRoute
     * @return void
     */
    private function getParams(string $definedRoute)
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $uri = explode('?', $uri, 2)[0];

        $uriParts = array_values(array_filter(explode('/', $uri), fn($v) => $v !== ''));
        $routeParts = array_values(array_filter(explode('/', $definedRoute), fn($v) => $v !== ''));

        foreach ($routeParts as $idx => $routePart) {
            if (str_starts_with($routePart, ':') && isset($uriParts[$idx])) {
                $paramName = ltrim($routePart, ':');
                $this->params[$paramName] = $uriParts[$idx];
            }
        }
    }

    /**
     * Get the query parameters
     * @return void
     */
    private function getQueries()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $parts = explode('?', $uri, 2);

        if (count($parts) < 2 || empty($parts[1])) {
            $this->queries = [];
            return;
        }

        $queryFullStr = $parts[1];
        $queryList = explode('&', $queryFullStr);

        foreach ($queryList as $queryItem) {
            if (strpos($queryItem, '=') !== false) {
                list($queryName, $queryValue) = explode('=', $queryItem, 2);
                $this->queries[$queryName] = $queryValue;
            }
        }
    }
}
