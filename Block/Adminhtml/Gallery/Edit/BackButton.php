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

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return mixed|array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back To Review Detail'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $galleryModel = $objectManager->create(\Lof\ProductReviews\Model\Gallery::class);
        $reviewId = $galleryModel->load($this->getGalleryId())->getReviewId();

        return $this->getUrl('review/product/edit', ['id' => $reviewId]);
    }
}
