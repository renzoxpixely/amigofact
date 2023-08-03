<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create"  @close="close" width="60%">
        <form autocomplete="off">
            <div class="form-body">
                <div class="row">
                    <div :class="{'has-danger': errors.start_period}"
                            class="form-group col-3">
                        <label class="control-label">Periodo Inicio</label>
                        <el-date-picker v-model="form.start_period" type="date" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                        <small v-if="errors.start_period"
                                class="form-control-feedback"
                                v-text="errors.start_period[0]"></small>
                    </div>
                    <div :class="{'has-danger': errors.months}"
                            class="form-group col-3">
                        <label class="control-label">Meses de Perido</label>
                        <el-input-number v-model="form.months"></el-input-number>
                        <small v-if="errors.months"
                                class="form-control-feedback"
                                v-text="errors.months[0]"></small>
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
                    Registrar
                </el-button>
            </div>
        </form>
    </el-dialog>
</template>

<script>
import moment from 'moment'
export default {
    props:['showDialog', 'contractItemId', 'itemLotId'],
    data() {
        return {
            resource: 'rental_periods',
            recordId: null,
            titleDialog: 'Registrar Periodos',
            loading_submit: false,
            form: {},
            errors: {}
        }
    },
    created() {
        
    },
    methods: {
        async create(){
            this.initForm()
        },
        initForm() {
            this.form = {
                start_period: moment().format('YYYY-MM-DD'),
                months: 0,
                contract_item_id: this.contractItemId,
                item_lot_id: this.itemLotId
            }
            this.errors= {}
        },
        submit() {
            this.loading_submit = true  
            this.$http.post(`/${this.resource}`, this.form)
                .then(response => {
                    if (response.data.success) {
                        this.$message.success(response.data.message)
						//this.$eventHub.$emit('reloadData')
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
