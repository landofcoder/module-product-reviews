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

namespace Lof\ProductReviews\Block\Product\View;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Lof\ProductReviews\Model\ReviewReply;

class ListView extends \Magento\Review\Block\Product\View\ListView
{

    /**
     * @var \Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory
     */
    protected $_galleryFactory;

    /**
     * @var \Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory
     */
    protected $_customFactory;

    /**
     * @var \Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory
     */
    protected $_replyFactory;

    /**
     * @var \Lof\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory
     */
    protected $_rateReportFactory;

    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Lof\ProductReviews\Helper\Data
     */
    protected $helperData;

    /**
     * @var \Magento\Framework\App\ScopeInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;

    /**
     * @var \Magento\Framework\Image\AdapterFactory
     */
    protected $_imageFactory;

    /**
     * ListView constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Stdlib\StringUtils $string
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\Session $customerSession
     * @param ProductRepositoryInterface $productRepository
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory
     * @param \Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory $galleryColFactory
     * @param \Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory $customColFactory
     * @param \Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory $replyColFactory
     * @param \Lof\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory $rateColFactory
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Review\Model\ResourceModel\Review\CollectionFactory $collectionFactory,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Lof\ProductReviews\Model\ResourceModel\Gallery\CollectionFactory $galleryColFactory,
        \Lof\ProductReviews\Model\ResourceModel\CustomReview\CollectionFactory $customColFactory,
        \Lof\ProductReviews\Model\ResourceModel\ReviewReply\CollectionFactory $replyColFactory,
        \Lof\ProductReviews\Model\ResourceModel\RateReport\CollectionFactory $rateColFactory,
        \Lof\ProductReviews\Helper\Data $helperData,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Image\AdapterFactory $imageFactory,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {

        $this->_galleryFactory = $galleryColFactory;
        $this->_customFactory = $customColFactory;
        $this->_replyFactory = $replyColFactory;
        $this->_rateReportFactory = $rateColFactory;
        $this->_storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->remoteAddress = $remoteAddress;
        $this->_fileSystem = $fileSystem;
        $this->_imageFactory = $imageFactory;
        $this->scopeConfig = $context->getScopeConfig();
        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $collectionFactory,
            $data
        );
    }

    /**
     * @return mixed
     */
    public function getCustomCollection()
    {
        return $this->_customFactory->create();
    }

    /**
     * @param $reviewId
     * @return \Lof\ProductReviews\Model\ResourceModel\CustomReview\Collection
     */
    public function getReviewsCustom($reviewId)
    {
        $customCollection = $this->getCustomCollection();
        $customize = $customCollection->addFieldToFilter('review_id', $reviewId);
        return $customize;
    }

    /**
     * @param $reviewId
     * @return \Lof\ProductReviews\Model\ResourceModel\CustomReview\Collection
     */
    public function getHelpful($reviewId)
    {
        $result = [];

        $customCollection = $this->getCustomCollection();
        $customize = $customCollection->addFieldToFilter('review_id', $reviewId);
        foreach ($customize as $data) {
            $result['helpful'] = $data->getCountHelpful();
            $result['total'] = $data->getTotalHelpful();
        }

        return $result;
    }

    /**
     * @param $reviewId
     * @return mixed|array|mixed
     */
    public function getReviewsGallery($reviewId)
    {
        $reviewGalleries = $this->_galleryFactory->create();
        $gallery = $reviewGalleries->addFieldToFilter('review_id', $reviewId);
        $imageName = [];
        foreach ($gallery as $value) {
            $imageName = json_decode($value->getValue());
        }
        return $imageName;
    }

    /**
     * @param $image
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrl($image)
    {
        $mediaUrl = $this->_storeManager
                ->getStore()
                ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA) . 'lof/product_reviews/' . ltrim($image, '/');

        return $mediaUrl;
    }

    /**
     * @param $image , $width, $height
     * @return $imageLink
     */
    public function resizeImage($image, $width, $height)
    {
        $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('lof/product_reviews/') . $image;
        if (!file_exists($absolutePath)) {
            return false;
        }
        $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/' . $width . '/') . $image;
        if (!file_exists($imageResized)) {
            //create image factory...
            $imageResize = $this->_imageFactory->create();
            $imageResize->open($absolutePath);
            $imageResize->constrainOnly(true);
            $imageResize->keepTransparency(true);
            $imageResize->keepFrame(false);
            $imageResize->keepAspectRatio(true);
            $imageResize->resize($width, $height);
            //destination folder
            $destination = $imageResized;
            //save image
            $imageResize->save($destination);
        }
        $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'resized/' . $width . '/' . $image;
        return $resizedURL;
    }

