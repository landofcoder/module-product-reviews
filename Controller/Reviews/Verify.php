<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ProductReviews
 * @copyright  Copyright (c) 2020 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\ProductReviews\Controller\Reviews;

class Verify extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $_resultJsonFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->_order = $order;
        $this->_customerSession = $customerSession;
        $this->_resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = [];

        $data = $this->getRequest()->getParams();

        if ($this->_customerSession->isLoggedIn()) {
            $customerId = $this->_customerSession->getCustomerId();
            $order = $this->_order->loadByIncrementId($data['order_id']);
            if (!empty($order) && $customerId == $order->getCustomerId()) {
                $allItems = $order->getAllVisibleItems();
                $productIds = [];
                foreach ($allItems as $item) {
                    $productIds[] = $item->getProductId();
                }
                if (in_array($data['id'], $productIds))
                    $result = ['error' => false, 'message' => 'correct'];
                else
                    $result = ['error' => true, 'message' => 'incorrect'];
            } else {
                $result = ['error' => true, 'message' => 'invalid_order'];
            }

        } else {
            $order = $this->_order->loadByIncrementId($data['order_id']);
            if (!empty($order)) {
                $email = $order->getCustomerEmail();
                if($email == $data['customer_email']) {
                    $allItems = $order->getAllVisibleItems();
                    $productIds = [];
                    foreach ($allItems as $item) {
                        $productIds[] = $item->getProductId();
                    }
                    if (in_array($data['id'], $productIds))
                        $result = ['error' => false, 'message' => 'correct'];
                    else
                        $result = ['error' => true, 'message' => 'incorrect'];
                } else {
                    $result = ['error' => true, 'message' => 'incorrect_email'];
                }
            } else {
                $result = ['error' => true, 'message' => 'invalid_order'];
            }
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_resultJsonFactory->create();

        return $resultJson->setData($result);
    }
}