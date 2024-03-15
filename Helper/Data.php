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
 * @copyright  Copyright (c) 2022 Hgati (https://hgati.com/)
 * @license    https://hgati.com/terms
 */

namespace Hgati\ProductReviews\Helper;

use Hgati\ProductReviews\Api\Data\ReviewInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    //Hgati Product Reviews frontend configs
    const REVIEW_ENABLE_SORTING = 'hgati_product_reviews/hgati_review_settings/enable_sorting';
    const REVIEW_COUPON = 'hgati_product_reviews/hgati_general_settings/choose_coupon';
    const REVIEW_AFTER_DAY = 'hgati_product_reviews/hgati_general_settings/after_day';
    const REVIEW_VERIFY_PURCHASED = 'hgati_product_reviews/hgati_review_settings/verify_purchased_code';
    const REVIEW_AUTO_VERIFY_PURCHASED = 'hgati_product_reviews/hgati_review_settings/enable_auto_verify';
    const REVIEW_ALLOW_UPLOAD_IMAGE = 'hgati_product_reviews/hgati_review_settings/allow_upload';
    const REVIEW_IMAGE_WIDTH = 'hgati_product_reviews/hgati_review_settings/image_width';
    const REVIEW_IMAGE_HEIGHT = 'hgati_product_reviews/hgati_review_settings/image_height';

    //Hgati Product Reviews backend configs
    const XML_SENDER_EMAIL = 'hgati_product_reviews/email_settings/email_sender';
    const XML_PATH_EMAIL_TEMPLATE = 'hgati_product_reviews/email_settings/email_templates';
    const XML_PATH_REPLY_EMAIL_TEMPLATE = 'hgati_product_reviews/email_settings/reply_email_templates';
    const XML_SEND_REMINDER_AUTO = 'hgati_product_reviews/email_settings/send_emails_automatically';
    const XML_PATH_EMAIL_PRODUCT_TEMPLATE = 'hgati_product_reviews/email_settings/review_product_templates';

    /**
     * @var string
     */
    const UPLOAD_FILE_PATH = 'hgati/product_reviews';

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $moduleList;

    protected $_filterProvider;
    protected $_storeManager;
    protected $_customerSession;
    protected $_orderFactory;
    protected $_orderCollectionFactory;
    protected $_orderConfig;
    protected $_moduleList;
    
    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Sales\Model\Order\Config $orderConfig
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Sales\Model\Order\Config $orderConfig,
        \Magento\Framework\Module\ModuleListInterface $moduleList
    ) {
        parent::__construct($context);
        $this->_filterProvider = $filterProvider;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_orderFactory = $orderFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_orderConfig = $orderConfig;
        $this->_moduleList = $moduleList;
        $this->_request = $context->getRequest();
    }

    /**
     * maaing customize review
     *
     * @param mixed|array|ReviewInterface
     * @return mixed|array|ReviewInterface
     */
    public function mappingReviewData($reviewDataObject)
    {
        $customizeReview = $reviewDataObject->getCustomize();
        if ($customizeReview) {
            $reviewDataObject->setVerifiedBuyer($customizeReview->getVerifiedBuyer());
            $reviewDataObject->setIsRecommended($customizeReview->getIsRecommended());
            $reviewDataObject->setAnswer($customizeReview->getAnswer());
            $reviewDataObject->setLikeAbout($customizeReview->getAdvantages());
            $reviewDataObject->setNotLikeAbout($customizeReview->getDisadvantages());
            $reviewDataObject->setGuestEmail($customizeReview->getEmailAddress());
            $reviewDataObject->setPlusReview($customizeReview->getCountHelpful());
            $reviewDataObject->setMinusReview($customizeReview->getCountUnhelpful());
            $reviewDataObject->setCountry($customizeReview->getCountry());
            $reviewDataObject->setReportAbuse($customizeReview->getReportAbuse());
        }
        return $reviewDataObject;
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
     * Retrieve Notify Email Template
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
     * Retrieve Product Review Email Template
     *
     * @param int $store
     * @return mixed
     */
    public function getProductEmailTemplate($store = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_EMAIL_PRODUCT_TEMPLATE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * Retrieve Reply Email Template
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

    /**
     * get is enabled module
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool)$this->getConfig("hgati_general_settings/enabled_module");
    }

    /**
     * get config
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig($key, $default = "")
    {
        $key = 'hgati_product_reviews/' . $key;
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
     * @return mixed|array
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

    /**
     * get coupon config data
     *
     * @param int|string|mixed
     * @return mixed|array
     */
    public function getCouponData($couponConfig)
    {
        return [
            'rule_id' => $couponConfig,
            'qty' => 1,
            'quantity' => 1,
            'length' => 8,
            'format' => 'alphanum',
            'prefix' => 'YSX',
            'suffix' => 'CXK',
        ];
    }

    /**
     * get available file type
     *
     * @return mixed|array
     */
    public function getAvailableFileTypes()
    {
        return ['jpg', 'jpeg', 'gif', 'png'];
    }

    /**
     * get upload file folder path
     *
     * @return string
     */
    public function getUploadFilePath()
    {
        return self::UPLOAD_FILE_PATH;
    }

    /**
     * Get gallery images from gallery model
     *
     * @param \Hgati\ProductReviews\Api\Data\GalleryInterface|mixed|object $galleryModel
     * @return mixed|array
     */
    public function getGalleryImages($galleryModel)
    {
        $value = $galleryModel->getValue();
        $images = [];
        if ($value) {
            $imagePath = $this->getUploadFilePath();
            $imageArr = json_decode($value);
            if ($imageArr) {
                foreach ($imageArr as $_img) {
                    $parsed = parse_url($_img);
                    if (!empty($parsed['scheme'])) {
                        $images[] = $_img;
                    } else {
                        $images[] = $imagePath."/".$_img;
                    }
                }
            }
        }
        return $images;
    }

    /**
     * format upload image
     *
     * @param string $imageUrl
     * @return string
     */
    public function formatUploadImage(string $imageUrl): string
    {
        $imagePath = $this->getUploadFilePath();
        $returnImagePath = "";
        $parsed = parse_url($imageUrl);
        if (!empty($parsed['scheme'])) {
            $returnImagePath = $imageUrl;
        } else {
            $returnImagePath = str_replace($imagePath."/", "", $imageUrl);
        }
        return $returnImagePath;
    }

    /**
     * Check module is installed or not
     *
     * @param string $moduleName
     * @return bool|int
     */
    public function checkModuleInstalled($moduleName)
    {
        return $this->_moduleList->has($moduleName);
    }

    /**
     * @param $data_array
     * @return mixed|array
     */
    public function xss_clean_array($data_array)
    {
        $result = [];
        if (is_array($data_array)) {
            foreach ($data_array as $key => $val) {
                $val = $this->xss_clean($val);
                $result[$key] = $val;
            }
        }
        return $result;
    }

    /**
     * @param $data
     * @return string|string[]|null
     */
    public function xss_clean($data)
    {
        if (!is_string($data)) {
            return $data;
        }
        // Fix &entity\n;
        $data = str_replace(['&amp;', '&lt;', '&gt;'], ['&amp;amp;', '&amp;lt;', '&amp;gt;'], $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

        // Remove javascript: and vbscript: protocols
        $data = preg_replace(
            '#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
            '$1=$2nojavascript...',
            $data
        );
        $data = preg_replace(
            '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu',
            '$1=$2novbscript...',
            $data
        );
        $data = preg_replace(
            '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u',
            '$1=$2nomozbinding...',
            $data
        );

        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace(
            '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i',
            '$1>',
            $data
        );
        $data = preg_replace(
            '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i',
            '$1>',
            $data
        );
        $data = preg_replace(
            '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu',
            '$1>',
            $data
        );

        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

        do {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace(
                '#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i',
                '',
                $data
            );
        } while ($old_data !== $data);

        // we are done...
        return $data;
    }
}
