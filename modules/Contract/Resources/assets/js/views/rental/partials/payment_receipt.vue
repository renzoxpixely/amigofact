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
                                        <th class="text-center">Fecha Emisión</th>
                                        <th>Cliente</th>
                                        <th>Recibo de pago</th>
                                        <th>Estado</th>
                                        <th class="text-center">Moneda</th>
                                        <th class="text-right">F. Vencimiento</th>
                                        <th class="text-right">Total</th>
                                        <th class="text-center">Pagado</th>
                                        <th class="text-center">Por pagar</th>
                                        <th class="text-center">Comprobantes</th>
                                        <th class="text-center">Estado pago</th>
                                        <th class="text-center">Pagos</th>
                                        <th class="text-center">Descarga</th>
                                        <th class="text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, index) in records" :key="index">
                                        <td>{{index+1}}</td>
                                        <td class="text-center">{{ row.date_of_issue }}</td>
                                        <td>{{ row.customer_name }}<br/>
                                            <small v-text="row.customer_number">
                                            </small>
                                        </td>
                                        <td>{{ row.full_number }} </td>
                                        <td>{{ row.state_type_description }}</td>
                                        <td class="text-center">{{ row.currency_type_id }}</td>
                                        <td
                                            class="text-right">{{ row.due_date }}
                                        </td>
                                        <td class="text-right">{{ row.total }}</td>
                                        <td class="text-center">{{ row.total_paid }}</td>
                                        <td class="text-center">{{ row.total_pending_paid }}</td>
                                        <td>
                                            <template v-for="(document,i) in row.documents">
                                                <label :key="i"
                                                    class="d-block"
                                                    v-text="document.number_full">
                                                </label>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <span
                                                :class="{'bg-success': (row.total_canceled), 'bg-warning': (!row.total_canceled)}"
                                                class="badge text-white">{{ row.total_canceled ? 'Pagado' : 'Pendiente' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn waves-effect waves-light btn-xs btn-primary"
                                                    style="min-width: 41px"
                                                    type="button"
                                                    @click.prevent="clickPayment(row.id)">
                                                <i class="fas fa-money-bill-alt">
                                                </i>
                                            </button>
                                        </td>
                                        <td class="text-right">
                                            <button class="btn waves-effect waves-light btn-xs btn-info"
                                                    type="button"
                                                    @click.prevent="clickDownload(row.external_id)">
                                                <i class="fas fa-file-pdf">
                                                </i>
                                            </button>
                                        </td>
                                        <td class="text-right">
											<button v-if="row.state_type_id != '11'"
													class="btn waves-effect waves-light btn-xs btn-success"
													data-placement="top"
													data-toggle="tooltip"
													title="Generar comprobante"
													type="button"
													@click.prevent="clickGenerate(row.id)">
												<i class="fas fa-file-excel">
												</i>
											</button>
                                            <button v-if="row.state_type_id != '11'"
                                                    class="btn waves-effect waves-light btn-xs btn-info"
                                                    data-placement="top"
                                                    data-toggle="tooltip"
                                                    title="Imprimir"
                                                    type="button"
                                                    @click.prevent="clickOptions(row.id)">
                                                <i class="fas fa-print">
                                                </i>
                                            </button>
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
		<sale-note-payments :documentId="recordId"
                            :showDialog.sync="showDialogPayments">
        </sale-note-payments>
		<sale-notes-options :configuration="config"
                            :recordId="saleNotesNewId"
                            :showClose="true"
                            :showDialog.sync="showDialogOptions">
        </sale-notes-options>
		<sale-note-generate :recordId="recordId"
                            :show.sync="showDialogGenerate"
                            :showClose="false"
                            :showGenerate="true">
		</sale-note-generate>
    </el-dialog>
</template>

<script>
import queryString from "query-string";
import SaleNotePayments from '@viewsModuleSuscription/payment_receipt/partials/payments.vue'
import SaleNotesOptions from '@viewsModuleSuscription/payment_receipt/partials/options.vue'
import SaleNoteGenerate from '@viewsModuleSuscription/payment_receipt/partials/option_documents'
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
export default {
    props:['showDialog', 'contractItemId', 'itemLotId'],
	components: {SaleNotePayments, SaleNotesOptions, SaleNoteGenerate},
    data() {
        return {
            resource: 'rental_periods/payment_receipts',
            recordId: null,
			saleNotesNewId: null,
            titleDialog: 'Recibos de pago',
			showDialogPayments: false,
			showDialogOptions: false,
			showDialogGenerate: false,
            loading_submit: false,
            pagination: {
                current_page: 1
            },
            records: []
        }
    },
	computed: {
        ...mapState([
            'config',
        ]),
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
		clickGenerate(recordId) {
            this.recordId = recordId
            this.showDialogGenerate = true
        },
        clickPayment(recordId) {
            this.recordId = recordId;
            this.showDialogPayments = true;
        },
        clickDownload(external_id) {
            window.open(`/sale-notes/downloadExternal/${external_id}`, '_blank');
        },
        clickOptions(recordId) {
            this.saleNotesNewId = recordId
            this.showDialogOptions = true
        },
        close() {
            this.$emit('update:showDialog', false)
        },
    }
}
</script>
