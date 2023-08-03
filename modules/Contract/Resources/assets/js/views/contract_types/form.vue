<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" width="80%">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name" :disabled="!form.edit_all"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.type}">
                            <label class="control-label">Tipo Contrato</label>
                            <el-select v-model="form.type">
                                <el-option
                                    v-for="option in types"
                                    :key="option.type"
                                    :label="option.description"
                                    :value="option.type"
                                ></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.type" v-text="errors.type[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.english_clauses}">
                            <label class="control-label">Cláusulas Ingles</label>
                            <wysiwyg v-model="form.english_clauses"></wysiwyg>
                            <small class="form-control-feedback" v-if="errors.english_clauses" v-text="errors.english_clauses[0]"></small>
                        </div>
                    </div> 
					<div class="col-md-6">
                        <div class="form-group" :class="{'has-danger': errors.spanish_clauses}">
                            <label class="control-label">Cláusulas Español</label>
                            <wysiwyg v-model="form.spanish_clauses"></wysiwyg>
                            <small class="form-control-feedback" v-if="errors.spanish_clauses" v-text="errors.spanish_clauses[0]"></small>
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
            const response = await this.$http.get(`/${this.resource}/tables`);
            this.types = response.data.types;
        },
        methods: {
            initForm() { 
                this.errors = {} 

                this.form = {
                    id: null,
                    name: null, 
                    type: null,
					english_clauses: null,
					spanish_clauses: null,
                    edit_all: true
                }
            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar tipo de contrato':'Nuevo tipo de contrato'
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