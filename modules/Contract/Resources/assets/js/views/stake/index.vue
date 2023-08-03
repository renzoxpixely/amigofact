<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" width="90%">
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
                                        <th class="text-left">Initial BET</th>
                                        <th class="text-left">Initial Win</th>
                                        <th class="text-left">Initial Progressive Win</th>
                                        <th class="text-left">Final BET</th>
                                        <th class="text-left">Final Win</th>
                                        <th class="text-left">Final Progressive Win</th>
										<th class="text-left">RESULT IN</th>
										<th class="text-left">RESULT OUT</th>
										<th class="text-left">RESULT PROGRESSIVE WIN</th>
										<th class="text-left">RESULTADO BRUTO</th>
										<th class="text-left">TASA DE MANTIMIENTO</th>
										<th class="text-left">TASA DE IMPUESTO</th>
										<th class="text-left">ISC</th>
										<th class="text-left">RESULTADO NETO</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in records" :key="index">
                                        <td>{{index+1}}</td>
                                        <td>{{row.start_period}}</td>
                                        <td>{{row.end_period}}</td>
                                        <td>{{row.initial_bet}}</td>
                                        <td>{{row.initial_win}}</td>
                                        <td>{{row.initial_progressive_win}}</td>
                                        <td>{{row.final_bet}}</td>
                                        <td>{{row.final_win}}</td>
                                        <td>{{row.final_progressive_win}}</td>
                                        <td>{{row.result_in}}</td>
                                        <td>{{row.result_out}}</td>
                                        <td>{{row.result_progressive_win}}</td>
                                        <td>{{row.result_crude}}</td>
                                        <td>{{row.result_maintenance}}</td>
                                        <td>{{row.result_tax}}</td>
                                        <td>{{row.result_isc}}</td>
                                        <td>{{row.result_net}}</td>
                                        <td class="text-right">
											<button type="button" v-show="!row.is_confirmed" class="btn waves-effect waves-light btn-xs btn-primary"
												@click.prevent="clickEdit(index)">Editar</button>
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
                <el-button @click.prevent="close()">Cerrar</el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import queryString from "query-string";
export default {
    props:['showDialog', 'contractItemId', 'itemLotId'],
    data() {
        return {
            resource: 'stake_periods',
            recordId: null,
            titleDialog: 'Listado de Periodos',
            loading_submit: false,
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
		clickEdit(index){
			const row = this.records[index]
			this.$emit('clickEditStake', row.id)
		},
        close() {
            this.$emit('update:showDialog', false)
        },
    }
}
</script>
