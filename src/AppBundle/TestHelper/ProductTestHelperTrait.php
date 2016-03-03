<?php
namespace AppBundle\TestHelper;

trait ProductTestHelperTrait {
    use DbTrait;
    use ContainerTrait;

    private function getForm($client, $url, $data = null)
    {
        return $client
            ->request('GET', $url)
            ->selectButton('product[save]')
            ->form($data);
    }

    private function ensureCleanDir($dir)
    {
        foreach(glob($dir . '/*') as $file)
            @unlink($file);

        foreach(glob($dir . '/thumbs/*') as $file)
            unlink($file);
    }

    private function uploadAndSubmit($client, $form, $image)
    {
        $form['product[image_file]']['file']->upload($image);
        $client->submit($form);
    }

    private function getImageDestination()
    {
        return $this->getContainer()->getParameter('product_image_path');
    }

    private function getTestImage($file = 'image.jpg')
    {
         return $this->getContainer()->getParameter('product_image_source')
            . DIRECTORY_SEPARATOR . $file
            ;
    }
}
