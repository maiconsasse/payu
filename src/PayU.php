<?php

namespace VerumConsilium\PayU;

use VerumConsilium\PayU\Api\SupportedLanguages;

/**
 *
 * Holds basic request information
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 20/10/2013
 *
 */
abstract class PayU
{
    
    /**
     * Api version
     */
    const  API_VERSION = "4.0.1";

    /**
     * Api name
     */
    const  API_NAME = "PayU SDK";
    
    
    const API_CODE_NAME = "PAYU_SDK";

    /**
     * The method invocation is for testing purposes
     */
    public static $isTest = false;

    /**
     * The merchant API key
     */
    public static $apiKey = null;

    /**
     * The merchant API Login
     */
    public static $apiLogin = null;

    /**
     * The merchant Id
     */
    public static $merchantId = null;

    /**
     * The request language
     */
    public static $language = SupportedLanguages::ES;
}
