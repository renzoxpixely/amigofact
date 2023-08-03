<template>
    <div class="card mb-0 pt-2 pt-md-0 negrita">
        <div class="card-header bg-info">
            <h3 class="my-0">Editar importación</h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off"
              @submit.prevent="submit">
            <div class="form-body">
                <div class="row mb-2">
                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.import_order_task}"
                             class="form-group">
                            <label class="control-label">Import order task</label>
                            <el-input v-model="form.import_order_task"
                            ></el-input>

                            <small v-if="errors.import_order_task"
                                   class="form-control-feedback"
                                   v-text="errors.import_order_task[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.number_proforma}"
                             class="form-group">
                            <label class="control-label">Número Proforma</label>
                            <el-input v-model="form.number_proforma"
                            ></el-input>

                            <small v-if="errors.number_proforma"
                                   class="form-control-feedback"
                                   v-text="errors.number_proforma[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.amount_proforma}"
                             class="form-group">
                            <label class="control-label">Monto Proforma</label>
                            <el-input v-model="form.amount_proforma"
                            ></el-input>

                            <small v-if="errors.amount_proforma"
                                   class="form-control-feedback"
                                   v-text="errors.amount_proforma[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.date_periodo}">
                            <label class="control-label">Periodo</label>
                            <el-date-picker v-model="form.date_periodo" type="month" value-format="yyyy-MM-dd" format="MM/yyyy" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.date_periodo" v-text="errors.date_periodo[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.date_of_issue}"
                             class="form-group">
                            <label class="control-label">Fec Emisión</label>
                            <el-date-picker v-model="form.date_of_issue"
                                            :clearable="false"
                                            type="date"
                                            value-format="yyyy-MM-dd"
                                            :readonly="readonly_date_of_due"
                                            @change="changeDateOfIssue"></el-date-picker>
                            <small v-if="errors.date_of_issue"
                                   class="form-control-feedback"
                                   v-text="errors.date_of_issue[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group" :class="{'has-danger': errors.arrival_date}">
                            <label class="control-label">Fecha de Arribo</label>
                            <el-date-picker v-model="form.arrival_date"
                                            :clearable="false"
                                            type="date"
                                            value-format="yyyy-MM-dd"></el-date-picker>
                            <small class="form-control-feedback" v-if="errors.arrival_date" v-text="errors.arrival_date[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.sale_reference}"
                             class="form-group">
                            <label class="control-label">Ref. liquidation</label>
                            <el-input v-model="form.sale_reference"
                            ></el-input>

                            <small v-if="errors.sale_reference"
                                   class="form-control-feedback"
                                   v-text="errors.sale_reference[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.ref_customs_agent}"
                             class="form-group">
                            <label class="control-label">Ref. Agente Aduanas</label>
                            <el-input v-model="form.ref_customs_agent"
                            ></el-input>

                            <small v-if="errors.ref_customs_agent"
                                   class="form-control-feedback"
                                   v-text="errors.ref_customs_agent[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.transport_type}"
                             class="form-group">
                            <label class="control-label">Conocimiento de embarque</label>
                            <el-select
                                v-model="form.transport_type"
                                placeholder="Embarques"
                            >
                                <el-option label="BL" value="BL"></el-option>
                                <el-option label="AWB" value="AWB"></el-option>
                            </el-select>

                            <small v-if="errors.transport_type"
                                   class="form-control-feedback"
                                   v-text="errors.transport_type[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.transport_code}"
                             class="form-group">
                            <label class="control-label">Nro. de conocimiento</label>
                            <el-input
                                v-model="form.transport_code"
                            >
                            </el-input>

                            <small v-if="errors.transport_code"
                                   class="form-control-feedback"
                                   v-text="errors.transport_code[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.dam}"
                             class="form-group">
                            <label class="control-label">DAM</label>
                            <el-input
                                v-model="form.dam"
                            >
                            </el-input>

                            <small v-if="errors.dam"
                                   class="form-control-feedback"
                                   v-text="errors.dam[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-lg-3">
                        <div :class="{'has-danger': errors.supplier_id}"
                             class="form-group">
                            <label class="control-label">
                                Proveedor
                                <a href="#"
                                   @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                            </label>
                            <el-select ref="select_person"
                                       v-model="form.supplier_id"
                                       filterable
                                       @change="changeSupplier"
                                       @keyup.native="keyupSupplier"
                                       @keyup.enter.native="keyupEnterSupplier">
                                <el-option v-for="option in suppliers"
                                           :key="option.id"
                                           :label="option.description"
                                           :value="option.id"></el-option>
                            </el-select>
                            <small v-if="errors.supplier_id"
                                   class="form-control-feedback"
                                   v-text="errors.supplier_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div :class="{'has-danger': errors.document_type_id}"
                             class="form-group">
                            <label class="control-label">Tipo comprobante</label>
                            <el-select disabled :value="'LIQUIDACIÓN DE COMPRA'">
                            </el-select>
                            <small v-if="errors.document_type_id"
                                   class="form-control-feedback"
                                   v-text="errors.document_type_id[0]"></small>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div :class="{'has-danger': errors.comments}"
                             class="form-group">
                            <label class="control-label">Comentarios</label>
                            <textarea
                                rows="1"
                                id="comentarios"
                                class="form-control"
                                v-model="form.comments"
                                :class="{ 'is-invalid': errors.comments }"
                            >
                                    </textarea>
                            <small v-if="errors.comments"
                                   class="form-control-feedback"
                                   v-text="errors.comments[0]"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2">
                        <div :class="{'has-danger': errors.uploadedFiles}"
                             class="form-group">
                            <label class="control-label">Adjuntar documentos</label>
                            <el-upload
                                ref="upload"
                                action=""
                                :auto-upload="false"
                                :fileList="fileList"
                                :onChange="addFiles"
                                :on-remove="checkRemove"
                                multiple
                            >
                                <el-button size="small" type="primary">Agregar documentos</el-button>
                            </el-upload>

                            <small v-if="errors.uploadedFiles"
                                   class="form-control-feedback"
                                   v-text="errors.uploadedFiles[0]"></small>
                        </div>
                    </div>
                    <div class="col-lg-2">

                    </div>

                    <!--                    TODO revisar con Edwin, no hay una mejor forma? -->
                    <!--                    <div class="col-lg-2">-->
                    <!--                        <button class="btn waves-effect waves-light btn-success"-->
                    <!--                                type="button"-->
                    <!--                                @click.prevent="exportSeries">+ Agregar Costos de Importación-->
                    <!--                        </button>-->
                    <!--                    </div>-->
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-6 d-flex align-items-end mt-4">
                        <div class="form-group">
                            <button v-if="form.import_order_task && form.import_order_task.length"
                                    class="btn waves-effect waves-light btn-primary"
                                    type="button"
                                    @click.prevent="clickAddInvoice">+ Agregar Invoice
                            </button>

                            <button class="btn waves-effect waves-light btn-primary"
                                    type="button"
                                    @click.prevent="clickShowImportCosts">+ Agregar Costos de Importación
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row" v-if="addedInvoices.length">
                    <div class="col-md-12 mt-2">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>N° de Comprobante</th>
                                    <th>Concepto Comprobante</th>
                                    <th>Título</th>
                                    <th class="text-right">Cantidad</th>
                                    <th class="text-right">Valor Total</th>
                                    <th class="text-right">Descuento</th>
                                    <th class="text-right">Cargo</th>
                                    <th class="text-right">Total</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in addedInvoices"
                                    :key="index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ row.series === 'n.a.' ? '' : `${row.series}-` }}{{ row.number}}</td>
                                    <td>{{ getInvoiceType(row.invoice_type) }}</td>
                                    <td>{{ row.title}}</td>
                                    <td class="text-right">{{ summedInvoiceItems(row) }}</td>
                                    <td class="text-right">{{ row.total_value }}
                                    </td>
                                    <td class="text-right">{{ row.total_discount }}</td>
                                    <td class="text-right">{{ row.total_charge }}</td>
                                    <td class="text-right">{{row.currency_type_id === 'PEN' ? 'S/' : '$'}}{{ row.total }}</td>
                                    <td class="text-right">
                                        <a :href="`/purchases/edit/${row.id}`" type="button" class="btn waves-effect waves-light btn-xs btn-info">Editar</a>
                                        <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                type="button"
                                                @click.prevent="deleteInvoice(row.id)"><i class="fa fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-12 d-flex justify-content-end mt-2" v-if="addedInvoices.length">
                        <div class="col-md-6">
                            <table class="table">
                            <thead>
                                <tr>
                                    <th>Concepto</th>
                                    <th>$</th>
                                    <th>S/</th>
                                </tr>
                                <tr>
                                    <td>Total EXW</td>
                                    <td>{{ invoicesTotalDol }}</td>
                                    <td>{{ invoicesTotalSol }}</td>
                                </tr>
                                <tr>
                                    <td>Total Flete</td>
                                    <td>{{ invoicesLadingDol }}</td>
                                    <td>{{ invoicesLadingSol }}</td>
                                </tr>
                                <tr>
                                    <td>Total Seguro</td>
                                    <td>{{ invoicesInsuranceDol }}</td>
                                    <td>{{ invoicesInsuranceSol }}</td>
                                </tr>
                                <br>
                                <tr>
                                    <td>Ad Valorem</td>
                                    <td>{{ recordData.ad_valorem_dol || 0 }}</td>
                                    <td>{{ invoicesAdValoremSol }}</td>
                                </tr>
                                <tr>
                                    <td>18% IGV & IPM</td>
                                    <td>{{ invoicesIgvIpmDol }}</td>
                                    <td>{{ invoicesIgvIpmSol }}</td>
                                </tr>
                                <tr>
                                    <td>Percepciones</td>
                                    <td>{{ invoicesPerceptionDol }}</td>
                                    <td>{{ invoicesPerceptionSol }}</td>
                                </tr>
                                <tr>
                                    <td>Costos de nacionalización</td>
                                    <td>{{ invoicesNationalizationCostDol }}</td>
                                    <td>{{ invoicesNationalizationCostSol }}</td>
                                </tr>
                                 <tr>
                                    <td>IGV Proveedores</td>
                                    <td>{{ invoicesSupplierTaxDol }}</td>
                                    <td>{{ invoicesSupplierTaxSol }}</td>
                                </tr>
                                <br>
                                <tr>
                                    <td><b>Total Liquidación</b></td>
                                    <td>{{ invoicesLiquidationTotalDol }}</td>
                                    <td>{{invoicesLiquidationTotalSol}}</td>
                                </tr>

                            </thead>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button v-if="addedInvoices.length"
                           :loading="loading_submit"
                           native-type="submit"
                           type="primary">Guardar
                </el-button>
            </div>
        </form>
        </div>

        <purchase-form-item
            :currency-type-id-active="form.currency_type_id"
            :exchange-rate-sale="form.exchange_rate_sale"
            :showDialog.sync="showDialogAddItem"
            :recordItem="recordItem"
            :invoice-numbers="invoiceNumbers"
            :is-import="true"
            @add="addRow">
        </purchase-form-item>

        <person-form :external="true"
                     :input_person="input_person"
                     :showDialog.sync="showDialogNewPerson"
                     type="suppliers"></person-form>

        <purchase-options :recordId="purchaseNewId"
                          :showClose="false"
                          :showDialog.sync="showDialogOptions"></purchase-options>

        <series-form
            ref="series_form"
            @addRowLot="addRowLot">
        </series-form>

        <import-costs-form
            :showDialog.sync="showDialogImportCosts"
            :recordData="form"
            @processImportCosts="processImportCosts"
            @showPurchaseNew="showPurchaseNew">
        </import-costs-form>

        <dialog-purchase-form-new
            :title="'Agregar Invoice'"
            :showDialog.sync="showDialogPurchaseNew"
            :recordData="recordData"
            :isImport="true"
            :importOrderTask="form.import_order_task"
            @addedInvoice="getAddedInvoices"
        ></dialog-purchase-form-new>

        <purchase-edit-dialog
            :showDialog.sync="showDialogPurchaseEdit"
            @getInvoices="getAddedInvoices"
        >
        </purchase-edit-dialog>
    </div>
