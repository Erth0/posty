<?php

namespace App;

use Illuminate\Http\Client\PendingRequest;

class Posty extends PendingRequest
{
    protected $config;

    protected $client;

    protected $timeout = 30;

    public function __construct(array $config)
    {
        $this->client = (new PendingRequest())
            ->acceptJson()
            ->timeout($this->timeout)
            ->baseUrl($config['base_url'])
            ->withToken($config['api_key']);
    }

    public function get(string $url, $query = null)
    {
        $response = $this->client->get($url, $query);

        if ($response->status() != 200) {
            return $this->handleRequestError($response);
        }

        return $response->json();
    }

    public function handleRequestError($response)
    {
        if($response->status() === 404) {
            Helpers::abort('Resource not found');
        }

        if($response->status() === 422) {
            Helpers::abort($this->validationErrors($response->json()));
        }

        if($response->status() === 500) {
            Helpers::abort('Server error');
        }
    }

    public function validationErrors(array $error)
    {
        $message = 'The given data was invalid:';

        foreach($error['errors'] as $error) {
            $message .= PHP_EOL . $error[0];
        }

        return $message;
    }

    /**
     * Set a new timeout.
     *
     * @param  int  $timeout
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * Get the timeout.
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }
}
