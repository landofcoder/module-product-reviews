<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * http://landofcoder.com/license
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_FlatRateShipping
 * @copyright  Copyright (c) 2017 Landofcoder (http://www.landofcoder.com/)
 * @license    http://www.landofcoder.com/LICENSE-1.0.html
 */

namespace Lof\ProductReviews\Controller\Adminhtml\Import;

use Lof\ProductReviews\Model\CustomReview;
use Lof\ProductReviews\Model\Gallery;
use Magento\Backend\App\Action;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Review\Model\Review;
use Magento\Review\Model\ReviewFactory;

/**
 * Class Save
 * @package Lof\ProductReviews\Controller\Adminhtml\Import
 */
class Save extends \Magento\Backend\App\Action {
    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploader;
    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $_csvReader;
    /**
     * @var ReviewFactory
     */
    protected $reviewFactory;
     /**
      * @var ReviewFactory
      */
     protected $_ratingFactory;
     /**
      * @var CustomerCollectionFactory
      */
     protected $customerCollectionFactory;

    /**
     * @param Action\Context                             $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry                $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        ReviewFactory $reviewFactory,
        UploaderFactory $fileUploader,
        CustomerCollectionFactory $customerCollectionFactory,
        \Magento\Framework\File\Csv $csvReader,
        \Magento\Review\Model\RatingFactory $ratingFactory
    )
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->_fileUploader      = $fileUploader;
        $this->reviewFactory      = $reviewFactory;
        $this->_csvReader         = $csvReader;
        $this->_ratingFactory         = $ratingFactory;
    }

    /**
     * Check for is allowed.
     *
     * @return bool
     */
    public function execute()
    {
        $data           = $this->getRequest()->getPostValue();
        if ($this->getRequest()->isPost()) {
            try {
                $reviewModel = $this->reviewFactory->create();
                if (isset($_FILES['import_file'])) {

                    if ( ! $this->_formKeyValidator->validate($this->getRequest())) {
                        return $this->resultRedirectFactory->create()->setPath('*/*/index');
                    }

                    $uploader = $this->_fileUploader->create(
                        ['fileId' => 'import_file']
                    );

                    $result = $uploader->validateFile();

                    $file          = $result['tmp_name'];
                    $fileNameArray = explode('.', $result['name']);

                    $ext = end($fileNameArray);
                    if ($file != '' && $ext == 'csv') {
                        $csvFileData = $this->_csvReader->getData($file);
                        $count       = 0;
                        foreach ($csvFileData as $key => $rowData) {

                            if (count($rowData) < 12 && $count == 0) {
                                $this->messageManager->addError(__('Csv file is not a valid file!'));

                                return $this->resultRedirectFactory->create()->setPath('*/*/index');
                            }
                            if ($rowData[0] == '' ||
                                $rowData[1] == '' ||
                                $rowData[2] == '' ||
                                $rowData[3] == '' ||
                                $count == 0
                            ) {
                                ++$count;
                                continue;
                            }
                            $temp                  = [];
                            $temp['entity_id']         = $rowData[0];
                            $temp['entity_pk_value']          = $rowData[1];
                            $temp['status_id'] = $rowData[2];
                            $temp['created_at']    = $rowData[3];
                            $temp['customer_id']         = $rowData[4];
                            $temp['title']         = $rowData[5];
                            $temp['detail']         = $rowData[6];
                            $temp['nickname']         = $rowData[7];
                            $temp['store_id']         = $rowData[8];
                            $temp['rating_id']         = $rowData[9];
                            $temp['option_id']         = $rowData[10];
                            $temp['email']             = $rowData[11];

                            $this->addDataToCollection($temp);
                        }
                        if (($count - 1) > 1) {
                            $this->messageManager->addNotice(__('Some rows are not valid!'));
                        }
                        if (($count - 1) <= 1) {
                            $this->messageManager
                                ->addSuccess(
                                    __('Your review detail has been successfully Saved')
                                );
                        }

                        return $this->resultRedirectFactory->create()->setPath('review/product/index');
                    } else {
                        $this->messageManager->addError(__('Please upload Csv file'));
                    }
                } else {
                    $params    = $data;
                    $id        = $this->getRequest()->getParam('review_id');
                    if ($id) {
                        $reviewModel->load($id);

                        if ($id != $reviewModel->getId()) {
                            throw new \Magento\Framework\Exception\LocalizedException(__('The wrong review is specified.'));
                        }
                    }
                    $reviewModel->setData($params);
                    $reviewModel->save();

                    $this->messageManager->addSuccess(__('Your review detail has been successfully Saved'));

                    return $this->resultRedirectFactory->create()->setPath('*/*/*');
                }
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }

        return $this->resultRedirectFactory->create()->setPath('*/*/index');
    }
    public function addDataToCollection($temp)
    {
        $collection = $this->reviewFactory->create()
            ->getCollection()
            ->addFieldToFilter('title', $temp['title'])
            ->addFieldToFilter('detail', $temp['detail']);

        if ($collection->getSize() > 0) {
            foreach ($collection as $data) {
                $dataArray = ['title' => $temp['title'],
                    'detail' => $temp['detail']];
                $data->addData($dataArray)->save();
            }
        } else {
            /** @var CustomReview $customReview */
            /** @var Gallery $reviewGallery */
            $customReview = $this->_objectManager->create(CustomReview::class);
            $reviewModel = $this->reviewFactory->create();
            $customer = $this->customerCollectionFactory->create()
                ->addFieldToFilter('email', $temp['email'])
                ->getFirstItem();
            if($customer->getData()) {
                $customerId = $customer->getData()['entity_id'];
                $temp['customer_id'] = $customerId;
            }
            $reviewModel->setData($temp);
            $reviewModel->setStatusId(Review::STATUS_APPROVED)
                ->setEntityId(1)
                ->setStoreId(1)
                ->setStores(1);

            $reviewModel->save();

            $reviewId = $reviewModel->getId();
            // Save customize review
            $data = [];
            $data['average'] = $customReview->addCountRating($reviewId);
            $data['count_helpful'] = 0;
            $data['total_helpful'] = 0;
            $data['report_abuse'] = 0;
            $data['review_id'] = $reviewId;
            $customReview->setData($data);
            $customReview->save();
            $ratingOptions = array(
                '4' => $temp['option_id'],

            );
            foreach ($ratingOptions as $ratingId => $optionIds) {
                $this->_ratingFactory->create()
                    ->setRatingId($ratingId)
                    ->setReviewId($reviewId)
                    ->addOptionVote($optionIds, $temp['entity_pk_value']);

            }
            $reviewModel->aggregate();
        }
    }
}
