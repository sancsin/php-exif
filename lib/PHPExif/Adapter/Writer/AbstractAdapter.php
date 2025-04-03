<?php

namespace PHPExif\Adapter\Writer;

use PHPExif\Adapter\ConvertToUTF8Trait;
use PHPExif\Contracts\Writer\AdapterInterface;
use PHPExif\Contracts\Writer\ExtractorInterface;
use PHPExif\Contracts\MapperInterface;
use PHPExif\Contracts\Reader\HydratorInterface;
use PHPExif\Extractor\Accessor;
use PHPExif\Hydrator\Mutator;

/**
 * PHP Exif Writer Adapter Abstract
 *
 * Implements common functionality for the writer adapters
 *
 * @category    PHPExif
 * @package     Writer
 */
abstract class AbstractAdapter implements AdapterInterface
{
    use ConvertToUTF8Trait;

    /** @var class-string $extractorClass */
    protected string $extractorClass = Accessor::class;
    /** @var class-string $hyderatorClass */
    protected string $hyderatorClass = Mutator::class;
    protected ?MapperInterface $mapper = null;
    protected ?ExtractorInterface $extractor = null;
    protected ?HydratorInterface $hydrator = null;
    /** @var class-string $mapperClass */
    protected string $mapperClass;

    /**
     * Class constructor
     *
     * @param array $options Optional array of data to initialize the object with
     * @return void
     */
    public function __construct(array $options = [])
    {
        if (count($options) > 0) {
            $this->setOptions($options);
        }
    }

    /**
     * Mutator for the data mapper
     *
     * @param \PHPExif\Contracts\MapperInterface $mapper
     * @return \PHPExif\Contracts\Writer\AdapterInterface
     */
    final public function setMapper(MapperInterface $mapper): AdapterInterface
    {
        $this->mapper = $mapper;

        return $this;
    }

    /**
     * Accessor for the data mapper
     *
     * @return \PHPExif\Contracts\MapperInterface
     */
    public function getMapper(): MapperInterface
    {
        if ($this->mapper === null) {
            // lazy load one
            /** @var MapperInterface */
            $mapper = new $this->mapperClass();

            $this->setMapper($mapper);
        }

        return $this->mapper;
    }

    /**
     * Mutator for the extractor
     *
     * @param \PHPExif\Contracts\Writer\ExtractorInterface $extractor
     * @return \PHPExif\Contracts\Writer\AdapterInterface
     */
    public function setExtractor(ExtractorInterface $extractor): AdapterInterface
    {
        $this->extractor = $extractor;

        return $this;
    }

    /**
     * Accessor for the data extractor
     * @return \PHPExif\Contracts\Writer\ExtractorInterface
     */
    public function getExtractor(): ExtractorInterface
    {
        if ($this->extractor === null) {
            // lazy load one
            /** @var ExtractorInterface */
            $extractor = new $this->extractorClass();

            $this->setExtractor($extractor);
        }

        return $this->extractor;
    }

    /**
     * Mutator for the hydrator
     * @param \PHPExif\Contracts\Reader\HydratorInterface $hydrator
     * @return \PHPExif\Contracts\Writer\AdapterInterface
     */
    public function setHydrator(HydratorInterface $hydrator): AdapterInterface
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * Accessor for the data hydrator
     * @return \PHPExif\Contracts\Writer\HydratorInterface
     */
    public function getHydrator(): HydratorInterface
    {
        if ($this->hydrator === null) {
            // lazy load one
            /** @var HydratorInterface */
            $hydrator = new $this->hyderatorClass();

            $this->setHydrator($hydrator);
        }

        return $this->hydrator;
    }

    /**
     * Set array of options in the current object
     * @param array $options
     * @return \PHPExif\Contracts\Writer\AdapterInterface
     */
    public function setOptions(array $options): AdapterInterface
    {
        $hyderator = $this->getHydrator();
        $hyderator->hydrate($this, $options);

        return $this;
    }

    // @codeCoverageIgnoreEnd
}
