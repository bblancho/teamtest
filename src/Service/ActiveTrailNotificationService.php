<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Yehudafh\ActiveTrail\ActiveTrail;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ActiveTrailNotificationService
{
    private $activeTrailClient;
    private $apiKey;
    private $listId;
    private $client;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger, HttpClientInterface $client )
    {
        // Récupération des paramètres de configuration depuis le ParameterBag
        $this->apiKey = $parameterBag->get('ACTIVETRAIL_API_KEY') ;
        $this->listId = $parameterBag->get('ACTIVETRAIL_LIST_ID') ;
        $this->logger = $logger ;
        $this->client = $client ;

        // Initialisation du client ActiveTrail avec la clé API
        $this->activeTrailClient = new ActiveTrail(
            [
                'api_key' => $this->apiKey,
                'soft_group' => '',  // À Remplacer par la valeur correcte ou par un paramètre
                'fields' => [],
            ]
        );
    
    }

    public function sendNotification(string $missionTitle, string $missionDescription)
    {
        // Prépare le contenu de l'email (tu peux adapter ça avec des placeholders ou un modèle d'email)
        $subject    = "Nouvelle mission : " . $missionTitle;
        $body       = "Une nouvelle mission est disponible : " . $missionDescription;
        $url        = 'https://webapi.mymarketing.co.il/api'; // /campaigns/1784611
        $method     = 'GET';
        
        // Appel API pour envoyer l'email (Exemple : créer une campagne et l'envoyer à la liste)
        try {

            $response = $this->client->request(
                $method,
                $url."/groups/436114",
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

            $content    = $response->toArray() ;

            $this->logger->info('Notification envoyée avec succès à ActiveTrail', ['response' => $content]);

        } catch (\Exception $e) {

            $httpLogs = $response->getInfo('debug');

            $this->logger->error('Erreur lors de l\'envoi de la notification ActiveTrail', ['error' => $e->getMessage()]);
        }
    }

}