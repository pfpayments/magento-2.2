<?php
/**
 * PostFinance Checkout Magento 2
 *
 * This Magento 2 extension enables to process payments with PostFinance Checkout (https://postfinance.ch/en/business/products/e-commerce/postfinance-checkout-all-in-one.html/).
 *
 * @package PostFinanceCheckout_Payment
 * @author wallee AG (http://www.wallee.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
namespace PostFinanceCheckout\Payment\Plugin\Payment\Model\Method;

use Magento\Framework\ObjectManagerInterface;
use Magento\Payment\Gateway\Config\Config;
use Magento\Payment\Gateway\Config\ConfigValueHandler;
use PostFinanceCheckout\Payment\Model\Payment\Gateway\Config\ValueHandlerPool;
use PostFinanceCheckout\Payment\Model\Payment\Method\Adapter;

/**
 * Interceptor to provide the payment method adapters for the PostFinance Checkout payment methods.
 */
class Factory
{

    /**
     *
     * @var ObjectManagerInterface
     */
    private $objectManager = null;

    /**
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param \Magento\Payment\Model\Method\Factory $subject
     * @param string $classname
     * @param array<mixed> $data
     * @return array|null
     */
    public function beforeCreate(\Magento\Payment\Model\Method\Factory $subject, $classname, $data = [])
    {
        if (strpos($classname, 'postfinancecheckout_payment::') === 0) {
            $configurationId = \substr($classname, \strlen('postfinancecheckout_payment::'));
            $data['code'] = 'postfinancecheckout_payment_' . $configurationId;
            $data['paymentMethodConfigurationId'] = $configurationId;
            $data['valueHandlerPool'] = $this->getValueHandlerPool($configurationId);
            $data['commandPool'] = $this->objectManager->get('PostFinanceCheckoutPaymentGatewayCommandPool');
            $data['validatorPool'] = $this->objectManager->get('PostFinanceCheckoutPaymentGatewayValidatorPool');
            return [
                Adapter::class,
                $data
            ];
        } else {
            return null;
        }
    }

    /**
     * @param int $configurationId
     * @return mixed
     */
    private function getValueHandlerPool($configurationId)
    {
        $configInterface = $this->objectManager->create(Config::class,
            [
                'methodCode' => 'postfinancecheckout_payment_' . $configurationId
            ]);
        $valueHandler = $this->objectManager->create(ConfigValueHandler::class,
            [
                'configInterface' => $configInterface
            ]);
        return $this->objectManager->create(ValueHandlerPool::class, [
            'handler' => $valueHandler
        ]);
    }
}