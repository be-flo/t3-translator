<?php


namespace Beflo\T3Translator\Domain\Model\Dto;


use Beflo\T3Translator\Authentication\AuthenticationInterface;
use Beflo\T3Translator\Exception\MissingInterfaceException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class AuthenticationMeta
{

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $class;

    /**
     * AuthenticationMeta constructor.
     *
     * @param array $config
     *
     * @throws MissingInterfaceException
     */
    public function __construct(array $config = [])
    {
        $this->label = $config['label'] ?? '';
        $this->setClass($config['class'] ?? '');
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return AuthenticationMeta
     */
    public function setLabel(string $label): AuthenticationMeta
    {
        $this->label = $label;

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
     * @return AuthenticationMeta
     *
     * @throws MissingInterfaceException
     */
    public function setClass(string $class): AuthenticationMeta
    {
        $implementations = @class_implements($class);
        if (empty($implementations[AuthenticationInterface::class])) {
            throw new MissingInterfaceException('A authentication class must implement the interface: ' . AuthenticationInterface::class);
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
     * @return AuthenticationInterface
     */
    public function getObject()
    {
        return GeneralUtility::makeInstance($this->class);
    }

}