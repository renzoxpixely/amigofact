<template>
    <div>
        <div class="mb-0 pt-2 pt-md-0">
            <div class="card-header bg-info">
                <h3 class="my-0">CONTRATO</h3>
            </div>
            <div v-loading="loading" class="card-body">
                <div class="table-responsive">
                    <el-tabs v-model="tab">
                        <el-tab-pane label="CONTRATO" name="contract">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Numero</label>
                                                    <el-input v-model="identifier" disabled></el-input>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Fecha</label>
                                                    <el-date-picker v-model="form.date_of_issue" type="date" value-format="yyyy-MM-dd" disabled></el-date-picker>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="form-group">
                                                    <label class="control-label">Tipo Contrato</label>
                                                    <el-input v-model="form.contract_type.name" disabled></el-input>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="control-label">Cliente</label>
                                                    <el-input v-model="customerName" disabled></el-input>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="control-label">Dirección</label>
                                                    <el-input v-model="form.address"></el-input>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="control-label">Descripción</label>
                                                    <el-input  type="textarea"  :rows="3" v-model="form.description"></el-input>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <el-table :data="form.items">
                                            <el-table-column label="Item"
                                                            property="item.description"
                                                            ></el-table-column>

                                            <el-table-column label="Sub total"
                                                            property="quantity"
                                                            width="100"></el-table-column>

                                            <el-table-column label="IGV"
                                                            property="unit_price"
                                                            width="100"></el-table-column>
                                            <el-table-column label="Total"
                                                            property="total"
                                                            width="100"></el-table-column>
                                        </el-table>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                        <!--<el-tab-pane label="SALAS" name="halls">
							<div class="form-body">
								<h3 class="mb-0 font-weight-bold">SALAS</h3>
								<div class="row mt-2">
                                    <div class="col-lg-3">
										<div class="form-group">
											<label class="control-label">Nombre Sala</label>
											<el-input v-model="form.address"></el-input>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label class="control-label">Capacidad</label>
											<el-input v-model="form.address"></el-input>
										</div>
									</div>
									<div class="col-lg-3" style="margin-top:29px">
										<el-button class="submit" type="primary">Agregar</el-button>
									</div>
								</div>
								<div class="row mt-2">
									<div class="col-lg-12">
										<el-table :data="form.items">
											<el-table-column label="Item"
															property="item.description"
															></el-table-column>

											<el-table-column label="Sub total"
															property="quantity"
															width="100"></el-table-column>

											<el-table-column label="IGV"
															property="unit_price"
															width="100"></el-table-column>
											<el-table-column label="Total"
															property="total"
															width="100"></el-table-column>
										</el-table>
									</div>
								</div>
							</div>
                        </el-tab-pane>-->
                        <el-tab-pane label="MÁQUINAS" name="machines">
							<div class="form-body">
								<h3 class="mb-0 font-weight-bold">MÁQUINAS</h3>
								<!--<div class="row mt-2">
                                    <div class="col-lg-3">
										<div class="form-group">
											<label class="control-label">Descripción</label>
											<el-input v-model="form.address"></el-input>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label class="control-label">Serie</label>
											<el-input v-model="form.address"></el-input>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="form-group">
											<label class="control-label">Seleccionar Sala</label>
											<el-input v-model="form.address"></el-input>
										</div>
									</div>
									<div class="col-lg-3" style="margin-top:29px">
										<el-button class="submit" type="primary">Agregar Nuevo maquina</el-button>
									</div>
								</div>-->
								<div class="row mt-2">
									<div class="col-lg-12">
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th class="text-left">Descripción</th>
														<th class="text-left">Sala</th>
                                                        <th class="text-left">Serie</th>
														<th class="text-left">Fecha de Inicio</th>
														<th class="text-left">Fecha de Fin</th>
														<th class="text-left">Precio</th>
														<th class="text-left">Depreciación</th>
														<th class="text-left">Depreciación Acumulada</th>
														<th class="text-left">Precio con Depreciación</th>
														<th class="text-left">Fecha de Activación</th>
														<th class="text-right">Acciones</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(row, index) in form.machines" :key="index">
														<td>{{ row.description }}</td>
														<td>{{ row.hall ? row.hall.name : '-' }}</td>
                                                        <td>
                                                            <el-select
                                                            v-if="row.edit"
                                                            v-model="row.lot"
                                                            value-key="id">
                                                                <el-option
                                                                    v-for="(serie, index) in getSeries(row.item.id)"
                                                                    :key="index"
                                                                    :label="serie.series"
                                                                    :value="serie"
                                                                >
                                                                </el-option>
                                                            </el-select>
                                                            <span v-else>{{row.lot.series}}</span>
                                                        </td>
														<td>
															<el-date-picker v-show="row.edit" v-model="row.depreciation.start_date"
																:clearable="true"
																type="date"
																value-format="yyyy-MM-dd"
																@keyup.native="calculateDepreciation(index)" @change="calculateDepreciation(index)"></el-date-picker>
															<div v-show="!row.edit">{{row.depreciation.start_date}}</div>
														</td>
														<td>{{row.depreciation.end_date}}</td>
														<td>{{form.currency_type.symbol}} {{row.unit_price}}</td>
														<td>
															<el-input v-show="row.edit" type="number" min="0" step="0.01" v-model.number="row.depreciation.percentage"
																@keyup.native="calculateDepreciation(index)" @change="calculateDepreciation(index)">
																<template slot="append">%</template>
															</el-input>
															<div v-show="!row.edit">{{row.depreciation.percentage}}%</div>
														</td>
														<td>{{row.depreciation.accumulated}}%</td>
														<td>{{form.currency_type.symbol}} {{row.depreciation.accumulated_value}}</td>
														<td>
															<el-date-picker v-show="row.edit" v-model="row.activation_date"
																:clearable="true"
																type="date"
																value-format="yyyy-MM-dd"></el-date-picker>
															<div v-show="!row.edit">{{row.activation_date}}</div>
														</td>
														<td class="text-right">
															<button type="button" v-show="!row.edit" class="btn waves-effect waves-light btn-xs btn-primary"
                                    								@click.prevent="clickEdit(index)">Editar</button>
															<button type="button" v-show="row.edit"  class="btn waves-effect waves-light btn-xs btn-success"
																	@click.prevent="clickSaveEdit(index)" :loading="row.loading">Guardar</button>
															<button type="button" v-show="row.edit"  class="btn waves-effect waves-light btn-xs btn-danger"
																	@click.prevent="clickCancelEdit(index)">Cancelar</button>
														</td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
                        </el-tab-pane>
                        <el-tab-pane label="CONSUMOS" name="consumptions">
							<div class="form-body">
								<h3 class="mb-0 font-weight-bold">CONSUMOS</h3>
								<div class="row mt-2">
									<div class="col-lg-12">
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th class="text-left">Descripción</th>
														<th class="text-left">Serie</th>
														<th class="text-left">Sala</th>
														<th class="text-left">Precio</th>
														<th class="text-right">Acciones</th>
													</tr>
												</thead>
												<tbody>
													<tr v-for="(row, index) in form.machines_with_series" :key="index">
														<td>{{row.description}}</td>
														<td>{{ row.lot ? row.lot.series : '-' }}</td>
														<td>{{ row.hall ? row.hall.name : '-' }}</td>
														<td>{{ form.currency_type.symbol}} {{row.unit_price}}</td>
														<td class="text-right"  v-if="form.is_rental || form.is_stake">
															<button type="button" class="btn waves-effect waves-light btn-xs btn-warning"
                                    								@click.prevent="clickViewPaymentReceipts(index)">Recibos de pago</button>
															<button type="button" class="btn waves-effect waves-light btn-xs btn-success"
                                    								@click.prevent="clickViewPeriods(index)">Ver Periodos</button>
															<button type="button" class="btn waves-effect waves-light btn-xs btn-primary"
                                    								@click.prevent="clickRegisterPeriod(index)">{{ form.is_rental ? 'Registrar Periodo' : 'Registrar Periodos' }}</button>
														</td>
														<td v-else></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
							</div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </div>
        </div>
		<!--Stake-->
		<index-stake
			:showDialog.sync="showDialogIndexStake"
			:contractItemId="contractItemId"
			:itemLotId="itemLotId"
			@clickEditStake="clickEditStake">
		</index-stake>
		<form-stake
			:showDialog.sync="showDialogFormStake"
			:stakeId="stakeId"
			:contractItemId="contractItemId"
			:itemLotId="itemLotId"
			:contractTypeId="form.contract_type_id">
		</form-stake>
		<payment-receipt-stake
			:showDialog.sync="showDialogPaymentReceiptStake"
			:contractItemId="contractItemId"
			:itemLotId="itemLotId">
		</payment-receipt-stake>
		<!--Rental-->
		<index-rental
			:showDialog.sync="showDialogIndexRental"
			:contractItemId="contractItemId"
			:itemLotId="itemLotId">
		</index-rental>
		<period-rental
			:showDialog.sync="showDialogPeriodRental"
			:contractItemId="contractItemId"
			:itemLotId="itemLotId">
		</period-rental>
		<payment-receipt-rental
			:showDialog.sync="showDialogPaymentReceiptRental"
			:contractItemId="contractItemId"
			:itemLotId="itemLotId">
		</payment-receipt-rental>
    </div>
