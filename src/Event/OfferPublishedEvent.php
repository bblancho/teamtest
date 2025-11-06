<?php

namespace App\Event;

use App\Entity\Offres;
use Symfony\Contracts\EventDispatcher\Event;

class OfferPublishedEvent extends Event
{
    public const NAME = 'offer.published';

    /** @var Offres */
    public $offer;

    public function __construct(Offres $offer)
    {
        $this->offer = $offer;
    }

    /**
     * @return Offres
     */
    public function getOffer(): Offres
    {
        return $this->offer;
    }
}