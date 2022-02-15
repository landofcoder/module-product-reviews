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

namespace Lof\ProductReviews\Plugin\Adminhtml;

use Lof\ProductReviews\Model\CustomReviewFactory;
use Lof\ProductReviews\Model\ReviewReplyFactory;

class Save
{

    /**
     * @var CustomReviewFactory
     */
    protected $customReviewFactory;

    /**
     * @var ReviewReplyFactory
     */
    protected $reviewReplyFactory;

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
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_dataObjectFactory;

    /**
     * Save constructor.
     * @param CustomReviewFactory $customReviewFactory
     * @param ReviewReplyFactory $reviewReplyFactory
     * @param \Lof\ProductReviews\Model\GalleryFactory $galleryFactory
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository
     * @param \Magento\Framework\Escaper $escaper
     * @param \Lof\ProductReviews\Helper\Data $dataHelper
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Controller\ResultFactory $resultFactory
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Backend\Model\Auth\Session $authSession
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        CustomReviewFactory $customReviewFactory,
        ReviewReplyFactory $reviewReplyFactory,
        \Lof\ProductReviews\Model\GalleryFactory $galleryFactory,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\Escaper $escaper,
        \Lof\ProductReviews\Helper\Data $dataHelper,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\ResultFactory $resultFactory,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Backend\Model\Auth\Session $authSession
    ) {

        $this->customReviewFactory = $customReviewFactory;
        $this->reviewReplyFactory = $reviewReplyFactory;
        $this->galleryFactory = $galleryFactory;
        $this->customerRepository = $customerRepository;
        $this->_transportBuilder = $transportBuilder;
        $this->_escaper = $escaper;
        $this->_dataHelper = $dataHelper;
        $this->inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->resultFactory = $resultFactory;
        $this->_dataObjectFactory = $dataObjectFactory;
        $this->authSession = $authSession;
    }

    /**
     * @return \Magento\User\Model\User|null
     */
    public function getCurrentUser()
    {
        return $this->authSession->getUser();
    }

    /**
     * @param \Magento\Review\Controller\Adminhtml\Product\Save $object
     * @param \Closure $proceed
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundExecute(\Magento\Review\Controller\Adminhtml\Product\Save $object, \Closure $proceed)
    {
        $reviewId = $object->getRequest()->getParam('id');
        $data = $object->getRequest()->getPostValue();
        $current_admin_user = $this->getCurrentUser();
        $admin_user_id = 0;
        $email_address = "";
        $website = "";
        $user_name = "";
        if ($current_admin_user) {
            $admin_user_id = $current_admin_user->getId();
            $email_address = $current_admin_user->getEmail();
            $user_name = $current_admin_user->getUsername();
        }

        if (isset($data['send_to']) && $data['send_to'] == true && !empty($data['reply_title']) && !empty($data['reply_comment'])) {
            $user = $this->_dataHelper->getSenderValue($this->_dataHelper->getSenderConfig());
            $sender = [
                'name' => $this->_escaper->escapeHtml($user['name']),
                'email' => $this->_escaper->escapeHtml($user['email']),
            ];

            $customer = $this->customerRepository->getById($data['customer_id']);
            $recipient = [
                'name' => $this->_escaper->escapeHtml($customer->getFirstname() . $customer->getLastname()),
                'email' => $this->_escaper->escapeHtml($customer->getEmail()),
            ];

            $title = htmlspecialchars($data['reply_title']);
            $message = nl2br(htmlspecialchars($data['reply_comment']));

            $this->sendReplyToCustomer($sender, $recipient, $title, $message);
        }
        $email_address = isset($data['email_address']) ? $data['email_address'] : '';
        $avatar_url = isset($data['avatar_url']) ? $data['avatar_url'] : '';
        /** @var \Lof\ProductReviews\Model\CustomReview $customReview */
        $modelCustom = $this->customReviewFactory->create();

