<?php

namespace Oilstone\ActOn;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Oilstone\ActOn\Exceptions\ActOnHttpException;
use Oilstone\ActOn\Exceptions\InvalidActOnParameters;

/**
 * Class ActOn
 * @package Oilstone\ActOn
 */
class ActOn
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $mappings = [];

    /**
     * ActOn constructor.
     * @param array $config
     * @param array $mappings
     */
    public function __construct(array $config, array $mappings = [])
    {
        $this->config = $config;

        $this->client = new Client([
            'curl' => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2
            ]
        ]);

        $this->mappings = array_merge($this->mappings, $mappings);
    }

    /**
     * @param string $form
     * @param array $data
     * @throws ActOnHttpException
     * @throws InvalidActOnParameters
     */
    public function notify(string $form, array $data): void
    {
        $formId = $this->formId($form);

        $data = $this->convertForActOn($data);

        if (!$formId || !$data) {
            throw new InvalidActOnParameters();
        }

        try {
            $this->client->request('POST', str_ireplace('{FORM-ID}', $formId, $this->url()), [
                'form_params' => $data
            ]);
        } catch (GuzzleException $e) {
            throw new ActOnHttpException($e);
        }
    }

    /**
     * @param $key
     * @return null|string
     */
    protected function formId($key): ?string
    {
        return $this->config['form_ids'][strtoupper($key) . '_ID'] ?? null;
    }

    /**
     * @param $data
     * @return array
     */
    protected function convertForActOn(array $data): array
    {
        $return = [];

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->mappings)) {
                $key = $this->mappings[$key];
            } else {
                $key = str_replace('_', ' ', Str::upper($key));
            }

            $return[$key] = $value;
        }

        return $return;
    }

    /**
     * @return null|string
     */
    protected function url(): ?string
    {
        return $this->config['url'];
    }

    /**
     * @param array $mappings
     * @return ActOn
     */
    public function setMappings(array $mappings): ActOn
    {
        $this->mappings = $mappings;

        return $this;
    }
}
