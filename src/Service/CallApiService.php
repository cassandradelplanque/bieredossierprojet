<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;
class CallApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getBiere(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.punkapi.com/v2/beers'
            
        );

        return $response->toArray();
    }
    /*public function getBieres($chaine): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.punkapi.com/v2/beers?ids='.$chaine
        );

        return $response->toArray();
    }*/
    public function getBieresnom($texte): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.punkapi.com/v2/beers?'.$texte
        );

        return $response->toArray();
    }
    public function getBierebyid($id): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.punkapi.com/v2/beers/'.$id
        );

        return $response->toArray();
    }
    
    public function  getBierehasard(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.punkapi.com/v2/beers/random'
        );

        return $response->toArray();
    }
    public function  getBierepourcompter($page): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.punkapi.com/v2/beers?page='.$page.'&per_page=80'
        );

        return $response->toArray();
    }
   
    
}