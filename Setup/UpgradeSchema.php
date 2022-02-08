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

namespace Lof\ProductReviews\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        //Update for version 1.0.5
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $table = $installer->getTable('lof_review_customize');
            $replyTable = $installer->getTable('lof_review_reply');

            $installer->getConnection()->addColumn(
                $table,
                'email_address',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Email Address'
                ]
            );

            $installer->getConnection()->addColumn(
                $table,
                'avatar_image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 150,
                    'nullable' => true,
                    'comment' => 'Avatar Image'
                ]
            );

            $installer->getConnection()->addColumn(
                $table,
                'avatar_url',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 200,
                    'nullable' => true,
                    'comment' => 'Avatar Image From Web Url'
                ]
            );

            $installer->getConnection()->addColumn(
                $replyTable,
                'user_name',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'User Name'
                ]
            );
            $installer->getConnection()->addColumn(
                $replyTable,
                'website',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Website Url'
                ]
            );
            $installer->getConnection()->addColumn(
                $replyTable,
                'email_address',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Email Address'
                ]
            );

            $installer->getConnection()->addColumn(
                $replyTable,
                'avatar_url',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Email Address'
                ]
            );

            $installer->getConnection()->addColumn(
                $replyTable,
                'admin_user_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 12,
                    'nullable' => true,
                    'comment' => 'Admin User Id'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $replyTable = $installer->getTable('lof_review_reply');

            $installer->getConnection()->addColumn(
                $replyTable,
                'parent_reply_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                    'length' => 20,
                    'unsigned' => true,
                    'nullable' => true,
                    'comment' => 'Parent Id'
                ]
            );

            $installer->getConnection()->addColumn(
                $replyTable,
                'status',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 4,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'Status: 0 - disabled, 1 - enabled'
                ]
            );
        }
        $installer->endSetup();
    }
}
