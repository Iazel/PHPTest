<?php
namespace AppBundle\Twig\Extension;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ThumbImageExtension extends \Twig_Extension
{
    private $thumbs_uri;

    public function __construct($thumbs_uri)
    {
        $this->thumb_uri = $thumbs_uri . '/';
    }

    public function getName()
    {
        return 'thumb_image_ext';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('thumb_image', array($this, 'asset')),
        );
    }

    public function asset($image)
    {
        return $this->thumb_uri . ($image ?: 'noimage.jpg');
    }
}
