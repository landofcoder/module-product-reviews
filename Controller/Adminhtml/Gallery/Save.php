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
 * @copyright  Copyright (c) 2021 Hgati (https://www.hgati.com/)
 * @license    https://hgati.com/terms
 */

namespace Hgati\ProductReviews\Controller\Adminhtml\Gallery;

use Magento\Backend\App\Action;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Hgati\ProductReviews\Model\Gallery
     */
    private $galleryFactory;

    /**
     * @var \Hgati\ProductReviews\Api\GalleryRepositoryInterface
     */
    private $galleryRepository;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param \Hgati\ProductReviews\Model\GalleryFactory $galleryFactory
     * @param \Hgati\ProductReviews\Api\GalleryRepositoryInterface $galleryRepository
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     */
    public function __construct(
        Action\Context $context,
        \Hgati\ProductReviews\Model\GalleryFactory $galleryFactory,
        \Hgati\ProductReviews\Api\GalleryRepositoryInterface $galleryRepository,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder
    ) {
        $this->_jsonEncoder = $jsonEncoder;
        $this->galleryFactory = $galleryFactory;
        $this->galleryRepository = $galleryRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $imageName = [];
        foreach ($data['value']['media_gallery']['images'] as $detail) {
            $imageName[] = chop($detail['file'], '.tmp');
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            /** @var \Hgati\ProductReviews\Model\Gallery $model */
            $model = $this->galleryFactory->create();
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                try {
                    $model = $this->galleryRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This gallery no longer exists.'));
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
            }

            $galleryData = [
                'id' => $data['id'],
                'review_id' => $data['review_id'],
                'label' => $data['label'],
                'value' => $this->_jsonEncoder->encode($imageName),
                'status' => $data['status']
            ];

            $model->setData($galleryData);
            $model->save();

            $this->messageManager->addSuccessMessage(__('You saved the gallery.'));
            return $resultRedirect->setPath('review/product/edit', ['id' => $data['review_id'], '_current' => true]);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Hgati_ProductReviews::hgati_product_gallery');
    }
}
