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

use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Sender
 * @package Lof\ProductReviews\Model
 */
class Sender
{
    /**
     * Sender email
     */
    const SENDER_EMAIL = 'trans_email/ident_general/email';
    /**
     * @var string|null
     */
    protected $messageBody = null;

    /**
     * @var
     */
    public $_storeManager;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var CollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * Sender constructor.
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder $_transportBuilder
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param CollectionFactory $customerCollectionFactory
     * @param Session $customerSession
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $_transportBuilder,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        CollectionFactory $customerCollectionFactory,
        Session $customerSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->messageManager = $messageManager;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->inlineTranslation = $inlineTranslation;
        $this->_transportBuilder = $_transportBuilder;
    }

    /**
     * @param $data
     */
    public function sendCouponCodeEmail($data)
    {
        $sender ['email'] = $this->scopeConfig->getValue(
            self::SENDER_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
        $sender['name'] = 'admin';
        try {
            $postObject = new \Magento\Framework\DataObject();
            $postObject->setData($data);

            $customerId = $this->customerSession->getCustomerId();
            if ($customerId) {
                $customer = $this->customerCollectionFactory->create()->addFieldToFilter(
                    'entity_id',
                    $customerId
                )->getData();
                $customerEmail = $customer[0]['email'];
                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('lof_product_reviews_email_settings_review_product_templates')
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom($sender)->addTo($customerEmail)->getTransport();

            } else {
                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('lof_product_reviews_email_settings_review_product_templates')
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars(['data' => $postObject])
                    ->setFrom($sender)->addTo($data['customer_email'])->getTransport();
            }
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->inlineTranslation->resume();
            $this->messageManager->addError(
                __('We can\'t process your request right now. Sorry, that\'s all we know.')
            );
            return;
        }
    }
}
