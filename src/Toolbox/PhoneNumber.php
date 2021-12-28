<?php declare(strict_types=1);

namespace Simtabi\Pheg\Toolbox;

use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberToTimeZonesMapper;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\ShortNumberInfo;
use Simtabi\Pheg\Core\CoreTools;

final class PhoneNumber
{

    private function __construct() {}

    public static function invoke(): self
    {
        return new self();
    }

    /**
     * method masks the username of an email address
     *
     * @param string $number the number address to mask
     * @param int $mask_percentage the percent of the number to mask
     * @param string $mask_char the character to use to mask with
     * @return $result
     */
    public function maskTelephone($number, $mask_percentage = 60, $mask_char = '*'){

        //username parts mask
        $number_length       = strlen( $number );
        $number_mask_count   = floor( $number_length * $mask_percentage /100 );
        $number_offset       = floor( ( $number_length - $number_mask_count ) / 2 );
        $masked_number       = substr( $number, 0, (int) $number_offset )
            .str_repeat( $mask_char, (int)$number_mask_count)
            .substr( $number, (int)$number_mask_count);

        //return results
        return( $masked_number );

    }


    public function unFormatInternationalPhoneNumber($number, $append_plus = true){
        // Strip a Phone number of all non alphanumeric characters
        $clean  = preg_replace('/(\W*)/', '', $number);
        $clean  = preg_replace('/\D+/',  '', $clean);
        return true === $append_plus ? '+' . $clean : $clean;
    }


