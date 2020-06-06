<?php


namespace Beflo\T3Translator\Authentication;


interface AuthenticationInterface
{

    /**
     * @return array
     */
    public function getRequiredFields(): array;
}