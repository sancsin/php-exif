<?php

namespace PHPExif\Mapper;

use PHPExif\Exif;

trait ExifToolTrait
{
    public const APERTURE                 = 'Composite:Aperture';
    public const APPROXIMATEFOCUSDISTANCE = 'XMP-aux:ApproximateFocusDistance';
    public const ARTIST                   = 'IFD0:Artist';
    public const CAPTIONABSTRACT          = 'IPTC:Caption-Abstract';
    public const COLORSPACE               = 'ExifIFD:ColorSpace';
    public const COPYRIGHT                = 'IFD0:Copyright';
    public const COPYRIGHT_IPTC           = 'IPTC:CopyrightNotice';
    public const DATETIMEORIGINAL         = 'ExifIFD:DateTimeOriginal';
    public const CREDIT                   = 'IPTC:Credit';
    public const EXPOSURETIME             = 'ExifIFD:ExposureTime';
    public const FILESIZE                 = 'System:FileSize';
    public const FILENAME                 = 'System:FileName';
    public const FOCALLENGTH              = 'ExifIFD:FocalLength';
    public const HEADLINE                 = 'IPTC:Headline';
    public const IMAGEHEIGHT              = 'File:ImageHeight';
    public const IMAGEWIDTH               = 'File:ImageWidth';
    public const ISO                      = 'ExifIFD:ISO';
    public const JOBTITLE                 = 'IPTC:By-lineTitle';
    public const KEYWORDS                 = 'IPTC:Keywords';
    public const MIMETYPE                 = 'File:MIMEType';
    public const MODEL                    = 'IFD0:Model';
    public const ORIENTATION              = 'IFD0:Orientation';
    public const SOFTWARE                 = 'IFD0:Software';
    public const SOURCE                   = 'IPTC:Source';
    public const TITLE                    = 'IPTC:ObjectName';
    public const TITLE_XMP                = 'XMP-dc:Title';
    public const XRESOLUTION              = 'IFD0:XResolution';
    public const YRESOLUTION              = 'IFD0:YResolution';
    public const GPSLATITUDE              = 'GPS:GPSLatitude';
    public const GPSLONGITUDE             = 'GPS:GPSLongitude';
    public const GPSALTITUDE              = 'GPS:GPSAltitude';
    public const IMGDIRECTION             = 'GPS:GPSImgDirection';
    public const DESCRIPTION              = 'IFD0:ImageDescription';
    public const DESCRIPTION_XMP          = 'XMP-dc:Description';
    public const MAKE                     = 'IFD0:Make';
    public const LENS                     = 'ExifIFD:LensModel';
    public const LENS_ID                  = 'Composite:LensID';
    public const SUBJECT                  = 'XMP-dc:Subject';
    public const CONTENTIDENTIFIER        = 'Apple:ContentIdentifier';
    public const MEDIA_GROUP_UUID         = 'Apple:MediaGroupUUID';
    public const MICROVIDEOOFFSET         = 'XMP-GCamera:MicroVideoOffset';
    public const SUBLOCATION              = 'IPTC2:Sublocation';
    public const CITY                     = 'IPTC2:City';
    public const STATE                    = 'IPTC2:Province-State';
    public const COUNTRY                  = 'IPTC2:Country-PrimaryLocationName';

    public const DATETIMEORIGINAL_QUICKTIME  = 'QuickTime:CreateDate';
    public const DATETIMEORIGINAL_AVI        = 'RIFF:DateTimeOriginal';
    public const DATETIMEORIGINAL_WEBM       = 'Matroska:DateTimeOriginal';
    public const DATETIMEORIGINAL_OGG        = 'Theora:CreationTime';
    public const DATETIMEORIGINAL_WMV        = 'ASF:CreationDate';
    public const DATETIMEORIGINAL_APPLE      = 'Keys:CreationDate';
    public const IMAGEHEIGHT_VIDEO           = 'Composite:ImageSize';
    public const IMAGEWIDTH_VIDEO            = 'Composite:ImageSize';
    public const MAKE_QUICKTIME              = 'QuickTime:Make';
    public const MODEL_QUICKTIME             = 'QuickTime:Model';
    public const CONTENTIDENTIFIER_QUICKTIME = 'QuickTime:ContentIdentifier';
    public const CONTENTIDENTIFIER_KEYS      = 'Keys:ContentIdentifier';
    public const GPSLATITUDE_QUICKTIME       = 'Composite:GPSLatitude';
    public const GPSLONGITUDE_QUICKTIME      = 'Composite:GPSLongitude';
    public const GPSALTITUDE_QUICKTIME       = 'Composite:GPSAltitude';
    public const FRAMERATE                   = 'MPEG:FrameRate';
    public const FRAMERATE_QUICKTIME_1       = 'Track1:VideoFrameRate';
    public const FRAMERATE_QUICKTIME_2       = 'Track2:VideoFrameRate';
    public const FRAMERATE_QUICKTIME_3       = 'Track3:VideoFrameRate';
    public const FRAMERATE_AVI               = 'RIFF:VideoFrameRate';
    public const FRAMERATE_OGG               = 'Theora:FrameRate';
    public const DURATION                    = 'Composite:Duration';
    public const DURATION_QUICKTIME          = 'QuickTime:Duration';
    public const DURATION_WEBM               = 'Matroska:Duration';
    public const DURATION_WMV                = 'ASF:SendDuration';
    public const DATETIMEORIGINAL_PNG        = 'PNG:CreationTime';

