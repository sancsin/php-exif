<?php

namespace PHPExif\Adapter;

use InvalidArgumentException;
use Safe\Exceptions\ExecException;
use PHPExif\Reader\PhpExifReaderException;

trait ExiftoolTrait
{
    public const TOOL_NAME = 'exiftool';

    protected string $toolPath = '';
    protected bool $numeric = true;
    protected array $encoding = [];

    /**
     * Setter for the exiftool binary path
     *
     * @param string $path The path to the exiftool binary
     * @return \PHPExif\Adapter\Exiftool Current instance
     * @throws \InvalidArgumentException When path is invalid
     */
    public function setToolPath(string $path): static
    {
        if (!file_exists($path)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Given path (%1$s) to the exiftool binary is invalid',
                    $path
                )
            );
        }
        $this->toolPath = $path;

        return $this;
    }

    /**
     * @param boolean $numeric
     */
    public function setNumeric(bool $numeric): void
    {
        $this->numeric = $numeric;
    }

    /**
     * @see  http://www.sno.phy.queensu.ca/~phil/exiftool/faq.html#Q10
     * @param array $encodings encoding parameters in an array eg. ["exif" => "UTF-8"]
     */
    public function setEncoding(array $encodings): void
    {
        $possible_keys = array("exif", "iptc", "id3", "photoshop", "quicktime",);
        $possible_values = array(
            "UTF8",
            "cp65001",
            "UTF-8",
            "Thai",
            "cp874",
            "Latin",
            "cp1252",
            "Latin1",
            "MacRoman",
            "cp10000",
            "Mac",
            "Roman",
            "Latin2",
            "cp1250",
            "MacLatin2",
            "cp10029",
            "Cyrillic",
            "cp1251",
            "Russian",
            "MacCyrillic",
            "cp10007",
            "Greek",
            "cp1253",
            "MacGreek",
            "cp10006",
            "Turkish",
            "cp1254",
            "MacTurkish",
            "cp10081",
            "Hebrew",
            "cp1255",
            "MacRomanian",
            "cp10010",
            "Arabic",
            "cp1256",
            "MacIceland",
            "cp10079",
            "Baltic",
            "cp1257",
            "MacCroatian",
            "cp10082",
            "Vietnam",
            "cp1258",
        );
        foreach ($encodings as $type => $encoding) {
            if (in_array($type, $possible_keys, true) && in_array($encoding, $possible_values, true)) {
                $this->encoding[$type] = $encoding;
            }
        }
    }

    /**
     * Getter for the exiftool binary path
     * Lazy loads the "default" path
     *
     * @return string
     */
    public function getToolPath(): string
    {
        if ($this->toolPath === '') {
            try {
                // Do not use "which": not available on sh
                $path = exec('command -v ' . self::TOOL_NAME);
                $this->setToolPath($path);
            } catch (ExecException) {
                // Do nothing
            }
        }

        return $this->toolPath;
    }

    /**
     * Returns the output from given cli command
     *
     * @param string $command
     * @return string|false
     * @throws PhpExifReaderException If the command can't be executed
     */
    protected function getCliOutput(string $command): string|false
    {
        $descriptorspec = array(
            0 => array('pipe', 'r'),
            1 => array('pipe', 'w'),
            2 => array('pipe', 'a')
        );

        $process = proc_open($command, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            throw new PhpExifReaderException(
                'Could not open a resource to the exiftool binary'
            );
        }

        $result = stream_get_contents($pipes[1]);
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);

        proc_close($process);

        return $result;
    }
}
