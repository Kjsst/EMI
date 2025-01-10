<?php // Code within app\Helpers\Helper.php
namespace App\Helper;
class CustomHelper
{
    public static function GetApi($url,$data=null)
    {
        $curl = curl_init();

        $url = (str_replace(' ', '%20', $url));
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: JSESSIONID=178B26CCF6CDBB5635F14560B26F52CC.node3'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            $data = $err;
        } else {
            $data = json_decode($response);
        }
        return $data;
    }

    public static function GetDeviceApi($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER=> false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: lhE8IVY4b1W0dP/ioK575dzAW2LgrPiUZXq5kfQFhNDzEeSigtGNW9VIfEl+xvC2QnazQ1JEgcDCc9JtcIguw+FWndboy7lmVUZjyfhiSEisasyBW3p/qv5cW/1EQE4P3T3F8IuqvZBJfoqgmnF4YPQNtU1dL4r9lR/wkqbDvTptSdOVyLp/50v8wLxuNG5zp8qTNrvwZ/DzCBNBKfD14l+/oZDV90YifIWrdXD54Hfzcq8AeUCS5ItO5XbrEuvYXnKeyqAwLmu5pncs1cgo6NOcEW+sdYFW9q+qgzWDs6o5txVan+MtsfC8truZbcyhcxkxBYXkgYg6T4yHjVUxzIZUpkIJe6wdw5CajRdnMGk='
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        curl_close($curl);
        $http_status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            $data = json_decode($err);
        } else {
            $data = json_decode($response,true);
            $data['statusCode']=$http_status_code;
        }
        return $data;
    }
}
