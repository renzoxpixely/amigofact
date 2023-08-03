<template>
    <el-dialog title="Reporte de costeo de importación" width="100%" :full-screen="true" :visible="showDialog" @close="close" :lock-scroll="false" :append-to-body="true">
        <div>
            <el-table v-if="report_data" :data="report_data.items" style="width: 100%"  height="600" :stripe="true">
                <el-table-column prop="import_order_task" label="Task"/>
                <el-table-column prop="invoice" label="Invoice" />
                <el-table-column prop="date" label="Date" />
                <el-table-column prop="part_number" label="Part number" width="150"/>
                <el-table-column prop="name" label="Name" width="200"/>
                <el-table-column prop="description" label="Description" :min-width="150"/>
                <el-table-column prop="category" label="Categoría" :min-width="300"/>
                <el-table-column prop="quantity" label="Cantidad" />
                <el-table-column prop="unit_price" label="Precio unitario" width="120"/>
                <el-table-column prop="amount_usd" label="Amount $" width="100"/>
                <el-table-column prop="exchange_rate" label="TC" />
                <el-table-column prop="amount_sol" label="Amount S/" width="100" />
                <el-table-column prop="afecto_ad_valorem" label="Afecto al ad-valorem" width="150"/>
                <el-table-column prop="weight" label="Peso" />
                <el-table-column prop="ad_valorem_pct" label="% ad-valorem" width="120"/>
                <el-table-column prop="weight_pct" label="% Peso" />
                <el-table-column prop="cost_pct" label="% Costo" />
                <el-table-column prop="ad_valorem_dol" label="Ad-valorem $" width="120"/>
                <el-table-column prop="lading_dol" label="Flete $" />
                <el-table-column prop="insurance_dol" label="Seguro $" />
                <el-table-column prop="import_cost_dol" label="Costos de importación $" width="170"/>
                <el-table-column prop="total_cost_dol" label="Costo total $" width="110"/>
                <el-table-column prop="unit_cost" label="Costo unitario $" width="120"/>
                <el-table-column prop="ad_valorem_sol" label="Ad-valorem S/" width="110"/>
                <el-table-column prop="lading_sol" label="Flete S/" width="100"/>
                <el-table-column prop="insurance_sol" label="Seguro S/" width="100"/>
                <el-table-column prop="import_cost_sol" label="Costos de importación S/" width="180"/>
                <el-table-column prop="total_cost_sol" label="Costo total S/" width="100"/>
                <el-table-column prop="unit_cost_sol" label="Costo unitario S/" width="120"/>
            </el-table>
        </div>


        <div class="form-actions text-right mt-5">
            <el-button type="success" @click.prevent="downloadExport">Exportar</el-button>
           <el-button type="info" @click.prevent="close">Cerrar</el-button>

        </div>
    </el-dialog>
</template>

<script>

export default {
    props:['showDialog', 'report_data'],
    methods: {
        close() {
            this.$emit('update:showDialog', false)
        },
        downloadExport() {
            this.$http.get(`/importation/${this.report_data.importation_id}/parent-report/export`,  {responseType: 'arraybuffer'})
                .then(response => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');
                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'reporte_padre_importacion.xlsx');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                });
        }
    },
}
</script>

<style scoped>
table {
    display: block;
    overflow-x: auto;
    white-space: nowrap;
}
</style>
