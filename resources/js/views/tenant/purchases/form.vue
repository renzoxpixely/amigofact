<template>
    <div class="card mb-0 pt-2 pt-md-0 negrita">
        <div class="card-header bg-info">
            <h3 class="my-0">Nueva Compra</h3>
        </div>
        <div class="tab-content">
            <purchase-form-new :purchase_order_id="purchase_order_id" :external="false"></purchase-form-new>
        </div>
    </div>
</template>

<script>
export default {
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
