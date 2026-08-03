<?php
/*
        Clase reutilizable
    */

class ApiClient
{
    private string $baseUrl;
    private int $timeout;
    private array $headers;

    public function __construct(string $baseUrl = API_BASE_URL, int $timeout = API_TIMEOUT)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->timeout = $timeout;
        $this->headers = API_HEADERS;
    }

    /*
            Peticion GET
        */
    public function get(string $endpoint)
    {
        return $this->request('GET', $endpoint);
    }

    /*
            Peticion POST
        */
    public function post(string $endpoint, $data = [])
    {
        return $this->request('POST', $endpoint, $data);
    }

    /*
            Peticion PUT
        */
    public function put(string $endpoint, $data = [])
    {
        return $this->request('PUT', $endpoint, $data);
    }

    /*
            Peticion DELETE
        */
    public function delete(string $endpoint)
    {
        return $this->request('DELETE', $endpoint);
    }

    /*
            Metodo generico para hacer peticiones
        */
    public function request(string $method, string $endpoint, $data = null)
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');

        $ch = curl_init($url);

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_FOLLOWLOCATION => true
        ];

        // Configuracion segun el metodo
        switch ($method) {
            case 'POST':
                $options[CURLOPT_POST] = true;
                $options[CURLOPT_POSTFIELDS] = json_encode($data);
                break;

            case 'PUT':
            case 'DELETE':
                $options[CURLOPT_CUSTOMREQUEST] = $method;
                if ($data) {
                    $options[CURLOPT_POSTFIELDS] = json_encode($data);
                }
                break;
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        if ($error) {
            throw new Exception("cURL Error: {$error}");
        }

        return [
            'success' => ($httpCode >= 200 && $httpCode < 300),
            'status' => $httpCode,
            'data' => json_decode($response, true),
            'raw' => $response
        ];
    }
}
