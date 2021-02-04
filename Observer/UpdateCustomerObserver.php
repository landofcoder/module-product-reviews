<?php
/**
 * *
 *  * Landofcoder
 *  *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the Landofcoder.com license that is
 *  * available through the world-wide-web at this URL:
 *  * https://landofcoder.com/license
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade this extension to newer
 *  * version in the future.
 *  *
 *  * @category   Landofcoder
 *  * @package    Lof_ProductReviews
 *  * @copyright  Copyright (c) 2020 Landofcoder (https://www.landofcoder.com/)
 *  * @license    https://landofcoder.com/LICENSE-1.0.html
 *
 */

namespace Lof\ProductReviews\Observer;

use Magento\Framework\Event\ObserverInterface;

class UpdateCustomerObserver implements ObserverInterface
{
    /**
     * @var \Lof\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Lof\ProductReviews\Model\Reminders $reminders
    )
    {
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