##sberbank-business - библеотека для работы со SberBusinessAPI

#Установка

```bash
composer install
```

## Возможности
getAuthCodeUrl - получение ссылки для авторизации

getTokenByCode - получение access token по коду

createInvoice - выставление счета

getPaymentUrl - получение ссылки для оплаты

checkState - получить статус ранее отправленного счета

## Настройка
При создании объекта SberHtttpClient в качестве параметра должен быть массив со следующими ключами:

clientId - уникальный идентификатор Приложения Партнера, полученный при регистрации приложения

clientSecret - секрет Приложения Партнера, полученный при регистрации приложения.

baseUri - url тестового или промышленного стенда

payeeAccount - счет получателя платежа

scope - обязательный параметр. Область сведений (разрешения), до которых требуется получить доступ Приложению Партнера. Должен содержать обязательный параметр «openid». Через пробел должны быть указаны атрибуты (claim) и ресурсы, полученные при регистрации приложения. Значения переданные в параметре scope сравниваются со значениями согласованными при регистрации приложения

## Пример использования
```php
<?php

$sberBusinessClient = new \SberBusiness\SberHttpClient([
    'clientId' => '111111',
    'clientSecret' => 'AAl11abc',
    'baseUri' => 'https://edupirfintech.sberbank.ru:9443',
    'payeeAccount' => '00000000000000000000',
    'scope' => 'openid PAY_DOC_RU_INVOICE'
]);

$redirectUri = 'https://demo.b2bmotion.ru/payment/42';
$authUrl = $sberBusinessClient->getAuthCodeUrl($redirectUri);
$this->redirect($authUrl);
```
