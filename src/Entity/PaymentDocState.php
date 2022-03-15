<?php

declare(strict_types=1);

namespace SberBusiness\Entity;

class PaymentDocState
{
    private string $bankStatus;

    private ?string $bankComment;

    private ?string $channelInfo;

    private string $crucialFieldsHash;

    /**
     * @return string
     */
    public function getBankStatus(): string
    {
        return $this->bankStatus;
    }

    /**
     * @param string $bankStatus
     * @return PaymentDocState
     */
    public function setBankStatus(string $bankStatus): PaymentDocState
    {
        $this->bankStatus = $bankStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBankComment(): ?string
    {
        return $this->bankComment;
    }

    /**
     * @param string|null $bankComment
     * @return PaymentDocState
     */
    public function setBankComment(?string $bankComment): PaymentDocState
    {
        $this->bankComment = $bankComment;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getChannelInfo(): ?string
    {
        return $this->channelInfo;
    }

    /**
     * @param string $channelInfo
     * @return PaymentDocState
     */
    public function setChannelInfo(?string $channelInfo): PaymentDocState
    {
        $this->channelInfo = $channelInfo;
        return $this;
    }

    /**
     * @return string
     */
    public function getCrucialFieldsHash(): string
    {
        return $this->crucialFieldsHash;
    }

    /**
     * @param string $crucialFieldsHash
     * @return PaymentDocState
     */
    public function setCrucialFieldsHash(string $crucialFieldsHash): PaymentDocState
    {
        $this->crucialFieldsHash = $crucialFieldsHash;
        return $this;
    }

}
