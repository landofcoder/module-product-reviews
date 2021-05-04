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

namespace Lof\ProductReviews\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    //Lof Product Reviews frontend configs
    const REVIEW_ENABLE_SORTING = 'lof_product_reviews/lof_review_settings/enable_sorting';
    const REVIEW_COUPON = 'lof_product_reviews/lof_general_settings/choose_coupon';
    const REVIEW_AFTER_DAY = 'lof_product_reviews/lof_general_settings/after_day';
    const REVIEW_VERIFY_PURCHASED = 'lof_product_reviews/lof_review_settings/verify_purchased_code';
    const REVIEW_AUTO_VERIFY_PURCHASED = 'lof_product_reviews/lof_review_settings/enable_auto_verify';
    const REVIEW_ALLOW_UPLOAD_IMAGE = 'lof_product_reviews/lof_review_settings/allow_upload';
    const REVIEW_IMAGE_WIDTH = 'lof_product_reviews/lof_review_settings/image_width';
    const REVIEW_IMAGE_HEIGHT = 'lof_product_reviews/lof_review_settings/image_height';

    //Lof Product Reviews backend configs
    const XML_SENDER_EMAIL = 'lof_product_reviews/email_settings/email_sender';
    const XML_PATH_EMAIL_TEMPLATE = 'lof_product_reviews/email_settings/email_templates';
    const XML_PATH_REPLY_EMAIL_TEMPLATE = 'lof_product_reviews/email_settings/reply_email_templates';
    const XML_SEND_REMINDER_AUTO = 'lof_product_reviews/email_settings/send_emails_automatically';

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\Order\Config $orderConfig
    ) {
        parent::__construct($context);
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_orderFactory = $orderFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderConfig = $orderConfig;
        $this->_request = $context->getRequest();
    }

    /**
     * @return bool
     */
    public function getReviewSortingConfig()
    {
        return $this->scopeConfig->isSetFlag(
            self::REVIEW_ENABLE_SORTING,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return int
     */
    public function getCouponCode()
    {
        return $this->scopeConfig->getValue(
            self::REVIEW_COUPON,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int
     */
    public function getDaysToSendReminderEmail()
    {
        return $this->scopeConfig->getValue(
            self::REVIEW_AFTER_DAY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getVerifyPurchasedConfig()
    {
        return $this->scopeConfig->isSetFlag(
            self::REVIEW_VERIFY_PURCHASED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return bool
     */
    public function getImageSetting()
    {
        return $this->scopeConfig->isSetFlag(
            self::REVIEW_ALLOW_UPLOAD_IMAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getImageWidth()
    {
        return $this->scopeConfig->getValue(
            self::REVIEW_IMAGE_WIDTH,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getImageHeight()
    {
        return $this->scopeConfig->getValue(
            self::REVIEW_IMAGE_HEIGHT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Retrieve Email Template
     *
     * @param int $store
     * @return mixed
     */
    public function getEmailTemplate($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_TEMPLATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Email Template
     *
     * @param int $store
     * @return mixed
     */
    public function getReplyEmailTemplate($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_REPLY_EMAIL_TEMPLATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     *  Get Sender
     *
     * @return mixed
     */
    public function getSenderConfig()
    {
        return $this->scopeConfig->getValue(self::XML_SENDER_EMAIL, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getConfig($key, $default = "")
    {
        $key = 'lof_product_reviews/' . $key;
        $value = $this->scopeConfig->getValue($key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $value ? $value : $default;
    }

    /**
     *  Get Reminder Configuration
     *
     * @return mixed
     */
    public function getAutoSendReminder()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_SEND_REMINDER_AUTO,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Sender Name and Email
     *
     * @param $config
     * @return array
     */
    public function getSenderValue($config)
    {
        $value = [];
        if ($config == 'general') {
            $value = [
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_general/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_general/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];
        } elseif ($config == 'sales') {
            $value = [
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_sales/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_sales/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];
        } elseif ($config == 'custom1') {
            $value = [
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_custom1/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_custom1/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];
        } elseif ($config == 'custom2') {
            $value = [
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_custom2/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_custom2/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];
        } else {
            $value = [
                'name' => $this->scopeConfig->getValue(
                    'trans_email/ident_support/name',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ),
                'email' => $this->scopeConfig->getValue(
                    'trans_email/ident_support/email',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                )
            ];
        }

        return $value;
    }

    /**
     * @return bool
     */
    public function getAutoVerifyConfig()
    {
        return $this->scopeConfig->isSetFlag(
            self::REVIEW_AUTO_VERIFY_PURCHASED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $product_id
     * @param int $customerId
     * @return bool
     */
    public function getAutoVerified($product_id, $customerId = 0)
    {
        $is_verified = false;
        if ($product_id && $customerId && $this->getAutoVerifyConfig()) {
            $order_collection = $this->_orderCollectionFactory->create($customerId)->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'status',
                ['in' => $this->_orderConfig->getVisibleOnFrontStatuses()]
            )->setOrder(
                'created_at',
                'desc'
            );

            if ($order_collection->count()) {
                foreach ($order_collection as $_order) {
                    $order_increment = $_order->getIncrementId();
                    $order = $this->_orderFactory->create()->loadByIncrementId($order_increment);
                    if ($order) {
                        $allItems = $order->getAllVisibleItems();
                        $productIds = [];
                        foreach ($allItems as $item) {
                            $productIds[] = $item->getProductId();
                        }
                        if (in_array($product_id, $productIds)) {
                            $is_verified = true;
                            break;
                        }
                    }
                }
            }
        }

        return $is_verified;
    }
}
