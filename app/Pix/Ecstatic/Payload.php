<?php
/**
 * @author Giulio Augusto Martinelli
 * @version 1.0.0
 */


namespace App\Pix\Ecstatic;


class Payload
{
    /**
     * IDs do Payload do Pix
     * @author William Costa
     * @link https://github.com/william-costa/wdev-qrcode-pix-estatico-php
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


    private $pixKey;
    private $description;
    private $merchantName;
    private $merchantCity;
    private $txid;
    private $amount;

    /**
     * @param mixed $pixKey
     * @return Payload
     */
    public function setPixKey($pixKey)
    {
        $this->pixKey = $pixKey;
        return $this;
    }

    /**
     * @param mixed $description
     * @return Payload
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param mixed $merchantName
     * @return Payload
     */
    public function setMerchantName($merchantName)
    {
        $this->merchantName = $merchantName;
        return $this;
    }

    /**
     * @param mixed $merchantCity
     * @return Payload
     */
    public function setMerchantCity($merchantCity)
    {
        $this->merchantCity = $merchantCity;
        return $this;
    }

    /**
     * @param mixed $txid
     * @return Payload
     */
    public function setTxid($txid)
    {
        $this->txid = $txid;
        return $this;
    }

    /**
     * @param mixed $amount
     * @return Payload
     */
    public function setAmount($amount)
    {
        $this->amount = (string) number_format($amount,2,'.','');
        return $this;
    }

    private function getValue($id, $value)
    {
        $size = str_pad(strlen($value),2,'0',STR_PAD_LEFT);
        return $id.$size.$value;
    }

    private function getMerchantAccountInformation()
    {
        $gui = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_GUI,'br.gov.bcb.pix');
        $key = $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_KEY,$this->pixKey);
        $description = strlen($this->description) ? $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION,$this->description) : '';
        return $this->getValue(self::ID_MERCHANT_ACCOUNT_INFORMATION,$gui.$key.$description);
    }

    private function getAdditionalDataFieldTemplate()
    {
        $txid = $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE_TXID,$this->txid);
        return $this->getValue(self::ID_ADDITIONAL_DATA_FIELD_TEMPLATE, $txid);
    }

    /**
     * @return
     */
    function generatePayload ()
    {
        $payload = $this->getValue(self::ID_PAYLOAD_FORMAT_INDICATOR,'01').
                   $this->getMerchantAccountInformation().
                   $this->getValue(self::ID_MERCHANT_CATEGORY_CODE,'0000').
                   $this->getValue(self::ID_TRANSACTION_CURRENCY,'986').
                   $this->getValue(self::ID_TRANSACTION_AMOUNT,$this->amount).
                   $this->getValue(self::ID_COUNTRY_CODE,'BR').
                   $this->getValue(self::ID_MERCHANT_NAME,$this->prepareString($this->merchantName)).
                   $this->getValue(self::ID_MERCHANT_CITY,$this->prepareString($this->merchantCity)).
                   $this->getAdditionalDataFieldTemplate();

        return $payload.$this->getCRC16($payload);
    }

    /**
     * IDs do Payload do Pix
     * Método responsável por calcular o valor da hash de validação do código pix
     * @author William Costa
     * @link https://github.com/william-costa/wdev-qrcode-pix-estatico-php
     * @return string
     */
    private function getCRC16($payload) {
        //ADICIONA DADOS GERAIS NO PAYLOAD
        $payload .= self::ID_CRC16.'04';

        //DADOS DEFINIDOS PELO BACEN
        $polinomio = 0x1021;
        $resultado = 0xFFFF;

        //CHECKSUM
        if (($length = strlen($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $resultado ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($resultado <<= 1) & 0x10000) $resultado ^= $polinomio;
                    $resultado &= 0xFFFF;
                }
            }
        }

        //RETORNA CÓDIGO CRC16 DE 4 CARACTERES
        return self::ID_CRC16.'04'.strtoupper(dechex($resultado));
    }


    public function prepareString($string){
        $string = preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
        return mb_strtoupper($string, 'UTF-8');
    }
}