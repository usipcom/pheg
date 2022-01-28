<?php

namespace Simtabi\Pheg\Toolbox\Wrappers\Guzzle;

interface GuzzleApiCallBuilderInterface
{
    /**
     * @param string $method
     * @return mixed
     */
    public function method(string $method);

    /**
     * @param string $uri
     * @return mixed
     */
    public function uri(string $uri);

    /**
     * @param array $body
     * @return mixed
     */
    public function body(array $body);

    /**
     * @param array $formParams
     * @return mixed
     */
    public function formParams(array $formParams);

    /**
     * @param array $multipart
     * @return mixed
     */
    public function multipart(array $multipart);

    /**
     * @param array $header
     * @return mixed
     */
    public function header(array $header);

    /**
     * @param string $token
     * @return mixed
     */
    public function bearerToken(string $token);

    /**
     * @return mixed
     */
    public function call();

}