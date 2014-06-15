<?php
class ImageHelper extends CComponent
{
    const IMAGE_ORIG  = 'original';
    const IMAGE_MEDIUM  = 'medium';
    const IMAGE_SMALL  = 'small';

    var $thumbnail_dimensions = array(
        self::IMAGE_MEDIUM => array(425, 300, false),
        self::IMAGE_SMALL => array(220, 220, true),
    );

    public function init() {

    }

    /**
     * Generating random image name
     *
     * @return string
     */
    public function generateImageName()
    {
        return md5(rand(1, 9999999)).'.jpg';
    }

    /**
     * Create image folder(if necessary) and return it path
     *
     * @param string $path
     * @param string $size
     * @return string
     */
    public function getImageFolder($path, $size = ImageHelper::IMAGE_SMALL)
    {
        $folder = $path.$size;
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        return $folder.'/';
    }

    /**
     * Create image folder url
     *
     * @param string $url
     * @param string $size
     * @return string
     */
    function getImageFolderUrl($url, $size = ImageHelper::IMAGE_SMALL)
    {
        return $url.$size.'/';
    }

    /**
     * Delete image in folder
     *
     * @param string $url
     * @param string $filename
     */
    public function deleteImage($path, $filename)
    {
        foreach ($this->thumbnail_dimensions as $imageSize => $dimensions) {
            $file = $this->getImageFolder($path, $imageSize).$filename;
            if (file_exists($file)) {
                unlink($file);
            }
        }
    }

    /**
     * Get image url or call creating thumbnail if it does not exist
     *
     * @param string $url
     * @param string $filename
     * @param string $size
     * @param boolean $fileTime
     * @return string
     */
    function getImageUrl($url, $filename, $size = ImageHelper::IMAGE_SMALL, $fileTime = true)
    {
//        if ($fileTime) {
//            $imageUrl = $this->getImageFolderUrl($url, $size).$filename.'?'.filemtime($folder.$filename);
//        } else {
            $imageUrl = $this->getImageFolderUrl($url, $size).$filename;
//        }

        return $imageUrl;
    }

    /**
     * Create thumbnail image
     *
     * @param string $path
     * @param string $filename
     * @param string $size
     */
    function makeThumbnail($path, $filename, $size = ImageHelper::IMAGE_SMALL) {
        $origFolder = $this->getImageFolder($path, ImageHelper::IMAGE_ORIG);

        $file = $origFolder.$filename;
        /** @var $img Iwi */
        $img = Yii::app()->iwi->load($file);

        $dimensions = $this->thumbnail_dimensions[$size];
        $folder = $this->getImageFolder($path, $size);
        if (!$dimensions[2]) {
            $img->resize($dimensions[0],$dimensions[1]);
        } else {
            $img->adaptive($dimensions[0],$dimensions[1]);
        }

        $img->sharpen(20)->quality(90)->save($folder.$filename);
    }

}