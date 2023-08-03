<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" width="60%">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.factor_creed}">
                            <label class="control-label">Factor CRED</label>
                            <el-input type="number" step=".01" v-model="form.factor_creed"></el-input>
                            <small class="form-control-feedback" v-if="errors.factor_creed" v-text="errors.factor_creed[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.maintenance_rate}">
                            <label class="control-label">Tasa de mantenimiento (%)</label>
                            <el-input type="number" step=".01" v-model="form.maintenance_rate">
                                <template slot="append">%</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.maintenance_rate" v-text="errors.maintenance_rate[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.tax_rate}">
                            <label class="control-label">Tasa de Impuesto (%)</label>
                            <el-input type="number" step=".01" v-model="form.tax_rate">
                                <template slot="append">%</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.tax_rate" v-text="errors.tax_rate[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.uit}">
                            <label class="control-label">UIT</label>
                            <el-input type="number" step=".01" v-model="form.uit"></el-input>
                            <small class="form-control-feedback" v-if="errors.uit" v-text="errors.uit[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.isc_one_uit}">
                            <label class="control-label">ISC 1 UIT (%)</label>
                            <el-input type="number" step=".01" v-model="form.isc_one_uit">
                                <template slot="append">%</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.isc_one_uit" v-text="errors.isc_one_uit[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.isc_three_uit}">
                            <label class="control-label">ISC 3 UIT</label>
                            <el-input type="number" step=".01" v-model="form.isc_three_uit">
                                <template slot="append">%</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.isc_three_uit" v-text="errors.isc_three_uit[0]"></small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.isc_more_than_three_uit}">
                            <label class="control-label">ISC MORE 3 UIT</label>
                            <el-input type="number" step=".01" v-model="form.isc_more_than_three_uit">
                                <template slot="append">%</template>
                            </el-input>
                            <small class="form-control-feedback" v-if="errors.isc_more_than_three_uit" v-text="errors.isc_more_than_three_uit[0]"></small>
                        </div>
                    </div> 
                </div> 
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>
    </el-dialog>
</template>
 
<script>
 

    export default {
        props: ['showDialog', 'recordId'],
        data() {
            return {
                loading_submit: false,
                titleDialog: null,
                resource: 'contract-types', 
                errors: {}, 
                form: {}, 
                types: []
            }
        },
        async created() {
            this.initForm() 
        },
        methods: {
            initForm() { 
                this.errors = {} 

                this.form = {
                    id: 0,
                    factor_creed: 0, 
                    maintenance_rate: 0,
					tax_rate: 0,
					uit: 0,
                    isc_one_uit: 0,
                    isc_three_uit: 0,
                    isc_more_than_three_uit: 0
                }
            },
            create() {

                this.titleDialog = 'Configuración'
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`).then(response => {
                            this.form = response.data.data
                        })
                }
            },
            submit() {   
 

                this.loading_submit = true  
                this.$http.post(`${this.resource}`, this.form)
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
            }
        }
    }
</script>