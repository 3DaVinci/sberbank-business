<?php

declare(strict_types=1);

namespace SberBusiness\Entity;

class AccessToken
{
    private string $token;

    private string $type = 'Bearer';

    private string $refreshToken;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return AccessToken
     */
    public function setToken(string $token): AccessToken
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return AccessToken
     */
    public function setType(string $type): AccessToken
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return AccessToken
     */
    public function setRefreshToken(string $refreshToken): AccessToken
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

}
