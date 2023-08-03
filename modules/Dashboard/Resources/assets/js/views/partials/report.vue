<template>
    <el-tabs v-model="tab" type="border-card" class="mt-2 mb-4">
        <el-tab-pane name="first">
            <span slot="label">Por Venta</span>
            <report-sale :customers="customers"></report-sale>
        </el-tab-pane>
        <el-tab-pane name="second">
            <span slot="label">Por Participación</span>
            <report-stake :customers="customers"></report-stake>
        </el-tab-pane>
        <el-tab-pane name="three">
            <span slot="label">Por Alquiler</span>
            <report-rental :customers="customers"></report-rental>
        </el-tab-pane>
    </el-tabs>
</template>
<script>
import ReportSale from './reports/sale.vue'
import ReportStake from './reports/stake.vue'
import ReportRental from './reports/rental.vue'
export default {
    components: {ReportSale, ReportStake, ReportRental},
    data() {
        return {
            resource: "dashboard",
			tab: 'first',
            customers: []
        }
    },
    async created () {  
        let response = await this.$http.get(`/${this.resource}/tables`)
        this.customers = response.data.customers
    }
}
</script>