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

namespace Hgati\ProductReviews\Setup;

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
            $table = $installer->getTable('hgati_review_customize');
            $replyTable = $installer->getTable('hgati_review_reply');

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
            $replyTable = $installer->getTable('hgati_review_reply');

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
                    'default' => 1,
                    'comment' => 'Status: 0 - disabled, 1 - enabled'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $replyTable = $installer->getTable('hgati_review_reply');
            $installer->getConnection()->addColumn(
                $replyTable,
                'reply_customer_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'nullable' => true,
                    'comment' => 'Reply By Customer Id'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $customizeTable = $installer->getTable('hgati_review_customize');
            $reviewSummaryTable = $installer->getTable('review_entity_summary');
            $installer->getConnection()->addColumn(
                $customizeTable,
                'verified_buyer',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 4,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'Is verified buyer 0 - no, 1 - yes'
                ]
            );
            $installer->getConnection()->addColumn(
                $customizeTable,
                'is_recommended',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'length' => 4,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'Is recommended 0 - no, 1 - yes'
                ]
            );

            $installer->getConnection()->addColumn(
                $customizeTable,
                'country',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 50,
                    'nullable' => true,
                    'comment' => 'country code'
                ]
            );

            $installer->getConnection()->addColumn(
                $customizeTable,
                'answer',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'admin answer here'
                ]
            );

            $installer->getConnection()->addColumn(
                $reviewSummaryTable,
                'rate_one',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'rate one'
                ]
            );
            $installer->getConnection()->addColumn(
                $reviewSummaryTable,
                'rate_two',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'rate two'
                ]
            );
            $installer->getConnection()->addColumn(
                $reviewSummaryTable,
                'rate_three',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'rate three'
                ]
            );
            $installer->getConnection()->addColumn(
                $reviewSummaryTable,
                'rate_four',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'rate four'
                ]
            );
            $installer->getConnection()->addColumn(
                $reviewSummaryTable,
                'rate_five',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'nullable' => true,
                    'default' => 0,
                    'comment' => 'rate five'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.6', '<')) {
            $replyTable = $installer->getTable('hgati_review_reply');
            $installer->getConnection()->addColumn(
                $replyTable,
                'updated_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'nullable' => true,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE,
                    'comment' => 'Updated time',
                ]
            );
        }
        $installer->endSetup();
    }
}
