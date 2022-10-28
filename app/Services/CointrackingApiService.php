<?php

namespace App\Services;

use App\Types\Column;
use App\Types\Transaction;
use GuzzleHttp\Exception\ClientException;

class CointrackingApiService
{

    private string $session;

    private string $host;
    private string $login;
    private string $pass;

    public function __construct()
    {
        $this->host = env("COINTRACKING_HOST") ?? throw new \Exception("Cointracking not configured");
        $this->login = env("COINTRACKING_LOGIN") ?? throw new \Exception("Cointracking not configured");
        $this->pass = env("COINTRACKING_PASSWORD") ?? throw new \Exception("Cointracking not configured");
        $this->connect();
    }

    public function connect()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', $this->host . "/ios/v10/users/login", [
            'body' => json_encode([
                "username" => $this->login,
                "password" => $this->pass
            ]),
            'headers' => ['Content-Type' => 'application/json'],
        ]);

        if ($response->getStatusCode() === !200) {
            throw new \Exception("CannotInvalid credentials for cointracking");
        }

        $this->session = json_decode($response->getBody())->session;
    }



    public function send($json)
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', $this->host . "/ios/v10/transactions", [
                'body' => $json,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-SESSION-ID' => md5($this->session)
                ],
            ]);
        } catch (ClientException $e) {
            echo $e->getResponse()->getBody();
        }
    }

    public function getAllTransactions()
    {
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', $this->host . "/ios/v10/transactions", [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-SESSION-ID' => md5($this->session)
                ],
            ]);
            return json_decode($response->getBody());
        } catch (ClientException $e) {
            echo $e->getResponse()->getBody();
        }
    }
}
