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
namespace Lof\ProductReviews\Block\Adminhtml\Gallery\Helper\Form;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Registry;

class Gallery extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * Gallery field name suffix
     *
     * @var string
     */
    protected $fieldNameSuffix = 'value';

    /**
     * Gallery html id
     *
     * @var string
     */
    protected $htmlId = 'media_gallery';

    /**
     * Gallery name
     *
     * @var string
     */
    protected $name = 'value[media_gallery]';

    /**
     * Html id for data scope
     *
     * @var string
     */
    protected $image = 'image';

    /**
     * @var string
     */
    protected $formName = 'lof_product_reviews_gallery_form';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\Data\Form
     */
    protected $form;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonDecoder;

    /**
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Registry $registry
     * @param \Magento\Framework\Data\Form $form
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form $form,
        \Magento\Framework\Json\DecoderInterface $jsonDecoder,
        $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->form = $form;
        $this->_jsonDecoder = $jsonDecoder;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        $html = $this->getContentHtml();
        return $html;
    }

    /**
     * Get product images
     *
     * @return array|null
     */
    public function getImages()
    {
        $data = $this->getDataObject()->getData() ?: null;
        $imageName = $this->_jsonDecoder->decode($data['value']);

        $images = [];
        foreach ($imageName as $key => $value) {
            $images['images'][$key] = [
                'value_id'   => $key,
                'file'       => $value,
                'media_type' => 'image',
                'entity_id'  => $data['id'],
                'label'      => $data['label'],
                'position'   => $key ++
            ];
            $images['values'] = [];
        }

        return $images;
    }

    /**
     * Get value for given type.
     *
     * @param string $type
     * @return string|null
     */
    public function getImageValue(string $type)
    {
        return $type;
    }

    /**
     * Prepares content block
     *
     * @return string
     */
    public function getContentHtml()
    {
        /* @var $content \Magento\Catalog\Block\Adminhtml\Product\Helper\Form\Gallery\Content */
        $content = $this->getChildBlock('content');
        $content->setId($this->getHtmlId() . '_content')->setElement($this);
        $content->setFormName($this->formName);
        $galleryJs = $content->getJsObjectName();
        $content->getUploader()->getConfig()->setMegiaGallery($galleryJs);
        return $content->toHtml();
    }

    /**
     * @return string
     */
    protected function getHtmlId()
    {
        return $this->htmlId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFieldNameSuffix()
    {
        return $this->fieldNameSuffix;
    }

    /**
     * @return string
     */
    public function getDataScopeHtmlId()
    {
        return $this->image;
    }

    /**
     * Check "Use default" checkbox display availability
     *
     * @param Attribute $attribute
     * @return bool
     */
    public function canDisplayUseDefault($attribute)
    {
        if (!$attribute->isScopeGlobal() && $this->getDataObject()->getStoreId()) {
            return true;
        }

        return false;
    }

    /**
     * Check default value usage fact
     *
     * @param Attribute $attribute
     * @return bool
     */
    public function usedDefault($attribute)
    {
        $attributeCode = $attribute->getAttributeCode();
        $defaultValue = $this->getDataObject()->getAttributeDefaultValue($attributeCode);

        if (!$this->getDataObject()->getExistsStoreValueFlag($attributeCode)) {
            return true;
        } elseif ($this->getValue() == $defaultValue &&
            $this->getDataObject()->getStoreId() != $this->_getDefaultStoreId()
        ) {
            return false;
        }
        if ($defaultValue === false && !$attribute->getIsRequired() && $this->getValue()) {
            return false;
        }
        return $defaultValue === false;
    }

    /**
     * @param $attribute
     * @return string
     */
    public function getScopeLabel($attribute)
    {
        $html = '';
        if ($this->storeManager->isSingleStoreMode()) {
            return $html;
        }

        if ($attribute->isScopeGlobal()) {
            $html .= __('[GLOBAL]');
        } elseif ($attribute->isScopeWebsite()) {
            $html .= __('[WEBSITE]');
        } elseif ($attribute->isScopeStore()) {
            $html .= __('[STORE VIEW]');
        }
        return $html;
    }

    /**
     * Retrieve data object related with form
     *
     * @return Gallery Data
     */
    public function getDataObject()
    {
        $galleryId = $this->getRequest()->getParam('id');
        $gallery = ObjectManager::getInstance()->get(\Lof\ProductReviews\Model\Gallery::class)->load($galleryId);

        return $gallery;
    }

    /**
     * Retrieve attribute field name
     *
     *
     * @param Attribute $attribute
     * @return string
     */
    public function getAttributeFieldName($attribute)
    {
        $name = $attribute->getAttributeCode();
        if ($suffix = $this->getFieldNameSuffix()) {
            $name = $this->form->addSuffixToName($name, $suffix);
        }
        return $name;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->getElementHtml();
    }

    /**
     * Default sore ID getter
     *
     * @return integer
     */
    protected function _getDefaultStoreId()
    {
        return \Magento\Store\Model\Store::DEFAULT_STORE_ID;
    }
}
