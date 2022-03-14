<?php

declare(strict_types=1);

namespace SberBusiness\Entity;

use DateTime;

class Invoice
{
    private string $externalId;

    private string $payeeAccount;

    private string $purpose;

    private float $amount;

    private DateTime $date;

    /**
     * @return string
     */
    public function getExternalId(): string
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     * @return Invoice
     */
    public function setExternalId(string $externalId): Invoice
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return string
     */
    public function getPayeeAccount(): string
    {
        return $this->payeeAccount;
    }

    /**
     * @param string $payeeAccount
     * @return Invoice
     */
    public function setPayeeAccount(string $payeeAccount): Invoice
    {
        $this->payeeAccount = $payeeAccount;

        return $this;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     * @return Invoice
     */
    public function setPurpose(string $purpose): Invoice
    {
        $this->purpose = $purpose;

        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return Invoice
     */
    public function setAmount(float $amount): Invoice
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return Invoice
     */
    public function setDate(DateTime $date): Invoice
    {
        $this->date = $date;

        return $this;
    }
}
