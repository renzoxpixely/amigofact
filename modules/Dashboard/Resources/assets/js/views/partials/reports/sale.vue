<template>
    <div class="row">
        <div class="col-md-3 mt-2">
            <label class="control-label">Cliente:</label>
            <el-select v-model="form.customer_id" :clearable="true" filterable>
                <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.name"></el-option>
            </el-select>
        </div>
        <div class="col-md-6" style="margin-top:39px">
            <el-button type="primary" @click="getRecords()" class="mb-2" :loading="loading_submit">
                <i class="fa fa-search mr-2"></i>
                Buscar
            </el-button>
            <el-button
                v-if="records.length > 0"
                class="submit mb-2"
                type="success"
                @click.prevent="clickDownload('excel')"
                >
                <i class="fa fa-file-excel"></i> Exportar Excel
            </el-button>
        </div>
        <div class="col-md-12 mt-3">
            <div class="table-responsive">
                <table class="table" :loading="loading_submit">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Serial N°</th>
                            <th rowspan="2">Model</th>
                            <th rowspan="2">Contract N°</th>
                            <th rowspan="2">Client</th>
                            <th rowspan="2">Company</th>
                            <th rowspan="2">Sala</th>
                            <th rowspan="2">Region</th>
                            <th rowspan="2">City</th>
                            <th rowspan="2" class="text-right">Date of Installation</th>
                            <th rowspan="2" class="text-right">Doc. de Atribución</th>
                            <th rowspan="2" class="text-right">Invoice N°</th>
                            <th colspan="2" class="text-center" v-for="(row, index) in months" :key="index">{{row}}</th>
                        </tr>
                        <tr>
                            <th class="text-center" v-for="(value, index) in months_subtitle" :key="index">{{value}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in records" :key="index">
                            <td>{{index+1}}</td>
                            <td>{{row.serie}}</td>
                            <td>{{row.item}}</td>
                            <td>{{row.contract}}</td>
                            <td>{{row.customer}}</td>
                            <td>{{row.contact}}</td>
                            <td>{{row.hall}}</td>
                            <td>{{row.region}}</td>
                            <td>{{row.city}}</td>
                            <td class="text-right">{{row.activation_date}}</td>
                            <td class="text-right">{{row.mincetur?'Si':'No'}}</td>
                            <td class="text-right">{{row.invoice}}</td>
                            <td class="text-center" v-for="(value, index2) in row.result" :key="index2">{{value}}</td>
                        </tr>
                    </tbody>
                </table>

              
            </div>
            <el-pagination
                :page-size="page_size"
                :current-page.sync="form.page"
                @current-change="getRecords"
                layout="prev, pager, next"
                :total="total">
                </el-pagination>
        </div>
    </div>
</template>
<script>
import queryString from "query-string";
export default {
    props: ['customers'],
    data() {
        return {
            resource: "dashboard",
            loading_submit: false,
            form: {customer_id: null, page: 1},
            records: [],
            months: [],
            total: 0,
            page_size: 0,
            months_subtitle: [],
        }
    },
	created(){
		this.getRecords()
	},
    methods: {
        getRecords(){
            this.loading_submit = true;
            return this.$http
            .get(`/${this.resource}/records_sale?${this.getQueryParameters()}`)
            .then(response => {
                this.records = response.data.data;
                this.months = response.data.months;
                this.months_subtitle = response.data.months_subtitle;
                this.total = response.data.total;
                this.page_size = response.data.page_size;
                this.form.page = response.data.page;
            })
            .catch(error => {})
            .then(() => {
                this.loading_submit = false;
            });
        },
        getQueryParameters() {
            return queryString.stringify(this.form);
        },
        clickDownload(){
            window.open(`/${this.resource}/records_sale_excel?${this.getQueryParameters()}`, "_blank");
        }
    }
}
</script>