</template>
<style>
.v-modal{z-index: 2000 !important;}
</style>
<script>

import PurchaseFormItem from './partials/item.vue'
import PersonForm from './../persons/form.vue'
import PurchaseOptions from './partials/options.vue'
import {exchangeRate, fnPaymentsFee, functions} from './../../../mixins/functions'
import {calculateRowItem} from './../../../helpers/functions'
import SeriesForm from './partials/series'
import ImportCostsForm from './partials/import_costs.vue'
import {mapActions, mapState} from "vuex";
import DialogPurchaseFormNew from './partials/dialog_form.vue';
import PurchaseEditDialog from "./partials/purchase-edit-dialog.vue";
import {deletable} from "./../../../mixins/deletable";
import {translateInvoiceType} from "../../../helpers/TranslateMaps";
import { currencyDict} from "../../../helpers/currency-dict";

export default {
    props: ['resourceId',],
    components: {
        PurchaseEditDialog,
        PurchaseFormItem, PersonForm, PurchaseOptions, SeriesForm, ImportCostsForm, DialogPurchaseFormNew},
    mixins: [functions, exchangeRate, fnPaymentsFee,deletable],
    computed: {
        ...mapState([
            'config',
            'establishment',
            'hasGlobalIgv',
        ]),
        creditPaymentMethod: function () {
            return _.filter(this.payment_method_types, {'is_credit': true})
        },
        cashPaymentMethod: function () {
            return _.filter(this.payment_method_types, {'is_credit': false})
        },
        isCreditPaymentCondition: function () {
            return ['02', '03'].includes(this.form.payment_condition_id)
        },
        invoicesTotalDol() {
            const total = this.addedInvoices.filter(invoice => {

                return !invoice.invoice_type
            })
            .reduce((carry, invoice) => {

                if (invoice.currency_type_id === currencyDict.USD) {
                    return carry += +invoice.total_value;
                }

                return carry += invoice.total_value / invoice.exchange_rate_sale;

            }, 0);
            return _.round(total, 2) || 0;
        },
        invoicesTotalSol() {
            const total = this.addedInvoices.filter(invoice => !invoice.invoice_type)
            .reduce((carry, invoice) => {

                if (invoice.currency_type_id === currencyDict.SOL) {
                  return carry += +invoice.total_value;
                }

                return carry += invoice.total_value * invoice.exchange_rate_sale;
               
            }, 0);    
            return _.round(total, 2) || 0;
        },
        invoicesLadingDol() {
            const total = this.addedInvoices.filter(invoice => invoice.invoice_type === 'lading')
            .reduce((carry, invoice) => {
                if (invoice.currency_type_id === currencyDict.USD) {
                  return carry += +invoice.total_value;
                }

                return carry += invoice.total_value / invoice.exchange_rate_sale;
               
            }, 0);

            return _.round(total, 2) || 0;
        },
        invoicesLadingSol() {
            const total = this.addedInvoices.filter(invoice => invoice.invoice_type === 'lading')
            .reduce((carry, invoice) => {
                if (invoice.currency_type_id === currencyDict.SOL) {
                  return carry += +invoice.total_value;
                }

                return carry += invoice.total_value * invoice.exchange_rate_sale;
               
            }, 0);
            return _.round(total, 2) || 0;
        },
        invoicesInsuranceDol() {
            const total = this.addedInvoices.filter(invoice => invoice.invoice_type === 'insurance')
            .reduce((carry, invoice) => {
                if (invoice.currency_type_id === currencyDict.USD) {
                  return carry += +invoice.total_value;
                }

                return carry += invoice.total_value / invoice.exchange_rate_sale;
               
            }, 0);
            return _.round(total, 2) || 0;
        },
        invoicesInsuranceSol() {
            const total = this.addedInvoices.filter(invoice => invoice.invoice_type === 'insurance')
            .reduce((carry, invoice) => {
                if (invoice.currency_type_id === currencyDict.SOL) {
                  return carry += +invoice.total_value;
                }

                return carry += invoice.total_value * invoice.exchange_rate_sale;
               
            }, 0);
            return _.round(total, 2) || 0;
        },
        invoicesAdValoremSol() {
            return _.round(this.recordData.ad_valorem_dol * this.recordData.exchange_rate, 2) || 0;
        },
        invoicesIgvIpmDol() {
            return _.round(this.recordData.igv_dol + this.recordData.ipm_dol, 2) || 0
        },
        invoicesIgvIpmSol() {
            return _.round((this.recordData.igv_dol * this.recordData.exchange_rate) + (this.recordData.ipm_dol * this.recordData.exchange_rate), 2) || 0
        },
        invoicesPerceptionDol() {
            return _.round(parseFloat(this.recordData.perception_dol), 2) || 0;
        },
        invoicesPerceptionSol() {
            return _.round(parseFloat(this.recordData.perception_dol * this.recordData.exchange_rate), 2) || 0;
        },
        invoicesNationalizationCostDol() {
            const requiredInvoices = ['commission', 'forklift', 'quadrille', 'local_transport', 'gate_in', 'thc', 'bl_emission', 'warehouse_discharge', 'overstay', 'special_service', 'others'];

            const total = this.addedInvoices.filter(invoice => requiredInvoices.includes(invoice.invoice_type))
            .reduce((carry, invoice) => {
                if (invoice.currency_type_id === currencyDict.DOL) {
                    return carry += +invoice.total_value;
                }

                return carry += invoice.total_value / invoice.exchange_rate_sale;

            }, 0);

            return _.round(total, 2) || 0;
        },
        invoicesNationalizationCostSol() {
            const requiredInvoices = ['commission', 'forklift', 'quadrille', 'local_transport', 'gate_in', 'thc', 'bl_emission', 'warehouse_discharge', 'overstay', 'special_service', 'others'];

            const total = this.addedInvoices.filter(invoice => requiredInvoices.includes(invoice.invoice_type))
            .reduce((carry, invoice) => {
                if (invoice.currency_type_id === currencyDict.SOL) {
                    return carry += +invoice.total_value;
                }

                return carry += invoice.total_value * invoice.exchange_rate_sale;

            }, 0);

            return _.round(total, 2) || 0;
        },
        invoicesSupplierTaxDol() {
            return _.round((this.invoicesNationalizationCostDol / 100) * 18, 2) || 0;
        },
        invoicesSupplierTaxSol() {
            return _.round((this.invoicesNationalizationCostSol / 100) * 18, 2) || 0;
        },
        invoicesLiquidationTotalDol() {
            return _.round(this.recordData.ad_valorem_dol + this.invoicesIgvIpmDol + this.invoicesPerceptionDol + this.invoicesNationalizationCostDol + this.invoicesSupplierTaxDol, 2) || 0;
        },
        invoicesLiquidationTotalSol() {
            return _.round(this.invoicesAdValoremSol + this.invoicesIgvIpmSol + this.invoicesPerceptionSol + this.invoicesNationalizationCostSol + this.invoicesSupplierTaxSol, 2) || 0;
        }
    },
    data() {
        return {
            addedInvoices: [],
            selectedInvoiceId: null,
            input_person: {},
            resource: 'importation',
            showDialogAddItem: false,
            readonly_date_of_due: false,
            localHasGlobalIgv: false,
            showDialogNewPerson: false,
            showDialogOptions: false,
            showDialogImportCosts: false,
            showDialogPurchaseNew: false,
            showDialogPurchaseEdit: false,
            showAddInvoiceDialog: false,
            loading_submit: false,
            hide_button: false,
            is_perception_agent: false,
            isLading: false,
            errors: {},
            form: {
                items:[]
            },
            recordItem: null,
            aux_supplier_id: null,
            total_amount: 0,
            document_types: [],
            currency_types: [],
            discount_types: [],
            charges_types: [],
            payment_method_types: [],
            all_suppliers: [],
            suppliers: [],
            all_customers: [],
            customers: [],
            company: null,
            operation_types: [],
            all_series: [],
            series: [],
            payment_destinations: [],
            payment_conditions: [],
            currency_type: {},
            loading_search: false,
            purchaseNewId: null,
            showDialogLots: false,
            configuration: {},
            note_credit_types: [],
            note_debit_types: [],
            affected_documents: [],
            recordData: null,
            invoiceNumbers: [],
            fileList: [],
            fileIdsToRemove: []
        }
    },
    mounted() {
        this.initForm()

        this.$http.get(`/purchases/tables`)
            .then(response => {
                let data = response.data
                this.document_types = data.document_types_invoice
                this.currency_types = data.currency_types
                this.payment_conditions = data.payment_conditions
                // this.establishment = data.establishment


                this.all_suppliers = data.suppliers
                this.discount_types = data.discount_types
                this.payment_method_types = data.payment_method_types
                this.payment_destinations = data.payment_destinations
                this.all_customers = data.customers
                this.configuration = response.data.configuration

                this.note_credit_types = response.data.note_credit_types
                this.note_debit_types = response.data.note_debit_types

                this.charges_types = data.charges_types
                this.$store.commit('setConfiguration', data.configuration);
                this.$store.commit('setEstablishment', data.establishment);
                this.form.currency_type_id = (this.currency_types.length > 0) ? this.currency_types[0].id : null
                this.form.establishment_id = (this.establishment.id) ? this.establishment.id : null
                this.form.document_type_id = (this.document_types.length > 0) ? this.document_types[0].id : null


            })
            .then(() => {
                this.changeDateOfIssue()
                this.changeDocumentType()
                this.changeCurrencyType()
            })
        this.$eventHub.$on('initInputPerson', () => {
            this.initInputPerson()
        })

        this.$eventHub.$on('clearForm', () => {
            if(this.external) this.resetForm()
        })

        this.filterCustomers()
        this.isGeneratePurchaseOrder()
        this.changeHasPayment()
        this.changeHasClient()
    },
    created() {
        this.loadConfiguration()
        this.loadHasGlobalIgv()
        this.loadEstablishment()
        this.localHasGlobalIgv = this.hasGlobalIgv;

        this.initRecord()
    },
    methods: {
        getInvoiceType: translateInvoiceType,
        ...mapActions([
            'loadConfiguration',
            'loadEstablishment',
            'loadHasGlobalIgv',
        ]),
        clickShowImportCosts() {
            this.showDialogImportCosts = true;
            this.recordData = this.form;
        },
        summedInvoiceItems(invoice) {
            return invoice.items.map(item => item.quantity).reduce((carry, quantity) => carry += +quantity, 0);
        },
        addFiles(files, fileList) {
            const mappedFiles = fileList.filter(file=> file.raw).map(file => file.raw);

            this.form.form_data.delete('uploadedFiles[]');
            mappedFiles.forEach(file => this.form.form_data.append('uploadedFiles[]', file));

        },
        changeHasGlobalIgv() {
            if(this.form.items.length < 1 && this.config.enabled_global_igv_to_purchase === true) {
                this.$store.commit('sethasGlobalIgv', !this.hasGlobalIgv);
                this.loadHasGlobalIgv()
            }
            this.localHasGlobalIgv = this.hasGlobalIgv;

        },
        changeHasPayment() {

            if (!this.form.has_payment) {
                this.form.payments = []
                this.form.fee = []
                this.form.payment_condition_id = '01'

            }else{
                this.changePaymentCondition()
            }
        },
        changeHasClient() {

            if (!this.form.has_client) {
                this.form.customer_id = null
            }
        },
        searchRemotePersons(input) {

            if (input.length > 1) {

                this.loading_search = true
                let parameters = `input=${input}`

                this.$http.get(`/reports/data-table/persons/customers?${parameters}`)
                    .then(response => {
                        this.customers = response.data.persons
                        this.loading_search = false

                        if (this.customers.length == 0) {
                            this.filterCustomers()
                        }
                    })
            } else {
                this.filterCustomers()
            }

        },
        filterCustomers() {
            this.customers = this.all_customers
        },
        getFormatUnitPriceRow(unit_price) {
            return _.round(unit_price, 6)
            // return unit_price.toFixed(6)
        },
        async isGeneratePurchaseOrder() {
            if (this.purchase_order_id) {

                await this.$http.get(`/purchase-orders/record/${this.purchase_order_id}`)
                    .then(response => {
                        let purchase_order = response.data.data.purchase_order
                        let warehouse = response.data.data.warehouse
                        let supp = purchase_order.supplier

                        if (supp.identity_document_type_id == 6) {
                            this.form.document_type_id = "01"
                        } else if (supp.identity_document_type_id == 1) {
                            this.form.document_type_id = "03"
                        }

                        this.form.items = response.data.data.purchase_order.items
                        this.form.supplier_id = purchase_order.supplier_id
                        this.form.currency_type_id = purchase_order.currency_type_id
                        this.form.purchase_order_id = purchase_order.id
                        this.form.payments[0].payment_method_type_id = purchase_order.payment_method_type_id
                        this.form.payments[0].payment = purchase_order.total
                        this.form.total = purchase_order.total
                        this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})

                        this.form.items.forEach((it) => {
                            it.warehouse_id = warehouse.id
                            it.charges = it.charges ? Object.values(it.charges) : []
                            it.attributes = it.attributes ? Object.values(it.attributes) : []
                            it.discounts = it.discounts ? Object.values(it.discounts) : []
                            it.lots = it.item.lots ? it.item.lots : []
                        })
                        // this.changeDocumentType()

                    })

            }
        },
        async validate_payments() {

            let error_by_item = 0
            let acum_total = 0
            let q_affectation_free = 0

            await this.form.payments.forEach((item) => {
                acum_total += parseFloat(item.payment)
                if (item.payment <= 0 || item.payment == null) error_by_item++;
            })

            //determinate affectation igv
            await this.form.items.forEach((item) => {
                if (item.affectation_igv_type.free) {
                    q_affectation_free++
                }
            })

            let all_free = (q_affectation_free == this.form.items.length) ? true : false

            if (!all_free && (acum_total > parseFloat(this.form.total) || error_by_item > 0)) {
                return {
                    success: false,
                    message: 'Los montos ingresados superan al monto a pagar o son incorrectos'
                }
            }

            if (this.form.has_client && !this.form.customer_id) {
                return {
                    success: false,
                    message: 'Debe seleccionar un cliente'
                }
            }

            if (this.form.has_payment) {

                if(this.form.payment_condition_id === '01' && this.form.payments.length == 0){

                    return {
                        success: false,
                        message: 'Debe registrar al menos un pago'
                    }

                }

                if(this.isCreditPaymentCondition && this.form.fee.length == 0){

                    return {
                        success: false,
                        message: 'Debe registrar al menos una cuota'
                    }

                }
            }

            return {
                success: true,
                message: null
            }
        },
        clickCancel(index) {
            this.form.payments.splice(index, 1);
            this.calculatePayments()
        },
        clickAddPayment() {

            this.form.payments.push({
                id: null,
                purchase_id: null,
                date_of_payment: moment().format('YYYY-MM-DD'),
                payment_method_type_id: '01',
                reference: null,
                payment_destination_id: this.getPaymentDestinationId(),
                payment: 0,
            });

            this.calculatePayments()

        },
        getPaymentDestinationId() {

            if (this.config.destination_sale && this.payment_destinations.length > 0) {

                let cash = _.find(this.payment_destinations, {id: 'cash'})

                return (cash) ? cash.id : this.payment_destinations[0].id

            }

            return null

        },
        initInputPerson() {
            this.input_person = {
                number: '',
                identity_document_type_id: ''
            }
        },
        keyupEnterSupplier() {

            if (this.input_person.number) {

                if (!isNaN(parseInt(this.input_person.number))) {

                    switch (this.input_person.number.length) {
                        case 8:
                            this.input_person.identity_document_type_id = '1'
                            this.showDialogNewPerson = true
                            break;

                        case 11:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                        default:
                            this.input_person.identity_document_type_id = '6'
                            this.showDialogNewPerson = true
                            break;
                    }
                }
            }
        },
        keyupSupplier(e) {

            if (e.key !== "Enter") {

                this.input_person.number = this.$refs.select_person.$el.getElementsByTagName('input')[0].value
                let exist_persons = this.suppliers.filter((supplier) => {
                    let pos = supplier.description.search(this.input_person.number);
                    return (pos > -1)
                })

                this.input_person.number = (exist_persons.length == 0) ? this.input_person.number : null
            }

        },
        inputSeries() {

            const pattern = new RegExp('^[A-Z0-9]+$', 'i');
            if (!pattern.test(this.form.series)) {
                this.form.series = this.form.series.substring(0, this.form.series.length - 1);
            } else {
                this.form.series = this.form.series.toUpperCase()
            }

        },
        initRecord()
        {
            this.$http.get(`/${this.resource}/${this.resourceId}` )
                .then(({data})=> {
                    this.form = {...this.form, ...data}

                    this.recordData = this.form;

                    const ladingData = data.invoices.find(invoice => {
                        if (invoice.invoice_type === 'lading') {
                            return invoice;
                        }
                    });

                    if (ladingData) {
                        this.form.lading = {
                            date: moment(ladingData.date_of_issue).format('YYYY-MM-DD'),
                            provider_transport: ladingData.supplier.name,
                            invoice: ladingData.number_full,
                            price: ladingData.total_value,
                            purchase_id: null
                        }
                    }

                    this.addedInvoices = data.invoices;

                    this.fileList = data.uploaded_files.map(file => {
                        return {name: file.filename, url: '', id: file.id}
                    });

                    setTimeout(() => this.form.supplier_id = +data.supplier.id, 500);

                    this.calculateTotal()

                })
        },
        changePaymentMethodType(index) {

            let id = '01'

            if (this.form.payments.length > 0) {
                id = this.form.payments[index].payment_method_type_id
            } else if (this.form.fee.length > 0) {
                id = this.form.fee[index].payment_method_type_id
            }

            let payment_method_type = _.find(this.payment_method_types, {'id': id})

            if (payment_method_type.number_days) {

                this.form.date_of_due = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                this.readonly_date_of_due = true

                let date = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')

                if (this.form.fee.length > 0) {
                    for (let index = 0; index < this.form.fee.length; index++) {
                        this.form.fee[index].date = date
                    }
                }

            } else {

                this.form.date_of_due = this.form.date_of_issue
                this.readonly_date_of_due = false

            }

        },
        inputTotalPerception() {
            this.total_amount = parseFloat(this.form.total) + parseFloat(this.form.total_perception)
            if (isNaN(this.total_amount)) {
                this.hide_button = true
            } else {
                this.hide_button = false

            }
        },
        changeSupplier() {

            this.calculatePerception()
        },
        filterSuppliers() {

            if (this.form.document_type_id === '01') {
                // this.suppliers = _.filter(this.all_suppliers, {'identity_document_type_id': '6'})
                this.suppliers = _.filter(this.all_suppliers, (item) => {
                    return ['6', '0'].includes(item.identity_document_type_id)
                })
                this.selectSupplier()

            } else {
                this.suppliers = this.all_suppliers  //_.filter(this.all_suppliers, (c) => { return c.identity_document_type_id !== '6' })
                this.selectSupplier()
            }
        },
        selectSupplier() {

            let supplier = _.find(this.suppliers, {'id': this.aux_supplier_id})
            this.form.supplier_id = (supplier) ? supplier.id : null
            this.aux_supplier_id = null

        },
        initForm() {
            this.errors = {}
            this.form = {
                establishment_id: null,
                document_type_id: '4',
                series: this.form.has_import ? 'n.a.' : null,
                number: this.form.has_import ? 'n.a.' : null,
                date_of_issue: moment().format('YYYY-MM-DD'),
                time_of_issue: moment().format('HH:mm:ss'),
                date_periodo:  moment().format('YYYY-MM-DD'),
                arrival_date: null,
                supplier_id: null,
                payment_method_type_id: '01',
                currency_type_id: null,
                purchase_order: null,
                exchange_rate_sale: 0,
                total_prepayment: 0,
                total_charge: 0,
                total_discount: 0,
                total_exportation: 0,
                total_free: 0,
                total_taxed: 0,
                total_unaffected: 0,
                total_exonerated: 0,
                total_igv: 0,
                total_base_isc: 0,
                total_isc: 0,
                total_base_other_taxes: 0,
                total_other_taxes: 0,
                total_taxes: 0,
                total_value: 0,
                total: 0,
                perception_date: null,
                perception_number: null,
                total_perception: 0,
                date_of_due: moment().format('YYYY-MM-DD'),
                items: [],
                charges: [],
                discounts: [],
                attributes: [],
                guides: [],
                payments: [],
                customer_id: null,
                has_client: false,
                has_payment: false,
                has_import: false,
                fixed_asset: false,
                payment_condition_id: '01',
                fee: [],
                type_basimp: '01',
                note: {
                    series:null,
                    number:null,
                    purchase_id:null,
                    note_type: null,
                    note_credit_type_id :null,
                    note_debit_type_id : null,
                    note_description : null,
                    affected_purchase_id :null
                },
                lading: {
                    date: null,
                    provider_transport: null,
                    invoice: null,
                    price: 0,
                    purchase_id: null
                },
                insurace: {
                    date: null,
                    insurace_transport: null,
                    invoice: null,
                    price: 0,
                    purchase_id: null
                },
                import_costs: {
                    commission_pen: 0,
                    commission_usd: 0,
                    commission_invoice: null,
                    commission_purchase_id: null,
                    stow_unstow_pen: 0,
                    stow_unstow_usd: 0,
                    stow_unstow_invoice: null,
                    stow_unstow_purchase_id: null,
                    handling_pen: 0,
                    handling_usd: 0,
                    handling_invoice: null,
                    handling_purchase_id: null,
                    transport_pen: 0,
                    transport_usd: 0,
                    transport_invoice: null,
                    transport_purchase_id: null,
                    antidumping_pen: 0,
                    antidumping_usd: 0,
                    antidumping_invoice: null,
                    antidumping_purchase_id: null,
                    storage_pen: 0,
                    storage_usd: 0,
                    storage_invoice: null,
                    storage_purchase_id: null,
                    dispatch_service_pen: 0,
                    dispatch_service_usd: 0,
                    dispatch_service_invoice: null,
                    dispatch_service_purchase_id: null,
                    others: [],
                    total_usd: 0,
                    total_pen: 0
                },
                fees_settlements: {
                    customs_law: 0,
                    isc_sol: 0,
                    isc_dol: 0,
                    igv_sol: 0,
                    igv_dol: 0,
                    ipm_sol: 0,
                    ipm_dol: 0,
                    ad_valorem_sol: 0,
                    ad_valorem_dol: 0,
                    dispatch_fee_sol: 0,
                    dispatch_fee_dol: 0,
                    uit: 0,
                    perception_sol: 0,
                    perception_dol: 0,
                    import_costs_total: 0,
                    tc: 0,
                    utility: 0
                },
                total_lading: 0,
                total_insurace: 0,
                total_cost: 0,
                total_tax_customs_law: 0,
                total_tax_isc: 0,
                total_tax_igv: 0,
                total_tax_ipm: 0,
                total_tax_customs_fee: 0,
                total_tax_rights: 0,
                total_tax_perception: 0,
                total_import_expenses_without_igv: 0,
                total_igv_expenses_usd: 0,
                total_ipm_expenses_usd: 0,
                total_import_expenses: 0,
                total_import_cost: 0,
                total_tax_credit: 0,
                import_order_task: '',
                number_proforma: '',
                amount_proforma: '',
                sale_reference: '',
                ref_customs_agent: '',
                transport_type: '',
                transport_code: '',
                dam: '',
                is_import_form: true,
                form_data: new FormData,
                exchange_rate: 0.00
            }
            this.clickAddPayment()

            this.initInputPerson()

            this.readonly_date_of_due = false
        },
        changePaymentCondition(){

            this.form.fee = []
            this.form.payments = []

            if (this.form.payment_condition_id === '01') {

                this.clickAddPayment()
                this.initDataPaymentCondition()

            }
            if (this.form.payment_condition_id === '02') {
                this.clickAddFeeNew()
                this.readonly_date_of_due = true
            }
            if (this.form.payment_condition_id === '03') {
                this.clickAddFee()
                this.initDataPaymentCondition()
            }

        },
        changeDateOfIssue() {
            this.form.date_of_due = this.form.date_of_issue
            this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                this.form.exchange_rate_sale = response
            })
        },
        changeDocumentType() {
            this.filterSuppliers()
        },
        addRow(row, index) {
            if(index != null) this.form.items[index] = row
            else this.form.items.push(row)
            this.calculateTotal()
        },
        async getAddedInvoices() {

            const result = await this.$http.get('/purchases/purchase-by-invoice-number', {
                params: {
                    import_order_task: this.form.import_order_task
                }
            });

            this.addedInvoices = result.data;
        },
        clickAddItem() {
            this.recordItem = null
            this.showDialogAddItem = true

            if (this.isImport) {
                this.$emit('update:hasImport', true)
            }
        },
        clickAddInvoice() {
            this.recordData = this.form;
            this.showDialogPurchaseNew = true;
        },
        clickEditItem(index) {
            this.recordItem =  {data: {... this.form.items[index]}, index: index}
            this.showDialogAddItem = true
        },
        clickRemoveItem(index) {
            this.form.items.splice(index, 1)
            this.calculateTotal()
        },
        changeCurrencyType() {
            this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})

            this.updateRowItems();
            this.calculateTotal();
        },
        updateRowItems() {
            let items = [];
            this.form.items.forEach((row) => {
                let item_push = {...row, ...calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale)};
                const itemsWithAdValoremCount = this.form.items.filter(item => item.purchase_has_ad_valorem).length;

                if (item_push.purchase_has_ad_valorem) {
                    item_push.total += this.form.fees_settlements.ad_valorem_sol / itemsWithAdValoremCount;
                }

                items.push(item_push)
            });

            this.form.items = items
        },
        calculateImport() {

            this.updateRowItems();

            let total_lading = parseFloat(this.form.lading.price)
            let total_insurance = parseFloat(this.form.insurace.price)
            let total_cost = parseFloat(this.form.total + total_lading + total_insurance)
            let total_tax_customs_law = parseFloat(this.form.fees_settlements.ad_valorem_sol + total_cost)
            let total_tax_isc = parseFloat(this.form.fees_settlements.isc_sol * (total_cost+total_tax_customs_law))
            let total_tax_igv = parseFloat(this.form.fees_settlements.igv_sol * (total_cost+total_tax_customs_law+total_tax_isc))
            let total_tax_ipm = parseFloat(this.form.fees_settlements.ipm_sol *(total_cost+total_tax_customs_law+total_tax_isc))
            let total_tax_customs_fee = this.form.fees_settlements.tc != 0 ? parseFloat(((this.form.fees_settlements.dispatch_fee/100)*this.form.fees_settlements.uit)/this.form.fees_settlements.tc) : 0
            let total_tax_rights = parseFloat(total_tax_customs_law+total_tax_isc+total_tax_igv+total_tax_ipm+total_tax_customs_fee)
            let total_tax_perception = parseFloat((total_tax_rights+total_cost)*(this.form.fees_settlements.perception/100))
            let total_import_expenses_without_igv = parseFloat(this.form.import_costs.total_usd-(this.form.import_costs.total_usd* this.form.fees_settlements.igv_sol + this.form.fees_settlements.ipm_sol ))
            let total_igv_expenses_usd = parseFloat(this.form.import_costs.total_usd * this.form.fees_settlements.igv_sol)
            let total_ipm_expenses_usd = parseFloat(this.form.import_costs.total_usd * this.form.fees_settlements.ipm_sol)
            let total_import_expenses = parseFloat(total_import_expenses_without_igv+total_igv_expenses_usd+total_ipm_expenses_usd)
            let total_import_cost = parseFloat(total_cost+total_tax_rights+total_tax_perception+total_import_expenses)
            let total_tax_credit = parseFloat(total_tax_igv+total_tax_ipm+total_tax_perception+total_igv_expenses_usd+total_ipm_expenses_usd)



            this.form.total_lading = _.round(total_lading, 2)
            this.form.total_insurace = _.round(total_insurance, 2)
            this.form.total_cost = _.round(total_cost, 2)
            this.form.total_tax_customs_law = _.round(total_tax_customs_law, 2)
            this.form.total_tax_isc = _.round(total_tax_isc, 2)
            this.form.total_tax_igv = _.round(total_tax_igv, 2)
            this.form.total_tax_ipm = _.round(total_tax_ipm, 2)
            this.form.total_tax_customs_fee = _.round(total_tax_customs_fee, 2)
            this.form.total_tax_rights = _.round(total_tax_rights, 2)
            this.form.total_tax_perception = _.round(total_tax_perception, 2)
            this.form.total_import_expenses_without_igv = _.round(total_import_expenses_without_igv, 2)
            this.form.total_igv_expenses_usd = _.round(total_igv_expenses_usd, 2)
            this.form.total_ipm_expenses_usd = _.round(total_ipm_expenses_usd, 2)
            this.form.total_import_expenses = _.round(total_import_expenses, 2)
            this.form.total_import_cost = _.round(total_import_cost, 2)
            this.form.total_tax_credit = _.round(total_tax_credit, 2)
            this.form.total_tax_customs_law = this.form.fees_settlements.ad_valorem_sol
        },
        calculateTotal() {
            let total_discount = 0
            let total_charge = 0
            let total_exportation = 0
            let total_taxed = 0
            let total_exonerated = 0
            let total_unaffected = 0
            let total_free = 0
            let total_igv = 0
            let total_value = 0
            let total = 0

            this.form.items.forEach((row) => {
                total_discount += parseFloat(row.total_discount)
                total_charge += parseFloat(row.total_charge)

                if (row.affectation_igv_type_id === '10') {
                    total_taxed += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '20') {
                    total_exonerated += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '30') {
                    total_unaffected += parseFloat(row.total_value)
                }
                if (row.affectation_igv_type_id === '40') {
                    total_exportation += parseFloat(row.total_value)
                }
                if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                    total_free += parseFloat(row.total_value)
                }

                total_value += parseFloat(row.total_value)
                total_igv += parseFloat(row.total_igv)
                total += parseFloat(row.total)
            });

            total += this.form.fees_settlements.fineSoles;

            this.form.total_exportation = _.round(total_exportation, 2)
            this.form.total_taxed = _.round(total_taxed, 2)
            this.form.total_exonerated = _.round(total_exonerated, 2)
            this.form.total_unaffected = _.round(total_unaffected, 2)
            this.form.total_free = _.round(total_free, 2)
            this.form.total_igv = _.round(total_igv, 2)
            this.form.total_value = _.round(total_value, 2)
            this.form.total_taxes = _.round(total_igv, 2)
            this.form.total = _.round(total, 2)

            this.calculatePerception()

            if (this.isLading) {
                // buscar por todos los invoice y sumar los que tengan el nombre FLETE a flete y SEGURO a seguro
                // borrar el radio group!

            }

            // this.form.payments[0].payment = this.form.total
            // this.setTotalDefaultPayment()
            this.calculatePayments()
            this.calculateFee()
            this.calculateImport()
        },
        setTotalDefaultPayment() {

            if (this.form.payments.length > 0) {

                this.form.payments[0].payment = this.form.total
            }
        },
        calculatePerception() {

            let supplier = _.find(this.all_suppliers, {'id': this.form.supplier_id})

            if (supplier) {

                if (supplier.perception_agent) {

                    let total_perception = 0
                    let quantity_item_perception = 0
                    let total_amount = 0
                    this.form.total_perception = 0

                    this.form.perception_date = moment().format('YYYY-MM-DD')

                    this.form.items.forEach((row) => {
                        quantity_item_perception += (row.item.has_perception) ? 1 : 0
                        total_perception += (row.item.has_perception) ? (parseFloat(row.unit_price) * parseFloat(row.quantity) * (parseFloat(row.item.percentage_perception) / 100)) : 0
                    });

                    this.is_perception_agent = (quantity_item_perception > 0) ? true : false
                    this.form.total_perception = _.round(total_perception, 2)
                    total_amount = this.form.total + parseFloat(this.form.total_perception)
                    this.total_amount = _.round(total_amount, 2)

                } else {

                    this.is_perception_agent = false
                    this.form.perception_date = null
                    this.form.perception_number = null
                    this.form.total_perception = null

                }
            }
        },
        async submit() {
            this.loading_submit = true

            this.form.form_data.append('id', this.form.id);
            this.form.form_data.append('import_order_task', this.form.import_order_task);
            this.form.form_data.append('supplier_id', this.form.supplier_id);
            this.form.form_data.append('document_type_id', this.form.document_type_id);
            this.form.form_data.append('number_proforma', this.form.number_proforma);
            this.form.form_data.append('amount_proforma', this.form.amount_proforma);
            this.form.form_data.append('sale_reference', this.form.sale_reference);
            this.form.form_data.append('ref_customs_agent', this.form.ref_customs_agent);
            this.form.form_data.append('transport_type', this.form.transport_type);
            this.form.form_data.append('transport_code', this.form.transport_code);
            this.form.form_data.append('transport_code', this.form.transport_code);
            this.form.form_data.append('dam', this.form.dam);
            this.form.form_data.append('date_periodo', this.form.date_periodo);
            this.form.form_data.append('date_of_issue', this.form.date_of_issue);
            this.form.form_data.append('arrival_date', this.form.arrival_date);
            this.form.form_data.append('comments', this.form.comments);
            this.form.form_data.append('exchange_rate', this.form.exchange_rate);

            if (this.fileIdsToRemove.length) {
                this.form.form_data.append('fileIdsToRemove[]', this.fileIdsToRemove);
            }


            await this.$http.post(`/${this.resource}/${this.form.id}`, this.form.form_data)
                .then(response => {

                    if (response.data.success) {
                        this.$message.success('Importación actualizada');

                        this.form.form_data.delete('fileIdsToRemove[]');
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data
                    } else {
                        this.$message.error(error.response.data.message)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        close() {
            if(this.external) this.$emit('closeDialog', null)
            else location.href = '/purchases'
        },
        clickOpenSeries(ind, qt, lt) {
            this.$refs.series_form.openDialog(ind, qt, lt)
        },
        openPurchaseEditModal(aPurchaseId) {
            this.selectedInvoiceId = aPurchaseId;
            this.showDialogPurchaseEdit = true;
        },
        deleteInvoice(anInvoiceId) {
            this.destroy(`/purchases/${anInvoiceId}`).then(() =>
                this.getAddedInvoices()
            )
        },
        addRowLot({lots, indexItem}) {
            this.form.items[indexItem].lots = lots
        },
        async validationItemSeries() {
            let error = 0

            await this.form.items.forEach((element) => {

                if (element.item.series_enabled) {
                    const count_lot = element.lots ? element.lots.length : 0
                    if (element.quantity != count_lot) {
                        error++;
                    }
                }
            })

            if (error > 0)
                return {success: false, message: 'Las series y la cantidad en los productos deben ser iguales.'}


            return {success:true, message: ''}
        },
        getHasDocument(){

            this.$http.get(`/${this.resource}/has-document/${this.form.note.series}/${this.form.note.number}`)
                .then(response => {

                    if(response.data.success){

                        this.affected_documents = response.data.data;
                        this.form.note.affected_purchase_id=this.affected_documents.id;

                        if(this.form.document_type_id === '07'){
                            this.form.note.note_type='credit';
                        }
                        else if(this.form.document_type_id === '08'){
                            this.form.note.note_type='debit';
                        }

                        let message = `<strong>El comprobante ${ this.affected_documents.series }-${ this.affected_documents.number } fué encontrado</strong><br/>`

                        this.$notify({
                            title: "",
                            dangerouslyUseHTMLString: true,
                            message: message,
                            type: "warning",
                            duration: 6000
                        })
                    }

                })

        },
        processImportCosts(row) {
            this.form.lading = {... row.lading}
            this.form.insurace = {... row.insurace}
            this.form.import_costs = {... row.import_costs}
            this.form.exchange_rate = row.exchange_rate;

            this.form.form_data.append('isc_dol', row.fees_settlements.isc_dol);
            this.form.form_data.append('igv_dol', row.fees_settlements.igv_dol);
            this.form.form_data.append('ipm_dol', row.fees_settlements.ipm_dol);
            this.form.form_data.append('ad_valorem_dol', row.fees_settlements.ad_valorem_dol);
            this.form.form_data.append('dispatch_fee_dol', row.fees_settlements.dispatch_fee_dol);
            this.form.form_data.append('perception_dol', row.fees_settlements.perception_dol);
            this.form.form_data.append('fine_dol', row.fees_settlements.fine_dol);
            this.form.form_data.append('utility', row.fees_settlements.utility);

            this.calculateTotal();
        },
        showPurchaseNew(data){

            this.recordData = {...data, import_order_task: this.form.import_order_task}
            this.showDialogPurchaseNew = true
        },
        checkRemove(file, fileList) {
            const foundFile= this.fileList.find(f => f.name === file.name)

            if (foundFile) {
                this.fileIdsToRemove.push(foundFile.id)
            }
        }
    },
}
</script>
