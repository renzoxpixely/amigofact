<template>
        <div>
            <div class="form-body">
                <div class="row" v-loading="loading">
                    <div class="col-md-12" v-if="canAddRows">
                        <el-button type="primary"  @click.prevent="clickAddLot()" >Agregar</el-button>
                        <el-button type="success"  @click.prevent="clickImport()" >Importar</el-button>
                    </div>
                    <div v-if="recordId" class="col-md-2 mt-2">
                        <div class="form-group">
                            <label>Agregar documentos RD</label>
                            <el-switch v-model="show_add_rd_file"></el-switch>
                        </div>
                    </div>
                    <div v-if="show_add_rd_file" class="col-md-2 mt-2">
                        <div class="form-group">
                            <el-input
                                v-model="rd_filename"
                            >
                            </el-input>
                        </div>

                    </div>
                    <div v-if="show_add_rd_file" class="col-md mt-2">
                        <div class="form-group">
                            <el-upload
                                :onChange="addFile"
                                action=""
                                :auto-upload="false"
                                :show-file-list="false"
                            >
                                <el-button size="small" type="info">Subir archivo</el-button>
                            </el-upload>
                        </div>
                    </div>

                    <div v-if="show_add_rd_file" class="col-md-2 mt-2">
                        <div class="form-group self-end">
                            <el-button @click="submitRdFile" size="small" type="info">Aplicar archivo</el-button>
                        </div>
                    </div>

                    <div class="col-md-12 mt-2">

                        <data-tables
                            :data="lots.filter(data => !query || data.series.toLowerCase().includes(query.toLowerCase()))"
                            :current-page.sync="currentPage"
                            :table-props="tableProps"
                            :pagination-props="{ pageSizes: [20] }"
                            style="width: 100%" >

                            <el-table-column
                                v-if="show_add_rd_file"
                                prop="index"
                                width="50"
                                label="Agr. RD"
                            >
                                <template slot-scope="scope">
                                    <el-checkbox
                                        v-model="scope.row.rd_checked"
                                        @change="syncDataWithParent"
                                    ></el-checkbox>
                                </template>
                            </el-table-column>

                            <el-table-column
                                width="80"
                                prop="index"
                                label="#">
                                <template slot-scope="scope">
                                    {{ scope.row.id || scope.row.index + 1 }}
                                </template>
                            </el-table-column>

                            <el-table-column prop="series"  label="Series">

                                <template slot-scope="scope">
                                    <el-input @blur="duplicateSerie(scope.row.series, scope.row.index)" v-model="scope.row.series"
                                              :ref="`ref_series_${scope.row.index}`"
                                              @keyup.enter.native="keyupEnterSeries(scope.row.series, scope.row.index)"
                                              @change="syncDataWithParent"
                                    ></el-input>
                                </template>

                            </el-table-column>

                            <el-table-column prop="game-names" label="Nombre del juego">
                                <template slot-scope="scope">
                                    <el-select
                                        filterable
                                        v-model="scope.row.game_id"
                                        @change="syncDataWithParent">

                                        <el-option
                                            v-for="gameName in gameNames"
                                            :key="gameName.id"
                                            :value="gameName.id"
                                            :label="gameName.description"
                                        ></el-option>
                                    </el-select>
                                </template>

                            </el-table-column>

                            <el-table-column prop="mincetur-code" label="Código Mincetur">
                                <template slot-scope="scope">
                                    <el-input
                                        type="number"
                                        v-model="scope.row.mincetur_code"
                                        @change="syncDataWithParent"
                                    ></el-input>
                                </template>

                            </el-table-column>

                            <el-table-column label="Archivo RD Mincetur">
                                <template slot-scope="scope">
                                    <span style="cursor: pointer;" @click="downloadRdFile(scope.row.rd_file_url)" class="text-blue cursor-pointer" v-if="scope.row.rd_file_url" :href="`http://prueba.miamigo-facturador.local/storage/rd-files/${scope.row.rd_file_url}`" :download="scope.row.rd_file_url">{{ parseFilename(scope.row.rd_file_url) }}</span>
                                    <span v-else>sin archivo</span>
                                </template>

                            </el-table-column>

