<?php
namespace TwinePM\Endpoints;

use Psr\Http\Message\ResponseInterface;
use Slim\Container;
use TwinePM\Exceptions\UserRequestFieldInvalidException;
class SearchEndpoint extends AbstractEndpoint {
    function __invoke(Container $container): ResponseInterface {
        $request = $container->get("request");
        $source = $request->getQueryParams();
        $query = isset($source["query"]) ? $source["query"] : null;
        if (!array_key_exists("query", $source)) {
            $errorCode = "QueryInvalid";
            throw new UserRequestFieldInvalidException($errorCode);
        }

        $queryType = isset($source["queryType"]) ? $source["queryType"] : null;
        if (!$queryType) {
            $errorCode = "QueryTypeInvalid";
            throw new UserRequestFieldInvalidException($errorCode);
        }

        $results = $container->get("searchQuery")($queryType, $query);

        $body = $container->get("responseBody");
        $successArray = $container->get("successArray");
        $successArray["results"] = $results
        $successStr = json_encode($successArray);
        $body->write($successStr);
        $response = $container->get("response")->withBody($body);
        return $response;
    }
}