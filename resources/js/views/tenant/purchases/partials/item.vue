<template>
    <el-dialog :title="titleDialog"
               :visible="showDialog"
			   @open="create"
               @close="close">
        <Keypress
            :key-code="112"
            key-event="keyup"
            @success="handleFn112"
        />
        <form autocomplete="off"
              @submit.prevent="clickAddItem">
            <div class="form-body row">
                    <div class="col-md-6">
                        <h2>
                            <el-switch
                                v-model="search_item_by_barcode"
                                active-text="Buscar con escaner de código de barras"
                                @change="changeSearchItemBarcode"
                            ></el-switch>
                        </h2>
                    </div>
                    <div class="col-md-6">
                        <h2>
                            <el-switch
                                v-model="hasSelectedServices"
                                active-text="Buscar por servicios"
                                @change="changeSearchItemBarcode"
                            ></el-switch>
                        </h2>
                    </div>
                    <div class="col-md-8 form-group" >
                        <div :class="{'has-danger': errors.item_id}"
                             class="form-group">
                            <label class="control-label">
                                Producto/Servicio
                                <a v-if="form.item.id" href="#"
                                   @click.prevent="showDialogNewItem = true">[Agrega]</a>
                                <a v-else href="#"
                                   @click.prevent="showDialogNewItem = true">[+ Nuevo]</a>
                            </label>
                            <el-select v-show="!search_item_by_barcode"
                                       v-model="form.item_id"
                                       :loading="loading_search"
                                       :remote-method="searchRemoteItems"
                                       filterable
                                       placeholder="Buscar"
                                       remote
                                       @change="changeItem"
                            >
                                <el-tooltip
                                    v-for="option in filteredProducts"
                                    :key="option.id"
                                    placement="left">
                                    <div
                                        slot="content"
                                        v-html="ItemSlotTooltipView(option)"
                                    ></div>
                                    <el-option
                                        :label="ItemOptionDescriptionView(option)"
                                        :value="option.id"
                                    ></el-option>

                                </el-tooltip>
                            </el-select>
                            <el-input v-show="search_item_by_barcode"
                                      v-model="form.barcode"
                                      :loading="loading_search"
                                      placeholder="Buscar"
                                      @change="searchBarCode"
                            ></el-input>
                            <small v-if="errors.item_id"
                                   class="form-control-feedback"
                                   v-text="errors.item_id[0]"></small>
                        </div>
                    </div>

                    <div v-if="!hasSelectedServices" class="col-lg-4">
                        <div :class="{'has-danger': errors.matrix_code}"
                             class="form-group">
                            <label class="control-label">Código de matriz SAP</label>
                            <el-input
                                v-model="form.matrix_code"
                            >
                            </el-input>

                            <small v-if="errors.matrix_code"
                                   class="form-control-feedback"
                                   v-text="errors.matrix_code[0]"></small>
                        </div>
                    </div>
                </div>


            <el-tabs v-model="activeName">

                <el-tab-pane
                    class
                    name="first">
                    <span slot="label">Datos generales</span>
                    <div class="row">
                        <div class="col-md-2">
                            <div :class="{'has-danger': errors.affectation_igv_type_id}"
                                 class="form-group">
                                <label class="control-label">Afectación Igv</label>
                                <el-select v-model="form.affectation_igv_type_id"
                                           :disabled="!change_affectation_igv_type_id"
                                           filterable>
                                    <el-option v-for="option in affectation_igv_types"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <el-checkbox v-model="change_affectation_igv_type_id">Editar</el-checkbox>
                                <small v-if="errors.affectation_igv_type_id"
                                       class="form-control-feedback"
                                       v-text="errors.affectation_igv_type_id[0]"></small>
                                <el-checkbox v-model="form.purchase_has_igv">Incluye Igv</el-checkbox><br>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div :class="{'has-danger': errors.quantity}"
                                 class="form-group">
                                <label class="control-label">Cantidad</label>
                                <el-input-number v-model="form.quantity"
                                                 :min="0.01"></el-input-number>
                                <small v-if="errors.quantity"
                                       class="form-control-feedback"
                                       v-text="errors.quantity[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div :class="{'has-danger': errors.quantity}"
                                 class="form-group">
                                <label class="control-label">Peso Kg</label>
                                <el-input-number v-model="form.weight"
                                                 :min="0.00"></el-input-number>
                                <small v-if="errors.weight"
                                       class="form-control-feedback"
                                       v-text="errors.weight[0]"></small>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div :class="{'has-danger': errors.unit_price}"
                                 class="form-group">
                                <label class="control-label">Precio Unitario

                                </label>
                                <el-input v-if="form.item.currency_type_id !== undefined"
                                          v-model="form.unit_price"
                                          @keyup.native="changeUnitPrice"
                                          class="input-with-select"
                                >
                                </el-input>
                                <small v-if="errors.unit_price"
                                       class="form-control-feedback"
                                       v-text="errors.unit_price[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2 ml-4">
                            <label class="control-label">Prorrateo</label>
                            <div class="row">
                                <el-checkbox v-model="form.prorated" class="pt-2">Costo</el-checkbox>
                                <el-checkbox v-model="form.prorated_by_weight" class="pt-2">Peso</el-checkbox>
                                <el-checkbox v-model="form.prorated_by_ad_valorem" class="pt-2">Ad Valorem</el-checkbox>
                            </div>
                        </div>
                        <div v-if="form.update_price"
                             class="col-md-2">
                            <div :class="{'has-danger': errors.unit_price}"
                                 class="form-group">
                                <label class="control-label">Precio de venta
                                    <el-tooltip class="item"
                                                content="Precio base, no confundir con precios de almacen "
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-input v-model="sale_unit_price"></el-input>
                                <small v-if="errors.sale_unit_price"
                                       class="form-control-feedback"
                                       v-text="errors.sale_unit_price[0]"></small>
                            </div>
                        </div>
                        <div v-if="canSetDateOfDue && !isLotEnabled"
                             class="col-md-2">
                            <div :class="{'has-danger': errors.date_of_due}"
                                 class="form-group">
                                <label class="control-label">Fec. Vencimiento
                                    <el-tooltip class="item"
                                                content="Fecha de vencimiento general del item. No aplica a lotes."
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-date-picker
                                    v-model="date_of_due"
                                    :clearable="false"
                                    :picker-options="datEmision"
                                    type="date"
                                    value-format="yyyy-MM-dd"
                                ></el-date-picker>

                                <small v-if="errors.date_of_due"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_due[0]"></small>
                            </div>
                        </div>
                        <div v-if="!hasSelectedServices"  class="col-md-4">
                            <div :class="{'has-danger': errors.warehouse_id}"
                                 class="form-group">
                                <label class="control-label">Almacén de destino
                                    <el-tooltip class="item"
                                                content="Aumenta el stock de ese almacen"
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-select v-model="form.warehouse_id"
                                           filterable>
                                    <el-option v-for="option in warehouses"
                                               :key="option.id"
                                               :label="option.description"
                                               :value="option.id"></el-option>
                                </el-select>
                                <small v-if="errors.warehouse_id"
                                       class="form-control-feedback"
                                       v-text="errors.warehouse_id[0]"></small>
                            </div>
                        </div>
                        <div v-if="!hasSelectedServices" class="col-lg-3">
                            <div class="form-group" :class="{'has-danger': errors.enters_warehouse_at}">
                                <label class="control-label">Fecha ingreso almacén</label>
                                <el-date-picker v-model="form.enters_warehouse_at"
                                                :clearable="false"
                                                type="date"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.enters_warehouse_at" v-text="errors.enters_warehouse_at[0]"></small>
                            </div>
                        </div>
                        <div v-if="form.item_id"
                             class="col-md-6 mt-2">
                            <div v-if="form.item.lots_enabled"
                                 :class="{'has-danger': errors.lot_code}"
                                 class="form-group">
                                <label class="control-label">
                                    Código lote
                                </label>
                                <el-input v-model="lot_code">
                                    <!--<el-button slot="append" icon="el-icon-edit-outline"  @click.prevent="clickLotcode"></el-button> -->
                                </el-input>
                                <small v-if="errors.lot_code"
                                       class="form-control-feedback"
                                       v-text="errors.lot_code[0]"></small>
                            </div>
                        </div>
                        <div v-show="form.item_id"
                             class="col-md-3"
                             style="padding-top: 1%;">
                            <div v-if="form.item.lots_enabled"
                                 :class="{'has-danger': errors.date_of_due}"
                                 class="form-group">
                                <label class="control-label">Fec. Vencimiento</label>
                                <el-date-picker v-model="form.date_of_due"
                                                :clearable="true"
                                                type="date"
                                                value-format="yyyy-MM-dd"></el-date-picker>
                                <small v-if="errors.date_of_due"
                                       class="form-control-feedback"
                                       v-text="errors.date_of_due[0]"></small>
                            </div>
                        </div>
                        <div v-show="form.item_id"
                             class="col-md-3"><br>
                            <div v-if="form.item.series_enabled"
                                 :class="{'has-danger': errors.lot_code}"
                                 class="form-group">
                                <label class="control-label">
                                    <!-- <el-checkbox v-model="enabled_lots"  @change="changeEnabledPercentageOfProfit">Código lote</el-checkbox> -->
                                    Ingrese series
                                </label>

                                <el-button icon="el-icon-edit-outline"
                                           style="margin-top:2%;"
                                           type="primary"
                                           @click.prevent="clickSeries"></el-button>

                                <small v-if="errors.lot_code"
                                       class="form-control-feedback"
                                       v-text="errors.lot_code[0]"></small>
                            </div>
                        </div>
                    </div>

                </el-tab-pane>

                <el-tab-pane v-if="!isImport" name="mincetur">
                    <span slot="label">Mincetur</span>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-padding">
                                    <tbody>
                                    <tr>
                                        <td>Nombre Comercial</td>
                                        <td width="400">
                                            <el-input v-model="form.mincetur.tradename"></el-input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Type & Version</td>
                                        <td width="400">
                                            <el-input v-model="form.mincetur.type_version"></el-input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>MINCETUR</td>
                                        <td width="400">
                                            <el-input v-model="form.mincetur.mincetur"></el-input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>VMT/DGJCMT</td>
                                        <td width="400">
                                            <el-input v-model="form.mincetur.vmt_dgjcmt"></el-input>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>LINK PDF</td>
                                        <td width="400">
                                            <!-- <el-input v-model="form.mincetur.link_pdf"></el-input> -->
                                            <el-upload
                                                    :data="{'type': 'purchase-import-cost-attached'}"
                                                    :headers="headers"
                                                    :multiple="false"
                                                    :on-remove="handleRemovePdf"
                                                    :action="`/purchases/import-cost/upload`"
                                                    :show-file-list="true"
                                                    :file-list="form.mincetur.link_pdf.list"
                                                    :on-success="onSuccessPdf"
                                                    :limit="1"
                                                    >
                                                <el-button slot="trigger" type="primary">Seleccione un archivo (PDF/JPG)</el-button>
                                            </el-upload>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>

                <el-tab-pane
                    v-if="!isImport && form.item_unit_types && form.item_unit_types.length"
                    name="second">
                    <span slot="label">
                         Lista de Precios
                                    <el-tooltip class="item"
                                                content="Aplica para realizar compra/venta en presentacion de diferentes precios y/o cantidades"
                                                effect="dark"
                                                placement="top">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                    </span>
                    <div class="row">
                        <div class="col-md-12 ">
                            <div class="table-responsive"
                                 style="margin:3px">

                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="text-center">Unidad</th>
                                        <th class="text-center">Descripción</th>
                                        <th class="text-center">Factor</th>
                                        <th class="text-center">Precio 1</th>
                                        <th class="text-center">Precio 2</th>
                                        <th class="text-center">Precio 3</th>
                                        <th class="text-center">Precio Default</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.item_unit_types"
                                        :key="index">
                                        <td class="text-center">{{ row.unit_type_id }}</td>
                                        <td class="text-center">{{ row.description }}</td>
                                        <td class="text-center">{{ row.quantity_unit }}</td>
                                        <td class="text-center">
                                            <template v-if=" canEditPrice === false">
                                                {{ row.price1 }}
                                            </template>
                                            <template v-else>
                                                <el-input v-model="row.price1"></el-input>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if=" canEditPrice === false">
                                                {{ row.price2 }}
                                            </template>
                                            <template v-else>
                                                <el-input v-model="row.price2"></el-input>
                                            </template>

                                        </td>
                                        <td class="text-center">
                                            <template v-if=" canEditPrice === false">
                                                {{ row.price3 }}
                                            </template>
                                            <template v-else>
                                                <el-input v-model="row.price3"></el-input>
                                            </template>
                                        </td>
                                        <td class="text-center">Precio {{ row.price_default }}</td>
                                        <td class="series-table-actions text-right">
                                            <button class="btn waves-effect waves-light btn-xs btn-success"
                                                    type="button"
                                                    @click.prevent="selectedPrice(row)">
                                                <i class="el-icon-check"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </el-tab-pane>
                <!-- Almacenes -->
