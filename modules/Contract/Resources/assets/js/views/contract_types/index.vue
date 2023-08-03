<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                    <button type="button" class="btn btn-custom btn-sm  mt-2 mr-2" @click.prevent="clickCreate()"><i class="fa fa-plus-circle"></i> Nuevo</button>

            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">
                <data-table :resource="resource">
                    <tr slot="heading">
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Fecha creación</th>
                        <th class="text-right">Acciones</th>
                    <tr>
                    <tr slot-scope="{ index, row }">
                        <td>{{ index }}</td>
                        <td>{{ row.name }}</td>
                        <td>{{ row.created_at }}</td>
                        <td class="text-right">
                            <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)">Editar</button>
                            <button type="button" v-show="row.is_stake" class="btn waves-effect waves-light btn-xs btn-primary" @click.prevent="clickSetting(row.id)">Configuración</button>
                            <button type="button" v-show="row.btn_remove" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickDelete(row.id)">Eliminar</button>
                        </td>
                    </tr>
                </data-table>
            </div>

            <contract-type-form 
                :showDialog.sync="showDialog"
                :recordId="recordId"
                    ></contract-type-form> 

            <contract-type-setting 
                :showDialog.sync="showDialogSetting"
                :recordId="recordId"
                    ></contract-type-setting>
        </div>
    </div>
</template>

<script>

    import ContractTypeForm from './form.vue' 
    import ContractTypeSetting from './partials/setting.vue' 
    import DataTable from '../../../../../../../resources/js/components/DataTable.vue'
    import {deletable} from '../../../../../../../resources/js/mixins/deletable'

    export default {
        mixins: [deletable],
        components: {DataTable, ContractTypeForm, ContractTypeSetting},
        data() {
            return {
                title: null,
                showDialog: false, 
                showDialogSetting: false,
                resource: 'contract-types',
                recordId: null,
            }
        },
        created() {
            this.title = 'Tipos de Contrato'
        },
        methods: { 
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            }, 
            clickSetting(recordId = null) {
                this.recordId = recordId
                this.showDialogSetting = true
            }, 
            clickDelete(id) {
                this.destroy(`/${this.resource}/${id}`).then(() =>
                    this.$eventHub.$emit('reloadData')
                )
            }
        }
    }
</script>
