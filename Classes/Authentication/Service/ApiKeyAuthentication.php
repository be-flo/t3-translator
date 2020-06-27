<?php


namespace Beflo\T3Translator\Authentication\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiKeyAuthentication extends AbstractAuthentication
{
    /**
     * @param string $url
     * @param array  $data
     *
     * @return array|null
     */
    public function post(string $url, array $data, bool $jsonResponse = true): ?array
    {
        $result = null;
        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                'form_params' => $data
            ]);
            if ($jsonResponse === true) {
                $result = @json_decode($response->getBody()->getContents(), true);
            } else {
                $result = $response->getBody()->getContents();
            }

        } catch (GuzzleException $e) {
            $this->logger->error($e->getMessage());
        }

        return $result;
    }

    /**
     * Initialize the required fields
     */
    protected function initialize(): void
    {
        $this->addRequiredField('api_key');
    }

}