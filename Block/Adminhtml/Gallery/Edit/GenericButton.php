<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Block\Adminhtml\Gallery\Edit;

use Magento\Backend\Block\Widget\Context;
use Lof\ProductReviews\Api\GalleryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var GalleryRepositoryInterface
     */
    protected $galleryRepository;

    /**
     * @param Context $context
     * @param GalleryRepositoryInterface $galleryRepository
     */
    public function __construct(
        Context $context,
        GalleryRepositoryInterface $galleryRepository
    ) {
        $this->context = $context;
        $this->galleryRepository = $galleryRepository;
    }

    /**
     * Return Product Review Gallery ID
     *
     * @return int|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getGalleryId()
    {
        try {
            return $this->galleryRepository->getById(
                $this->context->getRequest()->getParam('id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
