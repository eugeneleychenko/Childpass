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
     * @param string $childId
     * @param string $size
     * @return string
     */
    public function getChildImageFolder($childId, $size = ImageHelper::IMAGE_SMALL)
    {
        $folder = Yii::getPathOfAlias('webroot').'/children/'.$childId.'/photos/'.$size;
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        return $folder.'/';
    }

    /**
     * Create image folder url
     *
     * @param string $childId
     * @param string $size
     * @return string
     */
    function getChildImageFolderUrl($childId, $size = ImageHelper::IMAGE_SMALL)
    {
        return Yii::app()->request->getBaseUrl(true).'/children/'.$childId.'/photos/'.$size.'/';
    }

    /**
     * Save image in child folder
     *
     * @param string $childId
     * @param string $tmpName
     * @return string
     */
    public function saveImage($childId, $tmpName) {
        $filename = $this->generateImageName();

        $path = $this->getChildImageFolder($childId, self::IMAGE_ORIG);

        move_uploaded_file($tmpName, $path.$filename);

        return $filename;
    }

    /**
     * Delete image in child folder
     *
     * @param string $childId
     * @param string $filename
     */
    public function deleteChildImage($childId, $filename)
    {
        foreach ($this->thumbnail_dimensions as $imageSize => $dimensions) {
            unlink($this->getChildImageFolder($childId, $imageSize).$filename);
        }
        unlink($this->getChildImageFolder($childId, self::IMAGE_ORIG).$filename);
    }

    /**
     * Get image url or call creating thumbnail if it does not exist
     *
     * @param string $childId
     * @param string $filename
     * @param string $size
     * @return string
     */
    function getChildImageUrl($childId, $filename, $size = ImageHelper::IMAGE_SMALL, $fileTime = true)
    {
        $folder = $this->getChildImageFolder($childId, $size);

        if (!file_exists($folder.$filename)) {
            $this->makeChildThumbnail($childId, $filename, $size);
        }
        if ($fileTime) {
            $imageUrl = $this->getChildImageFolderUrl($childId, $size).$filename.'?'.filemtime($folder.$filename);
        } else {
            $imageUrl = $this->getChildImageFolderUrl($childId, $size).$filename;
        }

        return $imageUrl;
    }

    /**
     * Create thumbnail image
     *
     * @param string $childId
     * @param string $filename
     * @param string $size
     */
    function makeChildThumbnail($childId, $filename, $size = ImageHelper::IMAGE_SMALL) {
        $origFolder = $this->getChildImageFolder($childId, ImageHelper::IMAGE_ORIG);

        $file = $origFolder.$filename;
        /** @var $img Iwi */
        $img = Yii::app()->iwi->load($file);

        $dimensions = $this->thumbnail_dimensions[$size];
        $folder = $this->getChildImageFolder($childId, $size);
        if (!$dimensions[2]) {
            $img->resize($dimensions[0],$dimensions[1]);
        } else {
            $img->adaptive($dimensions[0],$dimensions[1]);
        }

        $img->sharpen(20)->quality(90)->save($folder.$filename);
    }

}