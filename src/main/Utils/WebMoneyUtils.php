<?php

namespace onphp\main\Utils;

use onPHP\core\Base\StaticFactory;

final class WebMoneyUtils extends StaticFactory
{
    // order does matter!
    private static $firstFields = array('LMI_PAYEE_PURSE', 'LMI_PAYMENT_AMOUNT', 'LMI_PAYMENT_NO', 'LMI_MODE', 'LMI_SYS_INVS_NO', 'LMI_SYS_TRANS_NO', 'LMI_SYS_TRANS_DATE');
    private static $secondFields = array('LMI_PAYER_PURSE', 'LMI_PAYER_WM');

    /**
     * @see https://merchant.webmoney.ru/conf/guide.asp#hash
     **/
    public static function makePaymentHash($secretKey, $postData)
    {
        $data = null;
        foreach (self::$firstFields as $field) {
            if (!isset($postData[$field])) {
                return null;
            } else {
                $data .= $postData[$field];
            }
        }
        $data .= $secretKey;
        foreach (self::$secondFields as $field) {
            if (!isset($postData[$field])) {
                return null;
            } else {
                $data .= $postData[$field];
            }
        }
        return mb_strtoupper(md5($data));
    }

    /**
     * @param postData [LMI_HASH] $expected
     **/
    public static function isValidPayment($expected, $test)
    {
        return $expected == $test;
    }
}