<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create"  @close="close" width="60%">
        <form autocomplete="off">
            <el-tabs v-model="tabActive">
                <el-tab-pane class name="first">
                    <span slot="label">
                        Registrar
                    </span>
                    <div class="form-body">
                        <div class="row">
                            <div :class="{'has-danger': errors.start_period}"
                                 class="form-group col-3">
                                <label class="control-label">Periodo Inicio</label>
                                <el-date-picker v-model="form.start_period" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                <small v-if="errors.start_period"
                                       class="form-control-feedback"
                                       v-text="errors.start_period[0]"></small>
                            </div>
                            <div :class="{'has-danger': errors.end_period}"
                                 class="form-group col-3">
                                <label class="control-label">Periodo Fin</label>
                                <el-date-picker v-model="form.end_period" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                <small v-if="errors.end_period"
                                       class="form-control-feedback"
                                       v-text="errors.end_period[0]"></small>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane class name="second">
                    <span slot="label">
                        Configuración
                    </span>
                    <div class="form-body">
                        <div class="row">
                             <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.factor_creed}">
                                    <label class="control-label">Factor CRED</label>
                                    <el-input type="number" step=".01" v-model="form.factor_creed" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                    <small class="form-control-feedback" v-if="errors.factor_creed" v-text="errors.factor_creed[0]"></small>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.maintenance_rate}">
                                    <label class="control-label">Tasa de mantenimiento (%)</label>
                                    <el-input type="number" step=".01" v-model="form.maintenance_rate" @keyup.native="calculateTotals()" @change="calculateTotals()">
                                        <template slot="append">%</template>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.maintenance_rate" v-text="errors.maintenance_rate[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.tax_rate}">
                                    <label class="control-label">Tasa de Impuesto (%)</label>
                                    <el-input type="number" step=".01" v-model="form.tax_rate" @keyup.native="calculateTotals()" @change="calculateTotals()">
                                        <template slot="append">%</template>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.tax_rate" v-text="errors.tax_rate[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.uit}">
                                    <label class="control-label">UIT</label>
                                    <el-input type="number" step=".01" v-model="form.uit" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                    <small class="form-control-feedback" v-if="errors.uit" v-text="errors.uit[0]"></small>
                                </div>
                            </div> 
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.isc_one_uit}">
                                    <label class="control-label">ISC 1 UIT (%)</label>
                                    <el-input type="number" step=".01" v-model="form.isc_one_uit" @keyup.native="calculateTotals()" @change="calculateTotals()">
                                        <template slot="append">%</template>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.isc_one_uit" v-text="errors.isc_one_uit[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.isc_three_uit}">
                                    <label class="control-label">ISC 3 UIT</label>
                                    <el-input type="number" step=".01" v-model="form.isc_three_uit" @keyup.native="calculateTotals()" @change="calculateTotals()">
                                        <template slot="append">%</template>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.isc_three_uit" v-text="errors.isc_three_uit[0]"></small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.isc_more_than_three_uit}">
                                    <label class="control-label">ISC MORE 3 UIT</label>
                                    <el-input type="number" step=".01" v-model="form.isc_more_than_three_uit" @keyup.native="calculateTotals()" @change="calculateTotals()">
                                        <template slot="append">%</template>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.isc_more_than_three_uit" v-text="errors.isc_more_than_three_uit[0]"></small>
                                </div>
                            </div> 
							<div class="col-md-3">
                                <div class="form-group" :class="{'has-danger': errors.participation_percentage}">
                                    <label class="control-label">Porcentage Participación (%)</label>
                                    <el-input type="number" step=".01" v-model="form.participation_percentage" @keyup.native="calculateTotals()" @change="calculateTotals()">
                                        <template slot="append">%</template>
                                    </el-input>
                                    <small class="form-control-feedback" v-if="errors.participation_percentage" v-text="errors.participation_percentage[0]"></small>
                                </div>
                            </div> 
                            
                        </div>
                    </div>
                </el-tab-pane>
            </el-tabs>
            <div class="form-body">
                <div class="row mt-2">
                    <div class="col-lg-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-left">Initial BET</th>
                                        <th class="text-left">Initial Win</th>
                                        <th class="text-left">Initial Progressive Win</th>
                                        <th class="text-left">Final BET</th>
                                        <th class="text-left">Final Win</th>
                                        <th class="text-left">Final Progressive Win</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <el-input type="number" step=".01" v-model="form.initial_bet" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                        </td>
                                        <td>
                                            <el-input type="number" step=".01" v-model="form.initial_win" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                        </td>
                                        <td>
                                            <el-input type="number" step=".01" v-model="form.initial_progressive_win" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                        </td>
                                        <td>
                                            <el-input type="number" step=".01" v-model="form.final_bet" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                        </td>
                                        <td>
                                            <el-input type="number" step=".01" v-model="form.final_win" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                        </td>
                                        <td>
                                            <el-input type="number" step=".01" v-model="form.final_progressive_win" @keyup.native="calculateTotals()" @change="calculateTotals()"></el-input>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <p class="text-right m-0">RESULT IN: {{ form.currency_type.symbol }} {{form.result_in}}</p>
                        <p class="text-right m-0">RESULT OUT: {{ form.currency_type.symbol }} {{form.result_out}}</p>
                        <p class="text-right m-0">RESULT PROGRESSIVE WIN: {{ form.currency_type.symbol }} {{form.result_progressive_win}}</p>
                        <h4 class="text-right m-0"><b>RESULTADO BRUTO: </b>{{ form.currency_type.symbol }} {{form.result_crude}}</h4>
                        <p class="text-right m-0">{{form.maintenance_rate}}%: {{ form.currency_type.symbol }} {{form.result_maintenance}}</p>
                        <p class="text-right m-0">{{form.tax_rate}}%: {{ form.currency_type.symbol }} {{form.result_tax}}</p>
                        <p class="text-right m-0">ISC: {{ form.currency_type.symbol }} {{form.result_isc}}</p>
                        <h4 class="text-right m-0"><b>RESULTADO NETO: </b>{{ form.currency_type.symbol }} {{form.result_net}}</h4>
						<h3 class="text-right m-0"><b>PAGAR ({{form.participation_percentage}}%): </b>{{ form.currency_type.symbol }} {{form.participation_value}}</h3>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    @click.prevent="clickConfirm"
                    type="success">
                    Confirmar
                </el-button>
                <el-button
                    :loading="loading_submit"
                     @click.prevent="clickSave"
                    type="primary">
                    Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import moment from 'moment'
