<?php
/**
 * Hgati
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Hgati.com license that is
 * available through the world-wide-web at this URL:
 * https://hgati.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Hgati
 * @package    Hgati_ProductReviews
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */

namespace Hgati\ProductReviews\Plugin\Frontend\Customer;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Hgati\ProductReviews\Model\Reminders;

class EditPost extends \Magento\Customer\Controller\Account\EditPost
{
    /**
     * @var \Hgati\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    /**
     * EditPost constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param CustomerRepositoryInterface $customerRepository
     * @param Validator $formKeyValidator
     * @param CustomerExtractor $customerExtractor
     * @param Reminders $reminders
     */
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
        parent::__construct(
            $context,
            $customerSession,
            $customerAccountManagement,
            $customerRepository,
            $formKeyValidator,
            $customerExtractor
        );//, $escaper);
        $this->_reminders = $reminders;
    }

    /**
     * @param \Magento\Customer\Controller\Account\EditPost $object
     * @param $resultRedirect
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterExecute(\Magento\Customer\Controller\Account\EditPost $object, $resultRedirect)
    {
        $data = $object->getRequest()->getPostValue();
        $customerSession = $this->session->getCustomerData();
        if ($customerSession) {
            $customerId = $customerSession->getId();
            $customerName = $data['firstname'] . ' ' . $data['lastname'];
            $customerEmail = isset($data['email']) ? $data['email'] : '';
            if ($customerEmail) {
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
            } else {
                $resultRedirect->setPath('customer/account');
                return $resultRedirect;
            }
        }
    }
}
