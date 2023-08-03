<template>
 <div>
     <div class="page-header pr-0">
         <h2><a href="/dashboard"><i class="fas fa-file-excel"></i></a></h2>
         <ol class="breadcrumbs">
             <li class="active"><span>Reporte Series</span></li>
         </ol>
         <div class="right-wrapper pull-right">
             <button type="button" class="btn btn-success btn-sm  mt-2 mr-2" @click.prevent="exportReport"><i class="fa fa-file-excel"></i> Exportar</button>
         </div>
     </div>
     <div class="card mb-0">
         <div class="card-header bg-info">
             <h3 class="my-0">Reporte de series</h3>
         </div>
         <div class="card-body">
                 <div class="col-md-12 col-lg-12 col-xl-12 ">
                     <div class="row">
                         <div class="col-md-12">
                             <div class="table-responsive">
                                 <table class="table">
                                     <thead>
                                     <tr>
                                         <th>#</th>
                                         <th>Sap Material #</th>
                                         <th>Import Order</th>
                                         <th>Invoice #</th>
                                         <th>Mes</th>
                                         <th>Prod. Year</th>
                                         <th>Type & Version</th>
                                         <th>Serial #</th>
                                         <th>Codigo Mincetur</th>
                                         <th>RD</th>
                                     </tr>
                                     </thead>
                                     <tbody>
                                     <tr v-for="(report, index) in report_records" :key="index">
                                         <td>{{ +index + 1}}</td>
                                         <td> {{report.matrix_code}}</td>
                                         <td> {{report.import_order_task}}</td>
                                         <td> {{report.invoice_number}}</td>
                                         <td> {{report.month}}</td>
                                         <td> {{report.prod_year}}</td>
                                         <td> {{report.type_version}}</td>
                                         <td> {{report.serial}}</td>
                                         <td> {{report.mincetur_code}}</td>
                                         <td> {{report.rd}}</td>
                                     </tr>
                                     </tbody>
                                 </table>
                                 <div>
                                     <el-pagination
                                         v-if="pagination"
                                         @current-change="changePage"
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
     </div>
 </div>
</template>

<script>
import ItemLotForm from "./form.vue";

export default {
    name: "report",
    components: {ItemLotForm},
    data() {
        return {
            report_records: [],
            pagination: null,
        }
    },
    beforeMount() {
      this.getRecords();
    },
    methods: {
        async getRecords() {
        const {data} = await this.$http.get( `/item-lots/report-records?page=${this.pagination ? this.pagination.current_page : 1}`);

        this.report_records = data.data;
        this.pagination =  data;

        },
        changePage(val) {
            this.pagination.current_page = val;
            this.getRecords();
        },
        exportReport() {
            this.$http.get('/item-lots/report-records-export',  {responseType: 'arraybuffer'})
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

<style scoped>

</style>
