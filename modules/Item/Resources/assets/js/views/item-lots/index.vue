<template>
    <div>
        <div class="page-header pr-0">
            <h2><a href="/dashboard"><i class="fas fa-tachometer-alt"></i></a></h2>
            <ol class="breadcrumbs">
                <li class="active"><span>{{ title }}</span></li>
            </ol>
            <div class="right-wrapper pull-right">
                <button type="button" class="btn btn-success btn-sm  mt-2 mr-2" @click.prevent="clickExport()"><i class="fa fa-file-excel"></i> Exportar</button>
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header bg-info">
                <h3 class="my-0">Listado de {{ title }}</h3>
            </div>
            <div class="card-body">

                <div v-loading="loading_submit">

                        <div class="col-md-12 col-lg-12 col-xl-12 ">
                            <div class="row">
                                <div class="col-md-10 col-lg-10 cl-sm-10">
                                    <div class="row border-bottom mr-2">
                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-checkbox
                                                label="Libre en almacén"
                                                v-model="search.isFree"
                                                @change="getRecords($event, true)"
                                            ></el-checkbox>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-checkbox
                                                label="En contrato"
                                                v-model="search.hasContract"
                                                @change="getRecords($event, true)"
                                            ></el-checkbox>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-checkbox
                                                label="En almacén con contrato"
                                                v-model="search.inStorageWithContract"
                                                @change="getRecords($event, true)"
                                            ></el-checkbox>
                                        </div>
                                    </div>
                                    <div class="row pt-4">

                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-select
                                                filterable
                                                clearable
                                                v-model="search.client"
                                                placeholder="Cliente"
                                                @change="getRecords($event, true)"
                                            >
                                                <el-option
                                                    v-for="(row, key) in customers"
                                                    :key="key"
                                                    :value="row.name"
                                                    :label="row.name"
                                                ></el-option>
                                            </el-select>

                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-select
                                                filterable
                                                clearable
                                                v-model="search.product_description"
                                                placeholder="Modelo"
                                                @change="getRecords($event, true)"
                                            >
                                                <el-option
                                                    v-for="(label, key) in products"
                                                    :key="key"
                                                    :value="label"
                                                    :label="label"
                                                ></el-option>
                                            </el-select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-select
                                                filterable
                                                clearable
                                                v-model="search.hall"
                                                placeholder="Sala"
                                                @change="getRecords($event, true)"
                                            >
                                                <el-option
                                                    v-for="(row, key) in halls"
                                                    :key="key"
                                                    :value="row.id"
                                                    :label="row.name"
                                                ></el-option>
                                            </el-select>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-12 pb-2">
                                            <el-select
                                                filterable
                                                clearable
                                                v-model="search.contract_type"
                                                placeholder="Tipo de contrato"
                                                @change="getRecords($event, true)"
                                            >
                                                <el-option
                                                    v-for="(row, key) in contract_types"
                                                    :key="key"
                                                    :value="row.name"
                                                    :label="row.name"
                                                ></el-option>
                                            </el-select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2 border-left pt-2">
                                  <div class="pb-4">
                                      <el-select
                                          filterable
                                          clearable
                                          v-model="search.serie"
                                          placeholder="Serie"
                                          @change="getRecords($event, true)"
                                      >
                                          <el-option
                                              v-for="(serie, key) in series"
                                              :key="key"
                                              :value="serie"
                                              :label="serie"
                                          ></el-option>
                                      </el-select>
                                  </div>

                                    <el-select
                                        filterable
                                        clearable
                                        v-model="search.contract_number"
                                        placeholder="Número de contrato"
                                        @change="getRecords($event, true)"
                                    >
                                        <el-option
                                            v-for="contract in contract_numbers"
                                            :key="contract.id"
                                            :value="contract.number"
                                            :label="contract.number"
                                        ></el-option>
                                    </el-select>
                                </div>
                            </div>

                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Serie</th>
                                            <th>Producto</th>
                                            <th>Fecha</th>
                                            <th>Grupo</th>
                                            <th>Cliente</th>
                                            <th>N° Contrato</th>
                                            <th>Tipo de Contrato</th>
                                            <th>Sala</th>
                                            <!-- <th>Estado</th>
                                            <th>Vendido</th> -->
                                            <th class="text-right">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(row, index) in records" :key="index">
                                            <td>{{ customIndex(index) }}</td>
                                            <td>{{ row.series }}</td>
                                            <td>{{ row.item_description }}</td>
                                            <td>{{ row.date }}</td>
                                            <td>{{ row.group }}</td>
                                            <td>{{ row.customer }}</td>
                                            <td>{{ row.contract }}</td>
                                            <td>{{ row.contract_type }}</td>
                                            <td>{{ row.hall }}</td>

                                            <!-- <td>{{ row.state }}</td>
                                            <td>{{ row.status }}</td> -->
                                            <td class="text-right">
                                                <button type="button" class="btn waves-effect waves-light btn-xs btn-info" @click.prevent="clickCreate(row.id)" v-if="!row.has_sale">Editar</button>
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
                                            :page-size="pagination.per_page">
                                    </el-pagination>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <item-lot-form
                :showDialog.sync="showDialog"
                :recordId="recordId"
                    ></item-lot-form>
        </div>
    </div>