    /**
     * Maps the ExifTool fields to the fields of
     * the \PHPExif\Exif class
     */
    protected array $map = array(
        self::APERTURE                 => Exif::APERTURE,
        self::ARTIST                   => Exif::AUTHOR,
        self::MODEL                    => Exif::CAMERA,
        self::COLORSPACE               => Exif::COLORSPACE,
        self::COPYRIGHT                => Exif::COPYRIGHT,
        self::COPYRIGHT_IPTC           => Exif::COPYRIGHT,
        self::DATETIMEORIGINAL         => Exif::CREATION_DATE,
        self::CREDIT                   => Exif::CREDIT,
        self::EXPOSURETIME             => Exif::EXPOSURE,
        self::FILESIZE                 => Exif::FILESIZE,
        self::FILENAME                 => Exif::FILENAME,
        self::FOCALLENGTH              => Exif::FOCAL_LENGTH,
        self::APPROXIMATEFOCUSDISTANCE => Exif::FOCAL_DISTANCE,
        self::HEADLINE                 => Exif::HEADLINE,
        self::IMAGEHEIGHT              => Exif::HEIGHT,
        self::XRESOLUTION              => Exif::HORIZONTAL_RESOLUTION,
        self::ISO                      => Exif::ISO,
        self::JOBTITLE                 => Exif::JOB_TITLE,
        self::KEYWORDS                 => Exif::KEYWORDS,
        self::MIMETYPE                 => Exif::MIMETYPE,
        self::ORIENTATION              => Exif::ORIENTATION,
        self::SOFTWARE                 => Exif::SOFTWARE,
        self::SOURCE                   => Exif::SOURCE,
        self::TITLE                    => Exif::TITLE,
        self::TITLE_XMP                => Exif::TITLE,
        self::YRESOLUTION              => Exif::VERTICAL_RESOLUTION,
        self::IMAGEWIDTH               => Exif::WIDTH,
        self::CAPTIONABSTRACT          => Exif::CAPTION,
        self::GPSLATITUDE              => Exif::LATITUDE,
        self::GPSLONGITUDE             => Exif::LONGITUDE,
        self::GPSALTITUDE              => Exif::ALTITUDE,
        self::MAKE                     => Exif::MAKE,
        self::IMGDIRECTION             => Exif::IMGDIRECTION,
        self::LENS                     => Exif::LENS,
        self::LENS_ID                  => Exif::LENS,
        self::DESCRIPTION              => Exif::DESCRIPTION,
        self::DESCRIPTION_XMP          => Exif::DESCRIPTION,
        self::SUBJECT                  => Exif::KEYWORDS,
        self::CONTENTIDENTIFIER        => Exif::CONTENTIDENTIFIER,
        self::MEDIA_GROUP_UUID         => Exif::CONTENTIDENTIFIER,
        self::DATETIMEORIGINAL_QUICKTIME  => Exif::CREATION_DATE,
        self::DATETIMEORIGINAL_AVI        => Exif::CREATION_DATE,
        self::DATETIMEORIGINAL_WEBM       => Exif::CREATION_DATE,
        self::DATETIMEORIGINAL_OGG        => Exif::CREATION_DATE,
        self::DATETIMEORIGINAL_WMV        => Exif::CREATION_DATE,
        self::DATETIMEORIGINAL_APPLE      => Exif::CREATION_DATE,
        self::MAKE_QUICKTIME              => Exif::MAKE,
        self::MODEL_QUICKTIME             => Exif::CAMERA,
        self::CONTENTIDENTIFIER_QUICKTIME => Exif::CONTENTIDENTIFIER,
        self::CONTENTIDENTIFIER_KEYS      => Exif::CONTENTIDENTIFIER,
        self::GPSLATITUDE_QUICKTIME       => Exif::LATITUDE,
        self::GPSLONGITUDE_QUICKTIME      => Exif::LONGITUDE,
        self::GPSALTITUDE_QUICKTIME       => Exif::ALTITUDE,
        self::IMAGEHEIGHT_VIDEO           => Exif::HEIGHT,
        self::IMAGEWIDTH_VIDEO            => Exif::WIDTH,
        self::FRAMERATE                   => Exif::FRAMERATE,
        self::FRAMERATE_QUICKTIME_1       => Exif::FRAMERATE,
        self::FRAMERATE_QUICKTIME_2       => Exif::FRAMERATE,
        self::FRAMERATE_QUICKTIME_3       => Exif::FRAMERATE,
        self::FRAMERATE_AVI               => Exif::FRAMERATE,
        self::FRAMERATE_OGG               => Exif::FRAMERATE,
        self::DURATION                    => Exif::DURATION,
        self::DURATION_QUICKTIME          => Exif::DURATION,
        self::DURATION_WEBM               => Exif::DURATION,
        self::DURATION_WMV                => Exif::DURATION,
        self::MICROVIDEOOFFSET            => Exif::MICROVIDEOOFFSET,
        self::SUBLOCATION                 => Exif::SUBLOCATION,
        self::CITY                        => Exif::CITY,
        self::STATE                       => Exif::STATE,
        self::COUNTRY                     => Exif::COUNTRY,
        self::DATETIMEORIGINAL_PNG        => Exif::CREATION_DATE
    );

    protected bool $numeric = true;

    /**
     * Mutator method for the numeric property
     *
     * @param bool $numeric
     * @return \PHPExif\Mapper\Exiftool
     */
    public function setNumeric(bool $numeric): static
    {
        $this->numeric = $numeric;

        return $this;
    }
}
