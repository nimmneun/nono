<?php

declare(strict_types=1);

namespace nimmneun\Nono;

/**
 * Simple request class, which is passed as the first argument to
 * any callable / controller method associated with a route.
 *
 * @method string|mixed get(string $name = null, mixed $default = null)
 * @method string|mixed post(string $name = null, mixed $default = null)
 * @method string|mixed query(string $name = null, mixed $default = null)
 * @method string|mixed request(string $name = null, mixed $default = null)
 * @method string|mixed server(string $name = null, mixed $default = null)
 * @method string|mixed session(string $name = null, mixed $default = null)
 * @method string|mixed cookie(string $name = null, mixed $default = null)
 * @method array|mixed  files(string $name = null, mixed $default = null)
 */
class Request
{
    /**
     * Just triggering the super global and fill the query global.
     */
    public function __construct()
    {
        $_SERVER;
        in_array($this->verb(), ['GET', 'POST'])
        || parse_str(file_get_contents('php://input'), $GLOBALS['_QUERY']);
    }

    /**
     * Return the http verb.
     */
    public function verb(): ?string
    {
        return $this->server('REQUEST_METHOD');
    }

    /**
     * Return URI without query string.
     */
    public function uri(): string
    {
        return explode('?', $this->uriWithQuery())[0] ?? '';
    }

    /**
     * Return URI with query string.
     */
    public function uriWithQuery(): string
    {
        return urldecode($this->server('REQUEST_URI'));
    }

    /**
     * Return plain text request content.
     */
    public function content(): string
    {
        return file_get_contents('php://input');
    }

    /**
     * Return whether the request URL is HTTPS URL or not.
     * Questionable reliability -> depends upon server config.
     */
    public function isHttps(): bool
    {
        return (int)$this->server('SERVER_PORT') === 443
            || strtoupper((string)$this->server('HTTPS')) === 'ON';
    }

    /**
     * Return the hostname.
     */
    public function host(): string
    {
        return $this->server('HTTP_HOST');
    }

    /**
     * Return the time the request was initiated.
     */
    public function requestTimeFloat(): float
    {
        return $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * Return the elapsed time since the request was initiated.
     */
    public function elapsedRequestTimeFloat(): float
    {
        return microtime(true) - $this->server('REQUEST_TIME_FLOAT');
    }

    /**
     * Allow redirects when headers have already been sent
     * due to sessions or other output.
     */
    public function redirect(string $url): void
    {
        echo "<script>location.replace('$url');</script>";
    }

    /**
     * Magic method to access super globals with optional default argument.
     * Calling without arguments e.g. $request->server() will simply
     * return the entire _SERVER global.
     */
    public function __call(string $name, array $args): mixed
    {
        $global = $GLOBALS['_' . strtoupper($name)];
        $default = $args[1] ?? null;

        if (!count($args)) {
            return $global;
        } elseif (isset($global[$args[0]])) {
            return $global[$args[0]];
        } else {
            return $default;
        }
    }
}
