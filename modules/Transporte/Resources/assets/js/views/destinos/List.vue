<template>
    <div>
        <div class="page-header pr-0">
            <h2>
                <a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a>
            </h2>
            <ol class="breadcrumbs">
                <li class="active"><span>REGISTRO DE DESTINOS</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <div class="btn-group flex-wrap">
                    <button
                        type="button"
                        class="btn btn-custom btn-sm mt-2 mr-2"
                        @click="onCreateDestino"
                    >
                        <i class="fa fa-plus-circle"></i> Nuevo
                    </button>
                </div>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de destinos</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Nombre</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in items" :key="item.id">
                            <td class="text-right">{{ item.id }}</td>
                            <td class="text-center">{{ item.nombre}}</td>
                            <td class="text-center">
                                <el-button type="success" @click="onEdit(item)">
                                    <i class="fa fa-edit"></i>
                                </el-button>
                                <el-button type="danger" @click="onDelete(item)">
                                    <i class="fa fa-trash"></i>
                                </el-button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ModalAddEdit
            :visible.sync="openModalAddEdit"
            @onAddItem="onAddItem"
            @onUpdateItem="onUpdateItem"
            :destino="destino"
        ></ModalAddEdit>
    </div>
</template>

<script>
    import ModalAddEdit from "./AddEdit";

    export default {
        props: {
            destinos: {
                type: Array,
                required: true,
            }
        },
        components: {
            ModalAddEdit,
        },
        data() {
            return {
                items: [],
                destino: null,
                openModalAddEdit: false,
                loading: false,
            };
        },
        mounted() {
            this.items = this.destinos;
        },
        methods: {
            onDelete(item) {
                this.$confirm(`¿estás seguro de eliminar al elemento ${item.nombre}?`, 'Atención', {
                    confirmButtonText: 'Si, continuar',
                    cancelButtonText: 'No, cerrar',
                    type: 'warning'
                }).then(() => {
                    this.$http.delete(`/transportes/destinos/${item.id}/delete`).then(response => {
                        this.$message({
                            type: 'success',
                            message: response.data.message
                        });
                        this.items = this.items.filter(i => i.id !== item.id);
                    }).catch(error => {
                        let response = error.response;
                        this.$message({
                            type: 'error',
                            message: response.data.message
                        });
                    });
                }).catch();
            },
            onEdit(item) {
                this.destino = { ...item };
                this.openModalAddEdit = true;
            },
            onUpdateItem(data) {
                this.items = this.items.map((i) => {
                    if (i.id === data.id) {
                        return data;
                    }
                    return i;
                });
            },
            onAddItem(data) {
                this.items.unshift(data);
            },
            onCreateDestino() {
                this.destino = null;
                this.openModalAddEdit = true;
            },
        },
    };
</script>
