<?php
namespace AppBundle\Twig\Extension;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ThumbImageExtension extends \Twig_Extension
{
    private $helper;

    public function __construct(UploaderHelper $helper)
    {
        $this->helper = $helper;
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

    public function asset($obj, $fieldName, $className = null)
    {
        $url = $this->helper->asset($obj, $fieldName, $className);
        return dirname($url) .'/thumbs/'. basename($url);
    }
}
