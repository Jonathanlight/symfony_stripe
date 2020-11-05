<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait StripeTrait
{
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $stripeToken;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $brandStripe;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $last4Stripe;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $idChargeStripe;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $statusStripe;

    /**
     * @return null|string
     */
    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    /**
     * @param null|string $stripeToken
     */
    public function setStripeToken(?string $stripeToken): void
    {
        $this->stripeToken = $stripeToken;
    }

    /**
     * @return null|string
     */
    public function getBrandStripe(): ?string
    {
        return $this->brandStripe;
    }

    /**
     * @param null|string $brandStripe
     */
    public function setBrandStripe(?string $brandStripe): void
    {
        $this->brandStripe = $brandStripe;
    }

    /**
     * @return null|string
     */
    public function getLast4Stripe(): ?string
    {
        return $this->last4Stripe;
    }

    /**
     * @param null|string $last4Stripe
     */
    public function setLast4Stripe(?string $last4Stripe): void
    {
        $this->last4Stripe = $last4Stripe;
    }

    /**
     * @return null|string
     */
    public function getIdChargeStripe(): ?string
    {
        return $this->idChargeStripe;
    }

    /**
     * @param null|string $idChargeStripe
     */
    public function setIdChargeStripe(?string $idChargeStripe): void
    {
        $this->idChargeStripe = $idChargeStripe;
    }

    /**
     * @return null|string
     */
    public function getStatusStripe(): ?string
    {
        return $this->statusStripe;
    }

    /**
     * @param null|string $statusStripe
     */
    public function setStatusStripe(?string $statusStripe): void
    {
        $this->statusStripe = $statusStripe;
    }
}