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

class GetOrderDataObserver implements ObserverInterface
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;
    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    protected $_reminders;

    public function __construct(
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Lof\ProductReviews\Model\Reminders $reminders
    ) {
        $this->_order = $order;
        $this->_customerSession = $customerSession;
        $this->messageManager = $messageManager;
        $this->_reminders = $reminders;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $order_ids  = $observer->getEvent()->getOrderIds();
            $order_id   = $order_ids[0];
            // get order info
            $order = $this->_order->load($order_id);


            $orderAllItems = $order->getAllVisibleItems();
            $productIds = [];
            foreach($orderAllItems as $item)
            {
                $storeId = $item->getStoreId();
                $productIds[]= $item->getProductId();
            }

            //get customer info
            if ($this->_customerSession->isLoggedIn()) {
                $customer = [
                    'customer_id' => $order->getCustomerId(),
                    'email'       => $order->getCustomerEmail(),
                    'name'        => $order->getCustomerName(),
                ];
            } elseif ($order->getCustomerIsGuest()) {
                $customer = [
                    'customer_id' => null,
                    'email'       => $order->getCustomerEmail(),
                    'name'        => $order->getCustomerName()
                ];
            }

            $model = $this->_reminders;
            foreach($productIds as $productId){
                $data = [
                    'order_id'    => $order_id,
                    'customer_id' => $customer['customer_id'],
                    'name'        => $customer['name'],
                    'email'       => $customer['email'],
                    'status'      => '1',
                    'store_id'    => $storeId,
                    'product_id'  => $productId
                ];
                $model->setData($data);
                $model->save();
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Getting order data error.'));
        }
    }
}