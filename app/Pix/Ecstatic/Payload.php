<?php
/**
 * @author Giulio Augusto Martinelli
 * @version 0.0.1
 */


namespace App\Pix\Ecstatic;

use App\Utils;

/**
 * Class Payload
 * @package App\Pix\Ecstatic
 */
class Payload
{
    /**
     * IDs do Payload do Pix
     *
     * @var string
     */
    const ID_PAYLOAD_FORMAT_INDICATOR = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION = '26';
    const ID_MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
    const ID_MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
    const ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
    const ID_MERCHANT_CATEGORY_CODE = '52';
    const ID_TRANSACTION_CURRENCY = '53';
    const ID_TRANSACTION_AMOUNT = '54';
    const ID_COUNTRY_CODE = '58';
    const ID_MERCHANT_NAME = '59';
    const ID_MERCHANT_CITY = '60';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';
    const ID_CRC16 = '63';

    /**
     * Chave PIX
     *
     * @var string
     */
    private $pixKey;

    /**
     * Descrição do PIX
     *
     * @var string
     */
    private $description;

    /**
     * Nome do pagador
     *
     * @var string
     */
    private $merchantName;

    /**
     * Cidade do pagador
     *
     * @var string
     */
    private $merchantCity;

    /**
     * txid da transação
     *
     * @var string
     */
    private $txid;

    /**
     * valor da transação
     *
     * @var string
     */
    private $amount;

    /**
     * instancia da Class App\Utils
     *
     * @var Utils
     */
    private $utils;

    /**
     * construtor instancia a Class App\Utils
     *
     * @return void
     */

    public function __construct()
    {
        $this->utils = new Utils();
    }

    /**
     * Set pixKey
     *
     * @param string $pixKey
     * @return Payload
     */
    public function setPixKey($pixKey)
    {
        $this->pixKey = $pixKey;
        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Payload
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set merchantName
     *
     * @param string $merchantName
     * @return Payload
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * Set merchantCity
     *
     * @param string $merchantCity
     * @return Payload
     */
    public function setMerchantCity($merchantCity)
    {
        $this->merchantCity = $merchantCity;
        return $this;
    }

    /**
     * Set txid
     *
     * @param string $txid
     * @return Payload
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;
        return $this;
    }

    /**
     * Set amount
     *
     * @param mixed $amount
     * @return Payload
     */
    public function setAmount(float $amount)
    {
        $this->amount = (string) number_format($amount,2,'.','');
        return $this;
    }

    /**
     * getValue pega o size do $value e concatena com $id.$size.$value para a construção corretas das
     * linhas do payload
     *
     * @param $id
     * @param $value
     * @return string $id.$size.$value
     */
    private function getValue($id, $value)
    {
        $size = str_pad(strlen($value),2,'0',STR_PAD_LEFT);
        return $id.$size.$value;
    }

    /**
     *
     * @return string
     */
    private function getMerchantAccountInformation()
    {
        $gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI,'br.gov.bcb.pix');
        $key = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY,$this->pixKey);
        $description = strlen($this->description) ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION,$this->description) : '';
        return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION,$gui.$key.$description);
    }

    /**
     *
     * @return string
     */
    private function getAdditionalDataFieldTemplate()
    {
        $txid = $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID,$this->txid);
        return $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE, $txid);
    }

    /**
     * gera o payload completo para uso como Pix copia e cola para ser usado corretamente precisa setar as
     * variveis $pixKey, $description, $merchantName, $merchantCity, $txid, $amount
     *
     * @return string
     */
    function generatePayload ()
    {
        $payload = $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR,'01').
                   $this->getMerchantAccountInformation().
                   $this->getValue(self::ID_MERCHANT_CATEGORY_CODE,'0000').
                   $this->getValue(self::ID_TRANSACTION_CURRENCY,'986').
                   $this->getValue(self::ID_TRANSACTION_AMOUNT,$this->amount).
                   $this->getValue(self::ID_COUNTRY_CODE,'BR').
                   $this->getValue(self::ID_MERCHANT_NAME,$this->utils->prepareString($this->merchantName)).
                   $this->getValue(self::ID_MERCHANT_CITY,$this->utils->prepareString($this->merchantCity)).
                   $this->getAdditionalDataFieldTemplate();
        return $payload.$this->getCRC16($payload);
    }

    /**
     * Método responsável por calcular o valor da hash de validação do código pix
     *
     * @return string
     */
    private function getCRC16($payload) {
        //ADICIONA DADOS GERAIS NO PAYLOAD
        $payload .= self::ID_CRC16.'04';
        //DADOS DEFINIDOS PELO BACEN
        $polynomial = 0x1021;
        $res = 0xFFFF;
        //CHECKSUM
        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $res ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($res <<= 1) & 0x10000) $res ^= $polynomial;
                    $res &= 0xFFFF;
                }
            }
        }
        //RETORNA CÓDIGO CRC16 DE 4 CARACTERES
        return self::ID_CRC16.'04'.strtoupper(dechex($res));
    }
}
