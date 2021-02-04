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

namespace Lof\ProductReviews\Cron;

class AutoSendReminders
{
    /**
     * @var \Lof\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $_orderRepository;

    /**
     * @var \Lof\ProductReviews\Helper\Data
     */
    protected $_dataHelper;

    protected $_orderData = null;

    /**
     * @param \Lof\ProductReviews\Model\Reminders $reminders
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Lof\ProductReviews\Helper\Data $dataHelper
     */
    public function __construct(
        \Lof\ProductReviews\Model\Reminders $reminders,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Lof\ProductReviews\Helper\Data $dataHelper
    ) {
        $this->_reminders = $reminders;
        $this->_orderRepository = $orderRepository;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * Sending email to customers who purchased products
     *
     * @return void
     */
    public function execute()
    {
        $config = $this->_dataHelper->getAutoSendReminder();
        if ($config == 1) {
            $reminderCollection = $this->_reminders->getCollection()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => '1']
            )->setOrder(
                'created_at',
                'asc'
            );
            if( !empty($reminderCollection) ) {
                $customers = [];
                foreach ($reminderCollection as $data) {
                    $orderId = $data->getOrderId();
                    $orderStatus = $this->getOrderStatus($orderId);
                    $order_increment_id = $this->getOrderIncrementId($orderId);
                    if( $orderStatus == 'complete' ) {
                        $customers[] = [
                            'name' => $data->getName(),
                            'email' => $data->getEmail(),
                            'product_id' => $data->getProductId(),
                            'order_increment_id' => $order_increment_id,
                            'order_id' => $orderId
                        ];
                    }
                }
                
                //Send reminder to customers
                $this->_reminders->send($customers);
            }
        }
    }

    protected function getOrderData($orderId) {
        if(!$this->_orderData){
            $this->_orderData = $this->_orderRepository->get((int)$orderId);
        }
        return $this->_orderData;
    }
    protected function getOrderStatus($orderId){
        $order = $this->getOrderData($orderId);
        $status = $order->getStatus();
        return $status;
    }
    protected function getOrderIncrementId($orderId){
        $order = $this->getOrderData($orderId);
        return $order->getIncrementId();
    }
}
