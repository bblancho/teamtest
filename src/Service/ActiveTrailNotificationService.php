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
    /** @var LoggerInterface */
    private $logger;
    private $client;

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
    
        //Test de la valeur de la clé API et de l'ID de la liste
       // dd($this->apiKey, $this->listId); // Assure-toi que les valeurs sont bonnes

        // Si tu veux tester le client
        // dd($this->activeTrailClient) ;
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
                'https://api.activetrail.com/v2/campaigns/1784611',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type' => 'application/json',
                        'Authorization' => $this->apiKey
                    ],
                    // 'body' => $jsonContent,
                ]
            );

            $contentType = $response->getHeaders();

            dd($contentType) ;

            $statusCode = $response->getStatusCode() ;
            $content    = $response->toArray() ;

            $this->logger->info('Notification envoyée avec succès à ActiveTrail', ['response' => $contentType]);

        } catch (\Exception $e) {
            $this->logger->error('Erreur lors de l\'envoi de la notification ActiveTrail', ['error' => $e->getMessage()]);
        }
    }

}