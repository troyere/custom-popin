<?php

namespace AppBundle\Services;

use Exception;
use Symfony\Component\Filesystem\Filesystem;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;

class ScriptImageService
{

    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var string
     */
    protected $tempDir;

    public function __construct(array $parameters)
    {
        $this->fileSystem = new Filesystem();
        $this->tempDir    = $parameters['temp_dir'];
    }

    /**
     * Resize an encoded image
     *
     * @param $encoded
     * @param $width
     * @param $height
     * @param string $mode
     * @return string
     * @throws Exception
     */
    public function resize($encoded, $width, $height, $mode = ImageInterface::THUMBNAIL_INSET)
    {
        $this->fileSystem->mkdir($this->tempDir);
        $id = uniqid();

        $originalPath  = $this->tempDir.DIRECTORY_SEPARATOR.$id.'-original.tmp';
        $thumbnailPath = $this->tempDir.DIRECTORY_SEPARATOR.$id.'-thumbnail.png';

        if (!file_put_contents($originalPath, $this->decode($encoded))) {
            throw new Exception('Something went wrong in the image creation.');
        }

        $imagine = new Imagine();
        $imagine->open($originalPath)
            ->thumbnail(new Box($width, $height), $mode)
            ->save($thumbnailPath);

        $newEncoded = $this->encode($thumbnailPath, 'image/png');

        $this->fileSystem->remove($this->tempDir);
        return $newEncoded;
    }

    /**
     * Decode an encoded image
     *
     * @param $encoded
     * @return string
     */
    private function decode($encoded)
    {
        $data = explode(',', $encoded);
        return base64_decode($data[1]);
    }

    /**
     * Encode an image
     *
     * @param $path
     * @param $type
     * @return string
     */
    private function encode($path, $type)
    {
        $encoded = base64_encode(file_get_contents($path));
        return 'data:'.$type.';base64,'.$encoded;
    }

}
