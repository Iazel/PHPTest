<?php
namespace AppBundle\EventListener;

use Gregwar\Image\Image;
use Doctrine\ORM\EntityManager;
use Vich\UploaderBundle\Event\Event;

class ProductImageUploadListener
{
    private $tp, $tw, $th, $old;

    public function __construct($thumb_path, $thumb_width, $thumb_height) {
        $this->tp = $thumb_path . DIRECTORY_SEPARATOR;
        $this->tw = $thumb_width;
        $this->th = $thumb_height;

        if( !file_exists($thumb_path) )
            mkdir($thumb_path);
    }

    public function onPostUpload(Event $e)
    {
        $this->cleanOld();
        $file = $e->getObject()->getImageFile();
        $thumb = $this->getThumbPath( $file->getFilename() );

        Image::open( $file->getRealPath() )
            ->zoomCrop($this->tw, $this->th)
            ->save($thumb, 'jpg', 85);
    }

    public function onRemoveUpload(Event $e)
    {
        $old = $this->getThumbPath( $e->getObject()->getImageName() );
        if( file_exists($old) )
            unlink($old);
    }

    private function cleanOld()
    {
        if($this->old === null)
            return;

    }

    private function getThumbPath($file)
    {
        return $this->tp . $file;
    }
}
