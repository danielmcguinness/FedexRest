<?php

namespace FedexRest\Services\Ship\Entity;

class ShippingChargesPayment
{
    public ?string $paymentType;
    public array $payor = [];

    /**
     * @param string  $paymentType
     * @return $this
     */
    public function setPaymentType(string $paymentType): ShippingChargesPayment
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    public function setPayor(array $payor): ShippingChargesPayment
    {
        $this->payor = $payor;
        return $this;
    }

    public function prepare(): array
    {
        $data = [];
        if (!empty($this->paymentType)) {
            $data['paymentType'] = $this->paymentType;
        }

        if (!empty($this->payor)) {
            $data['payor'] = $this->payor;
        }

        return $data;
    }
}
