<template>
    <el-dialog :visible="showDialog" @close="close" width="80%" :destroy-on-close="true" :append-to-body="true">
        <tenant-purchases-edit
            :is-modal="true"
            :resource-id="resourceId"
            @getInvoices="emitGetInvoices"
            @closeDialog="close"
        >
        </tenant-purchases-edit>
    </el-dialog>
</template>

<script>
export default {
    name: "purchase-edit-dialog",
    props: ['showDialog', 'resourceId'],
    data() {
        return {
            titleDialog: null
        }
    },
    created() {

    },
    methods: {
        close() {
            this.$emit('update:showDialog', false)
        },
        emitGetInvoices() {
            this.$emit('getInvoices');
        }
    }
}
</script>

<style scoped>

</style>
