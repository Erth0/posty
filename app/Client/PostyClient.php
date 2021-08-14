<?php

namespace App\Client;

use Exception;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\Response;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\FailedActionException;
use App\Exceptions\AuthorizationException;
use Illuminate\Http\Client\PendingRequest;

class PostyClient
{
    /**
     * Factory configuration
     */
    protected array $config;

    /**
     * The request body format.
     *
     * @var string
     */
    protected $bodyFormat = 'form_params';

    /**
     * Factory
     *
     * @var PendingRequest
     */
    protected PendingRequest $factory;

    public function __construct(array $config)
    {
        if (! $config['endpoint']) {
            throw new Exception('Endpoint is missing');
        }

        if (! $config['auth_token']) {
            throw new Exception('Authentication token is missing');
        }

        $this->factory = (new Factory())
            ->acceptJson()
            ->baseUrl($config['endpoint'] . '/' . $config['endpoint_prefix'])
            ->withToken($config['auth_token']);
    }

    /**
     * Issue a GET request to the given URL.
     *
     * @param  string  $url
     * @param  array|string|null  $query
     * @return \Illuminate\Http\Client\Response
     */
    public function get(string $url, $query = null)
    {
        return $this->send('GET', $url, [
            'query' => $query,
        ]);
    }

    /**
     * Issue a POST request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function post(string $url, array $data = [])
    {
        return $this->send('POST', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Issue a PATCH request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function patch($url, $data = [])
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Issue a PUT request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function put($url, $data = [])
    {
        return $this->send('PUT', $url, [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Issue a DELETE request to the given URL.
     *
     * @param  string  $url
     * @param  array  $data
     * @return \Illuminate\Http\Client\Response
     */
    public function delete($url, $data = [])
    {
        return $this->send('DELETE', $url, empty($data) ? [] : [
            $this->bodyFormat => $data,
        ]);
    }

    /**
     * Send the request to the given URL.
     *
     * @param  string  $method
     * @param  string  $url
     * @param  array  $options
     * @return \Illuminate\Http\Client\Response
     *
     * @throws \Exception
     */
    public function send(string $method, string $url, array $options = [])
    {
        $response = $this->factory->send($method, $url, $options);

        if (! $response->successful()) {
            $this->handleRequestError($response);
        }

        return $response->json();
    }

    protected function handleRequestError(Response $response) :Exception
    {
        if ($response->status() == 401) {
            throw new AuthorizationException($response->json());
        }

        if ($response->status() == 422) {
            throw new ValidationException($response->json());
        }

        if ($response->status() == 404) {
            throw new NotFoundException();
        }

        if ($response->status() == 400) {
            throw new FailedActionException($response->body());
        }

        throw new Exception($response->body());
    }
}
