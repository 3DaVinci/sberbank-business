<?php

namespace SberBusiness;

use DateTime;
use SberBusiness\Entity\AccessToken;
use SberBusiness\Entity\Invoice;
use SberBusiness\Entity\PaymentDocState;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SberHttpClient
{
    const CODE_RESPONSE_TYPE = 'code';
    const AUTH_CODE_GRANT_TYPE = 'authorization_code';

    private HttpClientInterface $client;

    private Config $config;

    private AccessToken $accessToken;

    private SerializerInterface $serializer;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Config($config);
        $this->client = HttpClient::createForBaseUri($this->config->getBaseUri() ,[
            'headers' => ['Content-Type' => 'application/json']
        ]);
        $this->accessToken = new AccessToken();
        $this->serializer = new Serializer();
    }

    /**
     * @param string $redirectUri
     * @return string
     * @throws \Exception
     */
    public function getAuthCodeUrl(string $redirectUri): string
    {
        $state = hash('sha256', bin2hex(random_bytes(20)));
        $nonce = Uuid::v4()->toRfc4122();
        $authCodeEndpoint = '/ic/sso/api/v2/oauth/authorize';
        $queryParams = [
            'scope' => $this->config->getScope(),
            'response_type' => self::CODE_RESPONSE_TYPE,
            'client_id' => $this->config->getClientId(),
            'redirect_uri' => $redirectUri,
            'state' => $state,
            'nonce' => $nonce
        ];

        return $this->config->getBaseUri() . $authCodeEndpoint . '?' . http_build_query($queryParams);
    }

    /**
     * @param string $code
     * @param string $redirectUri
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getTokenByCode(string $code, string $redirectUri): string
    {
        $response = $this->client->request('POST', '/v2/oauth/token', [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            'body' => [
                'grant_type' => self::AUTH_CODE_GRANT_TYPE,
                'code' => $code,
                'client_id' => $this->config->getClientId(),
                'redirect_uri' => $redirectUri,
                'client_secret' => $this->config->getClientSecret()
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->toArray()['errorMsg']);
        }
        $content = $response->toArray();
        $this->accessToken
            ->setToken($content['access_token'])
            ->setRefreshToken($content['refresh_token']);

        return $this->serializer->serialize($this->accessToken, 'json');
    }

    /**
     * @param string $token
     * @param float $amount
     * @param string $externalId
     * @param string $purpose
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function createInvoice(string $token, float $amount, string $externalId, string $purpose): string
    {
        $response = $this->client->request('POST', '/v1/payments/from-invoice', [
            'auth_bearer' => $token,
            'body' => [
                'amount' => $amount,
                'date' => (new DateTime())->format('Y-m-d'),
                'externalId' => $externalId,
                'payeeAccount' => $this->config->getPayeeAccount(), // Счет получателя платежа,
                'purpose' => $purpose
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->toArray()['errorMsg']);
        }
        $content = $response->toArray();
        $invoice = new Invoice();
        $invoice
            ->setExternalId($content['externalId'])
            ->setPayeeAccount($content['payeeAccount'])
            ->setPurpose($content['purpose'])
            ->setAmount($content['amount'])
            ->setDate(DateTime::createFromFormat('Y-m-d', $content['date']));

        return $this->serializer->serialize($invoice, 'json');
    }

    /**
     * @param string $externalId
     * @param string|null $backUrl
     * @return string
     */
    public function getPaymentUrl(string $externalId, ?string $backUrl = null): string
    {
        $url = $this->config->getBaseUri() . '/icdk/dcb/index.html#/payment-creator/' . $externalId;
        if ($backUrl) {
            $url .= '?backUrl=' . $backUrl;
        }

        return $url;
    }

    /**
     * @param string $token
     * @param string $externalId
     * @return string
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function checkState(string $token, string $externalId): string
    {
        $response = $this->client->request('GET', '/v1/payments/' .$externalId. '/state', [
            'auth_bearer' => $token
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->toArray()['errorMsg']);
        }
        $content = $response->toArray();
        $paymentDocState = new PaymentDocState();
        $paymentDocState
            ->setBankStatus($content['bankStatus'])
            ->setBankComment($content['bankComment'])
            ->setChannelInfo($content['channelInfo'])
            ->setCrucialFieldsHash($content['crucialFieldsHash']);

        return $this->serializer->serialize($paymentDocState, 'json');
    }
}
