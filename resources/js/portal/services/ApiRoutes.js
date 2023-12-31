const ApiVersion = "/api/v1.0";
const ApiRoutes = {
    // Profile
    profile: ApiVersion + '/profile/get',
    updateProfile: ApiVersion + '/profile/update',
    changePassword: ApiVersion + '/profile/update/password',
    profile_logout: ApiVersion + '/profile/logout',

    // Client
    clientCreate: ApiVersion + '/client/save',
    clientList: ApiVersion + '/client/list',
    clientSingle: ApiVersion + '/client/single',
    clientStatus: ApiVersion + '/client/update/status',
    clientUpdate: ApiVersion + '/client/update',
    clientDelete: ApiVersion + '/client/delete',

    // Payee
    payeeCreate: ApiVersion + '/payee/save',
    payeeList: ApiVersion + '/payee/list',
    payeeSingle: ApiVersion + '/payee/single',
    payeeStatus: ApiVersion + '/payee/update/status',
    payeeUpdate: ApiVersion + '/payee/update',
    payeeDelete: ApiVersion + '/payee/delete',

    // Category
    categoryCreate: ApiVersion + '/category/save',
    categoryList: ApiVersion + '/category/list',
    categorySingle: ApiVersion + '/category/single',
    categoryStatus: ApiVersion + '/category/update/status',
    categoryUpdate: ApiVersion + '/category/update',
    categoryDelete: ApiVersion + '/category/delete',

    // Invoice
    invoiceCreate: ApiVersion + '/invoice/save',
    invoiceUpdate: ApiVersion + '/invoice/update',
    invoiceList: ApiVersion + '/invoice/list',
    invoiceSingle: ApiVersion + '/invoice/single',
    invoicePublicView: ApiVersion + '/invoice/public/view',
    invoiceStatusList: ApiVersion + '/invoice/get/status',
    invoiceRecurring: ApiVersion + '/invoice/get/recurring',
    invoiceNumber: ApiVersion + '/invoice/get/number',
    invoiceActivity: ApiVersion + '/invoice/update/activity',
    invoiceDelete: ApiVersion + '/invoice/delete',
    invoiceDownload: ApiVersion + '/invoice/download',
    invoiceShare: ApiVersion + '/invoice/share',
    invoiceQRCode: ApiVersion + '/invoice/generate/qrcode',
    invoiceStatusUpdate: ApiVersion + '/invoice/update/status',

    // Recurring Invoice
    recurringInvoiceList: ApiVersion + '/recurring_invoice/list',
    recurringInvoiceCreate: ApiVersion + '/recurring_invoice/save',
    recurringInvoiceUpdate: ApiVersion + '/recurring_invoice/update',
    recurringInvoiceDelete: ApiVersion + '/recurring_invoice/delete',
    recurringInvoiceSingle: ApiVersion + '/recurring_invoice/single',
    recurringInvoiceFrequency: ApiVersion + '/recurring_invoice/get/frequency',

    // dashboard
    dashboardCount: ApiVersion + '/invoice/dashboard/count',
    dashboardMonth: ApiVersion + '/invoice/dashboard/chart/month',
    dashboardStatus: ApiVersion + '/invoice/dashboard/chart/status',
    dashboardCategory: ApiVersion + '/invoice/dashboard/chart/category',

    // User Logs
    userLogsList: ApiVersion + '/user/log/list',
    userLogsTypes: ApiVersion + '/user/log/get/type',

};
export default ApiRoutes;
