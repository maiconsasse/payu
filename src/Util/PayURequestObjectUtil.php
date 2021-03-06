<?php

namespace VerumConsilium\PayU\Util;

use InvalidArgumentException;
use stdClass;
use VerumConsilium\PayU\Api\PayUConfig;

/**
 *
 * This class has utility methods for objects used in the request
 *
 * @author PayU Latam
 * @since 1.0.0
 * @version 1.0
 */
class PayURequestObjectUtil
{
    
    /**
     * Remove null values of object or array
     * @param Object $object parameter with null values
     * @throws InvalidArgumentException if the object parameter is null
     * @return string the object processed
     */
    public static function removeNullValues($object)
    {
        if (!isset($object)) {
            throw new InvalidArgumentException("the object to remove null values is null ");
        }
        
        if (!is_object($object) && !is_array($object)) {
            throw new InvalidArgumentException("the parameter is not a object or array");
        }
        
        if (is_object($object)) {
            foreach ($object as $k => $v) {
                if ($v === null) {
                    unset($object->$k);
                } elseif (is_object($object->$k) || is_array($object->$k)) {
                    $tempObject = PayURequestObjectUtil::removeNullValues($object->$k);
                    if (!isset($tempObject)) {
                        unset($object->$k);
                    } else {
                        $object->$k = $tempObject;
                    }
                }
            }
        } elseif (is_array($object)) {
            foreach ($object as $k => $v) {
                if ($v === null) {
                    unset($object[$k]);
                } elseif (is_object($object[$k]) || is_array($object[$k])) {
                    $tempObject = PayURequestObjectUtil::removeNullValues($object[$k]);
                    if (!isset($tempObject)) {
                        unset($object[$k]);
                    } else {
                        $object[$k] = $tempObject;
                    }
                }
            }
        }
        
        if (count((array)$object) === 0) {
            return null;
        } else {
            return $object;
        }
    }
    
    
    /**
     * Convert to utf-8 the string values
     * @param stdClass|array $object parameter with string values
     * @throws InvalidArgumentException if the object parameter is null or it isn't a object
     * @return string the object processed
     */
    public static function encodeStringUtf8($object)
    {
        if (!isset($object)) {
            throw new InvalidArgumentException("the object to encode  is null ");
        }
        
        if (!is_object($object) && !is_array($object)) {
            throw new InvalidArgumentException("the parameter is not a object or array");
        }
        
        if (is_object($object)) {
            foreach ($object as $k => $v) {
                if ($v != null && is_string($v)  && mb_detect_encoding($v, 'UTF-8') != 'UTF-8') {
                    $object->$k = utf8_encode($v);
                } elseif (is_object($object->$k)) {
                    $object->$k = PayURequestObjectUtil::encodeStringUtf8($object->$k);
                }
            }
        } elseif (is_array($object)) {
            foreach ($object as $k => $v) {
                if ($v != null && is_string($v)  && mb_detect_encoding($v, 'UTF-8') != 'UTF-8') {
                    $object[$k] = utf8_encode($v);
                } elseif (is_object($object[$k]) || is_array($object[$k])) {
                    $object[$k] = PayURequestObjectUtil::encodeStringUtf8($object[$k]);
                }
            }
        }
        
        return $object;
    }
    
    /**
     * Adjust milliseconds from epoch to date format
     * @param the object or array $data
     * @throws InvalidArgumentException
     * @return stdClass|array the object or array processed
     */
    public static function formatDates($data)
    {
        if (!isset($data)) {
            throw new InvalidArgumentException("the object to format dates is null ");
        }
        
        if (!is_object($data) && !is_array($data)) {
            throw new InvalidArgumentException("the parameter to format dates is not a object or array");
        }
        
        
        
        foreach ($data as $k => $v) {
            if (PayURequestObjectUtil::isKeyDateField($k)) {
                if (is_array($data)) {
                    $milliseconds = $data[$k];
                    $data[$k] = PayURequestObjectUtil::getDate($milliseconds);
                } elseif (is_object($data)) {
                    $milliseconds = $data->$k;
                    $data->$k = PayURequestObjectUtil::getDate($milliseconds);
                }
            } elseif (is_object($data) && (is_object($data->$k) || is_array($data->$k))) {
                $data->$k = PayURequestObjectUtil::formatDates($data->$k);
            } elseif (is_array($data) && (is_object($data[$k]) || is_array($data[$k]))) {
                $data[$k] =  PayURequestObjectUtil::formatDates($data[$k]);
            }
        }
        
        return $data;
    }
    
    /**
     * Validates if the key in the object belows a date field
     * @param string $key the field name
     * @return boolean true if the key field is in the data fields name false the otherwise
     */
    private static function isKeyDateField($key)
    {
        $dateFields = array('EXPIRATION_DATE',
                            'operationDate',
                            'currentPeriodStart',
                            'currentPeriodEnd',
                            'dateCharge');

        foreach ($dateFields as $field) {
            if ($field === $key) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Format a integer to a date string using PayUConfig::PAYU_DATE_FORMAT
     * @param integer $milliseconds
     * @throws InvalidArgumentException
     * @return string the date string
     */
    public static function getDate($milliseconds)
    {
        $formattedValue = floatval($milliseconds);
        if ($formattedValue == 0 || $formattedValue == 1) {
            throw new InvalidArgumentException("the value in milliseconds for date is invalid");
        }
        
        $seconds = round($formattedValue/1000);
        return date(PayUConfig::PAYU_DATE_FORMAT, $seconds);
    }
}
