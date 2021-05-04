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

    /**
     * Form constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Review\Helper\Data $reviewData
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Review\Model\RatingFactory $ratingFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Http\Context $httpContext
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     * @param \Lof\ProductReviews\Helper\Data $helperData
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
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
    ) {
        $this->helperData = $helperData;
        $this->productMetadata = $productMetadata;
        $this->_customerSession = $customerSession;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderConfig = $orderConfig;
        parent::__construct(
            $context,
            $urlEncoder,
            $reviewData,
            $productRepository,
            $ratingFactory,
            $messageManager,
            $httpContext,
            $customerUrl,
            $data,
            $serializer
        );
    }

    protected function _construct()
    {
        parent::_construct();

        $magentoVersion = $this->productMetadata->getVersion();

        if (version_compare($magentoVersion, '2.3.0') >= 0) {
            $this->setTemplate('Lof_ProductReviews::form.phtml');
        } else {
            $this->setTemplate('form.phtml');
        }
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

    public function getVerifyAction()
    {
        return $this->getUrl(
            'lof_productreviews/reviews/verify',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId(),
            ]
        );
    }

    /**
     * @return bool
     */
    public function getVerifyConfig()
    {
        return $this->helperData->getVerifyPurchasedConfig();
    }

    /**
     * @return bool
     */
    public function getAutoVerifyConfig()
    {
        return $this->helperData->getAutoVerifyConfig();
    }

    /**
     * @param int $product_id
     * @return bool
     */
    public function getAutoVerified($product_id = 0)
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        $product_id = $product_id ? $product_id : $this->getProductId();
        return $this->helperData->getAutoVerified($product_id, $customerId);
    }

    /**
     * @return bool
     */
    public function getImageConfig()
    {
        return $this->helperData->getImageSetting();
    }

    /**
     * @param $key
     * @param string $default
     * @return mixed|string
     */
    public function getReviewConfig($key, $default = "")
    {
        $key = "lof_review_settings/" . $key;
        return $this->helperData->getConfig($key, $default);
    }

    /**
     * @return bool
     */
    public function checkCustomerLogin()
    {
        return $this->_customerSession->isLoggedIn() ? true : false;
    }

    /**
     * @return array|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    public function listOrderIds()
    {

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
