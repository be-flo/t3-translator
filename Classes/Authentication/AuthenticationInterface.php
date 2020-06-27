<?php


namespace Beflo\T3Translator\Authentication;


interface AuthenticationInterface
{

    /**
     * @return array
     */
    public function getRequiredFields(): array;

    /**
     * @param array $translationServiceRecord
     */
    public function fillRequiredFields(array $translationServiceRecord): void;

    /**
     * @param string $url
     * @param array  $data
     *
     * @return array|null
     */
    public function post(string $url, array $data): ?array;

    /**
     * @param string $fieldName
     *
     * @return mixed
     */
    public function getRequiredField(string $fieldName);
}