    public function getPhoneNumberByCountryInfo($number, $countryISOCode = CoreTools::DEFAULT_REGION){

        // output variables
        $status = false;
        $errors = null;

        //initialization variables
        $geoLocation                = null;
        $countryCode                = null;
        $phoneNumberRegion          = null;
        $numberType                 = null;
        $carrierName                = null;
        $timezone                   = null;
        $isValidNumber              = false;
        $nationalNumber             = null;
        $isPossibleNumber           = false;
        $isPossibleNumberWithReason = false;
        $isValidNumberForRegion     = false;

        $formattedE164              = null;
        $formattedOriginal          = null;
        $formattedNational          = null;
        $formattedInternational     = null;
        $formattedRFC3966           = null;
        $formattedFromUS            = null;
        $formattedFromCH            = null;
        $formattedFromGB            = null;

        $connectsToEmergencyNumber  = false;
        $isEmergencyNumber          = false;
        $getSupportedRegions        = null;

        try{

            // sanitize values
            $countryISOCode = trim($countryISOCode);
            $number         = trim($number);

            // validate country iso code
            if(!empty($countryISOCode)){
                if(!Validators::isCountry($countryISOCode)){
                    throw new PhegException('Invalid country ISO code');
                }
            }


            // validate number
            if((!Validators::isInteger($number)) && (!Validators::isString($number))){
                throw new PhegException(self::_e('INVALID_PHONE_NUMBER_FORMAT'));
            }

            //initialize classes
            $phoneNumberUtil            = PhoneNumberUtil::getInstance();
            $shortNumberUtil            = ShortNumberInfo::getInstance();
            $phoneNumberGeocoder        = PhoneNumberOfflineGeocoder::getInstance();

            $phoneNumber                = $phoneNumberUtil->parse($number, $countryISOCode, null, true);
            $isPossibleNumber           = $phoneNumberUtil->isPossibleNumber($phoneNumber);
            $isPossibleNumberWithReason = $phoneNumberUtil->isPossibleNumberWithReason($phoneNumber);
            $isValidNumber              = $phoneNumberUtil->isValidNumber($phoneNumber);
            $isValidNumberForRegion     = $phoneNumberUtil->isValidNumberForRegion($phoneNumber, $countryISOCode);
            $phoneNumberRegion          = $phoneNumberUtil->getRegionCodeForNumber($phoneNumber);
            $countryCode                = $phoneNumber->getCountryCode();
            $nationalNumber             = $phoneNumber->getNationalNumber();

            $formattedE164              = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::E164);
            $formattedOriginal          = $phoneNumberUtil->formatInOriginalFormat($phoneNumber, $countryISOCode);
            $formattedNational          = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::NATIONAL);
            $formattedInternational     = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::INTERNATIONAL);
            $formattedRFC3966           = $phoneNumberUtil->format($phoneNumber, PhoneNumberFormat::RFC3966);
            $formattedFromUS            = $phoneNumberUtil->formatOutOfCountryCallingNumber($phoneNumber, "US");
            $formattedFromCH            = $phoneNumberUtil->formatOutOfCountryCallingNumber($phoneNumber, "CH");
            $formattedFromGB            = $phoneNumberUtil->formatOutOfCountryCallingNumber($phoneNumber, "GB");

            $connectsToEmergencyNumber  = $shortNumberUtil->connectsToEmergencyNumber($number, $countryISOCode);
            $getSupportedRegions        = $shortNumberUtil->getSupportedRegions();
            $isEmergencyNumber          = $shortNumberUtil->isEmergencyNumber($number, $countryISOCode);

            //get the number type
            $getNumberType = $phoneNumberUtil->getNumberType($phoneNumber);
            switch ($getNumberType){
                case 0  : $numberType = self::_e('FIXED_LINE_NUMBER');                  break;
                case 1  : $numberType = self::_e('MOBILE_NUMBER');                      break;
                case 2  : $numberType = self::_e('FIXED_LINE_NUMBER_OR_MOBILE_NUMBER'); break;
                case 3  : $numberType = self::_e('TOLL_FREE_NUMBER');                   break;
                case 4  : $numberType = self::_e('PREMIUM_RATE_NUMBER');                break;
                case 5  : $numberType = self::_e('SHARED_COST_NUMBER');                 break;
                case 6  : $numberType = self::_e('VOIP_SERVICE_NUMBER');                break;
                case 7  : $numberType = self::_e('PERSONAL_NUMBER');                    break;
                case 8  : $numberType = self::_e('PAGER_NUMBER');                       break;
                case 9  : $numberType = self::_e('UAN_NUMBER');                         break;
                case 10 : $numberType = self::_e('UNKNOWN_NUMBER');                     break;
                case 27 : $numberType = self::_e('EMERGENCY_NUMBER');                   break;
                case 28 : $numberType = self::_e('VOICE_MAIL_NUMBER');                  break;
                case 29 : $numberType = self::_e('SHORT_CODE_NUMBER');                  break;
                case 30 : $numberType = self::_e('STANDARD_RATE_NUMBER');               break;
                default : $numberType = self::_e('UNKNOWN_NUMBER_TYPE');                break;
            }
            $numberType  = ucwords(strtolower($numberType));

            //get geo data information
            $geoLocation = $phoneNumberGeocoder->getDescriptionForNumber($phoneNumber, $phoneNumberRegion, $phoneNumberRegion);

            //get special number information and timezone info
            $carrierName = PhoneNumberToCarrierMapper::getInstance()->getNameForNumber($phoneNumber, $phoneNumberRegion);
            $timezone    = PhoneNumberToTimeZonesMapper::getInstance()->getTimeZonesForNumber($phoneNumber);
            $timezone    = !is_array($timezone) && (Validators::isString($timezone)) ? implode(',', $timezone) : $timezone;

            //validate if carrier name is available
            if(empty($carrierName)){
                $carrierName = self::_e('CARRIER_NAME_NOT_SET');
            }

            // set status
            $status = true;

        }catch (PhegException $e){
            $errors = $e->getMessage();
        } catch (NumberParseException $e) {
            $errors = $e->getMessage();
        }

        return TypeConverter::toObject([
            'status' => $status,
            'errors' => Helpers::filterArray($errors),
            'query'  => [
                'geoLocation'                => empty($geoLocation) ? null : $geoLocation,
                'countryCode'                => $countryCode,
                'phoneNumberRegion'          => $phoneNumberRegion,
                'numberType'                 => $numberType,
                'carrierName'                => $carrierName,
                'timezone'                   => $timezone,
                'isValidNumber'              => $isValidNumber,
                'nationalNumber'             => $nationalNumber,
                'isPossibleNumber'           => $isPossibleNumber,
                'isPossibleNumberWithReason' => $isPossibleNumberWithReason,
                'isValidNumberForRegion'     => $isValidNumberForRegion,

                'formattedE164'              => $formattedE164,
                'formattedOriginal'          => $formattedOriginal,
                'formattedNational'          => $formattedNational,
                'formattedInternational'     => $formattedInternational,
                'formattedRFC3966'           => $formattedRFC3966,
                'formattedFromUS'            => $formattedFromUS,
                'formattedFromCH'            => $formattedFromCH,
                'formattedFromGB'            => $formattedFromGB,

                'connectsToEmergencyNumber'  => $connectsToEmergencyNumber,
                'isEmergencyNumber'          => $isEmergencyNumber,
                'supportedRegions'           => $getSupportedRegions,
            ],

        ]);

    }






    /**
     * @param string|PhoneNumber $number
     * @param string $defaultRegion
     * @return LibPhoneNumber
     */
    protected static function getNumberProto($number, $defaultRegion = null) {
        if ($number instanceof LibPhoneNumber) {
            return $number;
        }
        $defaultRegion = Validators::isEmpty($defaultRegion) ? self::getDefaultRegion() : $defaultRegion;
        $util          = PhoneNumberUtil::getInstance();
        $region = strpos($number, '+') === false ? $defaultRegion : 'ZZ';
        return $util->parse($number, $region);
    }

    /**
     * @param string|PhoneNumber $number
     * @param string $defaultRegion
     * @return string
     */
    public function e164($number, $defaultRegion = null) {
        $defaultRegion = Validators::isEmpty($defaultRegion) ? self::getDefaultRegion() : $defaultRegion;
        $proto         = self::getNumberProto($number, $defaultRegion);
        $util          = PhoneNumberUtil::getInstance();
        return $util->format($proto, PhoneNumberFormat::E164);
    }

    /**
     * @param string|PhoneNumber $number
     * @param string $defaultRegion
     * @return string
     */
    public function national($number, $defaultRegion = null) {
        $defaultRegion = Validators::isEmpty($defaultRegion) ? self::getDefaultRegion() : $defaultRegion;
        $proto         = self::getNumberProto($number, $defaultRegion);
        $util          = PhoneNumberUtil::getInstance();
        return $util->format($proto, PhoneNumberFormat::NATIONAL);
    }

    /**
     * @param string|PhoneNumber $number
     * @param string $defaultRegion
     * @return string
     */
    public function international($number, $defaultRegion = null) {
        $defaultRegion = Validators::isEmpty($defaultRegion) ? self::getDefaultRegion() : $defaultRegion;
        $proto         = self::getNumberProto($number, $defaultRegion);
        $util          = PhoneNumberUtil::getInstance();
        return $util->format($proto, PhoneNumberFormat::INTERNATIONAL);
    }

    /**
     * @param string|PhoneNumber $number
     * @param string $defaultRegion
     * @return string
     */
    public function localized($number, $defaultRegion) {
        $defaultRegion = Validators::isEmpty($defaultRegion) ? self::getDefaultRegion() : $defaultRegion;
        $proto         = self::getNumberProto($number, $defaultRegion);
        $util          = PhoneNumberUtil::getInstance();
        return $util->getRegionCodeForNumber($proto) == $defaultRegion ? self::national($proto) : self::international($proto);
    }

}