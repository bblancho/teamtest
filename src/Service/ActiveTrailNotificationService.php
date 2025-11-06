<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ActiveTrailNotificationService
{
    private $activeTrailClient;
    private $apiKey;
    private $listId;
    /** @var LoggerInterface */
    private $logger;
    private $client;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger, HttpClientInterface $client )
    {
        // Récupération des paramètres de configuration depuis le ParameterBag
        $this->apiKey = $parameterBag->get('ACTIVETRAIL_API_KEY') ;
        $this->listId = $parameterBag->get('ACTIVETRAIL_LIST_ID') ;
        $this->logger = $logger;
        $this->client = $client;
    }

    public function sendNotification(string $missionTitle, string $missionDescription)
    {
        // Prépare le contenu de l'email (tu peux adapter ça avec des placeholders ou un modèle d'email)
        $subject    = "Nouvelle mission : " . $missionTitle;
        $body       = "Une nouvelle mission est disponible : " . $missionDescription;
        $url        = 'https://api.activetrail.com/v2/campaigns/1784611';
        $method     = 'GET';
        
        // Appel API pour envoyer l'email (Exemple : créer une campagne et l'envoyer à la liste)
        try {
            // $response = $this->client->request($method, $url, [
            //     'json' => [
            //         'api_key' => $this->apiKey,
            //         'list_id' => $this->listId,
            //         // 'subject' => $subject,
            //         // 'body' => $body,
            //     ]
            // ]);

            $response = $this->client->request(
                'GET',
                'https://webapi.mymarketing.co.il/api/groups/436114',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json',
                        'Authorization' => "Basic $this->apiKey"
                    ],
                    // 'auth_basic' => ['token', $this->apiKey],
                    // 'body' => $jsonContent,
                ]
            );

            $statusCode = $response->getStatusCode() ;
            $content    = $response->toArray() ;

            $this->logger->info('Notification envoyée avec succès à ActiveTrail', ['response' => $content]);

        } catch (\Exception $e) {

            $httpLogs = $response->getInfo('debug');

            dd($httpLogs) ;
            $this->logger->error('Erreur lors de l\'envoi de la notification ActiveTrail', ['error' => $e->getMessage()]);
        }
    }

    public function sendCampaignEmail($campaignId): array
    {
        //$url = "https://webapi.mymarketing.co.il/api/campaigns/{$campaignId}/send";
        $url ="https://webapi.mymarketing.co.il/api/campaigns";

        $response = $this->client->request(
            'GET', $url,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                    // 'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':'),
                    'Authorization' => "Basic $this->apiKey",
                ],
                //'json' => [
                //    'subject' => $subject,
                 //   'body' => $body,
                //],
            ]
        );

        foreach ($response->toArray() as $values) {
           foreach ($values as $value) {
               if($value['id'] == $campaignId) {
                   dd($value);
               }
           }
           exit();
        }

        dd($response->toArray());

        return $response->toArray(false);
    }

}