    /**
     * @param $reviewId
     * @return \Lof\ProductReviews\Model\ResourceModel\ReviewReply\Collection
     */
    public function getReviewsReply($reviewId)
    {
        $reviewReply = $this->_replyFactory->create();
        $reply = $reviewReply
                ->addFieldToFilter('review_id', $reviewId)
                ->addFieldToFilter('status', ReviewReply::STATUS_ENABLED);
        return $reply;
    }

    /**
     * @return mixed
     */
    public function getImgWidth()
    {
        $imageWidth = $this->helperData->getImageWidth();
        return !empty($imageWidth) ? $imageWidth : 150;
    }

    /**
     * @return mixed
     */
    public function getImgHeight()
    {
        $imageHeight = $this->helperData->getImageHeight();
        return !empty($imageHeight) ? $imageHeight : 150;
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFrontendUrl()
    {
        $frontendUrl = $this->_storeManager->getStore()->getBaseUrl();
        return $frontendUrl;
    }

    /**
     * @param $reviewId
     * @return mixed|array
     */
    public function _compareUserData($reviewId)
    {
        $result = [];
        $userIp = $this->remoteAddress->getRemoteAddress();
        $rateReport = $this->_rateReportFactory->create();
        $data = $rateReport->addFieldToFilter('review_id', $reviewId)->getData();
        if (!empty($data)) {
            foreach ($data as $userData) {
                if ($userData['ip_address'] == $userIp) {
                    $result['rate_type'] = isset($userData['rate_type']) ? $userData['rate_type'] : '';
                    $result['report_type'] = isset($userData['report_type']) ? $userData['report_type'] : '';
                }
            }
        }
        return $result;
    }

    /**
     * @param $reviewId
     * @return string
     */
    public function getGalleryStatus($reviewId)
    {
        $status = '';
        $modelGallery = $this->_galleryFactory->create();
        $data = $modelGallery->addFieldToFilter('review_id', $reviewId)->getData();

        if (!empty($data)) {
            foreach ($data as $value) {
                $status = $value['status'];
            }
        }
        return $status;
    }

    /**
     * @param $path
     * @return bool
     */
    public function getIsSetFlagConfig($path)
    {
        return $this->scopeConfig->isSetFlag($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getValueConfig($path)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $type
     * @return bool|\Magento\Framework\DataObject[]
     */
    public function getReviewsCollectionByType($type = 'helpful')
    {
        if ($type == 'helpful') {
            $collection = $this->_reviewsColFactory->create()->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product',
                $this->getProduct()->getId()
            );
            $collection->getSelect()->joinLeft(
                ['lof_customize' => $this->_reviewsCollection->getTable('lof_review_customize')],
                'main_table.review_id = lof_customize.review_id',
                ['count_helpful']
            );
            $items = $collection->setOrder(
                'count_helpful',
                'DESC'
            )->addRateVotes()->getItems();

            return $items;

        } elseif ($type == 'rating') {
            $collection = $this->_reviewsColFactory->create()->addStatusFilter(
                \Magento\Review\Model\Review::STATUS_APPROVED
            )->addEntityFilter(
                'product',
                $this->getProduct()->getId()
            );
            $collection->getSelect()->joinLeft(
                ['lof_customize' => $this->_reviewsCollection->getTable('lof_review_customize')],
                'main_table.review_id = lof_customize.review_id',
                ['average']
            );
            $items = $collection->setOrder(
                'average',
                'DESC'
            )->addRateVotes()->getItems();

            return $items;

        } elseif ($type == 'default') {
            return $this->_reviewsCollection->getItems();
        } else {
            return false;
        }
    }

    /**
     * Get URL for ajax call
     *
     * @return string
     */
    public function getProductReviewUrl()
    {
        return $this->getUrl(
            'review/product/listAjax',
            [
                '_secure' => $this->getRequest()->isSecure(),
                'id' => $this->getProductId()
            ]
        );
    }
}
