<?php


namespace Katsitu\Services;


use Katsitu\Contracts\GeoIP;
use Illuminate\Http\Request;

class FaizGeoIP implements GeoIP
{

    private $request;

    // https://freegeoip.net/json
    // http://www.telize.com/geoip
    private $serviceUrl = 'http://geoip.faizshukri.com/json';

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->request->setTrustedProxies(array('127.0.0.1')); // only trust proxy headers coming from the IP addresses on the array (change this to suit your needs)
    }

    public function getLocation()
    {

        if (!session('user_location')) {
            $this->checkLocalIp();

            $json = json_decode(file_get_contents($this->serviceUrl), true);

            session([
                'user_location' => [
                    'latitude' => $json['latitude'],
                    'longitude' => $json['longitude']
                ]
            ]);
        }

        return session('user_location');
    }

    private function checkLocalIp()
    {
        if (!in_array(substr($this->request->getClientIp(), 0, 7), ['::1', '192.168', '127.0.0'])) {
            $this->serviceUrl .= '/'. $this->request->getClientIp();
        }
    }
}
