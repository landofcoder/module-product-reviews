<?php
/**
 * Created by PhpStorm.
 * User: neverland
 * Date: 2018-12-27
 * Time: 16:05
 */

namespace Lof\ProductReviews\Plugin\Frontend\Customer;


use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Escaper;
use Lof\ProductReviews\Model\Reminders;
class EditPost extends \Magento\Customer\Controller\Account\EditPost
{
    /**
     * @var \Lof\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        CustomerRepositoryInterface $customerRepository,
        Validator $formKeyValidator,
        CustomerExtractor $customerExtractor,
        //Escaper $escaper = null,
        Reminders $reminders
        ) {
        parent::__construct($context, $customerSession, $customerAccountManagement, $customerRepository, $formKeyValidator, $customerExtractor);//, $escaper);
        $this->_reminders = $reminders;
    }

    public function afterExecute(\Magento\Customer\Controller\Account\EditPost $object, $resultRedirect)
    {
        $data = $object->getRequest()->getPostValue();
        $customerId =  $this->session->getCustomerData()->getId();
        $customerName = $data['firstname'] .' '. $data['lastname'];
        $customerEmail = isset($data['email'])?$data['email']:'';
        if($customerEmail){
            try {
                $reminders = $this->_reminders->getCollection()->getData();
                if (!empty($reminders)) {
                    foreach ($reminders as $reminder) {
                        if ($customerId == $reminder['customer_id']) {
                            $model = $this->_reminders->load($reminder['id']);
                            $model->setName($customerName)
                                ->setEmail($customerEmail)
                                ->save();
                        }
                    }
                }
                $resultRedirect->setPath('customer/account');
                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Could not update customer info to review reminder.'));
            }
        }else {
            $resultRedirect->setPath('customer/account');
            return $resultRedirect;
        }
    }
}