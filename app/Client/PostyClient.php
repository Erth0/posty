<?php

namespace App\Client;

use Exception;
use Illuminate\Http\Client\Response;
use App\Exceptions\NotFoundException;
use App\Exceptions\ValidationException;
use App\Exceptions\FailedActionException;
use Illuminate\Http\Client\PendingRequest;

class PostyClient extends PendingRequest
{
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
        $response = parent::send($method, $url, $options);

        if(! $response->ok()) {
            $this->handleRequestError($response);
        }

        return $response->json();
    }

    protected function handleRequestError(Response $response)
    {
        if ($response->getStatusCode() == 422) {
            throw new ValidationException($response->json());
        }

        if ($response->getStatusCode() == 404) {
            throw new NotFoundException();
        }

        if ($response->getStatusCode() == 400) {
            throw new FailedActionException($response->body());
        }

        throw new Exception($response->body());
    }
}
