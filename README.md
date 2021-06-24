# SDK PIX
> Um SDK pix PHP


Primeria versão SDK para geração de payload de pagameto via Pix


![](https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Logo_-_pix_powered_by_Banco_Central_%28Brazil%2C_2020%29.png/640px-Logo_-_pix_powered_by_Banco_Central_%28Brazil%2C_2020%29.png)

## Instalação

Composer:

```sh
composer require blablabla
```

## Exemplo de uso

Exemplo de uso do SDK PIX PHP

Importar biblioteca

```sh
use \App\Pix\Ecstatic\Payload;
```

Como utilizar o codigo ?

```sh
$payload = new Payload();

$payload = $payload->setPixKey('18e3e3c-0ca1-4244-b1e2-538c92858187')
                   ->setDescription('pedido')
                   ->setMerchantName('Nome do CLiente')
                   ->setMerchantCity('São Paulo')
                   ->setAmount(123.021)
                   ->setTxid('Idproduct123');
                   
echo $payload;
```


## Histórico de lançamentos

* 0.0.1
    * Primeiro lançamento
    * Criação payload estático

## Meta

Giulio Augusto Martinelli – [@giuliomartinelli](https://github.com/giuliomartinelli/sdk-pix) – giulioaugustomartinelli@gmail.com

Distribuído sob a licença MIT. Veja `LICENSE` para mais informações.

[https://github.com/giuliomartinelli/sdk-pix](https://github.com/giuliomartinelli/sdk-pix)

