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

namespace Hgati\ProductReviews\Observer;

use Magento\Framework\Event\ObserverInterface;

class UpdateCustomerObserver implements ObserverInterface
{
    /**
     * @var \Hgati\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * UpdateCustomerObserver constructor.
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Hgati\ProductReviews\Model\Reminders $reminders
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Hgati\ProductReviews\Model\Reminders $reminders
    ) {
        $this->_customerSession = $customerSession;
        $this->_reminders = $reminders;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customerId = $this->_customerSession->getCustomerData()->getId();
        $firstName = $this->_customerSession->getCustomerData()->getFirstname();
        $lastName = $this->_customerSession->getCustomerData()->getLastname();
        $email = $this->_customerSession->getCustomerData()->getEmail();

        try {
            $reminders = $this->_reminders->getCollection()->getData();
            if (!empty($reminders)) {
                foreach ($reminders as $reminder) {
                    if ($email == $reminder['email']) {
                        $model = $this->_reminders->load($reminder['id']);
                        $customerName = $firstName .' '. $lastName;
                        $model->setCustomerId($customerId)
                            ->setName($customerName)
                            ->save();
                    }
                }
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Could not update customer info to review reminder.'));
        }
    }
}
