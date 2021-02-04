<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Lof\ProductReviews\Model\ResourceModel;

use Magento\Store\Model\Store;

/**
 * Catalog product media gallery resource model.
 *
 * @api
 * @since 101.0.0
 */
class Gallery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const GALLERY_TABLE = 'lof_product_reviews_gallery';

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    protected function _construct()
    {
        $this->_init(self::GALLERY_TABLE, 'id');
    }


}
