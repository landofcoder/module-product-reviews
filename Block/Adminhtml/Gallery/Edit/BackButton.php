<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Block\Adminhtml\Gallery\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class BackButton
 */
class BackButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
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

        return $this->getUrl('review/product/edit', array('id' => $reviewId));
    }
}
