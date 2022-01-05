<?php

namespace EasyBroker;

class EasyBrokerProperties
{
    private $apiKey = 'l7u502p8v46ba3ppgvj5y2aad50lb9';

    public $properties = [];

    /**
     * Get the list of properties. 
     * You can pass the necessary parameters through fields:
     * e.g. ['limit' => 5]
     *
     * @param array $fields
     * @return void
     */
    public function getProperties(array $fields = [])
    {
        $url = "https://api.stagingeb.com/v1/properties";
        $response = $this->clientHandler($url, $fields);
        $this->properties = $response->content;
    }

    /**
     * Print the list of properties. 
     * You must first execute the getProperties function to 
     * load the properties to print.
     *
     * @return void
     */
    public function printProperties()
    {
        if ($this->properties  === []) {
            echo 'No properties available';
            return;
        }

        $cont = 1;
        foreach ($this->properties as $property) {
            echo "${cont}) {$property->title}\n";
            $cont++;
        }
    }

    private function clientHandler($url, array $fields = [])
    {
        $client = curl_init();

        if ($fields !== []) {
            $url .= '?' . http_build_query($fields);
        }

        curl_setopt($client, CURLOPT_URL, $url);

        curl_setopt($client, CURLOPT_HTTPHEADER, array(
            "accept: application/json",
            "X-Authorization: " . $this->apiKey,
        ));

        curl_setopt($client, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($client);

        return json_decode($response);
    }    
}
