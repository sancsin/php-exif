<?php

/**
 * PHP Exif Exiftool Mapper
 *
 * @link        http://github.com/miljar/PHPExif for the canonical source repository
 * @copyright   Copyright (c) 2015 Tom Van Herreweghe <tom@theanalogguy.be>
 * @license     http://github.com/miljar/PHPExif/blob/master/LICENSE MIT License
 * @category    PHPExif
 * @package     Mapper
 */

namespace PHPExif\Mapper;

use PHPExif\Mapper\ExiftoolTrait;

use PHPExif\Exif;
use Safe\DateTime;

use function Safe\preg_match;
use function Safe\preg_replace;

/**
 * PHP Exif Exiftool Mapper
 *
 * Maps Exiftool raw data to valid data for the \PHPExif\Exif class
 *
 * @category    PHPExif
 * @package     Mapper
 */
class Exiftool extends AbstractMapper
{
    use ExiftoolTrait;
    /**
     * Maps the array of raw source data to the correct
     * fields for the \PHPExif\Exif class
     *
     * @param array $data
     * @return array
     */
    public function mapRawData(array $data): array
    {
        $mappedData = [];

        foreach ($data as $field => $value) {
            if (!array_key_exists($field, $this->map)) {
                // silently ignore unknown fields
                continue;
            }

            $key = $this->map[$field];
            $value = $this->trim($value);

            // manipulate the value if necessary
            switch ($field) {
                case self::APERTURE:
                    $value = sprintf('f/%01.1f', $value);
                    break;
                case self::APPROXIMATEFOCUSDISTANCE:
                    $value = sprintf('%1$sm', $value);
                    break;
                case self::DATETIMEORIGINAL:
                case self::DATETIMEORIGINAL_PNG:
                case self::DATETIMEORIGINAL_QUICKTIME:
                case self::DATETIMEORIGINAL_AVI:
                case self::DATETIMEORIGINAL_WEBM:
                case self::DATETIMEORIGINAL_OGG:
                case self::DATETIMEORIGINAL_WMV:
                    // DATETIMEORIGINAL_APPLE contains data on timezone
                    // only set value if DATETIMEORIGINAL_APPLE has not been used
                    if (
                        !isset($mappedData[Exif::CREATION_DATE])
                        && preg_match('/^0000[-:]00[-:]00.00:00:00/', $value) === 0
                    ) {
                        try {
                            if (isset($data['ExifIFD:OffsetTimeOriginal'])) {
                                try {
                                    $timezone = new \DateTimeZone($data['ExifIFD:OffsetTimeOriginal']);
                                } catch (\Exception $e) {
                                    $timezone = null;
                                }
                                $value = new DateTime($value, $timezone);
                            } elseif (isset($data['ExifIFD:OffsetTime'])) {
                                try {
                                    $timezone = new \DateTimeZone($data['ExifIFD:OffsetTime']);
                                } catch (\Exception $e) {
                                    $timezone = null;
                                }
                                $value = new DateTime($value, $timezone);
                            } else {
                                $value = new DateTime($value);
                            }
                        } catch (\Exception $e) {
                            continue 2;
                        }
                    } else {
                        continue 2;
                    }

                    break;
                case self::DATETIMEORIGINAL_APPLE:
                    if (preg_match('/^0000[-:]00[-:]00.00:00:00/', $value) === 1) {
                        continue 2;
                    }
                    try {
                        $value = new DateTime($value);
                    } catch (\Exception $e) {
                        continue 2;
                    }

                    break;
                case self::EXPOSURETIME:
                    // Based on the source code of Exiftool (PrintExposureTime subroutine):
                    // http://cpansearch.perl.org/src/EXIFTOOL/Image-ExifTool-9.90/lib/Image/ExifTool/Exif.pm
                    if ($value < 0.25001 && $value > 0) {
                        $value = sprintf('1/%d', intval(0.5 + 1 / $value));
                    } else {
                        $value = sprintf('%.1f', $value);
                        $value = preg_replace('/.0$/', '', $value);
                    }
                    break;
                case self::FOCALLENGTH:
                    if (!$this->numeric || strpos($value, ' ') !== false) {
                        $focalLengthParts = explode(' ', $value);
                        $value = reset($focalLengthParts);
                    }
                    break;
                case self::ISO:
                    $value = explode(" ", $value)[0];
                    break;
                case self::GPSLATITUDE_QUICKTIME:
                    $value  = $this->extractGPSCoordinates($value);
                    if ($value === false) {
                        continue 2;
                    }
                    break;
                case self::GPSLATITUDE:
                    $value = $this->extractGPSCoordinates($value);
                    if ($value === false) {
                        continue 2;
                    }
                    $latitudeRef = !array_key_exists('GPS:GPSLatitudeRef', $data)
                        || $data['GPS:GPSLatitudeRef'] === null || $data['GPS:GPSLatitudeRef'] === '' ?
                        'N' : $data['GPS:GPSLatitudeRef'][0];
                    $value *= strtoupper($latitudeRef) === 'S' ? -1 : 1;
                    break;
                case self::GPSLONGITUDE_QUICKTIME:
                    $value  = $this->extractGPSCoordinates($value);
                    if ($value === false) {
                        continue 2;
                    }
                    break;
                case self::GPSLONGITUDE:
                    $value = $this->extractGPSCoordinates($value);
                    if ($value === false) {
                        continue 2;
                    }
                    $longitudeRef = !array_key_exists('GPS:GPSLongitudeRef', $data)
                        || $data['GPS:GPSLongitudeRef'] === null || $data['GPS:GPSLongitudeRef'] === '' ?
                        'E' : $data['GPS:GPSLongitudeRef'][0];
                    $value *= strtoupper($longitudeRef) === 'W' ? -1 : 1;
                    break;
                case self::GPSALTITUDE:
                    $flip = 1;
                    if (array_key_exists('GPS:GPSAltitudeRef', $data)) {
                        $flip = ($data['GPS:GPSAltitudeRef'] === '1') ? -1 : 1;
                    }
                    $value = $flip * (float) $value;
                    break;
                case self::GPSALTITUDE_QUICKTIME:
                    $flip = 1;
                    if (array_key_exists('Composite:GPSAltitudeRef', $data)) {
                        $flip = ($data['Composite:GPSAltitudeRef'] === '1') ? -1 : 1;
                    }
                    $value = $flip * (float) $value;
                    break;
                case self::IMAGEHEIGHT_VIDEO:
                case self::IMAGEWIDTH_VIDEO:
                    preg_match("#^(\d+)[^\d]+(\d+)$#", $value, $matches);
                    $value_split = array_slice($matches, 1);
                    $rotate = false;
                    if (array_key_exists('Composite:Rotation', $data)) {
                        if ($data['Composite:Rotation'] === '90' || $data['Composite:Rotation'] === '270') {
                            $rotate = true;
                        }
                    }
                    if (!array_key_exists(Exif::WIDTH, $mappedData)) {
                        if (!($rotate)) {
                            $mappedData[Exif::WIDTH]  = intval($value_split[0]);
                        } else {
                            $mappedData[Exif::WIDTH]  = intval($value_split[1]);
                        }
                    }
                    if (!array_key_exists(Exif::HEIGHT, $mappedData)) {
                        if (!($rotate)) {
                            $mappedData[Exif::HEIGHT] = intval($value_split[1]);
                        } else {
                            $mappedData[Exif::HEIGHT] = intval($value_split[0]);
                        }
                    }
                    continue 2;
                case self::IMGDIRECTION:
                    // Skip cases if image direction is not numeric
                    if (!(is_numeric($value))) {
                        continue 2;
                    }
                    break;
                    // Merge sources of keywords
                case self::KEYWORDS:
                case self::SUBJECT:
                    $xval = is_array($value) ? $value : [$value];
                    if (!array_key_exists(Exif::KEYWORDS, $mappedData)) {
                        $mappedData[Exif::KEYWORDS] = $xval;
                    } else {
                        $tmp = array_values(array_unique(array_merge($mappedData[Exif::KEYWORDS], $xval)));
                        $mappedData[Exif::KEYWORDS] = $tmp;
                    }

                    continue 2;
                case self::LENS_ID:
                    if (!array_key_exists(Exif::LENS, $mappedData)) {
                        $mappedData[Exif::LENS] = $value;
                    }
                    continue 2;
            }
            // set end result
            $mappedData[$key] = $value;
        }

        // add GPS coordinates, if available
        if ((isset($mappedData[Exif::LATITUDE])) && (isset($mappedData[Exif::LONGITUDE]))) {
            $mappedData[Exif::GPS] =
                sprintf('%s,%s', (string) $mappedData[Exif::LATITUDE], (string) $mappedData[Exif::LONGITUDE]);
        }

        return $mappedData;
    }

    /**
     * Extract GPS coordinates from formatted string
     *
     * @param mixed $coordinates
     * @return float|false
     */
    protected function extractGPSCoordinates(mixed $coordinates): float|false
    {
        if (is_numeric($coordinates) === true || $this->numeric === true) {
            return round(floatval($coordinates), self::ROUNDING_PRECISION);
        } else {
            if (preg_match('!^([0-9.]+) deg ([0-9.]+)\' ([0-9.]+)"!', $coordinates, $matches) === 0) {
                return false;
            }

            return round(
                floatval($matches[1]) + (floatval($matches[2]) / 60) + (floatval($matches[3]) / 3600),
                self::ROUNDING_PRECISION
            );
        }
    }
}
