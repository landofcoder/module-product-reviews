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
namespace Lof\ProductReviews\Block\Adminhtml\Gallery\Edit;

use Magento\Backend\Block\Widget\Context;
use Lof\ProductReviews\Api\GalleryRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

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
