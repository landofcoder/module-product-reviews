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

/**
 * Created by PhpStorm.
 * User: neverland
 * Date: 2018-11-27
 * Time: 09:50
 */
declare(strict_types=1);

namespace Lof\ProductReviews\Ui\Component\Reminders;

use Magento\Framework\AuthorizationInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\AbstractComponent;

/**
 * Provide validation of allowed massaction for user.
 */
class MassAction extends AbstractComponent
{
    const NAME = 'massaction';

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param AuthorizationInterface $authorization
     * @param ContextInterface $context
     * @param UiComponentInterface[] $components
     * @param array $data
     */
    public function __construct(
        AuthorizationInterface $authorization,
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        $this->authorization = $authorization;
        parent::__construct($context, $components, $data);
    }

    /**
     * @inheritdoc
     */
    public function prepare()
    {
        $config = $this->getConfiguration();

        foreach ($this->getChildComponents() as $actionComponent) {
            $actionType = $actionComponent->getConfiguration()['type'];
            if ($this->isActionAllowed($actionType)) {
                $config['actions'][] = $actionComponent->getConfiguration();
            }
        }
        $origConfig = $this->getConfiguration();
        if ($origConfig !== $config) {
            $config = array_replace_recursive($config, $origConfig);
        }

        $this->setData('config', $config);
        $this->components = [];

        parent::prepare();
    }

    /**
     * @inheritdoc
     */
    public function getComponentName(): string
    {
        return static::NAME;
    }

    /**
     * Check if the given type of action is allowed.
     *
     * @param string $actionType
     * @return bool
     */
    public function isActionAllowed(string $actionType): bool
    {
        $isAllowed = true;
        switch ($actionType) {
            case 'delete':
            case 'status':
                $isAllowed = $this->authorization->isAllowed('Lof_ProductReviews::reminders');
                break;
            case 'sent':
                $isAllowed = $this->authorization->isAllowed('Lof_ProductReviews::sent_reminder');
                break;
            default:
                break;
        }

        return $isAllowed;
    }
}
