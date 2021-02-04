<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Api\Data;

//use Lof\ProductReviews\Model\Gallery;

/**
 * Product Reviews Gallery interface.
 * @api
 * @since 100.0.2
 */
interface GalleryInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const GALLERY_ID    = 'id';
    const IDENTIFIER    = 'identifier';
    const REVIEW_ID     = 'review_id';
    const LABEL         = 'label';
    const VALUE         = 'value';
    const STATUS        = 'status';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Get Review Id
     *
     * @return int\null
     */
    public function getReviewID();

    /**
     * Get label
     *
     * @return string|null
     */
    public function getLabel();

    /**
     * Get value
     *
     * @return array|null
     */
    public function getValue();

    /**
     * Status
     *
     * @return bool|null
     */
    public function getStatus();

    /**
     * Set ID
     *
     * @param int $id
     * @return GalleryInterface
     */
    public function setId($id);

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return GalleryInterface
     */
    public function setIdentifier($identifier);

    /**
     * Set review id
     *
     * @param string $reviewId
     * @return GalleryInterface
     */
    public function setReviewId($reviewId);

    /**
     * Set label
     *
     * @param string $label
     * @return GalleryInterface
     */
    public function setLabel($label);

    /**
     * Set value
     *
     * @param array $value
     * @return GalleryInterface
     */
    public function setValue($value);

    /**
     * Set Status
     *
     * @param bool|int $status
     * @return GalleryInterface
     */
    public function setStatus($status);
}
