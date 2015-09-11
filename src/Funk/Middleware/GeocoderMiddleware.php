<?php
namespace Funk\Middleware;
use Geocoder\Geocoder;
use Geocoder\Provider\FreeGeoIp;
use Ivory\HttpAdapter\CurlHttpAdapter;

class GeocoderMiddleware extends Middleware
{
    protected $geocoder;

    public function __construct(callable $next, Geocoder $geocoder = null)
    {
        parent::__construct($next);
        $this->geocoder = $geocoder ?: $this->createDefaultGeocoder();
    }


    public function createDefaultGeocoder()
    {
        $adapter = new CurlHttpAdapter();
        $geocoder = new FreeGeoIp($adapter);
        return $geocoder;
    }


    public function call(array & $environment, array $response)
    {
        if (isset($environment['REMOTE_ADDR'])) {
            $results = $this->geocoder->geocode($environment['REMOTE_ADDR']);
            if ($countryCode = $results->get(0)->getCountryCode()) {
                $environment['geoip.country_code'] = $countryCode;
            }
        }
        $n = $this->next;
        return $n($environment, $response);
    }
}



