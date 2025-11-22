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
        $method     = 'POST';
        $user_profil_id = 30264;
        $groupe     = 436114;
        $contact_ID = 209253083;
        
        // créer une mailinglist 
            // http://webapi.mymarketing.co.il/api/mailinglist - data {"Name": "{{Name}}"}

        // Appel API pour envoyer l'email (Exemple : créer une campagne et l'envoyer à la liste)
        try {

            $newUser = [
                'status' => 'None',
                'email' => 'zoro.maythio@gmail.com',
                'first_name' => 'Zoro',
                'last_name' => 'popo',
                'is_do_not_mail' => false,
                'is_deleted' => false
            ];

            $list_users = 
                [
                    "blanchard.banyingela@laposte.net",
                    "hamshakour93@gmail.com",
                    "dkeddi94@gmail.com",
                    "proche@team2i.fr"
                ] 
            ;

            $campaign = [
                'campaign' => [
                    'details' => [
                        'name' => 'Test compaign 6',
                        'subject' => 'Test compaign 6',
                        'user_profile_id' => $user_profil_id,
                        'google_analytics_name' => 'UTM_Campaign',
                        'preheader' => 'sample string 4'
                    ],
                    'design' => [
                        'content' => 'sample string 1',
                        'language_type' => 'UTF-8',
                        'header_footer_language_type' => 'UTF-8',
                        'is_add_print_email' => true
                    ],
                    'template' => [
                        'id' => ""
                    ]
                ],
                'campaign_contacts' => [
                    'contacts_emails' => $list_users
                ]
            ];

            $response = $this->client->request(
                $method,
                "$url/campaigns/contacts",
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type'  => 'application/json',
                        'Authorization' => "Basic $this->apiKey"
                    ],
                    "json" => $campaign
                ]
            );

            $content  = $response->toArray() ;

            $this->logger->info('Notification envoyée avec succès à ActiveTrail', ['response' => $content]);

        } catch (\Exception $e) {

            $httpLogs = $response->getInfo('debug');

            $this->logger->error('Erreur lors de l\'envoi de la notification ActiveTrail', ['error' => $e->getMessage()]);
        }
    }

}