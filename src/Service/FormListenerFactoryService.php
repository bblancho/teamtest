<?php

namespace App\Service;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormListenerFactoryService {

    /**
     * @param string $field
     * @return callable
     */
    public function autoSlug(string $field): callable
    {
        return static function (PreSubmitEvent $event) use ($field) {
            $data = $event->getData();  // Récupère les données soumises avant validation

            if (!is_array($data)) {
                return;
            }

            // Si le slug n'est pas défini et que le champ 'nom' est renseigné
            if (empty($data['slug']) && !empty($data[$field])) {
                // Générer le slug en utilisant le slugger d'AsciiSlugger
                $slugger = new AsciiSlugger();
                $data['slug'] = strtolower(trim($slugger->slug($data[$field])));
                $event->setData($data);  // Met à jour les données du formulaire
            }
        };
    }

    public function timestamp(): callable
	{
        return function(PostSubmitEvent $event) {

            $data = $event->getData();
            //$data->setUpdateDateAT( new \DateTimeImmutable() ) ;

            if( !$data->getId() ) // Lors de la création 
            {
				if( method_exists($data, "setStartDateAT") ){
					$data->setStartDateAT( new \DateTimeImmutable() ) ;
				}
                
            }
        } ;
	}
    
}