<!--                            <el-table-column prop="state" label="Estado"   >-->

<!--                                <template slot-scope="scope">-->
<!--                                    <el-select  v-model="scope.row.state"-->
<!--                                                @change="syncDataWithParent">-->
<!--                                        <el-option-->
<!--                                            v-for="(option, index) in states"-->
<!--                                            :key="index"-->
<!--                                            :value="option"-->
<!--                                            :label="option"-->
<!--                                        ></el-option>-->
<!--                                    </el-select>-->
<!--                                </template>-->

<!--                            </el-table-column>-->

<!--                            <el-table-column prop="date" label="Fecha" >-->
<!--                                <template slot-scope="scope">-->
<!--                                    <el-date-picker-->
<!--                                        v-model="scope.row.date"-->
<!--                                        type="date"-->
<!--                                        value-format="yyyy-MM-dd"-->
<!--                                        :clearable="false"-->
<!--                                        @change="syncDataWithParent"-->
<!--                                    ></el-date-picker>-->

<!--                                </template>-->
<!--                            </el-table-column>-->

                            <el-table-column
                                style="width: 10%"
                                align="right"
                               >
                                <template slot="header" slot-scope="scope">
                                    <el-input
                                        v-model="query"
                                        size="mini"
                                        placeholder="Type to search"/>
                                </template>
                                <template slot-scope="scope">
                                    <el-button
                                        size="mini"
                                        type="danger"
                                        icon="el-icon-delete"
                                        circle
                                        @click.prevent="removeItem(scope.row.index)"></el-button>
                                </template>
                            </el-table-column>
                        </data-tables>

                    </div>
                </div>

                <series-import
                    :showDialog.sync="showImportDialog"
                ></series-import>

            </div>

            <div class="form-actions text-right pt-2 mt-3" v-if="canAddRows">
                <el-button @click.prevent="clickCancelSubmit()">Cancelar</el-button>
                <el-button type="primary" @click="submit" >Guardar</el-button>
            </div>
        </div>
</template>

