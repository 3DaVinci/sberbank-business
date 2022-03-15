<?php

declare(strict_types=1);

namespace SberBusiness;

class Config
{
    private ?int $clientId = null;

    private ?string $clientSecret = null;

    private string $baseUri = 'https://edupirfintech.sberbank.ru:9443';

    private string $payeeAccount = '';

    private string $scope = 'openid PAY_DOC_RU_INVOICE PAY_DOC_RU';

    public function __construct(array $config)
    {
        foreach ($config as $name => $value) {
            if (property_exists('Config', $name)) {
                $this->$name = $value;
            }
        }
    }

    /**
     * @return int|null
     */
    public function getClientId(): ?int
    {
        return $this->clientId;
    }

    /**
     * @return string|null
     */
    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * @return string
     */
    public function getPayeeAccount(): string
    {
        return $this->payeeAccount;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }
}
