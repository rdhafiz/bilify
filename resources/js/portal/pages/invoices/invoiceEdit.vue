<template>
    <div class="w-100">
        <div class="container-lg">
            <div class="row justify-content-center res">
                <div class="col-12">
                    <form @submit.prevent="invoiceUpdate">
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="d-flex justify-content-between flex-column flex-lg-row align-items-lg-center">
                                    <div class="h1 mb-2 mb-lg-0 page-title">Invoice Edit</div>
                                    <div class="text-end">
                                        <router-link :to="{name: 'Invoices'}" class="btn btn-danger w-160 me-3">Cancel</router-link>
                                        <button type="submit" class="btn btn-theme w-160" v-if="loading === false"> Save Changes</button>
                                        <button type="button" disabled v-if="loading === true" class="btn btn-theme w-160">
                                            <i class="fa fa-spinner spin" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="cl col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="row mb-4">
                                                    <div class="col-lg-5 col-xl-4">
                                                        <div class="form-group mb-3">
                                                            <div class="btn btn-light border">
                                                                <label for="invoice_no" class="me-2"><strong>Invoice Number: </strong></label>
                                                                <label for="invoice_no" class="text-theme"><strong>{{ formData.invoice_number }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group bg-light border p-3">
                                                            <div class="form-group mb-3">
                                                                <label for="category"><strong>Category</strong></label>
                                                                <select name="category_id" id="category" class="form-select"
                                                                        v-model="formData.category_id">
                                                                    <option value="" disabled>Select</option>
                                                                    <template v-if="categories.length > 0">
                                                                        <option :value="each.id" v-for="(each) in categories">
                                                                            {{ each.name }}
                                                                        </option>
                                                                    </template>
                                                                </select>
                                                                <div class="error-report text-danger "></div>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="currency"><strong>Currency</strong></label>
                                                                <select name="currency" id="currency"
                                                                        class="form-select" v-model="formData.currency">
                                                                    <option value="BDT">BDT</option>
                                                                    <option value="USA">USA</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-xl-4"></div>
                                                    <div class="col-lg-5 col-xl-4">
                                                        <div class="form-group bg-light border p-3">
                                                            <div class="form-group mb-3">
                                                                <label for="invoice_date"><strong>Invoice Date</strong></label>
                                                                <flat-pickr v-model="formData.invoice_date"
                                                                            :config="date_config"
                                                                            class="form-control"
                                                                            name="invoice_date"
                                                                            placeholder="Select date"/>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="invoice_due_date"><strong>Invoice Due
                                                                    Date</strong></label>
                                                                <flat-pickr v-model="formData.invoice_due_date"
                                                                            :config="due_date_config"
                                                                            class="form-control"
                                                                            name="invoice_due_date"
                                                                            placeholder="Select date"/>
                                                            </div>
                                                            <div class="form-group mb-3">
                                                                <label for="invoice_status"><strong>Invoice Status</strong></label>
                                                                <select name="invoice_status" id="invoice_status"
                                                                        class="form-select" v-model="formData.invoice_status">
                                                                    <option :value="each.value" v-for="(each) in status">{{ each.name }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 my-3">
                                                <div class="table-data table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                        <tr>
                                                            <th class="p-0" style="min-width: 160px;">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none" v-model="formData.invoice_item_headings.description">
                                                                        <option value="Services">Services</option>
                                                                        <option value="Products">Products</option>
                                                                        <option value="Description">Description</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th class="p-0" style="min-width: 160px;">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none text-center" v-model="formData.invoice_item_headings.frequency">
                                                                        <option value="Hours">Hours</option>
                                                                        <option value="Days">Days</option>
                                                                        <option value="Months">Months</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th class="p-0" style="min-width: 160px;">
                                                                <div class="form-group">
                                                                    <select class="form-select border-0 shadow-none text-center" v-model="formData.invoice_item_headings.value">
                                                                        <option value="Unit Payment">Unit Payment</option>
                                                                        <option value="Unit Price">Unit Price</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th class="py-0 text-end" style="min-width: 160px;">Total</th>
                                                            <th class="py-0" v-if="formData.invoice_items.length > 1"></th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr v-for="(each, index) in formData.invoice_items">
                                                            <td class="p-0">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control border-0 shadow-none"
                                                                           :name="'invoice_items.' + index + '.description'"
                                                                           v-model="formData.invoice_items[index]['description']">
                                                                    <div class="error-report text-danger "></div>
                                                                </div>
                                                            </td>
                                                            <td class="p-0">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control border-0 shadow-none text-center"
                                                                           :name="'invoice_items.' + index + '.unit_frequency'"
                                                                           v-model="formData.invoice_items[index]['unit_frequency']"
                                                                           @keypress="checkNumber($event)"
                                                                           @keyup="calculateInvoiceItemTotal(index)">
                                                                    <div class="error-report text-danger "></div>
                                                                </div>
                                                            </td>
                                                            <td class="p-0">
                                                                <div class="form-group">
                                                                    <input type="text" class="form-control border-0 shadow-none text-center"
                                                                           :name="'invoice_items.' + index + '.unit_value'"
                                                                           v-model="formData.invoice_items[index]['unit_value']"
                                                                           @keypress="checkNumber($event)"
                                                                           @keyup="calculateInvoiceItemTotal(index)">
                                                                    <div class="error-report text-danger "></div>
                                                                </div>
                                                            </td>
                                                            <td class="py-0 text-end">{{ each.total }}</td>
                                                            <td class="py-0 text-center" v-if="formData.invoice_items.length > 1">
                                                                <button type="button" class="btn btn-danger btn-sm px-1 py-0"
                                                                        @click="formData.invoice_items.splice(index, 1);calculateSubtotal();">
                                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <button type="button" class="btn btn-theme btn-sm" @click="insertData">
                                                    <i class="fa fa-plus" aria-hidden="true"></i> Add New
                                                </button>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="row my-3">
                                                    <div class="col-lg-5 col-xl-4">
                                                        <div class="form-group">
                                                            <label for="notes"><strong>Notes</strong></label>
                                                            <textarea name="note" rows="7"
                                                                      class="form-control" v-model="formData.note"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-2 col-xl-4">&nbsp;</div>
                                                    <div class="col-lg-5 col-xl-4">
                                                        <table class="table">
                                                            <tbody>
                                                            <tr>
                                                                <td class="text-end"><strong>Subtotal:</strong></td>
                                                                <td class="text-end">{{ this.subTotal }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="d-flex justify-content-end align-items-center">
                                                                    <strong>Tax:</strong>
                                                                    <select class="form-select ms-2 border-0 p-0" style="width: 60px;" v-model="formData.tax" @change="checkTax">
                                                                        <option value="0">0%</option>
                                                                        <option value="5">5%</option>
                                                                        <option value="10">10%</option>
                                                                        <option value="15">15%</option>
                                                                        <option value="20">20%</option>
                                                                        <option value="25">25%</option>
                                                                    </select>
                                                                </td>
                                                                <td class="text-end">{{ this.tax }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-end"><strong>Total:</strong></td>
                                                                <td class="text-end">{{ this.total }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

import apiService from "../../services/ApiService";
import apiRoutes from "../../services/ApiRoutes";

import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';

import {createToaster} from "@meforma/vue-toaster";

const toaster = createToaster({
    position: 'top-right'
});

export default {
    components: {createToaster, flatPickr},
    data() {
        return {
            loading: false,
            invoice_type: 1,
            formData: {
                client_id: '',
                payee_id: '',
                category_id: '',
                invoice_no: '',
                invoice_number: '',
                invoice_due_date: '',
                invoice_date: '',
                invoice_status: '',
                sub_total: '',
                tax: '',
                discount: '',
                bonus: '',
                note: '',
                currency: 'BDT',
                invoice_item_headings: {
                    description: 'Services',
                    frequency: 'Hours',
                    value: 'Unit Payment',
                },
                invoice_items: [{
                    description: '',
                    unit_frequency: '',
                    unit_value: '',
                    total: 0,
                }],
            },
            date_config: {
                altFormat: "F j, Y",
                altInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: true
            },
            due_date_config: {
                altFormat: "F j, Y",
                altInput: true,
                dateFormat: 'Y-m-d',
                disableMobile: true
            },
            payees: [],
            clients: [],
            categories: [],
            status: [],
            subTotal: 0,
            tax: 0,
            total: 0
        }
    },
    methods: {

        /*Get Payees*/
        getPayees() {
            apiService.POST(apiRoutes.payeeList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.payees = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Clients*/
        getClients() {
            apiService.POST(apiRoutes.clientList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.clients = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Categories*/
        getCategories() {
            apiService.POST(apiRoutes.categoryList, {pagination: false}, (res) => {
                if (res.status === 200) {
                    this.categories = res.data;
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Get Status*/
        getStatus() {
            apiService.GET(apiRoutes.invoiceStatusList, (res) => {
                this.status = res;
            })
        },

        /*Get Invoice Number*/
        getInvoiceNumber(type, id) {
            const param = type == 'client' ? {client_id: id} : {payee_id: id};
            apiService.POST(apiRoutes.invoiceNumber, param, (res) => {
                this.formData.invoice_no = res.invoice_number;
            })
        },

        /*Get Invoice Data*/
        getInvoice(id) {
            apiService.POST(apiRoutes.invoiceSingle, {id}, (res) => {
                if (res.status === 200) {
                    this.formData = {
                        ...res.data,
                        invoice_status: res.data.invoice_status,
                        invoice_item_headings: res.data.invoice_item_headings_formatted
                    };
                    this.subTotal = res.data.sub_total,
                        this.total = res.data.sub_total
                    this.invoice_type = res.data.client ? 1 : 2;
                    this.calculateTotal();
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*Create Invoice*/
        invoiceUpdate() {
            apiService.ClearErrorHandler();
            this.loading = true;
            apiService.POST(apiRoutes.invoiceUpdate, this.formData, (res) => {
                this.loading = false;
                if (res.status === 200) {
                    toaster.info(res.message);
                    const route = this.isRecurring ? 'RecurringInvoices' : 'Invoices';
                    this.$router.push({name: route})
                } else {
                    apiService.ErrorHandler(res.errors)
                }
            })
        },

        /*change invoice type*/
        changeInvoiceType() {
            this.formData.invoice_no = '';
            this.formData.client_id = '';
            this.formData.payee_id = '';
            this.invoice_number = '';
            this.formData.discount = '';
            this.formData.bonus = '';
            this.calculateTotal();
        },

        /*insert table data*/
        insertData() {
            this.formData.invoice_items.push({
                description: '',
                unit_frequency: '',
                unit_value: '',
                total: '',
            })
        },

        /* Check Tax */
        checkTax() {
            this.calculateTotal();
        },

        /*calculate invoice item total*/
        calculateInvoiceItemTotal(index) {
            const total = parseFloat(this.formData.invoice_items[index]['unit_frequency']) * parseFloat(this.formData.invoice_items[index]['unit_value']);
            this.formData.invoice_items[index]['total'] = isNaN(total) ? 0 : total.toFixed(2);
            this.calculateSubtotal();
        },

        /*calculate total*/
        calculateTotal() {
            if (this.formData.tax == '' || this.formData.tax == null) {
                this.tax = 0;
            } else {
                this.tax = this.subTotal * (parseFloat(this.formData.tax) / 100);
            }
            const discount = isNaN(parseFloat(this.formData.discount)) ? 0 : parseFloat(this.formData.discount);
            const bonus = isNaN(parseFloat(this.formData.bonus)) ? 0 : parseFloat(this.formData.bonus);
            this.total = (parseFloat(this.subTotal) - this.tax - discount + bonus).toFixed(2);
            this.total = isNaN(this.total) ? 0 : this.total
            return this.total;
        },

        /*calculate subtotal*/
        calculateSubtotal() {
            console.log(this.formData.invoice_items)
            this.subTotal = this.formData.invoice_items.reduce((prev, current) => (prev + parseFloat(current.total)), 0).toFixed(2);
            this.calculateTotal();
        },

        /*number validation*/
        checkNumber(evt) {
            var theEvent = evt || window.event;

            // Handle paste
            if (theEvent.type === 'paste') {
                // @ts-ignore
                key = event.clipboardData.getData('text/plain');
            } else {
                // Handle key press
                var key = theEvent.keyCode || theEvent.which;
                key = String.fromCharCode(key);
            }
            var regex = /^\d*\.?\d*$/;
            if (!regex.test(key)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault) theEvent.preventDefault();
            }
        },
    },
    mounted() {
        this.getPayees();
        this.getClients();
        this.getCategories();
        this.getStatus();
        if (this.$route.params) {
            this.getInvoice(this.$route.params.id);
        }
    },
    created() {
        window.scroll(0, 0);
    }
}
</script>
