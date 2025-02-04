<?php

namespace PHPExif\Adapter\Writer;

use PHPExif\Adapter\ConvertToUTF8Trait;
use PHPExif\Contracts\Writer\AdapterInterface;
use PHPExif\Contracts\Writer\ExtractorInterface;
use PHPExif\Contracts\MapperInterface;
use PHPExif\Contracts\Reader\HydratorInterface;
use PHPExif\Extractor\Accessor;
use PHPExif\Hydrator\Mutator;

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

    public function __construct(array $options = [])
    {
        if (count($options) > 0) {
            $this->setOptions($options);
        }
    }

    final public function setMapper(MapperInterface $mapper): AdapterInterface
    {
        $this->mapper = $mapper;

        return $this;
    }

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

    public function setExtractor(ExtractorInterface $extractor): AdapterInterface
    {
        $this->extractor = $extractor;

        return $this;
    }

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

    public function setHydrator(HydratorInterface $hydrator): AdapterInterface
    {
        $this->hydrator = $hydrator;

        return $this;
    }

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

    public function setOptions(array $options): AdapterInterface
    {
        $hyderator = $this->getHydrator();
        $hyderator->hydrate($this, $options);

        return $this;
    }

    // @codeCoverageIgnoreEnd
}
