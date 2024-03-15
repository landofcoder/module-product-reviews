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

    /**
     * @var \Hgati\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    /**
     * GetOrderDataObserver constructor.
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Hgati\ProductReviews\Model\Reminders $reminders
     */
    public function __construct(
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Hgati\ProductReviews\Model\Reminders $reminders
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
            $order_ids = $observer->getEvent()->getOrderIds();
            $order_id = $order_ids[0];
            // get order info
            $order = $this->_order->load($order_id);


            $orderAllItems = $order->getAllVisibleItems();
            $productIds = [];
            foreach ($orderAllItems as $item) {
                $storeId = $item->getStoreId();
                $productIds[] = $item->getProductId();
            }

            //get customer info
            if ($this->_customerSession->isLoggedIn()) {
                $customer = [
                    'customer_id' => $order->getCustomerId(),
                    'email' => $order->getCustomerEmail(),
                    'name' => $order->getCustomerName(),
                ];
            } elseif ($order->getCustomerIsGuest()) {
                $customer = [
                    'customer_id' => null,
                    'email' => $order->getCustomerEmail(),
                    'name' => $order->getCustomerName()
                ];
            }

            $model = $this->_reminders;
            foreach ($productIds as $productId) {
                $data = [
                    'order_id' => $order_id,
                    'customer_id' => $customer['customer_id'],
                    'name' => $customer['name'],
                    'email' => $customer['email'],
                    'status' => '1',
                    'store_id' => $storeId,
                    'product_id' => $productId
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