<!--                <el-tab-pane-->
<!--                    v-show="form.item.item_warehouse_prices !== undefined && form.item.item_warehouse_prices.length > 0 && canEditPrice === true"-->
<!--                    class-->
<!--                    name="third">-->
<!--                    <span slot="label">Precios por almacén</span>-->
<!--                    <div class="row">-->
<!--                        <div class="col-12">-->
<!--                            <div class="table-responsive">-->
<!--                                <table class="table table-bordered">-->
<!--                                    <tbody>-->
<!--                                    <tr v-for="item in form.item.item_warehouse_prices"-->
<!--                                        :key="item.id">-->
<!--                                        <td>{{ item.description }}</td>-->
<!--                                        <td width="150">-->
<!--                                            <el-input v-model="item.price"-->
<!--                                                      min="0"-->
<!--                                                      placeholder="Precio"-->
<!--                                                      step="0.01"-->
<!--                                                      type="number"></el-input>-->
<!--                                        </td>-->
<!--                                    </tr>-->
<!--                                    </tbody>-->
<!--                                </table>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </el-tab-pane>-->
            </el-tabs>

            <div v-if="form.item.sub_items">
                <h5>Sub productos</h5>
                <div class="row">
                    <div class="col-md-6">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Seleccionado</th>
                                <th>Nombre</th>
                                <th>Stock</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in form.item.sub_items">
                                <td width="100"><el-checkbox :value="form.sub_item_ids.includes(item.id)" @change="addSubItem($event, item.id)"></el-checkbox></td>
                                <td width="200">{{ item.description }}</td>
                                <td width="200">{{ form.quantity }}</td>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                </div>

            </div>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <template v-if="canShowExtraData">
                            <!-- resources/js/views/tenant/components/partials/item_extra_info.vue -->
                            <tenant-item-aditional-info-selector
                                :form="form"
                                :errors="errors"
                            ></tenant-item-aditional-info-selector>
                        </template>
                    </div>
                </div>
            </div>

            <div class="form-body">
                <div class="row">
                    <div class="col-md-12 mt-3">
                        <section id="card-section"
                                 class="card mb-2 card-transparent card-collapsed">
                            <header id="card-click"
                                    class="card-header hoverable bg-light border-top rounded-0 py-1"
                                    data-card-toggle
                                    style="cursor: pointer;">
                                <div class="card-actions"
                                     style="margin-top: -12px;">
                                    <a class="card-action card-action-toggle text-info"
                                       data-card-toggle=""
                                       href="#"></a>
                                </div>

                                <p class="pl-1">Información adicional atributos UBL 2.1</p>
                            </header>
                            <div class="card-body px-0 pt-2"
                                 style="display: none;">
                                <div v-if="discount_types.length > 0"
                                     class="col-md-12 px-0">
                                    <label class="control-label">
                                        Descuentos
                                        <a href="#"
                                           @click.prevent="clickAddDiscount">[+ Agregar]</a>
                                    </label>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th>Porcentaje</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.discounts">
                                            <td>
                                                <el-select v-model="row.discount_type_id"
                                                           @change="changeDiscountType(index)">
                                                    <el-option v-for="option in discount_types"
                                                               :key="option.id"
                                                               :label="option.description"
                                                               :value="option.id"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-input v-model="row.description"></el-input>
                                            </td>
                                            <td>
                                                <el-input v-model="row.percentage"></el-input>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveDiscount(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="charge_types.length > 0"
                                     class="col-md-12 px-0">
                                    <label class="control-label">
                                        Cargos
                                        <a href="#"
                                           @click.prevent="clickAddCharge">[+ Agregar]</a>
                                    </label>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th>Porcentaje</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.charges">
                                            <td>
                                                <el-select v-model="row.charge_type_id"
                                                           @change="changeChargeType(index)">
                                                    <el-option v-for="option in charge_types"
                                                               :key="option.id"
                                                               :label="option.description"
                                                               :value="option.id"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-input v-model="row.description"></el-input>
                                            </td>
                                            <td>
                                                <el-input v-model="row.percentage"></el-input>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveCharge(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-if="attribute_types.length > 0"
                                     class="col-md-12 px-0">
                                    <label class="control-label">
                                        Atributos
                                        <a href="#"
                                           @click.prevent="clickAddAttribute">[+ Agregar]</a>
                                    </label>
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Tipo</th>
                                            <th>Descripción</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.attributes">
                                            <td>
                                                <el-select v-model="row.attribute_type_id"
                                                           filterable
                                                           @change="changeAttributeType(index)">
                                                    <el-option v-for="option in attribute_types"
                                                               :key="option.id"
                                                               :label="option.description"
                                                               :value="option.id"></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-input v-model="row.value"></el-input>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger"
                                                        type="button"
                                                        @click.prevent="clickRemoveAttribute(index)">x
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button v-if="form.item_id"
                           native-type="submit"
                           type="primary">{{ this.recordItem ? 'Editar' : 'Agregar' }}
                </el-button>
            </div>
        </form>
        <item-form :external="true"
                   :showDialog.sync="showDialogNewItem"></item-form>

        <el-dialog :title="titleDialog" width="50%"  :visible="showItemLotDialog" @open="create" :close-on-click-modal="false" :close-on-press-escape="false" append-to-body :show-close="false">
            <lots-form
                :showDialog.sync="showItemLotDialog"
                :canAddRows="true"
                :lots="lots"
                :stock="form.quantity"
                :gameNames="gameNames"
                @addRowLot="addRowLot">
            </lots-form>
        </el-dialog>

    </el-dialog>
</template>
<style>
.el-select-dropdown {
    margin-right: 5% !important;
    max-width: 80% !important;
}

.el-select-currency {
    width: 59px;
}

.input-with-select {
    background-color: #FFFFFF;
}
.table-padding th, .table td{padding: 0.25rem;}
</style>
<script>

import itemForm from '../../items/form.vue'
import {calculateRowItem} from '../../../../helpers/functions'
import LotsForm from '@components/incomeLots.vue'
import Keypress from "vue-keypress";
import {ItemOptionDescription, ItemSlotTooltip} from "../../../../helpers/modal_item";
import {mapActions, mapState} from "vuex/dist/vuex.mjs";
import moment from "moment";

export default {
    props: [
        'showDialog',
        'currencyTypeIdActive',
        'exchangeRateSale',
		'recordItem',
        'isImport'
    ],
    components: {itemForm, LotsForm, Keypress},
    computed: {
        ...mapState([
            'config',
            'hasGlobalIgv',
            'colors',
            'CatItemUnitsPerPackage',
            'CatItemMoldProperty',
            'CatItemUnitBusiness',
            'CatItemStatus',
            'CatItemPackageMeasurement',
            'CatItemMoldCavity',
            'CatItemProductFamily',
            'CatItemSize',
            'extra_colors',
            'extra_CatItemUnitsPerPackage',
            'extra_CatItemMoldProperty',
            'extra_CatItemSize',
            'extra_CatItemUnitBusiness',
            'extra_CatItemStatus',
            'extra_CatItemPackageMeasurement',
            'extra_CatItemMoldCavity',
            'extra_CatItemProductFamily',
        ]),
        canShowExtraData: function(){
            if(this.config && this.config.show_extra_info_to_item  !== undefined){
                return this.config.show_extra_info_to_item;
            }
            return false;
        },
        canEditPrice() {
            if (this.form && this.form.update_price !== undefined) {
                return this.form.update_price
            }
            return false;
        },
        canSetDateOfDue() {
            if (this.form && this.form.update_date_of_due !== undefined) {
                return this.form.update_date_of_due
            }
            return false;
        },
        isLotEnabled() {
            if (this.form) {
                let form = this.form;
                if (
                    form &&
                    form.item &&
                    form.item.lots_enabled
                ) {
                    // this.date_of_due = null;
                    this.form.update_date_of_due = false;
                    return form.item.lots_enabled;
                }
            }

            return false;
        },
        filteredProducts() {
            const unit_type = this.hasSelectedServices ? 'ZZ' : 'NIU';

            return this.items.filter(item => item.unit_type_id === unit_type);
        }
    },
    data() {
        return {
            datEmision: {
                disabledDate(time) {
                    return time.getTime() < moment();
                }
            },
            headers: headers_token,
            search_item_by_barcode: false,
            sale_unit_price: 0,
            date_of_due: null,
            loading_search: false,
            titleDialog: null,
            showDialogLots: false,
            resource: 'purchases',
            showDialogNewItem: false,
            showItemLotDialog: false,
            errors: {},
            form: {},
            activeName: 'first',
            items: [],
            all_items: [],
            warehouses: [],
            lots: [],
            affectation_igv_types: [],
            system_isc_types: [],
            discount_types: [],
            charge_types: [],
            attribute_types: [],
            gameNames: [],
            use_price: 1,
            lot_code: null,
            change_affectation_igv_type_id: false,
            prices: {},
            hasSelectedServices: false
        }
    },
    created() {
        this.loadConfiguration()
        this.loadHasGlobalIgv()
        this.activeName = 'first'
        this.initForm()
        this.$http.get(`/${this.resource}/item/tables`).then(response => {
            this.all_items = response.data.items
            this.affectation_igv_types = response.data.affectation_igv_types
            this.system_isc_types = response.data.system_isc_types
            this.discount_types = response.data.discount_types
            this.charge_types = response.data.charge_types
            this.attribute_types = response.data.attribute_types
            this.warehouses = response.data.warehouses.filter(wh => ['Almacén Oficina Principal', 'Almacén - En Tránsito', 'Almacén - REPUESTOS', 'Almacén - Intangibles (Juegos)'].includes(wh.description)),
            this.gameNames = response.data.game_names;
            this.$store.commit('setConfiguration', response.data.configuration)
            this.initFilterItems()
        });
        this.getExtraInfoOfItems();
        this.$eventHub.$on('reloadDataItems', (item_id) => {
            this.reloadDataItems(item_id)
        })
    },
    methods: {
        ...mapActions([
            'loadConfiguration',
            'loadHasGlobalIgv',
            'clearExtraInfoItem',
        ]),
		create() {
			if(this.recordItem){
				this.titleDialog = 'Editar Producto o Servicio'
				this.items = []
				this.items.push(this.recordItem.data.item)
				this.form = {...this.form, ...this.recordItem.data}
                this.lots = Array.from(this.form.lots);

			}else{
				this.titleDialog = 'Agregar Producto o Servicio'
			}
		},
        ItemSlotTooltipView(item) {
            return ItemSlotTooltip(item);
        },
        ItemOptionDescriptionView(item) {
            return ItemOptionDescription(item)
        },
        handleFn112(response) {
            this.search_item_by_barcode = !this.search_item_by_barcode;
        },
        searchBarCode(input) {
            this.loading_search = true
            let parameters = `barcode=${input}`
            this.$http.get(`/${this.resource}/search-items/?${parameters}`)
                .then(response => {
                    let items = response.data.items
                    this.items = items
                    this.loading_search = false
                    if (items === undefined || items.length == 0) {
                        this.initFilterItems()
                    } else if (this.items.length > 2) {
                        // varios items
                    } else {
                        if (items.length == 1) {
                            this.form.item_id = items[0].id
                            this.items = response.data.items
                            this.form.item = items[0]
                            this.form.item_id = items[0].id
                            this.changeItemAlt();
                        }
                    }
                })
        },
        async searchRemoteItems(input) {

            if (input.length > 2) {

                this.loading_search = true
                let parameters = `input=${input}`

                await this.$http.get(`/${this.resource}/search-items/?${parameters}`)
                    .then(response => {
                        this.items = response.data.items
                        this.loading_search = false

                        if (this.items.length == 0) {
                            this.initFilterItems()
                        }
                    })
                    .then(() => {
                        this.activeName = 'first'
                    })
            } else {
                await this.initFilterItems()
            }

        },
        initFilterItems() {
            this.activeName = 'first'
            this.items = this.all_items
        },
        addRowLot(lots) {
            this.lots = lots
        },
        clickSeries() {
            this.showItemLotDialog = true
        },
        filterItems() {
            this.items = this.items.filter(item => item.warehouses.length > 0)
            // this.items = this.items.filter(item => (item.warehouses!== undefined && item.warehouses.length >0))
        },
        initForm() {
            this.errors = {}
            let warehouse = 1;
            if (this.config !== undefined && this.config.warehouse_id !== undefined) {
                warehouse = this.config.warehouse_id;
            }
            this.form = {
                item_id: null,
                sub_item_ids: [],
                barcode: null,
                warehouse_id: warehouse,
                warehouse_description: null,
                item: {},
                affectation_igv_type_id: null,
                affectation_igv_type: {},
                has_isc: false,
                system_isc_type_id: null,
                percentage_isc: 0,
                suggested_price: 0,
                quantity: 1,
                unit_price: 0,
                charges: [],
                discounts: [],
                attributes: [],
                item_unit_types: [],
                lot_code: null,
                date_of_due: null,
                purchase_has_igv: null,
                purchase_has_ad_valorem: null,
                update_price: false,
                update_date_of_due: false,
                update_purchase_price: true,
                detail: {
                    serial: null,
                    sap_serial: null,
                    import_order: null,
                    invoice: null,
                    month_manufacture: null,
                    year_manufacture: null,
                    type_version: null,
                    name_game: null,
                    owner: null,
                    nro_contract: null,
                    pro_forma: null,
                    date_contract: null,
                    status: null,
                    price_fob_unit: null,
                },
                mincetur: {
                    tradename: null,
                    type_version: null,
                    mincetur: null,
                    vmt_dgjcmt: null,
                    link_pdf: {
                        list: [],
                        attached: null,
                        temp_path: null
                    },
                },
                prorated: false,
                prorated_by_ad_valorem: false,
                prorated_by_weight: false,
                weight: null,
                matrix_code: '',
                enters_warehouse_at: moment().format("YYYY-MM-DD"),
            }

            this.item_unit_type = {};
            this.lots = []
            this.lot_code = null
            this.activeName = "first"
            this.form.detail.owner = `${this.$store.state.config.company ? this.$store.state.config.company.number : ''} - ${this.$store.state.company.trade_name}`;
        },
        clickAddDiscount() {
            this.form.discounts.push({
                discount_type_id: null,
                discount_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0
            })
        },
        clickRemoveDiscount(index) {
            this.form.discounts.splice(index, 1)
        },
        changeDiscountType(index) {
            let discount_type_id = this.form.discounts[index].discount_type_id
            this.form.discounts[index].discount_type = _.find(this.discount_types, {id: discount_type_id})
        },
        clickAddCharge() {
            this.form.charges.push({
                charge_type_id: null,
                charge_type: null,
                description: null,
                percentage: 0,
                factor: 0,
                amount: 0,
                base: 0
            })
        },
        clickRemoveCharge(index) {
            this.form.charges.splice(index, 1)
        },
        changeChargeType(index) {
            let charge_type_id = this.form.charges[index].charge_type_id
            this.form.charges[index].charge_type = _.find(this.charge_types, {id: charge_type_id})
        },
        clickAddAttribute() {
            this.form.attributes.push({
                attribute_type_id: null,
                description: null,
                value: null,
                start_date: null,
                end_date: null,
                duration: null,
            })
        },
        clickRemoveAttribute(index) {
            this.form.attributes.splice(index, 1)
        },
        changeAttributeType(index) {
            let attribute_type_id = this.form.attributes[index].attribute_type_id
            let attribute_type = _.find(this.attribute_types, {id: attribute_type_id})
            this.form.attributes[index].description = attribute_type.description
        },
        close() {
            this.initForm()
            this.$emit('update:showDialog', false)
        },
        selectedPrice(row) {
            this.form.item_unit_type_id = row.id
            this.item_unit_type = row
            this.form.item.unit_type_id = row.unit_type_id
        },
        changeItem() {
            const item = {..._.find(this.items, {'id': this.form.item_id})};
            this.form.item = item;
            this.form.sub_item_ids = [];
            this.form.item = this.setExtraFieldOfitem(this.form.item)

            const saleUnitPrice = item.sale_unit_price;
            this.sale_unit_price = parseFloat(saleUnitPrice).toFixed(2);
            this.form.unit_price = this.form.item.purchase_unit_price
            this.form.affectation_igv_type_id = this.form.item.purchase_affectation_igv_type_id
            this.form.item_unit_types = _.find(this.items, {'id': this.form.item_id}).item_unit_types
            this.prices = this.form.item_unit_types;
            this.date_of_due = this.form.date_of_due;
            this.form.purchase_has_igv = this.form.item.purchase_has_igv;
            this.form.purchase_has_ad_valorem = false;
            this.setExtraElements(this.form.item);
            this.setGlobalIgvToItem()
            this.changeUnitPrice()
            this.fillDataMincetur(item)
        },
        setGlobalIgvToItem() {
            if (this.config.enabled_global_igv_to_purchase === true) {
                // Ajusta el igv, si es global, se lo añade o quita al precio del item directamente
                this.form.purchase_has_igv = this.hasGlobalIgv
            }
        },
        changeUnitPrice() {
            this.form.detail.price_fob_unit  = this.form.unit_price
        },
        changeItemAlt() {
            let item = _.find(this.items, {'id': this.form.item_id});
            this.form.item = item;
            this.form.item = this.setExtraFieldOfitem(this.form.item)
            let saleUnitPrice = item.sale_unit_price;
            this.sale_unit_price = parseFloat(saleUnitPrice).toFixed(2);
            this.form.unit_price = this.form.item.purchase_unit_price
            this.form.affectation_igv_type_id = this.form.item.purchase_affectation_igv_type_id
            this.form.item_unit_types = item.item_unit_types
            this.prices = this.form.item_unit_types;
            this.date_of_due = this.form.date_of_due;
            this.form.purchase_has_igv = this.form.item.purchase_has_igv;
            this.form.purchase_has_ad_valorem = false;
            this.search_item_by_barcode = 0;
            this.setExtraElements(this.form.item);
            this.setGlobalIgvToItem()
            this.changeUnitPrice()
        },
        async clickAddItem() {
            if (this.form.item.lots_enabled) {
                if (!this.lot_code)
                    return this.$message.error('Código de lote es requerido');

                if (!this.form.date_of_due)
                    return this.$message.error('Fecha de vencimiento es requerido si lotes esta habilitado.');
            }

            if (this.form.item.series_enabled) {
                if (this.lots.length > this.form.quantity)
                    return this.$message.error('La cantidad de series registradas es superior al stock');

                if (this.lots.length != this.form.quantity)
                    return this.$message.error('La cantidad de series registradas son diferentes al stock');
            }

            let affectation_igv_types_exonerated_unaffected = ['20', '21', '30', '31', '32', '33', '34', '35', '36', '37']

            let unit_price = this.form.unit_price

            if (!affectation_igv_types_exonerated_unaffected.includes(this.form.affectation_igv_type_id)) {

                unit_price = (this.form.purchase_has_igv) ? this.form.unit_price : this.form.unit_price * 1.18;

            }

            let date_of_due = this.form.date_of_due
            if (this.date_of_due != null && this.form.update_date_of_due && !this.form.item.lots_enabled) {
                date_of_due = this.date_of_due;
            }

            this.form.item.unit_price = unit_price
            this.form.item.presentation = this.item_unit_type;
            this.form.affectation_igv_type = _.find(this.affectation_igv_types, {'id': this.form.affectation_igv_type_id})
            this.row = await calculateRowItem(this.form, this.currencyTypeIdActive, this.exchangeRateSale)
            this.row.sub_item_ids = [...this.form.sub_item_ids]
            this.row.lot_code = await this.lot_code
            this.row.lots = await this.lots
            this.row.update_price = this.form.update_price
            this.row.update_date_of_due = this.form.update_date_of_due
            this.row.update_purchase_price = this.form.update_purchase_price
            this.row.sale_unit_price = this.sale_unit_price

            this.row = this.changeWarehouse(this.row)
            this.row.warehouse = this.warehouses.find(wh => wh.id === this.row.warehouse_id)

			this.row.has_isc = this.form.has_isc
			this.row.item_unit_types = this.form.item_unit_types
			this.row.purchase_has_igv = this.form.purchase_has_igv
			this.row.purchase_has_ad_valorem = this.form.purchase_has_ad_valorem
			this.row.suggested_price = this.form.suggested_price

            this.row.date_of_due = date_of_due

            this.row.detail = this.form.detail
            this.row.detail.price_fob_unit = unit_price
            this.row.mincetur = this.form.mincetur
            this.row.lots = this.lots
            this.row.weight = this.form.weight;
            this.row.prorated = this.form.prorated;
            this.row.prorated_by_weight = this.form.prorated_by_weight;
            this.row.prorated_by_ad_valorem = this.form.prorated_by_ad_valorem;
            this.row.enters_warehouse_at = this.form.enters_warehouse_at;
            this.row.matrix_code = this.form.matrix_code;

			//this.row.temp_edit = {form: this.form, lots: this.lots}

            this.initForm()

			const index = this.recordItem ? this.recordItem.index : null
            this.$emit('add', {... this.row}, index)
			if(this.recordItem) this.close()
        },
        changeWarehouse(row) {
            let warehouse = _.find(this.warehouses, {'id': this.form.warehouse_id})
            row.warehouse_id = warehouse.id
            row.warehouse_description = warehouse.description
            return row
        },
        reloadDataItems(item_id) {

            if (!item_id) {

                this.$http.get(`/${this.resource}/table/items`).then((response) => {
                    this.items = response.data
                    this.form.item_id = item_id
                })

            } else {

                this.$http.get(`/${this.resource}/search/item/${item_id}`).then((response) => {

                    this.items = response.data.items
                    this.form.item_id = item_id
                    this.changeItem()

                })
            }

        },
        enabledSearchItemsBarcode() {
            if (this.search_item_by_barcode) {
                if (this.items.length == 1) {
                    this.clickAddItem(this.items[0], 0);
                    this.filterItems();
                }

                this.cleanInput();
            }
        },
        changeSearchItemBarcode() {
            this.cleanInput();
            if (!this.search_item_by_barcode) {
                this.initFilterItems()
            }
        },
        cleanInput() {
            this.input_item = null;
        },

        getExtraInfoOfItems(){
            let url = '/extra_info/items';
            this.$http.post(url,{})
            .then((response)=>{
                let data = response.data
                   if(this.canShowExtraData) {
                    this.$store.commit('setColors', data.colors);
                    this.$store.commit('setCatItemUnitsPerPackage', data.CatItemUnitsPerPackage);
                    this.$store.commit('setCatItemStatus', data.CatItemStatus);
                    this.$store.commit('setCatItemMoldCavity', data.CatItemMoldCavity);
                    this.$store.commit('setCatItemMoldProperty', data.CatItemMoldProperty);
                    this.$store.commit('setCatItemUnitBusiness', data.CatItemUnitBusiness);
                    this.$store.commit('setCatItemPackageMeasurement', data.CatItemPackageMeasurement);
                    this.$store.commit('setCatItemProductFamily', data.CatItemPackageMeasurement);
                    this.$store.commit('setCatItemSize', data.CatItemSize);
                }
                this.$store.commit('setConfiguration', data.configuration);
            })
            .finally(()=>{})
        },
        setExtraElements(item) {
            this.clearExtraInfoItem()
            if(this.canShowExtraData) {
                let temp = [];
                this.colors.find(obj => {
                    for (var i = 0, iLen = item.colors.length; i < iLen; i++) {
                        if (item.colors[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                });
                this.$store.commit('setExtraColors', temp)
                temp = [];
                this.CatItemUnitsPerPackage.find(obj => {
                    for (var i = 0, iLen = item.CatItemUnitsPerPackage.length; i < iLen; i++) {
                        if (item.CatItemUnitsPerPackage[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemUnitsPerPackage', temp)
                temp = [];
                this.CatItemMoldProperty.find(obj => {
                    for (var i = 0, iLen = item.CatItemMoldProperty.length; i < iLen; i++) {
                        if (item.CatItemMoldProperty[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemMoldProperty', temp)
                temp = [];
                this.CatItemUnitBusiness.find(obj => {
                    for (var i = 0, iLen = item.CatItemUnitBusiness.length; i < iLen; i++) {
                        if (item.CatItemUnitBusiness[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemUnitBusiness', temp)
                temp = [];
                this.CatItemStatus.find(obj => {
                    for (var i = 0, iLen = item.CatItemStatus.length; i < iLen; i++) {
                        if (item.CatItemStatus[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemStatus', temp)
                temp = [];
                this.CatItemPackageMeasurement.find(obj => {
                    for (var i = 0, iLen = item.CatItemPackageMeasurement.length; i < iLen; i++) {
                        if (item.CatItemPackageMeasurement[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemPackageMeasurement', temp)
                temp = [];
                this.CatItemMoldCavity.find(obj => {
                    for (var i = 0, iLen = item.CatItemMoldCavity.length; i < iLen; i++) {
                        if (item.CatItemMoldCavity[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemMoldCavity', temp)
                temp = [];
                this.CatItemProductFamily.find(obj => {
                    for (var i = 0, iLen = item.CatItemProductFamily.length; i < iLen; i++) {
                        if (item.CatItemProductFamily[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemProductFamily', temp)
                temp = [];
                this.CatItemSize.find(obj => {
                    for (var i = 0, iLen = item.CatItemSize.length; i < iLen; i++) {
                        if (item.CatItemSize[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemSize', temp)
                temp = [];
                this.CatItemMoldProperty.find(obj => {
                    for (var i = 0, iLen = item.CatItemMoldProperty.length; i < iLen; i++) {
                        if (item.CatItemMoldProperty[i] === obj.id) {
                            temp.push(obj)
                        }
                    }
                })
                this.$store.commit('setExtraCatItemMoldProperty', temp)
                if (this.extra_temp !== undefined) {
                    this.form.item.extra = this.extra_temp;
                }
            }
        },
        setExtraFieldOfitem(item) {

            if(this.canShowExtraData) {
                if (item.extra === undefined) item.extra = {};
                if (item.extra.colors === undefined) item.extra.colors = null;
                if (item.extra.CatItemUnitsPerPackage === undefined) item.extra.CatItemUnitsPerPackage = null;
                if (item.extra.CatItemMoldProperty === undefined) item.extra.CatItemMoldProperty = null;
                if (item.extra.CatItemUnitBusiness === undefined) item.extra.CatItemUnitBusiness = null;
                if (item.extra.CatItemStatus === undefined) item.extra.CatItemStatus = null;
                if (item.extra.CatItemPackageMeasurement === undefined) item.extra.CatItemPackageMeasurement = null;
                if (item.extra.CatItemMoldCavity === undefined) item.extra.CatItemMoldCavity = null;
                if (item.extra.CatItemProductFamily === undefined) item.extra.CatItemProductFamily = null;
                if (item.extra.CatItemSize === undefined) item.extra.CatItemSize = null;

                if (this.extra_temp !== undefined) {
                    item.extra = this.extra_temp;
                }
            }
            return item
        },
        onSuccessPdf(response, file, fileList) {
            this.form.mincetur.link_pdf.list = fileList
            if (response.success) {
                this.form.mincetur.link_pdf.attached = response.data.filename
                this.form.mincetur.link_pdf.temp_path = response.data.temp_path
            } else {
                this.form.mincetur.link_pdf.list = []
                this.$message.error(response.message)
            }
        },
        handleRemovePdf(file, fileList) {
            this.form.mincetur.link_pdf.attached = null
            this.form.mincetur.link_pdf.temp_path = null
            this.form.mincetur.link_pdf.list = []
        },
        fillDataMincetur(item) {
            this.form.mincetur.tradename = item.second_name;
            this.form.mincetur.type_version = item.description;
            this.form.mincetur.mincetur = item.mincetur_code;
        },
        addSubItem(isAdded, anItemId) {
            console.log(anItemId);
            if (isAdded && !this.form.sub_item_ids.includes(anItemId)) {
                this.form.sub_item_ids.push(anItemId);
            } else {
                const itemIndex = this.form.sub_item_ids.findIndex(id => id === anItemId);

                if (itemIndex >= 0) {
                    this.form.sub_item_ids.splice(itemIndex, 1);
                }
            }
        }
    }
}

</script>
