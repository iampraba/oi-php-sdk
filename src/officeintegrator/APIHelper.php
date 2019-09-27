<?php

namespace officeintegrator;

include_once __DIR__.'/configurations/AppConfiguration.php';

use officeintegrator\configurations\AppConfiguration;

/**
 * API utility class
 */

class APIHelper {

    /**
    * Appends the given set of parameters to the given query string
    * @param    string  $queryBuilder   The query url string to append the parameters
    * @param    array   $parameters     The parameters to append
    * @return   void
    */
    public static function appendUrlWithQueryParameters($queryBuilder, $parameters)
    {
        //perform parameter validation
        if (is_null($queryBuilder) || !is_string($queryBuilder)) {
            throw new InvalidArgumentException('Given value for parameter "queryBuilder" is invalid.');
        }
        if (is_null($parameters)) {
            return;
        }

        //does the query string already has parameters
        $hasParams = (strrpos($queryBuilder, '?') > 0);

        //if already has parameters, use the &amp; to append new parameters
        $queryBuilder = $queryBuilder . (($hasParams) ? '&' : '?');

        //append parameters
        $queryBuilder = $queryBuilder . http_build_query($parameters);

        return $queryBuilder;
    }

    /**
    * Replaces template parameters in the given url
    * @param    string  $url         The query string builder to replace the template parameters
    * @param    array   $parameters  The parameters to replace in the url
    * @return   string  The processed url
    */
    public static function appendUrlWithTemplateParameters($url, $parameters)
    {
        //perform parameter validation
        if (is_null($url) || !is_string($url)) {
            throw new InvalidArgumentException('Given value for parameter "queryBuilder" is invalid.');
        }

        if (is_null($parameters)) {
            return $url;
        }

        //iterate and append parameters
        foreach ($parameters as $key => $value) {
            $replaceValue = '';

            //load parameter value
            if (is_null($value)) {
                $replaceValue = '';
            } elseif (is_array($value)) {
                $replaceValue = implode("/", array_map("urlencode", $value));
            } else {
                $replaceValue = urlencode(strval($value));
            }

            //find the template parameter and replace it with its value
            $url = str_replace('{' . strval($key) . '}', $replaceValue, $url);
        }

        return $url;
    }

    public static function build_post_data($boundary, $fields, $files){
        $data = '';
        $eol = "\r\n";

        foreach ($fields as $name => $content) {
            $data .= "--" . $boundary . $eol
                . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
                . $content . $eol;
        }

        foreach ($files as $name => $file_path) {
            $data .= "--" . $boundary . $eol
                . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . basename($file_path) . '"' . $eol;
                //. 'Content-Type: ' . mime_content_type($file_path) . $eol;
                //. 'Content-Transfer-Encoding: binary'.$eol;

            $data .= $eol;
            $data .= file_get_contents($file_path) . $eol;
        }

        $data .= "--" . $boundary . "--".$eol;

        return $data;
    }


    /**
    * @return array headers with Authentication tokens added 
    */
    public static function set_headers(array $headers, $header_key, $header_value) 
    {
        $headers[] = $header_key . ':' . $header_value;
        return $headers;        
    }

    /**
    * @param string $method ('GET', 'POST', 'DELETE', 'PATCH')
    * @param string $path whichever API path you want to target.
    * @param array $data contains the POST data to be sent to the API.
    * @return array decoded json returned by API.
    */
    public static function make_api_call(string $method, string $request_url, array $url_params=null, array $post_params=null, array $file_params=null) 
    {
        $headers = array();
        $url_params["apikey"] = AppConfiguration::API_KEY;
        $request_url = self::appendUrlWithQueryParameters($request_url, $url_params);

        if( !is_null($file_params) && !empty($file_params)) {
            $boundary = '-------------WebKitFormBoundary' . uniqid();
            $post_data = self::build_post_data($boundary, $post_params, $file_params);
            $headers = self::set_headers($headers, "Content-Type", "multipart/form-data; boundary=" . $boundary);
            $headers = self::set_headers($headers, "Content-Length", strlen($post_data));
        } else {
            $post_data = http_build_query($post_params);
        }

        $options = array();
        $options[CURLOPT_HTTPHEADER] = $headers;
        $options[CURLOPT_RETURNTRANSFER] = true;

        if($method == 'POST') {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_CUSTOMREQUEST] = 'POST';
            $options[CURLOPT_POSTFIELDS] = $post_data;
        } else if($method == 'DELETE') {
            $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
        } else if($method == 'PATCH') {
            $options[CURLOPT_POST] = 1;
            $options[CURLOPT_POSTFIELDS] = $post_data;
            $options[CURLOPT_CUSTOMREQUEST] = 'PATCH';
        } else if ($method == 'GET' or $method == 'HEAD') {
            if (!empty($data)) {
                /* Update URL to container Query String of Paramaters */
                $request_url .= '?' . http_build_query($data);
            }
        }

        $options[CURLOPT_URL] = $request_url;
        $options[CURLOPT_SSL_VERIFYPEER] = true;

        $curl = curl_init();

        $setopt = curl_setopt_array($curl, $options);

        $response = curl_exec($curl);

        print_r($response);

        $response_headers = curl_getinfo($curl);

        if ( strpos($response_headers["content_type"], "application/json" ) !== false ) {
            $response_obj = json_decode($response, true);
        } else {
            $response_obj = $response;
        }
        $error_number = curl_errno($curl);
        $error_message = curl_error($curl);

        if($error_number != 0){
            if($error_number == 60){
                throw new \Exception("Something went wrong. cURL raised an error with number: $error_number and message: $error_message. " .
                                    "Please check http://stackoverflow.com/a/21114601/846892 for a fix." . PHP_EOL);
            } else {
                throw new \Exception("Something went wrong. cURL raised an error with number: $error_number and message: $error_message." . PHP_EOL);
            }
        }

        return $response_obj;
    }
}
?>