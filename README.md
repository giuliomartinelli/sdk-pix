# SDK PIX
> Um SDK pix PHP

[![NPM Version][npm-image]][npm-url]
[![Build Status][travis-image]][travis-url]
[![Downloads Stats][npm-downloads]][npm-url]

Primeria versão SDK para geração de payload de pagameto via Pix

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

Giulio Augusto Martinelli – [@giuliomartinelli](https://twitter.com/...) – giulioaugustomartinelli@gmail.com

Distribuído sob a licença XYZ. Veja `LICENSE` para mais informações.

[https://github.com/giuliomartinelli/github-link](https://github.com/giuliomartinelli/)

[npm-image]: https://img.shields.io/npm/v/datadog-metrics.svg?style=flat-square
[npm-url]: https://npmjs.org/package/datadog-metrics
[npm-downloads]: https://img.shields.io/npm/dm/datadog-metrics.svg?style=flat-square
[travis-image]: https://img.shields.io/travis/dbader/node-datadog-metrics/master.svg?style=flat-square
[travis-url]: https://travis-ci.org/dbader/node-datadog-metrics
[wiki]: https://github.com/seunome/seuprojeto/wiki
