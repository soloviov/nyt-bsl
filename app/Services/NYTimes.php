<?php

namespace App\Services;

use App\DTO\HistoryRequestDto;
use Illuminate\Http\Client\{
    PendingRequest,
    RequestException
};
use Illuminate\Support\Facades\Http;

class NYTimes
{
    public function getHistory(HistoryRequestDto $dto): array
    {
        $url = '/svc/books/v3/lists/best-sellers/history.json';
        $query = $dto->toArray();
        try {
            $response = $this->httpClient()->get($url, $query);
        } catch (RequestException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $response->json();
    }

    private function httpClient(): PendingRequest
    {
        $baseUrl = config('nytimes.url');
        $apiKey = config('nytimes.key');

        return Http::baseUrl($baseUrl)
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->withQueryParameters([
                'api-key' => $apiKey,
            ])
            ->throw();
    }
}
