<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div v-if="typeUser == 'admin'" class="right-wrapper pull-right">
                <!--<button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickImport()"><i class="fa fa-upload"></i> Importar</button>-->
                <div class="btn-group flex-wrap">
                    <button type="button" class="btn btn-custom btn-sm mt-2 mr-2 dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-upload"></i> Importar<span class="caret"></span></button>
                    <button type="button" class="btn btn-success btn-sm  mt-2 mr-2" @click.prevent="clickReport()"><i class="fa fa-file-excel"></i> Reporte</button>
                    <div class="dropdown-menu"
                    role="menu"
                    x-placement="bottom-start"
                    style="
                                position: absolute;
                                will-change: transform;
                                top: 0px;
                                left: 0px;
                                transform: translate3d(0px, 42px, 0px);
                            "
                        >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImport()"
                            >Inventario inicial</a
                            >
                            <a
                                class="dropdown-item text-1"
                                href="#"
                                @click.prevent="clickImportStock()"
                            >Importar stock</a
                            >
                        </div>
                    </div>
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate('input')"><i class="fa fa-plus-circle"></i> Ingreso</button>
                <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickOutput()"><i class="fa fa-minus-circle"></i> Salida</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource" ref="datatable">
                    <tr slot="heading">
                        <th>
                            <el-dropdown>
                                <span class="el-dropdown-link">
                                    <el-button>
                                        <i class="fa fa-ellipsis-v"></i>
                                    </el-button>
                                </span>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item @click.native="onChecktAll">Seleccionar todo</el-dropdown-item>
                                    <el-dropdown-item @click.native="onUnCheckAll">Deseleccionar todo</el-dropdown-item>
                                    <el-dropdown-item @click.native="onOpenModalMoveGlobal">Trasladar</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </th>
                        <th>Producto</th>
                        <th>Almacén</th>
                        <th class="text-right">Stock</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }" :key="index">
                        <td>
                            <el-switch v-model="row.selected" @click="onChangeSelectedStatus(row)"></el-switch>
                        </td>
                        <!-- <td>{{ index }}</td> -->
                        <td>{{ row.item_fulldescription }}</td>
                        <td>{{ row.warehouse_description }}</td>
                        <td class="text-right">{{ row.stock }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info"
                                    @click.prevent="clickMove(row.id)">Trasladar</button>
                            <button v-if="typeUser == 'admin'" type="button" class="btn waves-effect waves-light btn-xs btn-warning"
                                    @click.prevent="clickRemove(row.id)">Remover</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <inventories-form
                            :showDialog.sync="showDialog"
                            :type="typeTransaction"
                                ></inventories-form>

            <inventories-form-output
                            :showDialog.sync="showDialogOutput"
                            ></inventories-form-output>

            <inventories-import :showDialog.sync="showImportDialog"></inventories-import>
            <inventories-import-stock :showDialog.sync="showImportStockDialog"></inventories-import-stock>
            <inventories-move :showDialog.sync="showDialogMove"
                              :recordId="recordId"></inventories-move>
            <inventories-remove :showDialog.sync="showDialogRemove"
                                :recordId="recordId"></inventories-remove>
            <MoveGlobal :products="selectedItems" :show.sync="showHideModalMoveGlobal"></MoveGlobal>

            <movement-report
                            :showDialog.sync="showDialogMovementReport"
                                ></movement-report>

        </div>
    </div>
</template>

<script>

    import InventoriesForm from './form.vue'
    import InventoriesFormOutput from './form_output.vue'

    import InventoriesMove from './move.vue'
    import InventoriesRemove from './remove.vue'
    import InventoriesImport from './import.vue'
    import InventoriesImportStock from './import_stock.vue'
    import DataTable from '@components/DataTable.vue'
    import MoveGlobal from './MoveGlobal.vue';
    import MovementReport from './reports/movement_report.vue';

    export default {
        props: ['type', 'typeUser'],
        components: {DataTable, InventoriesForm, InventoriesMove, InventoriesRemove, InventoriesFormOutput, MoveGlobal, MovementReport, InventoriesImport,InventoriesImportStock},
        data() {
            return {
                showHideModalMoveGlobal: false,
                selectedItems: [],
                title: null,
                showDialog: false,
                showDialogMove: false,
                showDialogRemove: false,
                showDialogOutput: false,
                showImportDialog: false,
                showImportStockDialog: false,
                resource: 'inventory',
                recordId: null,
                typeTransaction:null,
                showDialogMovementReport:false
            }
        },
        created() {
            this.title = 'Inventario'
        },
        methods: {
            clickReport(){
                this.showDialogMovementReport = true
            },
            async onOpenModalMoveGlobal() {
                const itemsSelecteds = await this.$refs.datatable.records.filter(p => p.selected);
                if (itemsSelecteds.length > 0) {
                    this.selectedItems = itemsSelecteds;
                    this.showHideModalMoveGlobal = true;
                } else {
                    this.$message({
                        message: 'Selecciona uno o más productos.',
                        type: 'warning'
                    });
                }
            },
            async onChangeSelectedStatus(row) {
                this.$refs.datatable.records = await this.$refs.datatable.records.map(r => {
                    if (r.id === row.id) {
                        r.selected = row.selected ? false : true;
                    }
                    return r;
                });
                this.$forceUpdate();
            },
            onChecktAll() {
                this.$refs.datatable.records = this.$refs.datatable.records.map(r => {
                    r.selected = true;
                    return r;
                });
            },
            onUnCheckAll() {
                this.$refs.datatable.records = this.$refs.datatable.records.map(r => {
                    r.selected = false;
                    return r;
                });
            },
            clickMove(recordId) {
                this.recordId = recordId
                this.showDialogMove = true
            },
            clickCreate(type) {
                this.recordId = null
                this.typeTransaction = type
                this.showDialog = true
            },
            clickRemove(recordId) {
                this.recordId = recordId
                this.showDialogRemove = true
            },
            clickOutput() {
                this.recordId = null
                this.showDialogOutput = true
            },
            clickImport() {
                this.showImportDialog = true;
            },
            clickImportStock() {
                this.showImportStockDialog = true;
            },

        }
    }
</script>
