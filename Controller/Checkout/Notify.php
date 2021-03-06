<?php
/**
 * @project    : YabanPay-Magento2
 * @description:
 * @user       : persi
 * @email persi@sixsir.com
 * @date       : 2018/9/1
 * @time       : 11:42
 */

namespace YaBandPay\Payment\Controller\Checkout;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Payment\Helper\Data as PaymentHelper;
use YaBandPay\Payment\Controller\Controller;
use YaBandPay\Payment\Helper\General as YaBandWechatPayHelper;
use YaBandPay\Payment\Model\WechatPay;

class Notify extends Controller
{
    /**
     * Redirect constructor.
     *
     * @param Context               $context
     * @param Session               $checkoutSession
     * @param PageFactory           $resultPageFactory
     * @param PaymentHelper         $paymentHelper
     * @param YaBandWechatPayHelper $yaBandWechatPayHelper
     */
    public function __construct(
        Context $context,
        PaymentHelper $paymentHelper,
        WechatPay $wechatPay,
        YaBandWechatPayHelper $yaBandWechatPayHelper
    ) {
        $this->resultFactory = $context->getResultFactory();
        $this->paymentHelper = $paymentHelper;
        $this->yaBandWechatPayHelper = $yaBandWechatPayHelper;
        $this->wechatPay = $wechatPay;
        parent::__construct($context);
    }

    /**
     * Execute Redirect to Mollie after placing order
     */
    public function execute()
    {
        try {
            $orderInfo = $this->parseOrderInfo();
            if ($orderInfo['status'] === true) {
                $orderInfo = $orderInfo['order_info'];
                $this->wechatPay->processTransaction($orderInfo);
            }
            $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
            $result->setHeader('content-type', 'text/plain');
            $result->setContents('OK', true);
            return $result;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e, __($e->getMessage())
            );
            $this->yaBandWechatPayHelper->addTolog('error', $e->getMessage());
            $this->checkoutSession->restoreQuote();
            $this->_redirect('checkout/cart');
        }
    }
}
