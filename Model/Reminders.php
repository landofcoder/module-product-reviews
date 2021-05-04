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

namespace Lof\ProductReviews\Model;

class Reminders extends \Magento\Framework\Model\AbstractModel
{

    /**#@+
     * Reminders Status values
     */
    const STATUS_PENDING = 1;
    const STATUS_ON_HOLD = 2;
    const STATUS_SENT = 3;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $_escaper;

    /**
     * @var \Lof\ProductReviews\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_dataObjectFactory;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\Product $product,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Escaper $escaper,
        \Lof\ProductReviews\Helper\Data $dataHelper,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        ResourceModel\Reminders $resource = null,
        ResourceModel\Reminders\Collection $resourceCollection = null,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->_product = $product;
        $this->_storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->_escaper = $escaper;
        $this->_dataHelper = $dataHelper;
        $this->cookieManager = $cookieManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_dataObjectFactory = $dataObjectFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * ResourceModel Reminders
     */
    protected function _construct()
    {
        $this->_init(\Lof\ProductReviews\Model\ResourceModel\Reminders::class);
    }

    /**
     * @param array $customerInfo
     * @return $this
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function send($customerInfo = [])
    {
        $this->inlineTranslation->suspend();
        $user = $this->_dataHelper->getSenderValue($this->_dataHelper->getSenderConfig());

        $sender = [
            'name' => $this->_escaper->escapeHtml($user['name']),
            'email' => $this->_escaper->escapeHtml($user['email']),
        ];

        if ($customerInfo) {
            $tmp_customerInfo = [];
            foreach ($customerInfo as $key => $customer) {
                if (!isset($tmp_customerInfo[$customer['order_id']])) {
                    $tmp_customerInfo[$customer['order_id']] = $customer;
                    $tmp_customerInfo[$customer['order_id']]['products'] = [];
                    $tmp_customerInfo[$customer['order_id']]['products'][] = $customer['product_id'];
                } else {
                    $tmp_customerInfo[$customer['order_id']]['products'][] = $customer['product_id'];
                }
            }
            $customerInfo = $tmp_customerInfo;
        }
        foreach ($customerInfo as $data) {
            $recipient = [
                'name' => $this->_escaper->escapeHtml($data['name']),
                'email' => $this->_escaper->escapeHtml($data['email']),
            ];
            $orderId = $orderIncrementId = "";
            if (isset($data['order_id']) && $data['order_id']) {
                $orderId = (int)$data['order_id'];
            }
            if (isset($data['order_increment_id']) && $data['order_increment_id']) {
                $orderIncrementId = $data['order_increment_id'];
            }
            $product_ids = isset($data['products'])?$data['products']:[];
            $products = [];
            if ($product_ids) {
                foreach ($product_ids as $product_id) {
                    $product = $this->_product->load($product_id);
                    $productName = $product->getName();
                    $product->setStoreId($this->_storeManager->getStore()->getId());
                    $productUrl = $product->getProductUrl();
                    $tmp = ['product_name' => $productName, 'product_url' => $productUrl];
                    $products[] = $tmp;
                }
            }

            $products_html = $this->getProductsListHtml($products);

            $dataObj = $this->_dataObjectFactory->create()->setData(
                [
                    'sender_name' => $sender['name'],
                    'sender_email' => $sender['email'],
                    'name' => $recipient['name'],
                    'email' => $recipient['email'],
                    'products' => $products_html,
                    'order_increment_id' => $orderIncrementId,
                    'order_id' => $orderId
                ]
            );

            $transportBuilder = $this->_transportBuilder->setTemplateIdentifier(
                'lof_product_reviews_email_settings_email_templates'
            )->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => $this->_storeManager->getStore()->getId(),
                ]
            )->setFrom(
                $sender
            )->setTemplateVars(
                ['data' => $dataObj]
            )->addTo(
                $recipient['email'],
                $recipient['name']
            );

            $transport = $transportBuilder->getTransport();
            try {
                $transport->sendMessage();
                $this->inlineTranslation->resume();
                $reminderId = $data['id'];
                $this->load($reminderId)->setStatus('3')->save();
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        return $this;
    }

    public function getProductsListHtml($products)
    {
        $html = '<ul>';
        foreach ($products as $product) {
            $html .= '<li><a href="'.$product['product_url'].'" alt="'.$product['product_name'].'" target="_blank">'.$product['product_name'].'</a></li>';
        }
        $html .= '</ul>';
        return $html;
    }

    /**
     * @param $senderEmail
     * @param array $recipientsEmail
     * @return array|bool
     * @throws \Zend_Validate_Exception
     */
    public function validateEmail($email)
    {
        //validate sender email addresses
        if (empty($email) or !\Zend_Validate::is($email, \Magento\Framework\Validator\EmailAddress::class)) {
            $errors = __('Please Check Sender Email');
        }

        if (empty($errors)) {
            return true;
        }

        return $errors;
    }

    /**
     * Prepare reminder's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_PENDING => __('Pending'), self::STATUS_ON_HOLD => __('On Hold'), self::STATUS_SENT => __('Sent')];
    }
}
