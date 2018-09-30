<?php namespace professionalweb\payment\drivers\payonline;

use professionalweb\payment\contracts\PayProtocol;

require_once 'PayOnline.php';

/**
 * Wrapper for PayOnline protocol
 * @package professionalweb\payment\drivers\payonline
 */
class PayOnlineProtocol extends \PayOnline implements PayProtocol
{

    /**
     * Get payment form URL
     *
     * @param mixed $params
     *
     * @return string
     */
    public function getPaymentUrl(array $params): string
    {
        return $this->getUrl($params);
    }

    /**
     * Validate params
     *
     * @param mixed $params
     *
     * @return bool
     */
    public function validate(array $params): bool
    {
        $result = false;

        if (isset($params['SecurityKey']) && $this->getSecurityKey('callback', $params) === $params['SecurityKey']) {
            $result = true;
        }

        return $result;
    }

    /**
     * Get payment ID
     *
     * @return mixed
     */
    public function getPaymentId(): string
    {
        return '';
    }

    /**
     * Prepare response on notification request
     *
     * @param mixed $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getNotificationResponse($requestData, $errorCode = null): string
    {
        return $errorCode > 0 ? 'ERROR' : 'OK';
    }

    /**
     * Prepare response on check request
     *
     * @param array $requestData
     * @param int   $errorCode
     *
     * @return string
     */
    public function getCheckResponse($requestData, $errorCode = null): string
    {
        return $errorCode > 0 ? 'ERROR' : 'OK';
    }

    /**
     * Prepare parameters
     *
     * @param array $params
     *
     * @return array
     */
    public function prepareParams(array $params): array
    {
        return $params;
    }
}