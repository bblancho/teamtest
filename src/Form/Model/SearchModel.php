<?php

namespace App\Form\Model;


class SearchModel{

    public function __construct(
        public ?string $query = null,
        public ?string $skills = null,
        public bool $createdThisMonth = false
    ){
    }

}