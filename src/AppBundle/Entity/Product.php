<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Fogs\TaggingBundle\Interfaces\Taggable;
use Fogs\TaggingBundle\Traits\TaggableTrait;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="products")
 * @Vich\Uploadable
 */
class Product implements Taggable {
    use TaggableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $desc = '';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_name = '';

    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="image_name")
     */
    private $image_file;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set desc
     *
     * @param string $desc
     *
     * @return Product
     */
    public function setDesc($desc)
    {
        $this->desc = (string) $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * @param File $image
     *
     * @return Product
     */
    public function setImageFile($image = null)
    {
        $this->image_file = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->image_name = mt_rand();
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->image_file;
    }

    /**
     * @param string $fname
     *
     * @return Product
     */
    public function setImageName($fname)
    {
        $this->image_name = $fname;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageName()
    {
        return $this->image_name;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedNow()
    {
        $this->setCreatedAt(new \DateTime);
        return $this;
    }
}
