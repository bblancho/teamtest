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
    private $client;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ParameterBagInterface $parameterBag, LoggerInterface $logger, HttpClientInterface $client )
    {
        // Récupération des paramètres de configuration depuis le ParameterBag
        $this->apiKey = $parameterBag->get('ACTIVETRAIL_API_KEY');
        $this->listId = $parameterBag->get('ACTIVETRAIL_LIST_ID');
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

        // Appel API pour envoyer l'email (Exemple : créer une campagne et l'envoyer à la liste)
        try {
            
            // On récupère la liste des users du groupe qui va recevoir la campagne emailing
            $response = $this->client->request(
                "GET",
                "$url/groups/$groupe/members",
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-type'  => 'application/json',
                        'Authorization' => "Basic $this->apiKey"
                    ]
                ]
            );

            $content  = $response->toArray() ;
            $data = $content['contacts'];
            $list_users = [] ;

            foreach ($data as $user) {
                $list_users[] = $user['email']  ;
            }

            dd($list_users) ;

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
                        'name' => 'Test compaign 9',
                        'subject' => $subject,
                        'user_profile_id' => $user_profil_id,
                        'google_analytics_name' => 'UTM_Campaign',
                        'preheader' => 'sample string 4'
                    ],
                    'design' => [
                        'content' => $body,
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

            // On envoie la campagne aux utilisateurs
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

            $this->logger->info('Notification envoyée avec succès à ActiveTrail', ['response' => $response]);

        } catch (\Exception $e) {

            $httpLogs = $response->getInfo('debug');

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