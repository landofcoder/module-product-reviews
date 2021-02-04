define([
    'Magento_Ui/js/grid/columns/column',
    'jquery',
    'mage/template',
    'Magento_Ui/js/modal/modal'
], function (Column, $, mageTemplate) {
    'use strict';
 
    return Column.extend({
        defaults: {
            bodyTmpl: 'ui/grid/cells/html',
            fieldClass: {
                'data-grid-html-cell': true
            }
        },
        gethtml: function (row) {
            return row[this.index + '_html'];
        },
        getFormaction: function (row) {
            return row[this.index + '_formaction'];
        },
        getCustomerid: function (row) {
            return row[this.index + '_customerid'];
        },
        getLabel: function (row) {
            return row[this.index + '_html']
        }
    });
});