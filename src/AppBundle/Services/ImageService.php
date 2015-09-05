<?php

namespace AppBundle\Services;

use Exception;

class ImageService
{

    /**
     * Return the type mime from an encoded image
     *
     * @param $encodedImage
     * @return mixed
     * @throws Exception
     */
    public function getMimeType($encodedImage)
    {
        // Put the encoded image into a temporary file
        $currentFile   = tempnam(sys_get_temp_dir(), 'tmp-current-img');
        $currentHandle = fopen($currentFile, 'w');
        $this->decodeIntoFile($encodedImage, $currentHandle);

        // Read mime type
        $finfo    = finfo_open(FILEINFO_MIME_TYPE);
        $mimetype = finfo_file($finfo, $currentFile);
        finfo_close($finfo);

        // Close and delete
        fclose($currentHandle);
        unlink($currentFile);

        return $mimetype;
    }

    /**
     * Resize height of and base64 encoded image
     *
     * @param $encodedImage
     * @param $newHeight
     * @return string
     * @throws Exception
     */
    public function resizeHeight($encodedImage, $newHeight)
    {
        $tempDir = sys_get_temp_dir();

        // Current image
        $currentFile   = tempnam($tempDir, 'tmp-current-img');
        $currentHandle = fopen($currentFile, 'w');
        $this->decodeIntoFile($encodedImage, $currentHandle);

        // Resize current image
        $info = getimagesize($currentFile);
        $mime = $info['mime'];

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $imageCreateFunc = 'imagecreatefromjpeg';
                $imageSaveFunc   = 'imagejpeg';
                $newImageExt     = 'jpg';
                break;
            case 'image/png':
                $imageCreateFunc = 'imagecreatefrompng';
                $imageSaveFunc   = 'imagepng';
                $newImageExt     = 'png';
                break;
            case 'image/gif':
                $imageCreateFunc = 'imagecreatefromgif';
                $imageSaveFunc   = 'imagegif';
                $newImageExt     = 'gif';
                break;
            default:
                throw new Exception('Wrong mime type.');
        }

        // Save into target file
        $img = $imageCreateFunc($currentFile);
        list($width, $height) = getimagesize($currentFile);
        $newWidth = ($height / $width) * $newHeight;
        $tmp = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // New image
        $targetFile = $tempDir.DIRECTORY_SEPARATOR.'tmp-target-img.'.$newImageExt;
        touch($targetFile);
        $imageSaveFunc($tmp, $targetFile);

        // Retrieve the new encoded data
        $newEncodedImage = base64_encode(file_get_contents($targetFile));
        $newEncededImage = 'data:'.$mime.';base64,'.$newEncodedImage;

        // Close and delete files
        fclose($currentHandle);
        unlink($currentFile);
        unlink($targetFile);

        return $newEncededImage;
    }

    /**
     * Decode a base64 encoded image to a file
     *
     * @param string $encodedImage
     * @param $handle
     * @return string
     * @throws Exception
     */
    public function decodeIntoFile($encodedImage, $handle)
    {
        $data = explode(',', $encodedImage);
        if (!fwrite($handle, base64_decode($data[1]))) {
            throw new Exception('Unable to write');
        }
    }

}
