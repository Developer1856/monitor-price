<?php

namespace App\Services;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OlxCrawler
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getPrice(string $link): int
    {

        if ($this->checkLink($link) != 200) {
            return 0;
        }
        $response = $this->client->request('GET', $link);
        $content = $response->getContent();
        $crawler = new Crawler($content);
        $price = $crawler->filter('[data-testid="ad-price-container"] h3');
        return (int)filter_var($price->innerText(), FILTER_SANITIZE_NUMBER_INT);
    }

    public function checkLink(string $link): int
    {
        $response = $this->client->request('GET', $link);
        return $response->getStatusCode();
    }

}