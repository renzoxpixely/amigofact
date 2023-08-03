<template>
    <el-dialog :title="titleDialog" width="60%" :visible="showDialog" @close="close" @open="create" :close-on-press-escape="false" :close-on-click-modal=false>
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <el-tabs v-model="activeName">
                            <el-tab-pane name="first">
                                <span slot="label">Flete & seguro</span>
                                <div class="row">
									<div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
												<tr>
                                                    <td>Fecha</td>
                                                    <td width="200">
                                                        <el-input v-model="form.lading.date" disabled></el-input>
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>Proveedor de Transporte</td>
                                                    <td width="200">
                                                        <el-input v-model="form.lading.provider_transport" disabled></el-input>
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>Factura Flete<span v-if="form.lading.invoice">({{ form.lading.invoice }})</span></td>
                                                    <td width="200">
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('lading', null)">Agregar Flete</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Factura Seguro<span v-if="form.lading.invoice">({{ form.lading.invoice }})</span></td>
                                                    <td width="200">
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('insurance', null)">Agregar Seguro</el-button>
                                                    </td>
                                                </tr>
												<tr>
                                                    <td>Precio</td>
                                                    <td width="200">
                                                        <el-input type="number" min="0" step="0.01" v-model.number="form.lading.price" disabled>
															<template slot="prepend" v-if="form.lading.currency_type_id">{{ form.lading.currency_type_id }}</template>
														</el-input>
                                                    </td>
                                                </tr>
												</tbody>
											</table>
										</div>
									</div>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane name="two">
                                <span slot="label">Costos de Importación</span>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        Comisión agente de aduana <span v-if="form.import_costs.commission_invoice">({{ form.import_costs.commission_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.commission_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.commission_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('commission', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Montacarga <span v-if="form.import_costs.forklift_invoice">({{ form.import_costs.forklift_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.forklift_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.forklift_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('forklift', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Cuadrilla <span v-if="form.import_costs.quadrille_invoice">({{ form.import_costs.quadrille_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.quadrille_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.quadrille_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('quadrille', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Transporte local <span v-if="form.import_costs.local_transport_invoice">({{ form.import_costs.local_transport_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.local_transport_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.local_transport_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('local_transport', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Gate in <span v-if="form.import_costs.gate_in_invoice">({{ form.import_costs.gate_in_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.gate_in_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.gate_in_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('gate_in', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        THC <span v-if="form.import_costs.thc_invoice">({{ form.import_costs.thc_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.thc_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.thc_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('thc', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                         Emisión de BL<span v-if="form.import_costs.bl_emission_invoice">({{ form.import_costs.bl_emission_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.bl_emission_pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.bl_emission_usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('bl_emission', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Descarga / Almacén<span v-if="form.import_costs.warehouse_discharge_invoice">({{ form.import_costs.warehouse_discharge_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.warehouse_discharge_pen" disabled>
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.warehouse_discharge_usd" disabled>
                                                            <template slot="prepend">USD</template>
                                                        </el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('warehouse_discharge', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Sobreestadía<span v-if="form.import_costs.overstay_invoice">({{ form.import_costs.overstay_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.overstay_pen" disabled>
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.overstay_usd" disabled>
                                                            <template slot="prepend">USD</template>
                                                        </el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('overstay', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        Servicio Extraordinario<span v-if="form.import_costs.special_service_invoice">({{ form.import_costs.special_service_invoice }})</span>
                                                        <el-input class="mt-1" v-model="form.import_costs.special_service_pen" disabled>
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input class="mt-1" v-model="form.import_costs.special_service_usd" disabled>
                                                            <template slot="prepend">USD</template>
                                                        </el-input>
                                                    </td>
                                                    <td>
                                                        <el-button class="w-100" type="primary" @click="clickAddPurchase('special_service', null)">Agregar Compra</el-button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
									<div class="col-12">
										<h5 class="separator-title mt-0">
											Otros
										</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
												<tr v-for="(row, index) in form.import_costs.others"
													:key="index">
                                                    <td>
                                                        <el-input v-model.number="row.invoice" disabled>
														</el-input>
														<el-input class="mt-1" v-model="row.pen" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input class="mt-1" v-model="row.usd" disabled>
															<template slot="prepend">USD</template>
														</el-input>
                                                    </td>
                                                    <td>
														<div>
															<el-button size="small" class="w-100" type="primary" @click="clickAddPurchase('others', index)">Agregar Compra</el-button>
														</div>
														<div class="mt-1">
															<el-button size="small" type="danger" class="w-100"
																	@click.prevent="clickRemoveOther(index)">
																Remover
															</el-button>
														</div>
                                                    </td>
                                                </tr>
												</tbody>
											</table>
										</div>
									</div>
									<div class="col mt-1">
										<a class="control-label font-weight-bold text-info"
										   href="#"
										   @click="clickAddOther"> [ + Agregar]</a>
									</div>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane name="three">
                                <span slot="label">Derechos</span>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                <tr>
                                                    <td class="col-8">Ad Valorem</td>
                                                    <td class="col-2">
                                                       <el-input type="number" min="0" step="0.01" v-model="adValoremSol">
                                                           <template slot="prepend">PEN</template>
                                                       </el-input>
                                                        <el-input type="number" min="0" step="0.01" v-model="adValoremDol">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">ISC</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model="iscSoles">
                                                            <template slot="prepend">PEN</template>
														</el-input>
                                                        <el-input type="number" min="0" step="0.01" v-model="iscDolares">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">IGV</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model="igvSoles">
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input type="number" min="0" step="0.01" v-model="igvDolares">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">IPM</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model="ipmSoles">
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input type="number" min="0" step="0.01"  v-model="ipmDolares">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">Tasa de despacho aduanero</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model="dispatchFeeSoles">
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input type="number" min="0" step="0.01" v-model="dispatchFeeDolares">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">UIT</td>
                                                    <td class="col-2">
                                                        <el-input disabled type="number" min="0" step="0.01" v-model.number="config.uit">
															<template slot="prepend">PEN</template>
														</el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">Percepción</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model="perceptionSoles">
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input type="number" min="0" step="0.01"  v-model="perceptionDolares">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">Multas</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model="fineSoles">
                                                            <template slot="prepend">PEN</template>
                                                        </el-input>
                                                        <el-input type="number" min="0" step="0.01"  v-model="fineDolares">
                                                            <template slot="prepend">DOL</template>
                                                        </el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">Gastos de importación total</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model.number="fees_settlements.import_costs_total" disabled>
															<template slot="prepend">PEN</template>
														</el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">T/C.</td>
                                                    <td class="col-2">
                                                        <el-input v-model.number="fees_settlements.tc" disabled>
														</el-input>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="col-8">Utilidad</td>
                                                    <td class="col-2">
                                                        <el-input type="number" min="0" step="0.01" v-model.number="fees_settlements.utility">
															<template slot="append">%</template>
														</el-input>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4" >
                                        <div class="form-group">
                                            <label class="control-label">Tipo de cambio
                                                <el-tooltip class="item"
                                                            content="Tipo de cambio del día, extraído de SUNAT"
                                                            effect="dark"
                                                            placement="top-end">
                                                    <i class="fa fa-info-circle"></i>
                                                </el-tooltip>
                                            </label>
                                            <el-input v-model="exchange_rate"></el-input>
                                        </div>
                                    </div>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-5">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Procesar</el-button>
            </div>
        </form>


    </el-dialog>
</template>
<style>
.table th, .table td{padding: 0.25rem;}
.el-upload {width: 100%;}
</style>
<script>

    import {exchangeRate} from "../../../../mixins/functions";
    import {mapState} from "vuex";

    export default {
        props: ['showDialog', 'recordData'],
        mixins: [exchangeRate],
        data() {
            return {
                activeName: 'first',
                loading_submit: false,
                titleDialog: null,
                errors: {},
                headers: headers_token,
                resource: "purchases/import-cost",
                form: {},
                fees_settlements: {
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
                    fine_sol: 0,
                    fine_dol: 0,
                    import_costs_total: 0,
                    tc: 0,
                    utility: 0
                },
                exchange_rate: 0,
            }
        },
        async created() {
            this.initForm();
            this.searchExchangeRateByDate(moment().format('YYYY-MM-DD')).then(response => {
                this.form.exchange_rate = response
            });

			this.registerPurchaseCreatedEvent();
        },
        methods: {
            initForm() {
                this.errors = {}
                this.form = {
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
                        forklift_pen: 0,
                        forklift_usd: 0,
                        forklift_invoice: null,
						forklift_purchase_id: null,
                        quadrille_pen: 0,
                        quadrille_usd: 0,
                        quadrille_invoice: null,
						quadrille_purchase_id: null,
                        local_transport_pen: 0,
                        local_transport_usd: 0,
                        local_transport_invoice: null,
                        local_transport_purchase_id: null,
                        gate_in_pen: 0,
                        gate_in_usd: 0,
                        gate_in_invoice: null,
                        gate_in_purchase_id: null,
                        thc_pen: 0,
                        thc_usd: 0,
                        thc_invoice: null,
                        thc_purchase_id: null,
                        bl_emission_pen: 0,
                        bl_emission_usd: 0,
                        bl_emission_invoice: null,
                        bl_emission_purchase_id: null,
                        warehouse_discharge_pen: 0,
                        warehouse_discharge_usd: 0,
                        warehouse_discharge_invoice: null,
                        warehouse_discharge_purchase_id: null,
                        overstay_pen: 0,
                        overstay_usd: 0,
                        overstay_invoice: null,
                        overstay_purchase_id: null,
                        special_service_pen: 0,
                        special_service_usd: 0,
                        special_service_invoice: null,
                        special_service_purchase_id: null,
						others: [],
                        total_usd: 0,
                        total_pen: 0
                    },
                    fees_settlements: {
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
                        perception: 0,
                        import_costs_total: 0,
                        tc: 0,
                        utility: 0
                    },
                    exchange_rate: 0.00,
                }
            },
            create() {
                this.titleDialog = 'Costos de Importación'
                this.form.lading = {... this.recordData.lading }
                this.form.insurace = {... this.recordData.insurace}
                this.form.import_costs = {... this.recordData.import_costs}

                this.form.fees_settlements.isc_dol = this.recordData.isc_dol;
                this.form.fees_settlements.igv_dol = this.recordData.igv_dol;
                this.form.fees_settlements.ipm_dol = this.recordData.ipm_dol;
                this.form.fees_settlements.ad_valorem_dol = this.recordData.ad_valorem_dol;
                this.form.fees_settlements.dispatch_fee_dol = this.recordData.dispatch_fee_dol;
                this.form.fees_settlements.perception_dol = this.recordData.perception_dol;
                this.form.fees_settlements.fine_dol = this.recordData.fine_dol;
                this.form.fees_settlements.utility = this.recordData.utility;

				this.form.fees_settlements.tc = parseFloat(this.recordData.exchange_rate_sale)
            },
            registerPurchaseCreatedEvent() {
                this.$eventHub.$on('savePurchaseNew', (data) => {
                    let price_usd = data.total
                    let price_pen = data.total
                    if(data.currency_type_id == 'PEN') price_usd = price_usd / parseFloat(this.recordData.exchange_rate_sale)
                    else if(data.currency_type_id == 'USD') price_pen = price_pen * parseFloat(this.recordData.exchange_rate_sale)

                    if(data.type == 'lading'){
                        this.form.lading.date = data.date_of_issue
                        this.form.lading.provider_transport = data.supplier
                        this.form.lading.invoice = data.number_full
                        this.form.lading.price = _.round(price_usd, 2)
                        this.form.lading.currency_type_id = 'USD'
                        this.form.lading.purchase_id = data.id

                        this.$emit('addedLading', this.form.lading)
                    }else if(data.type == 'insurace'){
                        this.form.insurace.date = data.date_of_issue
                        this.form.insurace.insurace_transport = data.supplier
                        this.form.insurace.invoice = data.number_full
                        this.form.insurace.price = _.round(price_usd, 2)
                        this.form.insurace.currency_type_id = 'USD'
                        this.form.insurace.purchase_id = data.id
                    }else if(data.type == 'commission'){
                        this.form.import_costs.commission_pen = _.round(price_pen, 2)
                        this.form.import_costs.commission_usd = _.round(price_usd, 2)
                        this.form.import_costs.commission_invoice = data.number_full
                        this.form.import_costs.commission_purchase_id = data.id
                    }else if(data.type == 'stow_unstow'){
                        this.form.import_costs.stow_unstow_pen = _.round(price_pen, 2)
                        this.form.import_costs.stow_unstow_usd = _.round(price_usd, 2)
                        this.form.import_costs.stow_unstow_invoice = data.number_full
                        this.form.import_costs.stow_unstow_purchase_id = data.id
                    }else if(data.type == 'handling'){
                        this.form.import_costs.handling_pen = _.round(price_pen, 2)
                        this.form.import_costs.handling_usd = _.round(price_usd, 2)
                        this.form.import_costs.handling_invoice = data.number_full
                        this.form.import_costs.handling_purchase_id = data.id
                    }else if(data.type == 'transport'){
                        this.form.import_costs.transport_pen = _.round(price_pen, 2)
                        this.form.import_costs.transport_usd = _.round(price_usd, 2)
                        this.form.import_costs.transport_invoice = data.number_full
                        this.form.import_costs.transport_purchase_id = data.id
                    }else if(data.type == 'antidumping'){
                        this.form.import_costs.antidumping_pen = _.round(price_pen, 2)
                        this.form.import_costs.antidumping_usd = _.round(price_usd, 2)
                        this.form.import_costs.antidumping_invoice = data.number_full
                        this.form.import_costs.antidumping_purchase_id = data.id
                    }else if(data.type == 'storage'){
                        this.form.import_costs.storage_pen = _.round(price_pen, 2)
                        this.form.import_costs.storage_usd = _.round(price_usd, 2)
                        this.form.import_costs.storage_invoice = data.number_full
                        this.form.import_costs.storage_purchase_id = data.id
                    }else if(data.type == 'dispatch'){
                        this.form.import_costs.dispatch_pen = _.round(price_pen, 2)
                        this.form.import_costs.dispatch_usd = _.round(price_usd, 2)
                        this.form.import_costs.dispatch_invoice = data.number_full
                        this.form.import_costs.dispatch_purchase_id = data.id
                    }else if(data.type == 'others'){
                        this.form.import_costs.others[data.index].pen = _.round(price_pen, 2)
                        this.form.import_costs.others[data.index].usd = _.round(price_usd, 2)
                        this.form.import_costs.others[data.index].invoice = data.number_full
                        this.form.import_costs.others[data.index].purchase_id = data.id
                    }
                    this.calculateTotalImportCosts()
                });
            },
            calculateTotalImportCosts() {
				let total_others_pen = 0
				let total_others_usd = 0
				this.form.import_costs.others.forEach( row => {
					total_others_pen += row.pen
					total_others_usd += row.usd
				})
                this.form.import_costs.total_usd = this.form.import_costs.commission_usd + this.form.import_costs.stow_unstow_usd + this.form.import_costs.handling_usd +
                    this.form.import_costs.transport_usd + this.form.import_costs.antidumping_usd + this.form.import_costs.storage_usd +
                    this.form.import_costs.dispatch_service_usd + total_others_usd
                this.form.import_costs.total_pen = this.form.import_costs.commission_pen + this.form.import_costs.stow_unstow_pen + this.form.import_costs.handling_pen +
                    this.form.import_costs.transport_pen + this.form.import_costs.antidumping_pen + this.form.import_costs.storage_pen +
                    this.form.import_costs.dispatch_service_pen + total_others_pen
				this.form.fees_settlements.import_costs_total = this.form.import_costs.total_pen
            },
			clickAddOther(){
				this.form.import_costs.others.push({
					pen:0,
					usd:0,
					invoice: null,
                    purchase_id: null
				});
			},
			clickRemoveOther(index){
				this.form.import_costs.others.splice(index, 1)
			},
            async submit() {
                this.form.fees_settlements = this.fees_settlements;
                this.form.exchange_rate = this.exchange_rate;
                this.row = {... this.form}

                this.$emit('processImportCosts', this.row)
				this.close()

            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            },

            clickAddPurchase(type, index){
                // TODO fix bug derechos data is overwritten by newRecordData
				this.$emit('showPurchaseNew', {type: type, index: index})
            },
        },
        computed: {
            ...mapState([
                'config',
            ]),
            iscSoles: {
                get: function() {
                    return this.fees_settlements.isc_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.isc_sol = +newValue;
                    this.fees_settlements.isc_dol = _.round(+this.fees_settlements.isc_sol / this.exchange_rate, 2);
                }
            },
            iscDolares: {
                get: function() {
                    return this.fees_settlements.isc_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.isc_dol = +newValue;
                    this.fees_settlements.isc_sol = _.round(+this.fees_settlements.isc_dol * this.exchange_rate, 2);
                }
            },
            igvSoles: {
                get: function() {
                    return this.fees_settlements.igv_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.igv_sol = +newValue;
                    this.fees_settlements.igv_dol = _.round(+this.fees_settlements.igv_sol / this.exchange_rate, 2);
                }
            },
            igvDolares: {
                get: function() {
                    return this.fees_settlements.igv_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.igv_dol = +newValue;
                    this.fees_settlements.igv_sol = _.round(+this.fees_settlements.igv_dol * this.exchange_rate, 2);
                }
            },
            ipmSoles: {
                get: function() {
                    return this.fees_settlements.ipm_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.ipm_sol = +newValue;
                    this.fees_settlements.ipm_dol = _.round(+this.fees_settlements.ipm_sol / this.exchange_rate, 2);
                }
            },
            ipmDolares: {
                get: function() {
                    return this.fees_settlements.ipm_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.ipm_dol = +newValue;
                    this.fees_settlements.ipm_sol = _.round(+this.fees_settlements.ipm_dol * this.exchange_rate, 2);
                }
            },
            adValoremSol: {
                get: function() {
                    return this.fees_settlements.ad_valorem_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.ad_valorem_sol = +newValue;
                    this.fees_settlements.ad_valorem_dol = _.round(+this.fees_settlements.ad_valorem_sol / this.exchange_rate, 2);
                }
            },
            adValoremDol: {
                get: function() {
                    return this.fees_settlements.ad_valorem_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.ad_valorem_dol = +newValue;
                    this.fees_settlements.ad_valorem_sol = _.round(+this.fees_settlements.ad_valorem_dol * this.exchange_rate, 2);
                }
            },
            dispatchFeeSoles: {
                get: function() {
                    return this.fees_settlements.dispatch_fee_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.dispatch_fee_sol = +newValue;
                    this.fees_settlements.dispatch_fee_dol = _.round(+this.fees_settlements.dispatch_fee_sol / this.exchange_rate, 2);
                }
            },
            dispatchFeeDolares: {
                get: function() {
                    return this.fees_settlements.dispatch_fee_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.dispatch_fee_dol = +newValue;
                    this.fees_settlements.dispatch_fee_sol = _.round(+this.fees_settlements.dispatch_fee_dol * this.exchange_rate, 2);
                }
            },
            perceptionSoles: {
                get: function() {
                    return this.fees_settlements.perception_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.perception_sol = +newValue;
                    this.fees_settlements.perception_dol = _.round(+this.fees_settlements.perception_sol / this.exchange_rate, 2);
                }
            },
            perceptionDolares: {
                get: function() {
                    return this.fees_settlements.perception_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.perception_dol = +newValue;
                    this.fees_settlements.perception_sol = _.round(+this.fees_settlements.perception_dol * this.exchange_rate, 2);
                }
            },
            fineSoles: {
                get: function() {
                    return this.fees_settlements.fine_sol;
                },
                set: function(newValue) {
                    this.fees_settlements.fine_sol = +newValue;
                    this.fees_settlements.fine_dol = _.round(+this.fees_settlements.fine_sol / this.exchange_rate, 2);
                }
            },
            fineDolares: {
                get: function() {
                    return this.fees_settlements.fine_dol;
                },
                set: function(newValue) {
                    this.fees_settlements.fine_dol = +newValue;
                    this.fees_settlements.fine_sol = _.round(+this.fees_settlements.fine_dol * this.exchange_rate, 2);
                }
            },
        },
        watch: {
            recordData(newRecordData, oldRecordData) {
                if (newRecordData) {
                    this.exchange_rate = newRecordData.exchange_rate;
                    this.fees_settlements.isc_dol = newRecordData.isc_dol;
                    this.fees_settlements.igv_dol = newRecordData.igv_dol;
                    this.fees_settlements.ipm_dol = newRecordData.ipm_dol;
                    this.fees_settlements.ad_valorem_dol = newRecordData.ad_valorem_dol;
                    this.fees_settlements.dispatch_fee_dol = newRecordData.dispatch_fee_dol;
                    this.fees_settlements.perception_dol = newRecordData.perception_dol;
                    this.fees_settlements.fine_dol = newRecordData.fine_dol;
                    this.fees_settlements.utility = newRecordData.utility;

                    this.fees_settlements.isc_sol = _.round(newRecordData.isc_dol * this.exchange_rate, 2);
                    this.fees_settlements.igv_sol = _.round(newRecordData.igv_dol * this.exchange_rate, 2);
                    this.fees_settlements.ipm_sol = _.round(newRecordData.ipm_dol * this.exchange_rate, 2);
                    this.fees_settlements.ad_valorem_sol = _.round(newRecordData.ad_valorem_dol * this.exchange_rate, 2);
                    this.fees_settlements.dispatch_fee_sol = _.round(newRecordData.dispatch_fee_dol * this.exchange_rate, 2);
                    this.fees_settlements.perception_sol = _.round(newRecordData.perception_dol * this.exchange_rate, 2);
                    this.fees_settlements.fine_sol = _.round(newRecordData.fine_dol * this.exchange_rate, 2);
                    this.fees_settlements.utility = newRecordData.utility;
                }
            }
        }
    }
</script>
