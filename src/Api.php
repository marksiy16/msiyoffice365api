<?php


namespace MarkSiy16\MicrosoftGraphApi;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class Api
{
    public static function getAccessToken()
    {
        $tenantId = env('OAUTH_TENANT_ID');
        $clientId = env('OAUTH_APP_ID');
        $clientSecret = env('OAUTH_APP_PASSWORD');

        $guzzle = new Client();

        $url = 'https://login.microsoftonline.com/' . $tenantId . '/oauth2/v2.0/token';

        $token = json_decode($guzzle->post($url, [
            'form_params' => [
                'client_id' => $clientId,
                'scope' => 'https://graph.microsoft.com/.default',
                'client_secret' => $clientSecret,
                'grant_type' => 'client_credentials',
            ],
        ])->getBody()->getContents());

        return $token->access_token;
    }

    public static function getAllUsers()
    {
        $accessToken = Api::getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $users = $graph->createRequest('GET', '/users')
            ->setReturnType(Model\User::class)
            ->execute();


        return dd($users);
    }

    public static function getAllGroups()
    {
        $accessToken = Api::getAccessToken();

        // Create a Graph client
        $graph = new Graph();
        $graph->setAccessToken($accessToken);

        $groups = $graph->createRequest('GET', '/groups')
            ->setReturnType(Model\Group::class)
            ->execute();


        return dd($groups);
    }
}
