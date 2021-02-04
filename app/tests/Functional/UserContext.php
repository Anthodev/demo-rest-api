<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Behat\Behat\Context\Context;
use GuzzleHttp\Client;

final class UserContext implements Context
{
    public function __construct()
    {
    }

    /**
     * @Given I am an unauthenticated user
     *
     * @return bool
     */
    public function iAmAnUnauthenticatedUser(): bool
    {
        $client = new Client([
            'base_uri' => 'https://api.localhost'
        ]);
        $response = $client->request('GET', '/api', [
            'verify' => false,
        ]);
        $responseCode = $response->getStatusCode();

        if ($responseCode != 200) {
            throw new \Exception('Not able to access');
        }

        return true;
    }

    /**
     * @When I request a list of users from :arg1
     *
     * @param string $arg1
     * @return bool
     */
    public function iRequestAListOfUsersFrom(string $arg1): bool
    {
        $client = new Client(['base_uri' => 'https://api.localhost']);
        $response = $client->request('GET', $arg1, [
            'verify' => false
        ]);
        $responseCode = $response->getStatusCode();

        if ($responseCode != 200) {
            throw new \Exception('Not able to access');
        }

        return true;
    }

    /**
     * @Then The response should be in JSON
     */
    public function theResponseShouldBeInJson(): bool
    {
        return false;
    }

    /**
     * @Then The header :arg1 should be equal to :arg2
     */
    public function theHeaderShouldBeEqualTo($arg1, $arg2): bool
    {
        return false;
    }
}