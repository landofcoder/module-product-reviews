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

namespace Lof\ProductReviews\Controller\Adminhtml\Reminders;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class SendReminder extends \Magento\Backend\App\Action
{
    /**
     * @var \Lof\ProductReviews\Model\Reminders
     */
    protected $_reminders;

    /**
     * @var \Lof\ProductReviews\Model\ResourceModel\Reminders\CollectionFactory
     */
    protected $_reminderCollection;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    protected $orderRepository;

    /**
     * @var null
     */
    protected $_orderData = null;

    /**
     * SendReminder constructor.
     * @param Action\Context $context
     * @param \Lof\ProductReviews\Model\Reminders $reminders
     * @param \Lof\ProductReviews\Model\ResourceModel\Reminders\CollectionFactory $reminderCollection
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        Action\Context $context,
        \Lof\ProductReviews\Model\Reminders $reminders,
        \Lof\ProductReviews\Model\ResourceModel\Reminders\CollectionFactory $reminderCollection,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    ) {
        parent::__construct($context);
        $this->_reminders = $reminders;
        $this->_reminderCollection = $reminderCollection;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $customers = [];

        $data = $this->getRequest()->getParams();
        if (!empty($data) && isset($data['selected'])) {
            foreach ($data['selected'] as $reminderId) {
                $reminderObj = $this->_reminders->load($reminderId);
                $orderId = $reminderObj->getOrderId();
                $status = $this->getOrderStatus($orderId);
                $order_increment_id = $this->getOrderIncrementId($orderId);
                if ($status == 'complete') {
                    $customers[] = [
                        'id' => $reminderId,
                        'name' => $reminderObj->getName(),
                        'email' => $reminderObj->getEmail(),
                        'product_id' => $reminderObj->getProductId(),
                        'order_increment_id' => $order_increment_id,
                        'order_id' => $orderId
                    ];
                }
            }
        } else {
            $collection = $this->_reminderCollection->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => '1']
            )->setOrder(
                'created_at',
                'asc'
            );
            foreach ($collection as $data) {
                $orderId = $data->getOrderId();
                $status = $this->getOrderStatus($orderId);
                if ($status == 'complete') {
                    $customers[] = $data->getData();
                }
            }
        }

        if (!empty($customers)) {
            try {
                $this->_reminders->send($customers);
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 email(s) have been sent to customer(s).', count($customers))
                );

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Some emails were not sent.'));
            }
        } else {
            $this->messageManager->addError(__('Not found any record to send. Maybe list reminders had status: sent, or there order status are pending. Order status should completed, and reminders status are pending.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * @param $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    protected function getOrderData($orderId)
    {
        if (!$this->_orderData) {
            $this->_orderData = $this->orderRepository->get((int)$orderId);
        }
        return $this->_orderData;
    }

    /**
     * @param $orderId
     * @return string|null
     */
    protected function getOrderStatus($orderId)
    {
        $order = $this->getOrderData($orderId);
        $status = $order->getStatus();
        return $status;
    }

    /**
     * @param $orderId
     * @return string|null
     */
    protected function getOrderIncrementId($orderId)
    {
        $order = $this->getOrderData($orderId);
        return $order->getIncrementId();
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Lof_ProductReviews::lof_product_reviews_reminders');
    }
}
