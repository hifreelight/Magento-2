<?php
/**
 * Copyright © 2018 Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace YaBandPay\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Payment\Helper\Data as PaymentHelper;
use function var_export;
use YaBandPay\Payment\Helper\General as YaBandWechatPayHelper;

/**
 * Class PaymentConfigProvider
 *
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class PaymentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var AssetRepository
     */
    private $assetRepository;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    private $yabandpayPaymentHelper;

    /**
     * MollieConfigProvider constructor.
     *
     * @param PaymentHelper        $paymentHelper
     * @param CheckoutSession      $checkoutSession
     * @param AssetRepository      $assetRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper              $escaper
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        CheckoutSession $checkoutSession,
        AssetRepository $assetRepository,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper,
        YaBandWechatPayHelper $yabandpayPaymentHelper
    ) {
        $this->paymentHelper          = $paymentHelper;
        $this->checkoutSession        = $checkoutSession;
        $this->escaper                = $escaper;
        $this->assetRepository        = $assetRepository;
        $this->scopeConfig            = $scopeConfig;
        $this->yabandpayPaymentHelper = $yabandpayPaymentHelper;
    }


    /**
     * Config Data for checkout
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $isActive = $this->yabandpayPaymentHelper->checkUserPasswordToken();
        if ($isActive) {
            $config['payment'][WechatPay::CODE]['isActive'] = true;
        } else {
            $config['payment'][WechatPay::CODE]['isActive'] = false;
        }

        return $config;
    }
}
