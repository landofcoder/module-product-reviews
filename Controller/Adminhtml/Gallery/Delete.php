<?php
/**
 * *
 *  * Landofcoder
 *  *
 *  * NOTICE OF LICENSE
 *  *
 *  * This source file is subject to the Landofcoder.com license that is
 *  * available through the world-wide-web at this URL:
 *  * https://landofcoder.com/license
 *  *
 *  * DISCLAIMER
 *  *
 *  * Do not edit or add to this file if you wish to upgrade this extension to newer
 *  * version in the future.
 *  *
 *  * @category   Landofcoder
 *  * @package    Lof_ProductReviews
 *  * @copyright  Copyright (c) 2020 Landofcoder (https://www.landofcoder.com/)
 *  * @license    https://landofcoder.com/LICENSE-1.0.html
 *
 */

namespace Lof\ProductReviews\Controller\Adminhtml\Gallery;


use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem ;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_file;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \Magento\Framework\Json\DecoderInterface
     */
    protected $_jsonDecoder;

    /**
     * ListView constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Magento\Framework\Filesystem $fileSystem
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Json\DecoderInterface $jsonDecoder
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder
    ) {
        $this->_file = $file;
        $this->_fileSystem = $fileSystem;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_jsonDecoder = $jsonDecoder;
        parent::__construct($context);
    }

    public function execute()
    {
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            // init model and delete
            $model = $this->_objectManager->create(\Lof\ProductReviews\Model\Gallery::class);
            $model->load($id);
            $reviewId =  $model->getReviewId();

            $imageNames = $this->_jsonDecoder->decode($model->getValue());
            foreach ($imageNames as $name) {
                $mediaDirectory = $this->_fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                $mediaRootDir = $mediaDirectory->getAbsolutePath('lof/product_reviews/');

                if( $this->_file->isExists($mediaRootDir .$name) )
                    $this->_file->deleteFile($mediaRootDir .$name);
            }

            $model->setValue($this->_jsonEncoder->encode([]))->save();

            // display success message
            $this->messageManager->addSuccessMessage(__('The gallery has been deleted.'));
            return $resultRedirect->setPath('review/product/edit', ['id' => $reviewId, '_current' => true]);
        }
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Lof_ProductReviews::lof_product_gallery');
    }
}