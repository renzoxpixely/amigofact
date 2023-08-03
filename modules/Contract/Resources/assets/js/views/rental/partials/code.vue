<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create"  @close="close" append-to-body width="40%">
        <form autocomplete="off">
            <div class="form-body">
                <div class="row">
                    <div :class="{'has-danger': errors.code}"
                            class="form-group col-12">
                        <label class="control-label">Código</label>
                        <el-input v-model="form.code"></el-input>
                        <small v-if="errors.code"
                                class="form-control-feedback"
                                v-text="errors.code[0]"></small>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button
                    @click.prevent="close()">
                    Cancelar
                </el-button>
                <el-button
                    :loading="loading_submit"
                    @click.prevent="submit"
                    type="primary">
                    Guardar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
export default {
    props:['showDialog', 'rentalPeriodId', 'recordCode'],
    data() {
        return {
            resource: 'rental_periods',
            recordId: null,
            titleDialog: 'Editar Código',
            loading_submit: false,
            form: {},
            errors: {}
        }
    },
    created() {
        this.initForm()
    },
    methods: {
        async create(){
            this.initForm()
        },
        initForm() {
            this.form = {
                rental_period_id: this.rentalPeriodId,
                code: this.recordCode
            }
            this.errors= {}
        },
        submit() {
            this.loading_submit = true  
            this.$http.post(`/${this.resource}/save/code`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
						this.$eventHub.$emit('reloadData')
                        this.close()
                    } else {
                        this.$message.error(response.data.message)
                    }
                })
                .catch(error => {
                    if (error.response.status === 422) {
                        this.errors = error.response.data 
                    } else {
                        console.log(error.response)
                    }
                })
                .then(() => {
                    this.loading_submit = false
                })
        },
        close() {
            this.$emit('update:showDialog', false)
            this.initForm()
        },
    }
}
</script>
