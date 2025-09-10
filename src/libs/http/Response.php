<?php

namespace App\libs\http;

class Response
{
    /**
     * Terminate the http response
     * @return void
     */
    public function end(): void
    {
        exit;
    }

    /**
     * Set multiple headers
     * @param array<string, string> $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $header => $value) {
            header($header . ': ' . $value);
        }
        return $this;
    }

    /**
     * Set the HTTP status code
     * @param int $statusCode
     * @return $this
     */
    public function status(int $statusCode): self
    {
        http_response_code($statusCode);

        return $this;
    }

    /**
     * Send a JSON response
     * @param array<string, mixed> $data
     * @return $this
     */
    public function json(array $data): self
    {
        $this->setHeaders([
            'Content-Type' => 'application/json',
        ]);

        $this->status(200);
        file_put_contents('php://output', json_encode($data));

        return $this;
    }

    /**
     * Render a template with context
     * @param string $templateName
     * @param array<string, mixed> $context
     * @return $this
     */
    public function render(string $templateName, array $context = []): self
    {
        $this->setHeaders([
            'Content-Type' => 'text/html',
        ]);

        ob_start();
        extract($context);
        require $templateName;
        $content = ob_get_contents();
        ob_end_clean();

        $this->status(200);
        echo $content;

        return $this;
    }

    /**
     * Redirect to a different URL
     * @param string $url
     * @return $this
     */
    public function redirect(string $url): self
    {
        header('Location: ' . $url);

        return $this;
    }
}
