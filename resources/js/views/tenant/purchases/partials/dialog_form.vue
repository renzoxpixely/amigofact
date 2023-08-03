<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" width="80%">
        <purchase-form-new :purchase_order_id="null" :external="true" :recordDataPurchase="recordData" @closeDialog="close" @addedInvoice="emitAddedInvoice"  :is-import="isImport"></purchase-form-new>
    </el-dialog>
</template>

<script>
	//import PurchaseFormNew from '../components/form_new.vue'
    export default {
        props: ['showDialog', 'recordData', 'isImport', 'title'],
		//components: {PurchaseFormNew},
        data() {
            return {
				titleDialog: null
            }
        },
        created() {

        },
        methods: {
            create() {
                this.titleDialog = this.title ? this.title : 'Nueva Compra';
				this.$eventHub.$emit("clearForm");
            },
            close() {
                this.$emit('update:showDialog', false)
            },
            emitAddedInvoice(anInvoiceNumber) {
                this.$emit('addedInvoice', anInvoiceNumber);
            }
        }
    }
</script>
