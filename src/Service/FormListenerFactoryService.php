<?php

namespace App\Service;

use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;

class FormListenerFactoryService {

    public function autoSlug(string $fields): callable
	{
		return function(PreSubmitEvent $event) use($fields){
			
			$data = $event->getData(); // On récupère les données lors de la soumission du formulaire

			//dd("ee", $data['refMission']) ;
			
			if( empty($data['slug']) ) 
			{
				$slugger = new AsciiSlugger() ;
				$data['slug'] = strtolower( $slugger->slug( $data[$fields] ) ) ;
				$event->setData($data) ;
			}
		}	;	
	}

    public function timestamp(): callable
	{
        return function(PostSubmitEvent $event) {

            $data = $event->getData();
            //$data->setUpdateDateAT( new \DateTimeImmutable() ) ;

            if( !$data->getId() ) // Lors de la création 
            {
                $data->setStartDateAT( new \DateTimeImmutable() ) ;
            }
        } ;
	}
    
}