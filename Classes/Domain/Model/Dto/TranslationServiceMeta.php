<?php


namespace Beflo\T3Translator\Domain\Model\Dto;


use Beflo\T3Translator\Exception\MissingInterfaceException;
use Beflo\T3Translator\TranslationService\TranslationServiceInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class TranslationServiceMeta
{

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $class;

    /**
     * @var TranslationServiceInterface
     */
    private $object;

    /**
     * TranslationServiceMeta constructor.
     *
     * @param array $config
     *
     * @throws MissingInterfaceException
     */
    public function __construct(array $config = [])
    {
        $this->name = $config['name'] ?? '';
        $this->description = $config['description'] ?? '';
        $this->setClass($config['class'] ?? '');
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return TranslationServiceMeta
     */
    public function setName(string $name): TranslationServiceMeta
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return TranslationServiceMeta
     */
    public function setDescription(string $description): TranslationServiceMeta
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     *
     * @return TranslationServiceMeta
     *
     * @throws MissingInterfaceException
     */
    public function setClass(string $class): TranslationServiceMeta
    {
        $implementations = @class_implements($class);
        if (empty($implementations[TranslationServiceInterface::class])) {
            throw new MissingInterfaceException('A translation service class must implement the interface: ' . TranslationServiceInterface::class);
        }
        $this->class = $class;
        $this->identifier = md5($class);

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return TranslationServiceInterface
     */
    public function getObject()
    {
        if(empty($this->object)) {
            $this->object = GeneralUtility::makeInstance($this->class);
        }

        return $this->object;
    }
}