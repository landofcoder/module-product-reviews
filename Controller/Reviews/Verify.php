<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_ProductReviews
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

namespace Lof\ProductReviews\Controller\Reviews;

use Lof\ProductReviews\Model\Review\Command\VerifyBuyerInterface;

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

    /**
     * @var VerifyBuyerInterface
     */
    protected $_commandVerifyBuyer;

    /**
     * Verify constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param VerifyBuyerInterface $commandVerifyBuyer
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        VerifyBuyerInterface $commandVerifyBuyer
    ) {
        $this->_order = $order;
        $this->_customerSession = $customerSession;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_commandVerifyBuyer = $commandVerifyBuyer;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function execute()
    {
        $result = [];

        $data = $this->getRequest()->getParams();

        $customer_email = isset($data['customer_email']) ? $data['customer_email'] : "";
        $order_id = isset($data['order_id']) ? $data['order_id'] : "";
        $product_id = isset($data['id']) ? (int)$data['id'] : 0;
        $customerId = 0;

        $isVerified = $this->_commandVerifyBuyer->execute($customerId, $customer_email, $product_id, $order_id);

        if ($isVerified) {
            $result = ['error' => false, 'message' => 'correct'];
        } else {
            $result = ['error' => true, 'message' => 'incorrect'];
        }

        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->_resultJsonFactory->create();

        return $resultJson->setData($result);
    }
}