</template>

<script>

    import ItemLotForm from './form.vue'
    import queryString from 'query-string'

    export default {
        components: {ItemLotForm},
        data() {
            return {
                title: null,
                showDialog: false,
                resource: 'item-lots',
                recordId: null,
                search: {
                    isFree: false,
                    hasContract: false,
                    inStorageWithContract: false,
                    client: '',
                    product_description: '',
                    hall: '',
                    contract_type: '',
                    serie: '',
                    contract_number: ''
                },
                columns: [],
                records: [],
                pagination: {},
                products: [],
                halls: [],
                customers: [],
                contract_types: [],
                series: [],
                contract_numbers: [],
                loading_submit: false
            }
        },
        async mounted () {
            let column_resource = _.split(this.resource, '/')
            const { data } = await this.$http.get(`/${_.head(column_resource)}/tables`);

            this.products = data.products
            this.halls = data.halls
            this.customers = data.customers
            this.contract_types = data.contract_types
            this.series = data.series;
            this.contract_numbers = data.contract_numbers

            await this.$http.get(`/${_.head(column_resource)}/columns`).then((response) => {
                this.columns = response.data
                this.search.column = _.head(Object.keys(this.columns))
            });
            await this.getRecords()

        },
        created() {

            this.title = 'Series'

            this.$eventHub.$on('reloadData', () => {
                this.getRecords()
            })

        },
        methods: {
            customIndex(index) {
                return (this.pagination.per_page * (this.pagination.current_page - 1)) + index + 1
            },
            getRecords(value = null, resetPagination = false) {
                if (resetPagination) {
                    this.pagination.current_page = 1;
                }

                this.loading_submit = true

                return this.$http.get(`/${this.resource}/records?${this.getQueryParameters()}`).then((response) => {

                    this.records = response.data.data
                    this.pagination = response.data.meta
                    this.pagination.per_page = parseInt(response.data.meta.per_page)

                })
                .catch(error => {
                })
                .then(() => {
                    this.loading_submit = false
                });
            },
            getQueryParameters() {
                return queryString.stringify({
                    page: this.pagination.current_page,
                    limit: this.limit,
                    ...this.search
                })
            },
            changeClearInput(){
                this.search.value = ''
                this.getRecords()
            },
            clickExport(){

                let query = queryString.stringify({
                    ...this.search
                })

                window.open(`/${this.resource}/export?${query}`, '_blank');

            },
            clickCreate(recordId = null) {
                this.recordId = recordId
                this.showDialog = true
            }
        }
    }
</script>
