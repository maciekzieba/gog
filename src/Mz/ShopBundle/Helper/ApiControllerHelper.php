<?php

class ApiControllerHelper {

    static public function createErrorResponse($httpCode, $body)
    {
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setStatusCode($httpCode);
        $response->setContent($body);

        return $response;
    }

}