        $data['is_recommended'] = isset($data['is_recommended']) ? (int)$data['is_recommended'] : 0;
        $data['verified_buyer'] = isset($data['verified_buyer']) ? (int)$data['verified_buyer'] : 0;
        $data['answer'] = isset($data['answer']) ? $data['answer'] : "";

        if ($modelCustom->getCollection()->getItemByColumnValue('review_customize_id', $data['review_customize_id'])) {
            $custom = $modelCustom->load($data['review_customize_id']);
            $custom->setReviewId($reviewId)
                ->setAdvantages($data['advantages'])
                ->setDisadvantages($data['disadvantages'])
                ->setEmailAddress($email_address)
                ->setAvatarUrl($avatar_url)
                ->setIsRecommended($data['is_recommended'])
                ->setVerifiedBuyer($data['verified_buyer'])
                ->setAnswer($data['answer'])
                ->save();
        } else {
            $modelCustom->setReviewId($reviewId)
                ->setAdvantages($data['advantages'])
                ->setDisadvantages($data['disadvantages'])
                ->setEmailAddress($email_address)
                ->setAvatarUrl($avatar_url)
                ->setIsRecommended($data['is_recommended'])
                ->setVerifiedBuyer($data['verified_buyer'])
                ->setAnswer($data['answer'])
                ->save();
        }

        /** @var \Lof\ProductReviews\Model\ReviewReply $reviewReply */
        if (isset($data['reply_id'])) {
            $modelReply = $this->reviewReplyFactory->create();
            if ($modelReply->getCollection()->getItemByColumnValue('reply_id', $data['reply_id'])) {
                $reply = $modelReply->load($data['reply_id']);
                $reply->setReviewId($reviewId)
                    ->setCustomerId($data['customer_id'])
                    ->setReplyTitle($data['reply_title'])
                    ->setReplyComment($data['reply_comment'])
                    ->setAdminUserId($admin_user_id)
                    ->setEmailAddress($email_address)
                    ->setUserName($user_name)
                    ->setWebsite($website)
                    ->setStatus(\Lof\ProductReviews\Model\ReviewReply::STATUS_ENABLED)
                    ->save();
            } else {
                $modelReply->setReviewId($reviewId)
                    ->setCustomerId($data['customer_id'])
                    ->setReplyTitle($data['reply_title'])
                    ->setReplyComment($data['reply_comment'])
                    ->setAdminUserId($admin_user_id)
                    ->setEmailAddress($email_address)
                    ->setUserName($user_name)
                    ->setWebsite($website)
                    ->setStatus(\Lof\ProductReviews\Model\ReviewReply::STATUS_ENABLED)
                    ->save();
            }
        }

        $modelGallery = $this->galleryFactory->create();
        if ($modelGallery->getCollection()->getItemByColumnValue('review_id', $reviewId) != true) {
            $modelGallery->setReviewId($reviewId)
                ->setLabel('Gallery of Review ' . $reviewId)
                ->setStatus(\Lof\ProductReviews\Model\Gallery::STATUS_DISABLED)
                ->setValue(json_encode([]))
                ->save();
        }

        return $proceed();
    }

    /**
     * @param $sender
     * @param $recipient
     * @param $title
     * @param $message
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendReplyToCustomer($sender, $recipient, $title, $message)
    {
        $this->inlineTranslation->suspend();

        $dataObj = $this->_dataObjectFactory->create()->setData(
            [
                'sender_name' => $sender['name'],
                'sender_email' => $sender['email'],
                'name' => $recipient['name'],
                'email' => $recipient['email'],
                'title' => $title,
                'message' => $message
            ]
        );

        $this->_transportBuilder->setTemplateIdentifier(
            $this->_dataHelper->getReplyEmailTemplate()
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
        $transport = $this->_transportBuilder->getTransport();
        $transport->sendMessage();

        $this->inlineTranslation->resume();

        return $this;
    }
}
