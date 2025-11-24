<?php

namespace App\EventSubscriber;


use App\Entity\Offres;
use App\Event\OfferPublishedEvent;
use App\Service\ActiveTrailNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class OfferSubscriber implements EventSubscriberInterface
{
    private ActiveTrailNotificationService $activeTrail;

    public function __construct(ActiveTrailNotificationService $activeTrail)
    {
        $this->activeTrail = $activeTrail;
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            OfferPublishedEvent::class => 'onOfferPublished',
        ];
    }

    /**
     * @param OfferPublishedEvent $event
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function onOfferPublished(OfferPublishedEvent $event)
    {
        /** @var Offres $offer */
        $offer = $event->getOffer();

        $subject = "Nouvelle offre : " . $offer->getNom();
        $body = sprintf(
            "Découvrez la nouvelle offre : %s\n\nLien : %s",
            $offer->getDescription(),
            'https://127.0.0.1:8000/offres/creation/' . $offer->getId()
        );

        // Envoi via ActiveTrail
        $this->activeTrail->sendNotification(
            $subject,
            $body
        );
    }
}
