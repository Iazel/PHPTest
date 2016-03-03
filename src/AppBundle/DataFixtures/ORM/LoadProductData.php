<?php
namespace AppBundle\DataFixtures\ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadProductData extends Base implements ContainerAwareInterface
{
    use CoreTagTrait;
    use CoreProductTrait;
    use ContainerAwareTrait;

    protected $image_dest, $thumb_dest;

    public function __construct() {
    }

    protected function genData()
    {
        $image = $this->getTestImage();
        $this
            ->new_prod('Test1', $image, '2015-01-10 23:59', array())
            ->new_prod('Test2', $image, '2015-01-04', array('tag1', 'tag2'))
            ->new_prod('Test3', $image, '2015-01-08', array('tag3', 'tag1'))
            ->new_prod('Test4', $image, '2015-01-01 00:12', array('tag2'))
            ->new_prod('Test5', $image, '2015-01-02', array('tag2'))
            ->new_prod('Test6', $image, '2015-01-01 00:14', array('tag3'))
            ;
    }

    protected function new_prod($name, $image, $created_at, $tags)
    {
        $tmp_image = '/tmp/test_' . md5($image) ."_$name";
        copy($image, $tmp_image);

        $file = new UploadedFile($tmp_image, 'image.jpg', 'image/jpeg', null, null, true);
        $p = $this->core_prod($name, $created_at, null, false)
            ->setDesc('Lorem ipsum dolor sit amet')
            ->setImageFile($file)
            ;

        $this->persist($p); // we need to persist it here to trigger the upload!

        foreach($tags as $t)
            $p->addTag( $this->core_tag($t) );

        return $this;
    }

    public function getTestImage()
    {
        return $this->container->getParameter('product_image_source')
            . DIRECTORY_SEPARATOR . 'image.jpg';
    }
}
