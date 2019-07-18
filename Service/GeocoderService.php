<?php

namespace Vlabs\AddressBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Debug\Exception\ContextErrorException;
use Vlabs\AddressBundle\Exception\InvalidParameterException;
use Vlabs\AddressBundle\Exception\NoGeocodingFoundForAddressException;
use Vlabs\AddressBundle\Exception\UnableToParseGeocodingJsonReponseException;
use Vlabs\AddressBundle\VO\CoordinatesVO;

class GeocoderService
{
    /**
     * @var string
     */
    private $googleApiKey;

    /**
     * @var string
     */
    private $geocoderBaseUrl;

    /**
     * GeocoderService constructor.
     * @param string $googleApiKey
     * @param string $geocoderBaseUrl
     */
    public function __construct($googleApiKey, $geocoderBaseUrl)
    {
        $this->googleApiKey     = $googleApiKey;
        $this->geocoderBaseUrl  = $geocoderBaseUrl;
    }

    /**
     * @param $address
     * @return bool|CoordinatesVO
     * @throws InvalidParameterException
     */
    public function geocode($address)
    {
        if(empty($this->googleApiKey)){
            throw new InvalidParameterException('You must provide the "google_api_key" parameter to use the Geocoder.');
        }

        if(empty($this->geocoderBaseUrl)){
            throw new InvalidParameterException('You must provide the "google_geocoder_base_url" parameter to use the Geocoder.');
        }

        // todo: Move to bundle conf
        $outputFormat   = 'json';
        $addressEncoded = urlencode($address);

        $client = new Client();

        /** @var Response $response */
        $response = $client->get(sprintf('%s%s?address=%s&key=%s',
            $this->geocoderBaseUrl,
            $outputFormat,
            $addressEncoded,
            $this->googleApiKey
        ));

        if($response->getStatusCode() == \Symfony\Component\HttpFoundation\Response::HTTP_OK)
        {
            return $this->resolveCoordinates($response->getBody()->getContents());
        }

        return false;
    }

    /**
     * @param $jsonResponse
     * @return CoordinatesVO
     * @throws NoGeocodingFoundForAddressException
     * @throws UnableToParseGeocodingJsonReponseException
     */
    private function resolveCoordinates($jsonResponse)
    {
        $data = json_decode($jsonResponse);

        if($data->status != 'OK')
        {
            throw new NoGeocodingFoundForAddressException($data->status . ( isset($data->error_message) ? ': '. $data->error_message : '' ));
        }

        try{

            $results    = $data->results;
            $node       = $results[0]->geometry->location;

            return new CoordinatesVO(
                $node->lat,
                $node->lng
            );

        }catch(ContextErrorException $e)
        {
            throw new UnableToParseGeocodingJsonReponseException($e->getMessage());
        }
    }
}