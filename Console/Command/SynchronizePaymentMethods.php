<?php
/**
 * PostFinance Checkout Magento 2
 *
 * This Magento 2 extension enables to process payments with PostFinance Checkout (https://www.postfinance.ch/checkout/).
 *
 * @package PostFinanceCheckout_Payment
 * @author customweb GmbH (http://www.customweb.com/)
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache Software License (ASL 2.0)
 */
namespace PostFinanceCheckout\Payment\Console\Command;

use Magento\Framework\App\Area;
use Magento\Framework\App\State as AppState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PostFinanceCheckout\Payment\Api\PaymentMethodConfigurationManagementInterface\Proxy as PaymentMethodConfigurationManagementInterfaceProxy;

/**
 * Command to synchronize the payment methods.
 */
class SynchronizePaymentMethods extends Command
{

    /**
     *
     * @var AppState
     */
    private $appState;

    /**
     *
     * @var PaymentMethodConfigurationManagementInterfaceProxy
     */
    private $paymentMethodConfigurationManagement;

    /**
     *
     * @param AppState $appState
     * @param PaymentMethodConfigurationManagementInterfaceProxy $paymentMethodConfigurationManagement
     */
    public function __construct(AppState $appState,
        PaymentMethodConfigurationManagementInterfaceProxy $paymentMethodConfigurationManagement)
    {
        parent::__construct();
        $this->appState = $appState;
        $this->paymentMethodConfigurationManagement = $paymentMethodConfigurationManagement;
    }

    protected function configure()
    {
        $this->setName('postfinancecheckout:payment-method:synchronize')->setDescription(
            'Synchronizes the PostFinance Checkout payment methods.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->appState->setAreaCode(Area::AREA_ADMINHTML);
        $this->paymentMethodConfigurationManagement->synchronize($output);
    }
}