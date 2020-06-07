<?php


namespace Beflo\T3Translator\TranslationService;


class TranslationValue
{

    /**
     * @var array
     */
    private $valuesToTranslate = [];

    /**
     * @var array
     */
    private $pattern = [];

    /**
     * @var string
     */
    private $fieldName;

    /**
     * TranslationValue constructor.
     *
     * @param string $fieldName
     * @param string $value
     */
    public function __construct(string $value, string $fieldName = '')
    {
        $this->fieldName = $fieldName;
        $this->analyze($value);
    }

    /**
     * @param string $value
     */
    private function analyze(string $value)
    {
        preg_match_all("/(<.*?>\s*)+/", $value, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $match) {
                $tmp = explode($match, $value, 2);
                if (count($tmp) == 2) {
                    if (!empty($tmp[0])) {
                        $key = count($this->valuesToTranslate);
                        $this->valuesToTranslate[$key] = $tmp[0];
                        $this->pattern[] = &$this->valuesToTranslate[$key];
                    }
                    $value = $tmp[1];
                    $this->pattern[] = $match;
                }
            }
        } else {
            $key = count($this->valuesToTranslate);
            $this->valuesToTranslate[$key] = $value;
            $this->pattern[] = &$this->valuesToTranslate[$key];
        }
    }

    /**
     * @return array
     */
    public function getValuesToTranslate(): array
    {
        return $this->valuesToTranslate;
    }

    /**
     * @return string
     */
    public function getNewValue(): string
    {
        return (string)implode('', $this->pattern);
    }

    /**
     * @return string
     */
    public function getFieldName(): string
    {
        return $this->fieldName;
    }

}