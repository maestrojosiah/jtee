<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table(name="purchase")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PurchaseRepository")
 */
class Purchase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchased_on", type="datetime")
     */
    private $purchasedOn;

    /**
     * @ORM\ManyToOne(targetEntity="Prospect", inversedBy="purchases")
     * @ORM\JoinColumn(name="prospect_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $prospect;

    /**
     * @ORM\ManyToOne(targetEntity="Orda", inversedBy="purchases")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $order;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="purchases")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $product;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=255)
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="string", length=255)
     */
    private $cost;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set purchasedOn
     *
     * @param \DateTime $purchasedOn
     *
     * @return Purchase
     */
    public function setPurchasedOn($purchasedOn)
    {
        $this->purchasedOn = $purchasedOn;

        return $this;
    }

    /**
     * Get purchasedOn
     *
     * @return \DateTime
     */
    public function getPurchasedOn()
    {
        return $this->purchasedOn;
    }

    /**
     * Set prospect
     *
     * @param \AppBundle\Entity\Prospect $prospect
     *
     * @return Purchase
     */
    public function setProspect(\AppBundle\Entity\Prospect $prospect = null)
    {
        $this->prospect = $prospect;

        return $this;
    }

    /**
     * Get prospect
     *
     * @return \AppBundle\Entity\Prospect
     */
    public function getProspect()
    {
        return $this->prospect;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Purchase
     */
    public function setProduct(\AppBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }



    /**
     * Set quantity
     *
     * @param string $quantity
     *
     * @return Purchase
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set cost
     *
     * @param string $cost
     *
     * @return Purchase
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Orda $order
     *
     * @return Purchase
     */
    public function setOrder(\AppBundle\Entity\Orda $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Orda
     */
    public function getOrder()
    {
        return $this->order;
    }
}
