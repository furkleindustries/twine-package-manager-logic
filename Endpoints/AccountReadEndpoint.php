<?php
namespace TwinePM\Endpoints;

use Psr\Http\Message\ResponseInterface;
use Slim\Container;
class AccountReadEndpoint extends AbstractEndpoint {
    function __invoke(Container $container): ResponseInterface {
        $token = $container->get("authorizeToken");
        $getFromToken = $container->get("getFromToken");
        $sqlAbstractionType = "account";
        $account = $getFromToken($sqlAbstractionType, $token);

        $body = $container->get("responseBody");
        $successArray = $container->get("successArray");
        $successArray["account"] = $account;
        $successStr = json_encode($successArray);
        $body->write($successStr);
        $response = $container->get("response")->withBody($body);
        return $response;
    }
}