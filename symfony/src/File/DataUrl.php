<?php

namespace App\File;

use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

class DataUrl
{
    /**
     * @var static[]
     */
    protected static $instances = [];

    /**
     * @var string
     */
    private $mimeType;

    /**
     * @var string
     */
    private $blob;

    /**
     * @param string $dataUrl
     */
    private function __construct(string $dataUrl)
    {
        $this->setDataUrl($dataUrl);
    }

    /**
     * @param string $dataUrl
     * @return static
     */
    public static function create($dataUrl)
    {
        if (array_key_exists($dataUrl, self::$instances)) {
            return self::$instances[$dataUrl];
        }

        return self::$instances[$dataUrl] = new static($dataUrl);
    }

    /**
     * Checks validity of data URLs.
     *
     * @param string $dataUrl
     * @return bool
     */
    public static function isValid(string $dataUrl)
    {
        return 0 === strpos($dataUrl, 'data:');
    }

    /**
     * @param string $dataUrl
     */
    protected function setDataUrl(string $dataUrl)
    {
        // Check correctness of format
        if (!self::isValid($dataUrl)) {
            throw new \LogicException('Data URL is not properly formatted');
        }

        // Normalize for PHP
        $dataUrl = str_replace(' ', '+', $dataUrl);

        // Parse components
        $pos = strpos($dataUrl, ';');
        $this->mimeType = substr($dataUrl, 5, $pos - 5);
        $this->blob     = base64_decode(substr($dataUrl, $pos + 8));

        // Check success of parsing
        if (false === $this->blob) {
            throw new \LogicException('Data URL could not be decoded');
        }
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getBlob()
    {
        return $this->blob;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return ExtensionGuesser::getInstance()->guess($this->mimeType);
    }
}