<script>
    import { DataTables } from 'vue-data-tables'
    import SeriesImport from "@views/purchases/partials/import_series.vue";

    export default {
        components: {
            DataTables,
            SeriesImport
        },
        props: ['showDialog', 'lots', 'stock','recordId', 'gameNames', 'canAddRows'],
        data() {
            return {
                titleDialog: 'Series',
                showImportDialog: false,
                loading: false,
                errors: {},
                form: {},
                states: ['Activo', 'Inactivo', 'Desactivado', 'Voz', 'M2m'],
                tableProps: {
                    border: true,
                },
                currentPage: 1,
                per_page: 20,
                query: '',
                show_add_rd_file: false,
                rd_filename: 'test',
                rd_file: null
            }
        },
        async created() {
            this.$eventHub.$on('responseImportSeries', (response) => {
                this.responseImportSeries(response)
            })
        },
        mounted(){
            if(!this.recordId){

                if(this.lots.length === 0){

                    this.addMoreLots(this.stock)

                }else{

                    let quantity = this.stock - this.lots.length
                    if(quantity > 0){
                        this.addMoreLots(quantity)
                    }
                    // else{
                    //     this.deleteMoreLots(quantity)
                    // }
                }

            }
            this.loading = false
        },
        methods: {
            async responseImportSeries(response){

                let lots_import = response.data.news_rows

                try {

                    for (let index = 0; index < this.lots.length; index++) {

                        this.lots[index].series = lots_import[index].series
                        this.lots[index].date = lots_import[index].date
                        this.lots[index].state = lots_import[index].state
                        this.lots[index].game_name = lots_import[index].game_name
                        this.lots[index].mincetur_code = lots[index].mincetur_code
                        this.lots[index].rd_checked = false
                    }

                } catch (error) {
                }

                if(response.data.news_rows.length != this.lots.length){
                    this.$notify({title: "", message: "La cantidad de registros del archivo importado, es diferente a la cantidad ingresada", type: "error", duration: 4000});
                }

                this.$emit('addRowLot', this.lots);

            },
            clickImport() {
                this.showImportDialog = true;
            },
            getMaxItems(index) {

                if(this.currentPage > 1){
                    index = index - this.per_page
                }

                return (this.per_page * (this.currentPage - 1)) + index + 1
            },
            async keyupEnterSeries(series, index){

                if(index == this.getIndex() - 1){
                    return
                }

                try {
                    await this.changeFocus(index)

                }
                catch(e) {

                    await this.nextPage()

                    await this.$nextTick(() => {
                        this.changeFocus(index)
                    })

                }

            },
            changeFocus(index){
                this.$refs[`ref_series_${index+1}`].$el.getElementsByTagName('input')[0].focus()
            },
            nextPage(){
                this.currentPage++
            },
            async duplicateSerie(data, index)
            {
                if(data){

                    let duplicates = await _.filter(this.lots, {'series':data})
                    if(duplicates.length > 1)
                    {
                        this.$message.error('Ingresó una serie duplicada');
                        this.lots[index].series = ''
                    }

                }
            },
            create(){
                this.loading = true
            },
            addMoreLots(number){

                for (let i = 0; i < number; i++) {
                    this.clickAddLot()
                }

            },
            deleteMoreLots(number){

                for (let i = 0; i < number; i++) {
                    this.lots.pop();
                    this.$emit('addRowLot', this.lots);
                }

            },
            async validateLots(){

                let error = 0

                await this.lots.forEach(element => {
                    if(element.series == null){
                        error++
                    }
                });

                if(error>0)
                    return {success:false, message:'El campo serie es obligatorio'}


                return {success:true}

            },
            async submit(){

                let val_lots = await this.validateLots()
                if(!val_lots.success)
                    return this.$message.error(val_lots.message);

                await this.$emit('addRowLot', this.lots, true);
                await this.$emit('update:showDialog', false)
                this.$emit('closeDialog');

            },
            syncDataWithParent() {
                this.$emit('addRowLot', this.lots);
            },
            async clickAddLot() {

                if(!this.recordId){
                    if(this.lots.length >= this.stock)
                        return this.$message.error('La cantidad de registros es superior al stock o cantidad');
                }

                // let _index = index ? index :  this.getIndex()

                await this.lots.push({
                    id: null,
                    item_id: null,
                    series: null,
                    date:  moment().format('YYYY-MM-DD'),
                    state: 'Activo',
                    index: this.getIndex(),
                    rd_checked: false

                });

                this.$emit('addRowLot', this.lots);
            },
            getIndex(){
                return this.lots.length
            },
            async removeItem(index) {

                await this.lots.splice(index, 1);
                await this.renewIndexes()
                this.syncDataWithParent()
            },
            async renewIndexes(){
                await this.lots.forEach((row, index)=>{
                    row.index = index
                })
            },
            async clickCancelSubmit() {
                //this.$emit('addRowLot', []);
                await this.$emit('update:showDialog', false)
                this.$emit('closeDialog');
            },
            close() {
                this.$emit('update:showDialog', false)
            },
            addFile(file, fileList) {
                this.rd_file = file.raw;

                this.syncDataWithParent();
            },
            submitRdFile() {
                const selectedLotIndexes = this.lots.filter(lot => lot.rd_checked).map(lot => lot.id);

                this.$emit('addRdFile', this.rd_file, this.rd_filename, selectedLotIndexes);

                this.rd_file = null;
                this.rd_filename = '';
                this.lots.forEach(lot => lot.rd_checked === false);
            },
            downloadRdFile(anUrl) {
                const a = document.createElement('a');
                a.href = `http://prueba.miamigo-facturador.local/storage/rd-files/${anUrl}`;
                a.download = this.parseFilename(anUrl)
                document.body.appendChild(a);
                a.click();
            },
            parseFilename(aSlug) {
                return aSlug.split('-')[0];
            }
        },
        watch: {
            stock(newStock, oldStock) {
                if (!this.recordId) {
                    if (newStock > this.lots.length) {
                        this.addMoreLots(newStock - this.lots.length)
                    } else {
                        this.deleteMoreLots(this.lots.length - newStock);
                    }
                }
            }
        }

    }
</script>
