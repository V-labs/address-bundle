<?php

namespace Vlabs\AddressBundle\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Vlabs\AddressBundle\Exception\UnableToGetGeocodingResultException;
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
        // todo: Move to bundle conf
        $this->googleApiKey     = $googleApiKey;
        $this->geocoderBaseUrl  = $geocoderBaseUrl;
    }

    /**
     * @param string $address
     * @return false|CoordinatesVO
     */
    public function geocode($address)
    {
        // todo: Move to bundle conf
        $outputFormat   = 'json';
        $addressEncoded = urlencode($address);

        $client = new Client();

        /** @var Response $response */
        $response = $client->get(sprintf('%s%s?address=%s',
            $this->geocoderBaseUrl,
            $outputFormat,
            $addressEncoded
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
     * @throws UnableToGetGeocodingResultException
     * @throws UnableToParseGeocodingJsonReponseException
     */
    private function resolveCoordinates($jsonResponse)
    {
        $data = json_decode($jsonResponse);

        if($data->status != 'OK')
        {
            throw new UnableToGetGeocodingResultException($data->status);
        }

        try{

            $results    = $data->results;
            $node       = $results[0]->geometry->location;

            return new CoordinatesVO(
                $node->lat,
                $node->lng
            );

        }catch(\Exception $e)
        {
            throw new UnableToParseGeocodingJsonReponseException($e->getMessage());
        }
    }
}