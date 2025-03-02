<?php

namespace PHPExif\Adapter\Reader;

use PHPExif\Adapter\ConvertToUTF8Trait;
use PHPExif\Contracts\Reader\AdapterInterface;
use PHPExif\Contracts\Reader\HydratorInterface;
use PHPExif\Contracts\MapperInterface;
use PHPExif\Hydrator\Mutator;

/**
 * PHP Exif Reader Adapter Abstract
 *
 * Implements common functionality for the reader adapters
 *
 * @category    PHPExif
 * @package     Reader
 */
abstract class AbstractAdapter implements AdapterInterface
{
    use ConvertToUTF8Trait;

    /** @var class-string $hydratorClass */
    protected string $hydratorClass = Mutator::class;
    protected ?MapperInterface $mapper = null;
    protected ?HydratorInterface $hydrator = null;
    /** @var class-string $mapperClass */
    protected string $mapperClass;

    /**
     * Class constructor
     *
     * @param array $options Optional array of data to initialize the object with
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
     * @return \PHPExif\Contracts\Reader\AdapterInterface
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
        if (null === $this->mapper) {
            // lazy load one
            /** @var MapperInterface */
            $mapper = new $this->mapperClass();

            $this->setMapper($mapper);
        }

        return $this->mapper;
    }

    /**
     * Mutator for the hydrator
     *
     * @param \PHPExif\Contracts\Reader\HydratorInterface $hydrator
     * @return \PHPExif\Contracts\Reader\AdapterInterface
     */
    public function setHydrator(HydratorInterface $hydrator): AdapterInterface
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    /**
     * Accessor for the data hydrator
     *
     * @return \PHPExif\Contracts\HydratorInterface
     */
    public function getHydrator(): HydratorInterface
    {
        if (null === $this->hydrator) {
            // lazy load one
            /** @var HydratorInterface */
            $hydrator = new $this->hydratorClass();

            $this->setHydrator($hydrator);
        }

        return $this->hydrator;
    }

    /**
     * Set array of options in the current object
     *
     * @param array $options
     * @return \PHPExif\Contracts\AdapterInterface
     */
    public function setOptions(array $options): AdapterInterface
    {
        $hydrator = $this->getHydrator();
        $hydrator->hydrate($this, $options);

        return $this;
    }
    // @codeCoverageIgnoreEnd
}
