<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>Importaciones</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <a :href="`/purchases/import`" class="btn btn-custom btn-sm  mt-2 mr-2"><i class="fa fa-plus-circle"></i> Nuevo</a>
            </div>
        </div>
        <div class="card mb-0">
            <div class="data-table-visible-columns">
                <el-button @click="exportImportations" type="success">
                    Exportar importaciones<i class="el-icon-download el-icon--right"></i>
                </el-button>
                <el-dropdown :hide-on-click="false">
                    <el-button type="primary">
                        Mostrar/Ocultar columnas<i class="el-icon-arrow-down el-icon--right"></i>
                    </el-button>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item v-for="(column, index) in columns" :key="index">
                            <el-checkbox v-model="column.visible">{{ column.title }}</el-checkbox>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th class="text-center">Import order task</th>
                        <th class="text-center">Número proforma</th>
                        <th>Monto proforma</th>
                        <th>Periodo</th>
                        <th>Fecha emisión</th>
                        <th>Fecha arribo</th>
                        <th>Ref. Liquidación</th>
                        <th>Ref. agente aduanas</th>
                        <th>Conocimiento de embarque</th>
                        <th>Nro. de conocimiento</th>
                        <th>DAM</th>
                        <th>Proveedor</th>
                        <th>Comentarios</th>
                        <th>Acciones</th>

                    </tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td class="text-center">{{ row.import_order_task}}</td>
                        <td class="text-center">{{ row.number_proforma}}</td>
                        <td class="text-center">{{ row.amount_proforma }}</td>
                        <td>{{row.date_periodo}}</td>
                        <td>{{row.date_of_issue }}</td>
                        <td>{{row.arrival_date}}</td>
                        <td>{{ row.sale_reference }}</td>
                        <td>{{ row.ref_customs_agent }}</td>
                        <td>{{row.transport_type}}</td>
                        <td>{{row.transport_code}}</td>
                        <td>{{row.dam}}</td>
                        <td>{{row.supplier.name}}</td>
                        <td>{{row.comments}}</td>

                        <td width="250" class="text-right">

                            <a :href="`/${resource}/edit/${row.id}`" type="button" class="btn waves-effect waves-light btn-xs btn-info">Editar</a>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-success" @click.prevent="clickOpenReport(row.id)">Reporte</button>
                        </td>
                    </tr>
                </data-table>
            </div>
        </div>

        <importation-parent-report
        :showDialog.sync="showParentReportDialog"
        :report_data="report_data"
        >
        </importation-parent-report>
    </div>
</template>

<script>
import {mapActions, mapState} from 'vuex'
import importationParentReport from './partials/importation_parent_report.vue'
import {deletable} from '../../../mixins/deletable'
import DataTable from "../../../components/DataTable.vue";


export default {
    mixins: [deletable],
    components: {DataTable, importationParentReport},
    props:[
        'typeUser',
        'configuration',
    ],
    data() {
        return {
            disableGuideBtn: true,
            showModalGuide: false,
            showDialogVoided: false,
            resource: 'importation',
            recordId: null,
            showDialogOptions: false,
            showParentReportDialog: false,
            columns: {
                date_of_due: {
                    title: 'F. Vencimiento',
                    visible: false
                },
                total_free: {
                    title: 'T.Gratuita',
                    visible: false
                },
                total_unaffected: {
                    title: 'T.Inafecta',
                    visible: false
                },
                total_exonerated: {
                    title: 'T.Exonerado',
                    visible: false
                },
                total_taxed: {
                    title: 'T.Gravado',
                    visible: false
                },
                total_igv: {
                    title: 'T.Igv',
                    visible: false
                },
                total_perception:{
                    title: 'Percepcion',
                    visible: false
                },
                guides: {
                    title: 'Guias',
                    visible: false
                },

            },
            report_data: null
        }
    },
    computed: {
        ...mapState([
            'document_types_guide',
            'warehouses',
        ]),
    },
    created() {
        this.$store.commit('setConfiguration',this.configuration)
        this.$store.commit('setTypeUser',this.typeUser)
        this.getDocumentTypes()
    },
    methods: {
        ...mapActions(['loadConfiguration']),
        clickOpenReport(anId) {
            this.$http.get(`/importation/${anId}/parent-report`).then(res => {
                if (res.data.items.length) {
                    this.report_data = res.data;
                    this.showParentReportDialog = true;
                } else {
                    this.$message.error('Importación no tiene items para mostrar')
                }
            })
                .catch(err => {
                    this.$message.error(err.response.data.message)
                })
        },
        clickPurchasePayment(recordId) {
            this.recordId = recordId;
            this.showDialogPurchasePayments = true
        },
        clickVoided(recordId = null) {
            this.recordId = recordId
            this.showDialogVoided = true
        },
        clickDownload(download) {
            window.open(download, '_blank');
        },
        clickOptions(recordId = null) {
            this.recordId = recordId
            this.showDialogOptions = true
        },
        clickGuide(recordId = null) {
            this.recordId = recordId
            this.showModalGuide = true
        },
        clickAnulate(id)
        {
            this.anular(`/${this.resource}/anular/${id}`).then(() =>
                this.$eventHub.$emit('reloadData')
            )
        },
        clickDelete(id)
        {
            this.$confirm('¿Desea eliminar permanentemente la importación?', 'Eliminar', {
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                type: 'warning'
            })
            .then(() => {
                    this.$http.delete(`/${this.resource}/${id}`).then(() =>
                        this.$eventHub.$emit('reloadData')
                    )});
        },
        getDocumentTypes(){

            this.$http
                .post('/dispatches/getDocumentType')
                .then((result)=>{
                    this.$store.commit('setDocumentTypesGuide',result.data)
                }).then(()=>{
                this.disableGuideBtn = !this.disableGuideBtn;
            })
        },
        async exportImportations() {
            this.$http.get('/importation/export',  {responseType: 'arraybuffer'})
                .then(response => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'importaciones.xlsx');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                });
        }
    }
}
</script>
