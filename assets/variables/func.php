<?php
class ImageCrop
{

    //Image resources
    private $srcImage, $dstImage;

    //original width and height
    private $width, $height;

    /**
     * Read an image from disk.
     * @return true in case of success, false otherwise.
     */
    public function openImage($filename)
    {
        if (!file_exists($filename)) {
            return false;
        }
        $original = getimagesize($filename);
        switch ($original['mime']) {
            case 'image/jpeg':
                $this->srcImage = imagecreatefromjpeg($filename);
                break;
            case 'image/png':
                $this->srcImage = imagecreatefrompng($filename);
                break;
            case 'image/gif':
                $this->srcImage = imagecreatefromgif($filename);
                break;
            default:
                return false;
        }
        $this->width = $original[0];
        $this->height = $original[1];
        return true;
    }

    /**
     * Crop an image to the new specified dimension trying to get an 
     * internal rectangle of the original image. No crop is done if the 
     * original dimension is already smaller than $newWidth or $newHeight.
     */
    public function crop($newWidth, $newHeight)
    {
        $this->dstImage = imagecreatetruecolor($newWidth, $newHeight);
        $srcX = $srcY;
        $srcW = $this->width;
        $srcH = $this->height;
        $extraWidth = $this->width - $newWidth;
        if ($extraWidth > 0) {
            $srcX = $extraWidth / 2;
        }
        $extraHeight = $this->height - $newHeight;
        if ($extraHeight > 0) {
            $srcY = $extraHeight / 2;
        }
        imagecopy(
            $this->dstImage,
            $this->srcImage,
            0,
            0,
            $srcX,
            $srcY,
            $srcW,
            $srcH
        );
    }

    /**
     * Save the destination image, the crop function should have been 
     * called already.
     */
    public function save($filename)
    {
        imagejpeg($this->dstImage, $filename, 90);
    }

    /**
     * Output the destination image to the browser.
     */
    public function output()
    {
        imagejpeg($this->dstImage);
    }

}
?>