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

namespace Lof\ProductReviews\Block;

class Form extends \Magento\Review\Block\Form
{
    /**
     * @var \Lof\ProductReviews\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = [],
        \Magento\Framework\Serialize\Serializer\Json $serializer = null,
        \Lof\ProductReviews\Helper\Data $helperData,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\Order\Config $orderConfig
    )
    {
        $this->helperData = $helperData;
        $this->productMetadata = $productMetadata;
        $this->_customerSession = $customerSession;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderConfig = $orderConfig;
        parent::__construct($context, $urlEncoder, $reviewData, $productRepository, $ratingFactory, $messageManager, $httpContext, $customerUrl, $data, $serializer);
    }

    protected function _construct()
    {
        parent::_construct();

        $magentoVersion = $this->productMetadata->getVersion();

        if (version_compare($magentoVersion, '2.3.0') >= 0)
            $this->setTemplate('Lof_ProductReviews::form.phtml');
        else
            $this->setTemplate('form.phtml');
    }

    /**
     * Get review product post action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getUrl(
            'lof_productreviews/reviews/save',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
    }

    public function getVerifyAction() {
        return $this->getUrl(
            'lof_productreviews/reviews/verify',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
    }

    public function getVerifyConfig() {
        return $this->helperData->getVerifyPurchasedConfig();
    }

    public function getAutoVerifyConfig() {
        return $this->helperData->getAutoVerifyConfig();
    }

    public function getAutoVerified($product_id = 0) {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        $product_id = $product_id?$product_id:$this->getProductId();
        return $this->helperData->getAutoVerified($product_id, $customerId);
    }

    public function getImageConfig()
    {
        return $this->helperData->getImageSetting();
    }

    public function getReviewConfig($key, $default = "")
    {
        $key = "lof_review_settings/".$key;
        return $this->helperData->getConfig($key, $default);
    }

    public function checkCustomerLogin() {
        return $this->_customerSession->isLoggedIn() ? true : false;
    }

    public function listOrderIds() {
        
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return [];
        }

        $order_collection = $this->_orderCollectionFactory->create($customerId)->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'status',
            ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
        )->setOrder(
            'created_at',
            'desc'
        );

        return $order_collection;
    }
}