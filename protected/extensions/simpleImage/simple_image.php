<?php
class simple_image {
   
   var $image;
   var $image_type;
 	var $image_info;
   function __construct($filename) {
      $this->image_info = $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
         $this->image = imagecreatefrompng($filename);
      }
   }
   
   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image,$filename);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image,$filename);
      }   
      if( $permissions != null) {
         chmod($filename,$permissions);
      }
   }

   function output($image_type=IMAGETYPE_JPEG) {
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
         imagegif($this->image);         
      } elseif( $image_type == IMAGETYPE_PNG ) {
         imagepng($this->image);
      }   
   }

   function getWidth() {
      return imagesx($this->image);
   }

   function getHeight() {
      return imagesy($this->image);
   }

   function resizeToHeight($height) {
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }

   function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }

   function resizeProportional($width, $height = null, $mobileDisplay = false) {
        if (!$height)
            $height = $width;

        $oldWidth  = $this->getWidth();
        $oldHeight = $this->getHeight();

        $newWidth  = $oldWidth;
        $newHeight = $oldHeight;

        //Calc aspect ratios
        $K_source = $oldWidth / $oldHeight;
        $K_target = $width / $height;

        //If it is thumbnail for mobile device we should choose best fit for portrait or landscape mode
        if ($mobileDisplay) {
           if (($K_source > 1 && $K_target < 1) || ($K_source < 1 && $K_target > 1)) {
               $tmp = $width;
               $width = $height;
               $height = $tmp;
               $K_target = $width / $height;
           }
        }

        //Check if we need to resample image
        if ($K_source >= $K_target) {
           if ($oldWidth > $width) {
               $newWidth = $width;
               $newHeight = round($height / $K_source);
           }
        } else {
           if ($oldHeight > $height) {
               $newHeight = $height;
               $newWidth  = round($height * $K_source);
           }
        }

        $this->resize($newWidth,$newHeight);
   }

   function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getHeight() * $scale/100;
      $this->resize($width,$height);
   }

   function resize($width,$height) {
        if (($width != $this->getWidth()) || ($height != $this->getHeight())) {
            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            $this->image = $new_image;
        }
   }      
}
?>