</template>
<script>
import IndexStake from '../stake/index.vue'
import FormStake from '../stake/partials/form.vue'
import PaymentReceiptStake from '../stake/partials/payment_receipt.vue'
import IndexRental from '../rental/index.vue'
import PeriodRental from '../rental/partials/period.vue'
import IncidentRental from '../rental/partials/incident.vue'
import PaymentReceiptRental from '../rental/partials/payment_receipt.vue'
import moment from 'moment'
export default {
    props:['typeUser', 'quotationId', 'id', 'showPayments'],
    components:{IndexStake, FormStake, PaymentReceiptStake, IndexRental, PeriodRental, IncidentRental, PaymentReceiptRental},
    async created() {
        this.initForm();
        if(this.id) {
            await this.$http.get(`/${this.resource}/record/${this.id}`)
            .then(response => {
                this.form = response.data.data.contract;
				this.identifier = response.data.data.identifier;
				this.form.machines.forEach( (row, index) => {
					this.calculateDepreciation(index)
				})
				this.form.machines_with_series = this.getMachineWithSeries(this.form.machines)
				//console.log(this.form.machines)
            });
        }
            const { data } = await this.$http.get(`/${this.resource}/machines-by-contract/${this.id}`)
            this.machineSeries = data;
    },
    data() {
        return {
            loading: false,
			showDialogIndexStake: false,
			showDialogFormStake: false,
			showDialogPaymentReceiptStake: false,
			showDialogIndexRental: false,
			showDialogPeriodRental: false,
			showDialogPaymentReceiptRental: false,
            tab:'contract',
            form: {},
            resource: 'contracts',
			stakeId: null,
			contractItemId: null,
			itemLotId: null,
			identifier: null,
            machineSeries: []
        };
    },
	computed: {
		customerName(){
			return this.form.customer ? `${this.form.customer.number} - ${this.form.customer.name}` : null;
		}
	},
    methods: {
        initForm() {
            this.form = {
				contract_type: {name: null}
            }
        },
        getSeries(aMachineId) {
            return this.machineSeries.filter(lot => lot.item_id === aMachineId);
        },
		getMachineWithSeries(machines){
			let machines_with_series = []
			machines.forEach(row => {
				if(row.lot) machines_with_series.push(row)
			});
			return machines_with_series
		},
		clickEdit(index) {
			this.form.machines[index].depreciation.start_date_temp = this.form.machines[index].depreciation.start_date
			this.form.machines[index].depreciation.percentage_temp = this.form.machines[index].depreciation.percentage
			this.form.machines[index].activation_date_temp = this.form.machines[index].activation_date
			this.form.machines[index].edit = true
		},
		async clickSaveEdit(index) {
			try{
				this.form.machines[index].loading = true
				const row = this.form.machines[index]
				const form = {
					contract_id: row.contract_id,
					contract_item_id: row.id,
					contract_item_lot_id: row.contract_item_lot_id,
					depreciation: row.depreciation,
					activation_date: row.activation_date,
                    item_lot: row.lot.id
				}
				let response = await this.$http.post(`/${this.resource}/save/items`, form)
				if(!response.data.success) throw Error(response.data.message)
				this.form.machines[index].edit = false
				this.form.machines[index].loading = false
				//this.calculateDepreciation(index)
			}catch(error){
				this.form.machines[index].loading = false
				this.$message.error(error)
			}
		},
		clickCancelEdit(index) {
			this.form.machines[index].depreciation.start_date = this.form.machines[index].depreciation.start_date_temp
			this.form.machines[index].depreciation.percentage = this.form.machines[index].depreciation.percentage_temp
			this.form.machines[index].activation_date = this.form.machines[index].activation_date_temp
			this.form.machines[index].edit = false
			this.calculateDepreciation(index)
		},
		calculateDepreciation(index) {
			const row = this.form.machines[index]
			const depreciation = row.depreciation
			let unit_price = row.unit_price
			if(depreciation.start_date != null && depreciation.percentage > 0){

				let days = Math.ceil((100/depreciation.percentage)*365)
				let start_date = moment(depreciation.start_date, "YYYY-MM-DD")
				let end_date = moment(depreciation.start_date, "YYYY-MM-DD")
				let now = moment().utcOffset(0)

				now.set({hour:0,minute:0,second:0,millisecond:0})
				end_date.add(days, 'days')
				let res_days = now.diff(start_date, 'days')
				let accumulated_now = (res_days*depreciation.percentage)/365
				let accumulated_value = unit_price - ((accumulated_now / 100) * unit_price)
				this.form.machines[index].depreciation.end_date= end_date.format("YYYY-MM-DD")
				this.form.machines[index].depreciation.accumulated= _.round(accumulated_now, 2)
				this.form.machines[index].depreciation.accumulated_value = _.round(accumulated_value, 2)

			}else{
				this.form.machines[index].depreciation.end_date= null
				this.form.machines[index].depreciation.accumulated= 0
				this.form.machines[index].depreciation.accumulated_value= _.round(unit_price,2)
			}
		},
		clickViewPeriods(index){
			const row = this.form.machines_with_series[index]
			this.contractItemId = row.id
			this.itemLotId = row.lot ? row.lot.item_lot_id : null

			if(this.form.is_stake) this.showDialogIndexStake= true
			else if (this.form.is_rental) this.showDialogIndexRental= true
		},
		clickRegisterPeriod(index){
			const row = this.form.machines_with_series[index]
			this.stakeId = null
			this.contractItemId = row.id
			this.itemLotId = row.lot ? row.lot.item_lot_id : null

			if(this.form.is_stake) this.showDialogFormStake= true
			else if (this.form.is_rental) this.showDialogPeriodRental= true
		},
		clickEditStake(id){
			this.stakeId = id
			this.showDialogFormStake= true
		},
		clickViewPaymentReceipts(index){
			const row = this.form.machines_with_series[index]
			this.contractItemId = row.id
			this.itemLotId = row.lot ? row.lot.item_lot_id : null

			if(this.form.is_stake) this.showDialogPaymentReceiptStake= true
			else if (this.form.is_rental) this.showDialogPaymentReceiptRental= true

		}
    }
}
</script>
