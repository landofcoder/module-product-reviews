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
 * @package    Lof_Formbuilder
 * @copyright  Copyright (c) 2020 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\ProductReviews\Controller\Reviews;

use Lof\ProductReviews\Model\Sender;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Review\Controller\Product as ProductController;
use Magento\Framework\Controller\ResultFactory;
use Magento\Review\Model\Review;
use Lof\ProductReviews\Model\CustomReview;
use Lof\ProductReviews\Model\Gallery;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Save
 * @package Lof\ProductReviews\Controller\Reviews
 */
class Save extends ProductController
{
     protected $cutomerCollectionFactory;
     protected $sender;
    public function __construct(\Magento\Framework\App\Action\Context $context,
                                \Magento\Framework\Registry $coreRegistry,
                                CollectionFactory $cutomerCollectionFactory,
                                \Magento\Customer\Model\Session $customerSession,
                                \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
                                \Psr\Log\LoggerInterface $logger,
                                Sender $sender,
                                \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
                                \Magento\Review\Model\ReviewFactory $reviewFactory,
                                \Magento\Review\Model\RatingFactory $ratingFactory,
                                \Magento\Catalog\Model\Design $catalogDesign,
                                \Magento\Framework\Session\Generic $reviewSession,
                                \Magento\Store\Model\StoreManagerInterface $storeManager,
                                \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator)
    {
         $this->cutomerCollectionFactory = $cutomerCollectionFactory;
         $this->sender = $sender;
        parent::__construct($context, $coreRegistry, $customerSession, $categoryRepository, $logger, $productRepository, $reviewFactory, $ratingFactory, $catalogDesign, $reviewSession, $storeManager, $formKeyValidator);
    }

    /**
     * Submit new review action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        /* @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }

        $data = $this->reviewSession->getFormData(true);
        if ($data) {
            $rating = [];
            if (isset($data['ratings']) && is_array($data['ratings'])) {
                $rating = $data['ratings'];
            }
        } else {
            $data = $this->getRequest()->getPostValue();
            $rating = $this->getRequest()->getParam('ratings', []);
        }

        if (($product = $this->initProduct()) && !empty($data)) {

            $data1 = array_slice($data, 0, 6);
            $data2 = array_slice($data, 6, 8);

            /** @var \Magento\Review\Model\Review $review */
            $review = $this->reviewFactory->create()->setData($data1);
            $review->unsetData('review_id');

            /** @var \Lof\ProductReviews\Model\CustomReview $customReview */
            /** @var \Lof\ProductReviews\Model\Gallery $reviewGallery */
            $customReview = $this->_objectManager->create(CustomReview::class);

            $validate = $review->validate();
            if ($validate === true) {
                try {
                    $review->setEntityId($review->getEntityIdByCode(Review::ENTITY_PRODUCT_CODE))
                        ->setEntityPkValue($product->getId())
                        ->setStatusId(Review::STATUS_PENDING)
                        ->setCustomerId($this->customerSession->getCustomerId())
                        ->setStoreId($this->storeManager->getStore()->getId())
                        ->setStores([$this->storeManager->getStore()->getId()])
                        ->save();


                    $reviewId = $review->getId();
                    try {
                        // Save customize review
                        $data2['average'] = $customReview->addCountRating($reviewId);
                        $data2['count_helpful'] = 0;
                        $data2['total_helpful'] = 0;
                        $data2['report_abuse'] = 0;
                        $data2['review_id'] = $reviewId;
                        $customReview->setData($data2);
                        $customReview->save();

                        //Upload review image
                        $this->uploadMultipleImages('review_images', $reviewId);

                    } catch (\Exception $e) {
                        $this->messageManager->addExceptionMessage($e, __('Something went wrong while making the review.'));
                    }

                    foreach ($rating as $ratingId => $optionId) {
                        $this->ratingFactory->create()
                            ->setRatingId($ratingId)
                            ->setReviewId($review->getId())
                            ->setCustomerId($this->customerSession->getCustomerId())
                            ->addOptionVote($optionId, $product->getId());
                    }

                    $review->aggregate();
                    $customer =$this->cutomerCollectionFactory->create()->addFieldToFilter('entity_id',$this->customerSession->getCustomerId())->getData();
                    $dataEmail = [];
                    $dataEmail['name'] = $customer[0]['firstname'] .' '. $customer[0]['lastname'];
                    $dataEmail['product_name'] = $product->getName();
                    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                    $couponGenerator = $objectManager->create('Magento\SalesRule\Model\CouponGenerator');
                    $data = array(
                        'rule_id' =>5,
                        'qty' => '1',
                        'length' => '8',
                        'format' => 'alphanum',
                        'prefix' => 'YSX',
                        'suffix' => 'CXK',
                    );
                    $codes = $couponGenerator->generateCodes($data);
                    $dataEmail['couponcode'] = $codes[0];
                    $this->sender->sendCouponCodeEmail($dataEmail);
                    $this->messageManager->addSuccess(__('You submitted your review for moderation.'));
                } catch (\Exception $e) {
                    $this->reviewSession->setFormData($data);
                    $this->messageManager->addError(__('We can\'t post your review right now.'));
                }
            } else {
                $this->reviewSession->setFormData($data);
                if (is_array($validate)) {
                    foreach ($validate as $errorMessage) {
                        $this->messageManager->addError($errorMessage);
                    }
                } else {
                    $this->messageManager->addError(__('We can\'t post your review right now.'));
                }
            }
        }

        $redirectUrl = $this->reviewSession->getRedirectUrl(true);
        $resultRedirect->setUrl($redirectUrl ?: $this->_redirect->getRedirectUrl());
        return $resultRedirect;
    }

    public function uploadMultipleImages($imageField, $reviewId){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $jsonEncode = $this->_objectManager->get(\Magento\Framework\Json\EncoderInterface::class);
        $reviewGallery = $this->_objectManager->create(Gallery::class);
        $moduleHelper = $this->_objectManager->create(\Lof\ProductReviews\Helper\Data::class);
        $limit_images = $moduleHelper->getConfig("lof_review_settings/limit_upload_image", 1);
        $limit_images = $limit_images?(int)$limit_images:1;
        $default_status = $moduleHelper->getConfig("lof_review_settings/default_status", 2);
        $default_status = $default_status?(int)$default_status:2;
            $names = [];
            if(isset($_FILES)) {
                for($i = 0; $i < count($_FILES); $i++) {
                    if($i <= $limit_images){
                        $fileId = $imageField.'_'.$i;
                        $image = $this->getRequest()->getFiles($fileId);
                        $names[] = $image['name'];

                        $uploader = $objectManager->create(\Magento\MediaStorage\Model\File\Uploader::class, ['fileId' => $fileId]);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        $uploader->setAllowCreateFolders(true);
                        $mediaDirectory = $objectManager->get(\Magento\Framework\Filesystem::class)->getDirectoryRead(DirectoryList::MEDIA);
                        $uploader->save($mediaDirectory->getAbsolutePath('lof/product_reviews'));
                    }
                }
            }
            if($names) {
                if(count(array_filter($names)) == count($names)) {
                    $imageName = $jsonEncode->encode($names);
                } else {
                    $imageName = '';
                }

                $data = [
                    'review_id' => $reviewId,
                    'label'     => __('Gallery of Review ') . $reviewId,
                    'value'     => $imageName,
                    'status'    => $default_status
                ];

                $reviewGallery->setData($data);
                $reviewGallery->save();
            }
    }
}
