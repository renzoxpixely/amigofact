<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" width="60%">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row" v-loading="loading_submit">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">Periodo Inicio</th>
                                        <th class="text-left">Periodo Fin</th>
										<th class="text-left">Código</th>
                                        <th class="text-left">Dias de Incidencias</th>
                                        <th class="text-left">Monto</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in records" :key="index">
                                        <td>{{index+1}}</td>
                                        <td>{{row.start_period}}</td>
                                        <td>{{row.end_period}}</td>
										<td>{{row.code}}</td>
										<td>{{row.days}}</td>
										<td>{{row.currency_type.symbol}} {{row.amount}}</td>
                                        <td class="text-right">
                                            <button type="button" class="btn waves-effect waves-light btn-xs btn-warning"
												@click.prevent="clickEditCode(index)">Código</button>
											<button type="button" v-show="!row.sale_note_id" class="btn waves-effect waves-light btn-xs btn-primary"
												@click.prevent="clickCreateIncident(index)">Crear Incidencia</button>
                                                <button type="button" v-show="!row.sale_note_id" class="btn waves-effect waves-light btn-xs btn-success"
												@click.prevent="clickConfirmReceipt(index)">Confirmar Recibo</button>
										</td>
                                    </tr>
                                </tbody>
                            </table>
                            <div>
                                <el-pagination
                                    @current-change="getRecords"
                                    layout="total, prev, pager, next"
                                    :total="pagination.total"
                                    :current-page.sync="pagination.current_page"
                                    :page-size="pagination.per_page"
                                >
                                </el-pagination>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button type="primary" @click.prevent="remove()" :loading="loading_remove">Eliminar Periodos</el-button>
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
		
		<form-incident 
            :showDialog.sync="showDialogFormIncident"
            :rentalPeriodId="rentalPeriodId">
        </form-incident>

        <form-code 
            :showDialog.sync="showDialogFormCode"
            :rentalPeriodId="rentalPeriodId"
            :recordCode="recordCode">
        </form-code>
		
    </el-dialog>
</template>

<script>
import FormIncident from "./partials/incident.vue";
import FormCode from "./partials/code.vue";
import queryString from "query-string";
export default {
    props:['showDialog', 'contractItemId', 'itemLotId'],
    components: {FormIncident, FormCode},
    data() {
        return {
            resource: 'rental_periods',
            recordId: null,
            recordCode: null,
            titleDialog: 'Listado de Periodos',
            showDialogFormIncident: false,
            showDialogFormCode: false,
            loading_submit: false,
			loading_remove: false,
            rentalPeriodId: null,
            pagination: {
                current_page: 1
            },
            records: []
        }
    },
    created() {
        this.$eventHub.$on("reloadData", () => {
            this.getRecords();
        });
    },
    methods: {
        create(){
            this.getRecords();
        },
        getRecords(){
            this.loading_submit = true;
            return this.$http
                .get(`/${this.resource}/records?${this.getQueryParameters()}`)
                .then(response => {
                    this.records = response.data.data;
                    this.pagination = response.data.meta;
                    this.pagination.per_page = parseInt(
                        response.data.meta.per_page
                    );
                })
                .catch(error => {})
                .then(() => {
                    this.loading_submit = false;
                });
        },
        getQueryParameters() {
            return queryString.stringify({
                page: this.pagination.current_page,
                contract_item_id: this.contractItemId,
                item_lot_id: this.itemLotId
            });
        },
		clickCreateIncident(index){
			const row = this.records[index]
            this.rentalPeriodId = row.id
            this.showDialogFormIncident = true
			
		},
        clickEditCode(index){
            const row = this.records[index]
            this.rentalPeriodId = row.id
            this.recordCode = row.code
            this.showDialogFormCode = true
        },
        clickConfirmReceipt(index){
			const row = this.records[index]
            this.$confirm('¿Desea generar la nota de venta del periodo?', 'Confirmar', {
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                type: 'warning',
				beforeClose: (action, instance, done) => {
					if (action === "confirm") {
						instance.confirmButtonLoading = true;
						instance.confirmButtonText = "Cargando...";
						const form = { rental_period_id: row.id}
						this.$http.post(`/${this.resource}/confirm`, form)
						.then(res => {
							if(res.data.success) {
								this.$message.success(res.data.message)
								this.getRecords()
							}else{
								this.$message.error(res.data.message)
							}
						})
						.catch(error => {
							if (error.response.status === 500) {
								this.$message.error('Error al intentar confirmar');
							} else {
								console.log(error.response.data.message)
							}
						}).then(() => {
							instance.confirmButtonLoading = false;
							done();
						});
					}else{
						done()
					}
				}
            }).then(() => {
                
            }).catch(error => {
                console.log(error)
            });
		},
        remove(){
			this.loading_remove = true  
			const data = {contract_item_id: this.contractItemId, item_lot_id: this.itemLotId}
			this.$http.post(`/${this.resource}/destroy`, data)
			.then(response => {
				if (response.data.success) {
					this.$message.success(response.data.message)
					this.getRecords()
					//this.close()
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
				this.loading_remove = false
			})
        },
        close() {
            this.$emit('update:showDialog', false)
        },
    }
}
</script>
