/**
 * Receiving System Tab Refresh Functions
 * These functions help refresh the different tabs in the receiving system
 */

// Function to refresh the purchase orders tab
function refreshPurchaseOrdersTab() {
    console.log('Refreshing Purchase Orders tab...');
    // This function should be implemented in your main receiving page
    // to refresh the pending POs list
    if (typeof loadPendingPOs === 'function') {
        loadPendingPOs();
    }
}

// Function to refresh the goods receipt tab
function refreshGoodsReceiptTab() {
    console.log('Refreshing Goods Receipt tab...');
    // This function should be implemented in your main receiving page
    // to refresh the goods receipts list
    if (typeof loadGoodsReceipts === 'function') {
        loadGoodsReceipts();
    }
}

// Function to refresh the suppliers return tab
function refreshSuppliersReturnTab() {
    console.log('Refreshing Suppliers Return tab...');
    // This function should be implemented in your main receiving page
    // to refresh the supplier returns list
    if (typeof loadSupplierReturns === 'function') {
        loadSupplierReturns();
    }
}

// Function to refresh all tabs
function refreshAllReceivingTabs() {
    refreshPurchaseOrdersTab();
    refreshGoodsReceiptTab();
    refreshSuppliersReturnTab();
}

// Export functions for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        refreshPurchaseOrdersTab,
        refreshGoodsReceiptTab,
        refreshSuppliersReturnTab,
        refreshAllReceivingTabs
    };
}
