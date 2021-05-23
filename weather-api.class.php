<?php

class Weather
{
        private static $API_KEY = "YOUR API KEY HERE";

        private static function getAPIKey()
        {
                return self::$API_KEY;
        }

        private function getURL($data)
        {
                $BASE_URL = "http://api.openweathermap.org/";

                $URLs = array(
                        "getCoordinates" => array(
                                "geo" => "geo/1.0/direct?q={city},{country}&limit={limit}&appid={API_key}",
                                "zip" => "geo/1.0/zip?zip={zip},{country}&appid={API_key}",
                        ),
                        "getCoordinatesReverse" => array(
                                "reverse" => "geo/1.0/reverse?lat={lat}&lon={lon}&limit={limit}&appid={API_key}",
                        ),
                        "getForecast" => array(
                                "current" => "data/2.5/weather?q={city}&appid={API_key}",
                                "one-call" => "data/2.5/onecall?lat={lat}&lon={lon}&appid={API_key}&units=metric",
                        )
                );

                $key = $URLs[$data['operation']][$data['suboperation']];
                if ($key !== "") {
                        $URL = $URLs[$data['operation']][$data['suboperation']];
                        foreach ($data as $key => $value) {
                                $URL = str_replace("{" . $key . "}", $value, $URL);
                        }
                        return $BASE_URL . str_replace("{API_key}", $this->getAPIKey(), $URL);
                } else {
                        return false;
                }
        }

        private function callAPI($data)
        {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->getURL($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                return json_decode($output, true);
        }

        public function getCoordinates($city, $country)
        {
                $city = trim($city);
                $country = trim($country);
                $data = array(
                        "operation" => "getCoordinates",
                        "suboperation" => (preg_replace("/[0-9]/", "", $city) == "" ? "zip" : "geo"),
                        "city" => $city,
                        "zip" => $city,
                        "country" => $country,
                        "limit" => 50
                );
                return $this->callAPI($data);
        }

        public function getCoordinatesReverse($lat, $lon)
        {
                $data = array(
                        "operation" => "getCoordinatesReverse",
                        "suboperation" => "reverse",
                        "lat" => $lat,
                        "lon" => $lon,
                        "limit" => 50
                );
                return $this->callAPI($data);
        }

        public function getForecast($city, $country, $simple = true)
        {
                $data = array(
                        "operation" => "getForecast",
                        "suboperation" => ($simple ? "current" : "one-call"),
                        "city" => $city,
                        "country" => $country,
                );
                if (!$simple) {
                        $city = $this->getCoordinates($city, $country);
                        $data['lat'] = $city[0]['lat'];
                        $data['lon'] = $city[0]['lon'];
                }
                return $this->callAPI($data);
        }
}
