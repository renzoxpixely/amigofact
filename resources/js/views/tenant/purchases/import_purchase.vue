<template>
    <div class="card mb-0 pt-2 pt-md-0 negrita">
        <div class="card-header bg-info">
            <h3 class="my-0">Nueva Importación</h3>
        </div>
        <div class="tab-content">
            <import-form :purchase_order_id="purchase_order_id" :external="false"></import-form>
        </div>
    </div>
</template>

<script>
import ImportForm from "./components/import-form.vue";

export default {
    components: {ImportForm},
    props: ['purchase_order_id'],
    //components: {DialogPurchaseFormNew},
    data() {
        return {
        }
    },
    mounted() {
    },
    created() {
    },
    methods: {

    }
}
</script>
