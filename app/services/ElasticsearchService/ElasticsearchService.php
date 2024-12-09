<?php

namespace App\Services\ElasticsearchService;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()
            ->setHosts([config('services.elastic.host')])
            // ->setBasicAuthentication(config('services.elastic.user_name'), config('services.elastic.password')) // use auth if required
            ->build();
    }

    public function createIndex($indexName, $mapping)
    {
        return $this->client->indices()->create([
            Constants::INDEX => $indexName,
            Constants::BODY => $mapping,
        ]);
    }

    public function indexDocument($index, $id, $body)
    {
        return $this->client->index([
            Constants::INDEX => $index,
            Constants::ID => $id,
            Constants::BODY => $body,
        ]);
    }

    public function search($index, $query)
    {
        $response = $this->client->search([
            Constants::INDEX => $index,
            Constants::BODY => $query,
        ]);

        return $response->asArray();
    }

    public function getClient()
    {
        return $this->client;
    }
}