export default {
    props:['showDialog', 'stakeId', 'contractItemId', 'itemLotId', 'contractTypeId'],
    data() {
        return {
            resource: 'stake_periods',
            recordId: null,
            titleDialog: null,
            loading_submit: false,
            tabActive: 'first',
            form: {},
            errors: {}
        }
    },
    created() {
        this.initForm()
    },
    methods: {
        async create(){
			if(this.stakeId){
                this.titleDialog = 'Editar Periodo'
				const response = await this.$http.get(`/${this.resource}/record/${this.stakeId}`);
				this.form = response.data.data
				this.calculateTotals()
			}else{
                this.titleDialog = 'Nuevo Periodo'
				this.form.contract_item_id = this.contractItemId
				this.form.item_lot_id = this.itemLotId 
				const response = await this.$http.get(`/${this.resource}/tables/${this.contractTypeId}`);
				this.form.factor_creed = response.data.factor_creed
				this.form.maintenance_rate = response.data.maintenance_rate
				this.form.tax_rate = response.data.tax_rate
				this.form.uit = response.data.uit
				this.form.isc_one_uit = response.data.isc_one_uit
				this.form.isc_three_uit = response.data.isc_three_uit
				this.form.isc_more_than_three_uit = response.data.isc_more_than_three_uit
                this.form.currency_type_id = response.data.currency_type.id
                this.form.currency_type = response.data.currency_type
			}
        },
        initForm() {
            this.form = {
                id: null,
                currency_type_id: null,
                currency_type: {symbol: null},
                is_confirmed: false,
                contract_item_id: null,
                item_lot_id: null,
				start_period: moment().format('YYYY-MM-DD'),
                end_period: moment().format('YYYY-MM-DD'),
                initial_bet: 0,
                initial_win: 0,
                initial_progressive_win: 0,
                final_bet: 0,
                final_win: 0,
                final_progressive_win: 0,
                factor_creed: 0, 
                maintenance_rate: 0,
                tax_rate: 0,
                uit: 0,
                isc_one_uit: 0,
                isc_three_uit: 0,
                isc_more_than_three_uit: 0,
                result_in: 0,
                result_out: 0,
                result_progressive_win: 0,
                result_crude: 0,
                result_maintenance: 0,
                result_tax: 0,
                result_isc: 0,
                result_net: 0,
				participation_percentage: 0,
				participation_value: 0
            }
            this.errors= {}
            this.tabActive= 'first'
            this.calculateTotals()
        },
        calculateTotals() {
            let result_in = 0
            let result_out = 0
            let result_progressive_win = 0
            let result_crude = 0
            let result_maintenance = 0
            let result_tax = 0
            let result_isc = 0
            let result_net = 0
			let participation_value = 0

            let net_win_one_uit = this.form.uit * 1
            let net_win_three_uit = this.form.uit * 3
            let net_win_more_than_three_uit = (this.form.uit * 3)+0.01

            let isc_amount_one_uit =  this.form.uit*(this.form.isc_one_uit/100)
            let isc_amount_three_uit =  this.form.uit*(this.form.isc_three_uit/100)
            let isc_amount_more_than_three_uit =  this.form.uit*(this.form.isc_more_than_three_uit/100)

            result_in = (this.form.final_bet-this.form.initial_bet)*this.form.factor_creed
            result_out = (this.form.final_win-this.form.initial_win)*this.form.factor_creed
            result_progressive_win = (this.form.final_progressive_win-this.form.initial_progressive_win)*this.form.factor_creed
            result_crude = result_in-result_out-result_progressive_win
            result_maintenance = result_crude*(this.form.maintenance_rate/100)
            result_tax = (result_crude-result_maintenance)*(this.form.tax_rate/100)
            result_isc = (result_crude!=0)?(result_crude<=net_win_one_uit)?isc_amount_one_uit:(result_crude<=net_win_three_uit)?isc_amount_three_uit:isc_amount_more_than_three_uit:0
            result_net = result_crude-result_tax-result_isc
			participation_value = result_net*(this.form.participation_percentage/100)

            this.form.result_in = _.round(result_in, 2)
            this.form.result_out = _.round(result_out, 2)
            this.form.result_progressive_win = _.round(result_progressive_win, 2)
            this.form.result_crude = _.round(result_crude, 2)
            this.form.result_maintenance = _.round(result_maintenance, 2)
            this.form.result_tax = _.round(result_tax, 2)
            this.form.result_isc = _.round(result_isc, 2)
            this.form.result_net = _.round(result_net, 2)
			this.form.participation_value = _.round(participation_value, 2)

        },
        clickConfirm(){
            this.form.is_confirmed = true
            this.submit()
        },
        clickSave(){
            this.form.is_confirmed = false
            this.submit()
        },
        submit() {
            this.loading_submit = true  
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
						this.$eventHub.$emit('reloadData')
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data 
                    } else {
                        console.log(error.response)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
