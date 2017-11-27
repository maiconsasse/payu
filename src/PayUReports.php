<?php

namespace VerumConsilium\PayU;

use InvalidArgumentException;
use VerumConsilium\PayU\Api\Environment;
use VerumConsilium\PayU\Api\PayUHttpRequestInfo;
use VerumConsilium\PayU\Api\RequestMethod;
use VerumConsilium\PayU\Exception\PayUErrorCodes;
use VerumConsilium\PayU\Exception\PayUException;
use VerumConsilium\PayU\Util\CommonRequestUtil;
use VerumConsilium\PayU\Util\PayUApiServiceUtil;
use VerumConsilium\PayU\Util\PayUParameters;
use VerumConsilium\PayU\Util\PayUReportsRequestUtil;

/**
 * Manages all PayU reports operations
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0.0, 17/10/2013
 *
 */
class PayUReports
{
    
    
    /**
     * Makes a ping request
     *
     * @param string $lang language of request see SupportedLanguages class
     * @throws PayUException
     * @return string
     */
    public static function doPing($lang = null)
    {
        $payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::REPORTS_API, RequestMethod::POST);
         
        return PayUApiServiceUtil::sendRequest(PayUReportsRequestUtil::buildPingRequest($lang), $payUHttpRequestInfo);
    }
    
    
    /**
     * Makes an order details reporting petition by the id
     *
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return null|string order found
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function getOrderDetail($parameters, $lang = null)
    {
        CommonRequestUtil::validateParameters($parameters, array(PayUParameters::ORDER_ID));
        
        $request = PayUReportsRequestUtil::buildOrderReportingDetails($parameters, $lang);
        
        $payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::REPORTS_API, RequestMethod::POST);
        
        $response = PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
        
        if (isset($response) && isset($response->result)) {
            return $response->result->payload;
        }
        
        return null;
    }
    
    /**
     * Makes an order details reporting petition by reference code
     *
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The order list corresponding whit the given reference code
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function getOrderDetailByReferenceCode($parameters, $lang = null)
    {
        CommonRequestUtil::validateParameters($parameters, array(PayUParameters::REFERENCE_CODE));
        
        $request = PayUReportsRequestUtil::buildOrderReportingByReferenceCode($parameters, $lang);
        
        $payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::REPORTS_API, RequestMethod::POST);
        
        $response = PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
        
        if (isset($response) && isset($response->result)) {
            return $response->result->payload;
        } else {
            throw new PayUException(PayUErrorCodes::INVALID_PARAMETERS, "the reference code doesn't exist ");
        }
    }
    
    /**
     * Makes a transaction reporting petition by the id
     *
     * @param array $parameters The parameters to be sent to the server
     * @param string $lang language of request see SupportedLanguages class
     * @return string The transaction response to the request sent
     * @throws PayUException
     * @throws InvalidArgumentException
     */
    public static function getTransactionResponse($parameters, $lang = null)
    {
        CommonRequestUtil::validateParameters($parameters, array(PayUParameters::TRANSACTION_ID));
        
        $request = PayUReportsRequestUtil::buildTransactionResponse($parameters, $lang);
        
        $payUHttpRequestInfo = new PayUHttpRequestInfo(Environment::REPORTS_API, RequestMethod::POST);
        
        $response = PayUApiServiceUtil::sendRequest($request, $payUHttpRequestInfo);
        
        if (isset($response) && isset($response->result)) {
            return $response->result->payload;
        }
        
        return null;
    }
}
