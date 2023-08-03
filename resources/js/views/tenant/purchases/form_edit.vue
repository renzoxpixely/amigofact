<template>
    <div class="card mb-0 pt-2 pt-md-0 negrita">
        <div class="card-header bg-info">
            <h3 class="my-0">Editar Compra</h3>
        </div>
        <div class="tab-content">
            <form autocomplete="off" @submit.prevent="submit">
                <div class="form-body">
                    <div class="row">
                         <div class="col-lg-4">
                            <div class="form-group" :class="{'has-danger': errors.document_type_id}">
                                <label class="control-label">Tipo comprobante</label>
                                <el-select v-model="form.document_type_id" @change="changeDocumentType">
                                    <el-option v-for="option in document_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.document_type_id" v-text="errors.document_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.series}">
                                <label class="control-label">Serie <span class="text-danger">*</span></label>
                                <el-input v-model="form.series" :maxlength="4"   @input="inputSeries"></el-input>

                                <small class="form-control-feedback" v-if="errors.series" v-text="errors.series[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.number}">
                                <label class="control-label">Número <span class="text-danger">*</span></label>
                                <el-input v-model="form.number"></el-input>

                                <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                            </div>
                        </div>



                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.date_of_issue}">
                                <label class="control-label">Fec Emisión</label>
                                <el-date-picker v-model="form.date_of_issue" :readonly="readonly_date_of_due" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_issue" v-text="errors.date_of_issue[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.date_of_due}">
                                <label class="control-label">Fec. Vencimiento</label>
                                <el-date-picker v-model="form.date_of_due" type="date" :readonly="readonly_date_of_due" value-format="yyyy-MM-dd" :clearable="false"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_of_due" v-text="errors.date_of_due[0]"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.supplier_id}">
                                <label class="control-label">
                                    Proveedor
                                    <a href="#" @click.prevent="showDialogNewPerson = true">[+ Nuevo]</a>
                                </label>
                                <el-select v-model="form.supplier_id" filterable @change="changeSupplier" ref="select_person" @keyup.native="keyupSupplier" @keyup.enter.native="keyupEnterSupplier">
                                    <el-option v-for="option in suppliers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.supplier_id" v-text="errors.supplier_id[0]"></small>
                            </div>
                        </div>
                        <!-- <div class="col-lg-3">
                            <div class="form-group" :class="{'has-danger': errors.payment_method_type_id}">
                                <label class="control-label">
                                    Forma de pago
                                </label>
                                <el-select v-model="form.payment_method_type_id" filterable @change="changePaymentMethodType">
                                    <el-option v-for="option in payment_method_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.payment_method_type_id" v-text="errors.payment_method_type_id[0]"></small>
                            </div>
                        </div> -->
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.date_periodo}">
                                <label class="control-label">Periodo</label>
                                <el-date-picker v-model="form.date_periodo" type="month" value-format="yyyy-MM-dd" format="MM/yyyy" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.date_periodo" v-text="errors.date_periodo[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2" v-show="form.has_import">
                            <div class="form-group" :class="{'has-danger': errors.arrival_date}">
                                <label class="control-label">Fecha de Arribo <span class="text-danger">*</span></label>
                                <el-date-picker v-model="form.arrival_date"
                                            :clearable="false"
                                            type="date"
                                            value-format="yyyy-MM-dd"></el-date-picker>
                                <small class="form-control-feedback" v-if="errors.arrival_date" v-text="errors.arrival_date[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.currency_type_id}">
                                <label class="control-label">Moneda</label>
                                <el-select v-model="form.currency_type_id" @change="changeCurrencyType">
                                    <el-option v-for="option in currency_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>
                                <small class="form-control-feedback" v-if="errors.currency_type_id" v-text="errors.currency_type_id[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                <label class="control-label">Tipo de cambio
                                    <el-tooltip class="item" effect="dark" content="Tipo de cambio del día, extraído de SUNAT" placement="top-end">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-input v-model="form.exchange_rate_sale"></el-input>
                                <small class="form-control-feedback" v-if="errors.exchange_rate_sale" v-text="errors.exchange_rate_sale[0]"></small>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.exchange_rate_sale}">
                                <label>Tipo Base Imp.
                                    <el-tooltip class="item" effect="dark" content="Si sus compra tienen IGV y venderá CON  IGV, le corresponde la opción por defecto, si tiene compras que serán en parte destinadas como NO GRABADA Y GRABADA, la opción sería PRORRATA, si su compra está destinado a la venta como exonerado, la opción que le corresponde es COSTO O GASTO, ejemplo zona selva." placement="top-end">
                                        <i class="fa fa-info-circle"></i>
                                    </el-tooltip>
                                </label>
                                <el-select v-model="form.type_basimp">
                                    <el-option key="01" value="01" label="CRÉDITO FISCAL" title="Base imponible de las adquisiciones gravadas que dan derecho a crédito fiscal y/o saldo a favor por exportación, destinadas exclusivamente a operaciones gravadas y/o de exportación."></el-option>
                                    <el-option key="02" value="02" label="PRORRATA" title="Base imponible de las adquisiciones gravadas que dan derecho a crédito fiscal y/o saldo a favor por exportación, destinadas a operaciones gravadas y/o de exportación y a operaciones no gravadas."></el-option>
                                    <el-option key="03" value="03" label="COSTO O GASTO" title="Base imponible de las adquisiciones gravadas que no dan derecho a crédito fiscal y/o saldo a favor por exportación, por no estar destinadas a operaciones gravadas y/o de exportación."></el-option>
                                </el-select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group" :class="{'has-danger': errors.title}">
                                <label class="control-label">Título</label>
                                <el-input v-model="form.title"></el-input>
                                <small class="form-control-feedback" v-if="errors.title" v-text="errors.title[0]"></small>
                            </div>
                        </div>

                        <div class="col-lg-4 mt-4">
                            <template v-if="form.document_type_id === '08'">
                                <div class="form-group" :class="{'has-danger': errors['note.note_debit_type_id']}">
                                    <label class="control-label">Tipo nota de débito</label>
                                    <el-select v-model="form.note.note_debit_type_id">
                                        <el-option v-for="option in note_debit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors['note.note_debit_type_id']" v-text="errors['note.note_debit_type_id'][0]"></small>
                                </div>
                            </template>
                            <template v-if="form.document_type_id === '07'">
                                <div class="form-group" :class="{'has-danger': errors['note.note_credit_type_id']}">
                                    <label class="control-label">Tipo nota de crédito</label>
                                    <el-select v-model="form.note.note_credit_type_id">
                                        <el-option v-for="option in note_credit_types" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                    </el-select>
                                    <small class="form-control-feedback" v-if="errors['note.note_credit_type_id']" v-text="errors['note.note_credit_type_id'][0]"></small>
                                </div>
                            </template>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <div v-if="form.document_type_id === '08' || form.document_type_id === '07'" class="row">
                                <div class="col-lg-4">
                                    <div class="form-group" :class="{'has-danger': errors.series}">
                                        <label class="control-label">Serie Relacionado<span class="text-danger">*</span></label>
                                        <el-input v-model="form.note.series" :maxlength="4"   @input="inputSeries"></el-input>

                                        <small class="form-control-feedback" v-if="errors.series" v-text="errors.series[0]"></small>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group" :class="{'has-danger': errors.number}">
                                        <label class="control-label">Número Relacionado<span class="text-danger">*</span></label>
                                        <el-input v-model="form.note.number"></el-input>

                                        <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                                    </div>
                                </div>
                                <div class="col-lg-4 mt-4">
                                    <div class="form-group" :class="{'has-danger': errors.number}">
                                        <el-button @click="getHasDocument">Validar</el-button>
                                        <small class="form-control-feedback" v-if="errors.number" v-text="errors.number[0]"></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4" v-if="form.document_type_id === '08' || form.document_type_id === '07'">
                            <div class="form-group" :class="{'has-danger': errors['note.note_description']}">
                                <label class="control-label">Descripción</label>
                                <el-input v-model="form.note.note_description"></el-input>
                                <small class="form-control-feedback" v-if="errors['note.note_description']" v-text="errors['note.note_description'][0]"></small>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group" >
                                <el-checkbox v-model="form.has_client" @change="changeHasClient">¿Desea agregar el cliente para esta compra?</el-checkbox>
                            </div>
                        </div>

                        <div class="col-md-8 mt-2 mb-2">
                            <div class="form-group" >
                                <el-checkbox v-model="form.has_payment" @change="changeHasPayment">¿Desea agregar pagos a esta compra?</el-checkbox>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6" v-if="form.has_client">
                            <div class="form-group">
                                <label class="control-label">
                                    Clientes
                                </label>

                                <el-select v-model="form.customer_id" filterable remote  popper-class="el-select-customers"  clearable
                                    placeholder="Nombre o número de documento"
                                    :remote-method="searchRemotePersons"
                                    :loading="loading_search">
                                    <el-option v-for="option in customers" :key="option.id" :value="option.id" :label="option.description"></el-option>
                                </el-select>

                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <template v-if="form.has_payment">

                            <div class="col-lg-2 col-md-2">
                                <div :class="{'has-danger': errors.payment_condition_id}"
                                    class="form-group">
                                    <label class="control-label">Condición de pago</label>
                                    <el-select v-model="form.payment_condition_id"
                                            @change="changePaymentCondition">
                                        <el-option v-for="option in payment_conditions"
                                                :key="option.id"
                                                :label="option.name"
                                                :value="option.id"></el-option>
                                    </el-select>
                                    <small v-if="errors.payment_condition_id"
                                        class="form-control-feedback"
                                        v-text="errors.payment_condition_id[0]"></small>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-12 mt-2">
                                <!-- Contado -->
                                <template v-if="form.payment_condition_id === '01'">
                                    <table>
                                        <thead>
                                        <tr width="100%">
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Forma de pago
                                            </th>
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Desde
                                                <el-tooltip class="item"
                                                            content="Aperture caja o cuentas bancarias"
                                                            effect="dark"
                                                            placement="top-start">
                                                    <i class="fa fa-info-circle"></i>
                                                </el-tooltip>
                                            </th>
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Referencia
                                            </th>
                                            <th v-if="form.payments.length>0"
                                                class="pb-2">Monto
                                            </th>
                                            <th width="15%"><a class="text-center font-weight-bold text-info"
                                                            href="#"
                                                            @click.prevent="clickAddPayment">[+ Agregar]</a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.payments"
                                            :key="index">
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_method_type_id"
                                                            @change="changePaymentMethodType(index)">
                                                        <el-option v-for="option in cashPaymentMethod"
                                                                :key="option.id"
                                                                :label="option.description"
                                                                :value="option.id"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-select v-model="row.payment_destination_id"
                                                            filterable>
                                                        <el-option v-for="option in payment_destinations"
                                                                :key="option.id"
                                                                :label="option.description"
                                                                :value="option.id"></el-option>
                                                    </el-select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-input v-model="row.reference"></el-input>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group mb-2 mr-2">
                                                    <el-input v-model="row.payment"></el-input>
                                                </div>
                                            </td>
                                            <td class="series-table-actions text-center">
                                                <button class="btn waves-effect waves-light btn-xs btn-danger"
                                                        type="button"
                                                        @click.prevent="clickCancel(index)">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <br>
                                        </tr>
                                        </tbody>
                                    </table>
                                </template>

                                <!-- Credito -->
                                <template v-else-if="form.payment_condition_id === '02'">
                                    <table v-if="form.fee.length>0">
                                        <thead>
                                        <tr width="100%">
                                            <th class="pb-2" v-if="form.fee.length>0"
                                                >Método de pago
                                            </th>
                                            <th class="pb-2"
                                                >Fecha
                                            </th>
                                            <th class="pb-2"
                                                >Monto
                                            </th>
                                            <th class="pb-2" ></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(row, index) in form.fee"
                                            :key="index">
                                            <td>
                                                <el-select
                                                    v-model="row.payment_method_type_id"
                                                    @change="changePaymentMethodType(index)">
                                                    <el-option
                                                        v-for="option in creditPaymentMethod"
                                                        :key="option.id"
                                                        :label="option.description"
                                                        :value="option.id"
                                                    ></el-option>
                                                </el-select>
                                            </td>
                                            <td>
                                                <el-date-picker
                                                    v-model="row.date"
                                                    :clearable="false"
                                                    format="dd/MM/yyyy"
                                                    type="date"
                                                    :readonly="readonly_date_of_due"
                                                    value-format="yyyy-MM-dd"></el-date-picker>
                                            </td>
                                            <td>
                                                <el-input v-model="row.amount"></el-input>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </template>

                                <!-- Crédito con cuotas -->
                                <template v-else>
                                    <table v-if="form.fee.length > 0">
                                        <thead>
                                            <tr width="100%">
                                                <th class="pb-2" >Fecha
                                                </th>
                                                <th class="pb-2" >Monto
                                                </th>
                                                <th class="pb-2" ></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, index) in form.fee"
                                                :key="index">
                                                <td>
                                                    <el-date-picker v-model="row.date"
                                                                    :clearable="false"
                                                                    format="dd/MM/yyyy"
                                                                    type="date"
                                                                    value-format="yyyy-MM-dd"></el-date-picker>
                                                </td>
                                                <td>
                                                    <el-input v-model="row.amount"></el-input>
                                                </td>
                                                <td class="text-center">
                                                    <button v-if="index > 0"
                                                            class="btn waves-effect waves-light btn-xs btn-danger"
                                                            type="button"
                                                            @click.prevent="clickRemoveFee(index)">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5">
                                                    <label class="control-label">
                                                        <a class=""
                                                            href="#"
                                                            @click.prevent="clickAddFee"><i class="fa fa-plus font-weight-bold text-info"></i>
                                                            <span style="color: #777777">Agregar cuota</span></a>
                                                    </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </template>
                            </div>
                        </template>
                    </div>

                    <div class="row">

                        <div class="col-lg-12 col-md-6 d-flex align-items-end mt-4">
                            <div class="form-group">
                                <button type="button" class="btn waves-effect waves-light btn-primary" @click.prevent="clickAddItem">+ Agregar Producto</button>
                                <button v-show="form.has_import" class="btn waves-effect waves-light btn-primary"
                                        type="button"
                                        @click.prevent="showDialogImportCosts = true">+ Agregar Costos de Importación
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row" v-if="form.items.length > 0">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Descripción</th>
                                        <th>Almacén</th>
                                        <th>Lote</th>
                                        <th class="text-center">Unidad</th>
                                        <th class="text-right">Cantidad</th>
                                        <th class="text-right">Valor Unitario</th>
                                        <th class="text-right">Precio Unitario</th>
                                        <th class="text-right">Descuento</th>
                                        <th class="text-right">Cargo</th>
                                        <th class="text-right">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="(row, index) in form.items" :key="index">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ row.item.description }}<br/><small>{{ row.affectation_igv_type.description }}</small></td>
                                        <td class="text-left">{{ row.warehouse.description }}</td>
                                        <td class="text-left">{{ row.lot_code }}</td>
                                        <td class="text-center">{{ row.item.unit_type_id }}</td>
                                        <td class="text-right">{{ row.quantity }}</td>
                                        <!-- <td class="text-right">{{ currency_type.symbol }} {{ row.unit_price }}</td> -->
                                        <td class="text-right">{{currency_type.symbol}} {{getFormatUnitPriceRow(row.unit_value)}}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ getFormatUnitPriceRow(row.unit_price) }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_discount }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total_charge }}</td>
                                        <td class="text-right">{{ currency_type.symbol }} {{ row.total }}</td>
                                        <td class="text-right">
                                            <button class="btn waves-effect waves-light btn-xs btn-primary"
                                                    type="button"
                                                    @click.prevent="clickEditItem(index)"><i class="fa fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn waves-effect waves-light btn-xs btn-danger"
                                                    @click.prevent="clickRemoveItem(index)"><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-right" v-if="form.total_exportation > 0">OP.EXPORTACIÓN: {{ currency_type.symbol }} {{ form.total_exportation }}</p>
                            <p class="text-right" v-if="form.total_free > 0">OP.GRATUITAS: {{ currency_type.symbol }} {{ form.total_free }}</p>
                            <p class="text-right" v-if="form.total_unaffected > 0">OP.INAFECTAS: {{ currency_type.symbol }} {{ form.total_unaffected }}</p>
                            <p class="text-right" v-if="form.total_exonerated > 0">OP.EXONERADAS: {{ currency_type.symbol }} {{ form.total_exonerated }}</p>
                            <p class="text-right" v-if="form.total_taxed > 0">OP.GRAVADA: {{ currency_type.symbol }} {{ form.total_taxed }}</p>
                            <p class="text-right" v-if="form.total_igv > 0">IGV: {{ currency_type.symbol }} {{ form.total_igv }}</p>
                            <h4 class="text-right" v-if="form.total > 0"><b>TOTAL COMPRAS: </b>{{ currency_type.symbol }} {{ form.total }}</h4>
                            <template v-if="form.has_import">
								<h4 class="text-right m-0"><b>TOTAL FLETE: </b>{{ currency_type.symbol }} {{ form.total_lading }}</h4>
								<h4 class="text-right m-0"><b>TOTAL SEGURO: </b>{{ currency_type.symbol }} {{ form.total_insurace }}</h4>
								<h4 class="text-right m-0"><b>TOTAL COSTO: </b>{{ currency_type.symbol }} {{ form.total_cost }}</h4>
								<br>
								<p class="text-right m-0">CIF Base Imponible: {{ currency_type.symbol }} {{ form.total_cost }}</p>
								<p class="text-right m-0">Ad valorem ({{form.fees_settlements.customs_law}}%): {{ currency_type.symbol }} {{ form.total_tax_customs_law }}</p>
								<p class="text-right m-0">ISC ({{form.fees_settlements.isc}}%): {{ currency_type.symbol }} {{ form.total_tax_isc }}</p>
								<p class="text-right m-0">IGV ({{form.fees_settlements.igv}}%): {{ currency_type.symbol }} {{ form.total_tax_igv }}</p>
								<p class="text-right m-0">IPM ({{form.fees_settlements.ipm}}%): {{ currency_type.symbol }} {{ form.total_tax_ipm }}</p>
								<p class="text-right m-0">Tasa de despacho aduanero ({{form.fees_settlements.dispatch_fee}}%): {{ currency_type.symbol }} {{ form.total_tax_customs_fee }}</p>
								<h4 class="text-right m-0"><b>Total Derechos Tributarios: </b>{{ currency_type.symbol }} {{ form.total_tax_rights }}</h4>
								<h4 class="text-right m-0"><b>Percepción ({{form.fees_settlements.perception}}%): </b>{{ currency_type.symbol }} {{ form.total_tax_perception }}</h4>
								<br>
								<p class="text-right m-0">Gastos de importación sin IGV: {{ currency_type.symbol }} {{ form.total_import_expenses_without_igv }}</p>
								<p class="text-right m-0">IGV Gastos de importación USD ({{form.fees_settlements.igv}}%): {{ currency_type.symbol }} {{ form.total_igv_expenses_usd }}</p>
								<p class="text-right m-0">IPM Gastos de importación USD ({{form.fees_settlements.ipm}}%): {{ currency_type.symbol }} {{ form.total_ipm_expenses_usd }}</p>
								<h4 class="text-right m-0"><b>Total Gastos de importacion: </b>{{ currency_type.symbol }} {{ form.total_import_expenses }}</h4>
                                <br>
                                <h4 class="text-right m-0"><b>COSTO TOTAL DE IMPORTACIÓN: </b>{{ currency_type.symbol }} {{ form.total_import_cost }}</h4>
                                <h4 class="text-right m-0"><b>TOTAL CREDITO FISCAL: </b>{{ currency_type.symbol }} {{ form.total_tax_credit }}</h4>
							</template>

                            <template v-if="is_perception_agent">
                                <hr>
                                <div class="row mt-1">
                                    <div class="col-lg-10 float-right">
                                        <label class="float-right control-label">NÚMERO PERCEPCIÓN: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div class="form-group" :class="{'has-danger': errors.perception_number}">
                                            <el-input v-model="form.perception_number"></el-input>

                                            <small class="form-control-feedback" v-if="errors.perception_number" v-text="errors.perception_number[0]"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-lg-10 float-right">
                                        <label class="float-right control-label">FEC EMISIÓN PERCEPCIÓN: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div class="form-group" :class="{'has-danger': errors.perception_date}">
                                            <el-date-picker v-model="form.perception_date" type="date" value-format="yyyy-MM-dd" :clearable="false" @change="changeDateOfIssue"></el-date-picker>
                                            <small class="form-control-feedback" v-if="errors.perception_date" v-text="errors.perception_date[0]"></small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-1">
                                    <div class="col-lg-10 float-right">
                                        <label class="float-right control-label">IMPORTE PERCEPCIÓN: </label>
                                    </div>
                                    <div class="col-lg-2 float-right">
                                        <div class="form-group" :class="{'has-danger': errors.total_perception}">
                                            <el-input v-model="form.total_perception" @input="inputTotalPerception" :readonly="true"></el-input>

                                            <small class="form-control-feedback" v-if="errors.total_perception" v-text="errors.total_perception[0]"></small>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-right" v-if="form.total > 0 && !hide_button"><b>MONTO TOTAL : </b>{{ currency_type.symbol }} {{ total_amount }}</h3>


                            </template>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right mt-4">
                    <el-button @click.prevent="close()">Cancelar</el-button>
                    <el-button type="primary" native-type="submit" :loading="loading_submit" v-if="form.items.length > 0 && !hide_button">Guardar cambios</el-button>
                </div>
            </form>
        </div>

        <purchase-form-item :showDialog.sync="showDialogAddItem"
                           :currency-type-id-active="form.currency_type_id"
                           :exchange-rate-sale="form.exchange_rate_sale"
                           :recordItem="recordItem"
                           @add="addRow"></purchase-form-item>

        <person-form :showDialog.sync="showDialogNewPerson"
                       type="suppliers"
                       :input_person="input_person"
                       :external="true"></person-form>

        <purchase-options :type="type" :showDialog.sync="showDialogOptions"
                          :recordId="purchaseNewId"
                          :showClose="false"></purchase-options>

        <import-costs-form
            :showDialog.sync="showDialogImportCosts"
            :recordData="form"
            @processImportCosts="processImportCosts"
			@showPurchaseNew="showPurchaseNew">
        </import-costs-form>

		<dialog-purchase-form-new :showDialog.sync="showDialogPurchaseNew" :recordData="recordData"></dialog-purchase-form-new>

    </div>
</template>

<script>

    import PurchaseFormItem from './partials/item.vue'
    import PersonForm from '../persons/form.vue'
    import PurchaseOptions from './partials/options.vue'
    import {functions, exchangeRate, fnPaymentsFee} from '../../../mixins/functions'
    import {calculateRowItem} from '../../../helpers/functions'
    import ImportCostsForm from './partials/import_costs.vue'
	import DialogPurchaseFormNew  from './partials/dialog_form.vue';
    import {deletable} from "../../../mixins/deletable";

    export default {
        props: {
            'resourceId': {
                required: true,
                default: 0
            },
            'isModal': {
                required: false,
                default: false
            }
        },
        components: {PurchaseFormItem, PersonForm, PurchaseOptions, ImportCostsForm, DialogPurchaseFormNew},
        mixins: [functions, exchangeRate, fnPaymentsFee, deletable],
        data() {
            return {
                input_person:{},
                type: 'edit',
                resource: 'purchases',
                showDialogAddItem: false,
                showDialogNewPerson: false,
                showDialogOptions: false,
                showDialogImportCosts: false,
				showDialogPurchaseNew: false,
                loading_submit: false,
                hide_button: false,
                is_perception_agent: false,
                errors: {},
                form: {},
                recordItem: null,
                aux_supplier_id:null,
                total_amount:0,
                document_types: [],
                currency_types: [],
                discount_types: [],
                charges_types: [],
                payment_method_types: [],
                all_suppliers: [],
                suppliers: [],
                all_customers: [],
                customers: [],
                company: null,
                operation_types: [],
                establishment: {},
                all_series: [],
                payment_destinations:  [],
                payment_conditions: [],
                series: [],
                loading_search: false,
                currency_type: {},
                readonly_date_of_due: false,
                configuration: {},
                purchaseNewId: null,
                note_credit_types: [],
                note_debit_types: [],
                affected_documents: [],
				recordData: null
            }
        },
        async created() {
            await this.initForm()
            await this.$http.get(`/${this.resource}/tables`)
                .then(response => {

                    this.document_types = response.data.document_types_invoice
                    this.currency_types = response.data.currency_types
                    this.establishment = response.data.establishment
                    this.all_suppliers = response.data.suppliers
                    this.discount_types = response.data.discount_types
                    this.payment_method_types = response.data.payment_method_types
                    this.payment_destinations = response.data.payment_destinations
                    this.all_customers = response.data.customers

                    this.note_credit_types = response.data.note_credit_types
                    this.note_debit_types = response.data.note_debit_types

                    this.charges_types = response.data.charges_types
                    this.configuration = response.data.configuration
                    this.payment_conditions = response.data.payment_conditions

                    this.charges_types = response.data.charges_types
                    this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                    this.form.establishment_id = (this.establishment.id) ? this.establishment.id:null
                    this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null

                    this.changeDateOfIssue()
                    this.changeDocumentType()
                    this.changeCurrencyType()

                    this.initRecord()
                })

            this.$eventHub.$on('reloadDataPersons', (supplier_id) => {
                this.reloadDataSuppliers(supplier_id)
           })

           this.$eventHub.$on('initInputPerson', () => {
                this.initInputPerson()
            })

            await this.filterCustomers()
            await this.changeHasPayment()
            await this.changeHasClient()
        },
        computed: {
            creditPaymentMethod: function () {
                return _.filter(this.payment_method_types, {'is_credit': true})
            },
            cashPaymentMethod: function () {
                return _.filter(this.payment_method_types, {'is_credit': false})
            },
            isCreditPaymentCondition: function () {
                return ['02', '03'].includes(this.form.payment_condition_id)
            },
        },
        methods: {

            changeHasPayment(){

                if(!this.form.has_payment){
                    this.form.payments = []
                    this.form.fee = []
                    this.form.payment_condition_id = '01'
                }else{
                    this.changePaymentCondition()
                }

            },
            changeHasClient(){

                if(!this.form.has_client){
                    this.form.customer_id = null
                }
            },
            searchRemotePersons(input) {

                if (input.length > 1) {

                    this.loading_search = true
                    let parameters = `input=${input}`

                    this.$http.get(`/reports/data-table/persons/customers?${parameters}`)
                            .then(response => {
                                this.customers = response.data.persons
                                this.loading_search = false

                                if(this.customers.length == 0){
                                    this.filterCustomers()
                                }
                            })
                } else {
                    this.filterCustomers()
                }

            },
            filterCustomers() {
                this.customers = this.all_customers
            },
            getFormatUnitPriceRow(unit_price){
                return _.round(unit_price, 6)
                // return unit_price.toFixed(6)
            },
            async validate_payments(){

                let error_by_item = 0
                let acum_total = 0
                let q_affectation_free = 0

                await this.form.payments.forEach((item)=>{
                    acum_total += parseFloat(item.payment)
                    if(item.payment <= 0 || item.payment == null) error_by_item++;
                })

                //determinate affectation igv
                await this.form.items.forEach((item)=>{
                    if(item.affectation_igv_type.free){
                        q_affectation_free++
                    }
                })

                let all_free = (q_affectation_free == this.form.items.length) ? true : false

                if(!all_free && (acum_total > parseFloat(this.form.total) || error_by_item > 0)) {
                    return  {
                        success : false,
                        message : 'Los montos ingresados superan al monto a pagar o son incorrectos'
                    }
                }

                if(this.form.has_client && !this.form.customer_id){
                    return  {
                        success : false,
                        message : 'Debe seleccionar un cliente'
                    }
                }

                if (this.form.has_payment) {

                    if(this.form.payment_condition_id === '01' && this.form.payments.length == 0){

                        return {
                            success: false,
                            message: 'Debe registrar al menos un pago'
                        }

                    }

                    if(this.isCreditPaymentCondition && this.form.fee.length == 0){

                        return {
                            success: false,
                            message: 'Debe registrar al menos una cuota'
                        }

                    }
                }

                return  {
                    success : true,
                    message : null
                }
            },
            clickCancel(index) {
                this.form.payments.splice(index, 1);
                this.calculatePayments()
            },
            clickAddPayment() {

                this.form.payments.push({
                    id: null,
                    purchase_id: null,
                    date_of_payment:  moment().format('YYYY-MM-DD'),
                    payment_method_type_id: '01',
                    reference: null,
                    payment_destination_id: this.getPaymentDestinationId(),
                    payment: 0,
                });

                this.calculatePayments()

            },
            setTotalDefaultPayment(){

                if(this.form.payments.length > 0){

                    this.form.payments[0].payment = this.form.total
                }
            },
            getPaymentDestinationId() {

                if(this.configuration.destination_sale && this.payment_destinations.length > 0) {

                    let cash = _.find(this.payment_destinations, {id : 'cash'})

                    return (cash) ? cash.id : this.payment_destinations[0].id

                }

                return null

            },
            initInputPerson(){
                this.input_person = {
                    number:'',
                    identity_document_type_id:''
                }
            },
            keyupEnterSupplier(){

                if(this.input_person.number){

                    if(!isNaN(parseInt(this.input_person.number))){

                        switch (this.input_person.number.length) {
                            case 8:
                                this.input_person.identity_document_type_id = '1'
                                this.showDialogNewPerson = true
                                break;

                            case 11:
                                this.input_person.identity_document_type_id = '6'
                                this.showDialogNewPerson = true
                                break;
                            default:
                                this.input_person.identity_document_type_id = '6'
                                this.showDialogNewPerson = true
                                break;
                        }
                    }
                }
            },
            keyupSupplier(e){

                if(e.key !== "Enter"){

                    this.input_person.number = this.$refs.select_person.$el.getElementsByTagName('input')[0].value
                    let exist_persons = this.suppliers.filter((supplier)=>{
                        let pos = supplier.description.search(this.input_person.number);
                        return (pos >- 1)
                    })

                    this.input_person.number = (exist_persons.length == 0) ? this.input_person.number : null
                }

            },
            inputSeries(){

                const pattern = new RegExp('^[A-Z0-9]+$', 'i');
                if(!pattern.test(this.form.series)){
                    this.form.series = this.form.series.substring(0, this.form.series.length - 1);
                } else {
                    this.form.series = this.form.series.toUpperCase()
                }

            },
            initRecord()
            {
                this.$http.get(`/${this.resource}/record/${this.resourceId}` )
                .then(response => {
                    let dato = response.data.data.purchase
                    this.form.id = dato.id
                    this.form.document_type_id = dato.document_type_id
                    this.form.series = dato.series
                    this.form.number = dato.number
                    this.form.date_of_due = dato.date_of_due
                    this.form.date_of_issue = dato.date_of_issue
                    this.form.supplier_id = dato.supplier_id
                    this.aux_supplier_id = dato.supplier_id
                    this.form.payment_method_type_id = dato.purchase_payments.payment_method_type_id
                    this.form.currency_type_id = dato.currency_type_id
                    this.form.exchange_rate_sale = dato.exchange_rate_sale
                    this.form.items = dato.items
                    this.form.payments = dato.purchase_payments
                    this.form.purchase_payments_id = dato.purchase_payments.id
                    this.form.purchase_order_id = dato.purchase_order_id
                    this.form.customer_id = dato.customer_id
                    this.form.title = dato.title;


                    if(this.form.customer_id){
                        this.searchRemotePersons(dato.customer_number)
                    }

                    // this.form.has_payment = (this.form.payments.length>0) ? true:false
                    this.form.has_client = (this.form.customer_id) ? true:false

                    this.form.payment_condition_id = dato.payment_condition_id
                    this.form.fee = dato.fee
                    this.form.has_payment = (this.form.fee.length>0 || this.form.payments.length>0) ? true:false

                    if(this.form.payment_condition_id == '02') this.readonly_date_of_due = true
                    this.form.type_basimp= dato.type_basimp
                    this.form.date_periodo = dato.date_periodo

                    if(this.form.document_type_id=='07' || this.document_type_id =='08'){
                        this.form.note.purchase_id= dato.note_purchase.purchase_id
                        this.form.note.note_credit_type_id =dato.note_purchase.note_credit_type_id
                        this.form.note.note_debit_type_id =dato.note_purchase.note_debit_type_id
                        this.form.note.note_description =dato.note_purchase.note_description
                        this.form.note.affected_purchase_id =dato.note_purchase.affected_purchase_id

                        this.getPurchaseRef()
                    }

                    this.form.has_import = dato.has_import
                    this.form.fixed_asset = dato.fixed_asset
                    this.form.lading = dato.lading
                    this.form.insurace = dato.insurace
                    this.form.import_costs = dato.import_costs
                    this.form.fees_settlements = dato.fees_settlements


                    this.changeDocumentType()
					this.changeCurrencyType()
                    // this.changePaymentMethodType()
                    this.calculateTotal()

                   // this.calculateTotal()
                })
            },
            getPayments(payments){

                payments.forEach(it => {
                    it.payment_destination_id = it.global_payment.destination_type ==  "App\Models\Tenant\Cash" ? 'cash':it.global_payment.destination_id
                });

                return payments
            },
            changePaymentMethodType(index) {

                let id = '01'

                if (this.form.payments.length > 0) {
                    id = this.form.payments[index].payment_method_type_id
                } else if (this.form.fee.length > 0) {
                    id = this.form.fee[index].payment_method_type_id
                }

                let payment_method_type = _.find(this.payment_method_types, {'id': id})

                if (payment_method_type.number_days) {

                    this.form.date_of_due = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')
                    this.readonly_date_of_due = true

                    let date = moment(this.form.date_of_issue).add(payment_method_type.number_days, 'days').format('YYYY-MM-DD')

                    if (this.form.fee.length > 0) {
                        for (let index = 0; index < this.form.fee.length; index++) {
                            this.form.fee[index].date = date
                        }
                    }

                } else {

                    this.form.date_of_due = this.form.date_of_issue
                    this.readonly_date_of_due = false

                }

            },
            inputTotalPerception(){
                this.total_amount = parseFloat(this.form.total) + parseFloat(this.form.total_perception)
                if(isNaN(this.total_amount)){
                    this.hide_button = true
                }else{
                    this.hide_button = false

                }
            },
            changeSupplier(){
                this.calculatePerception()
            },
            filterSuppliers() {
                if(this.form.document_type_id === '01') {
                    this.suppliers = _.filter(this.all_suppliers, (item) => { return ['6', '0'].includes(item.identity_document_type_id)})
                    this.selectSupplier()

                } else {
                    this.suppliers = this.all_suppliers;
                    this.selectSupplier()
                }
            },
            selectSupplier(){

                let supplier = _.find(this.suppliers, {'id': this.aux_supplier_id})
                this.form.supplier_id = (supplier) ? supplier.id : null
                this.aux_supplier_id = null

            },
            initForm() {
                this.errors = {}
                this.form = {
                    purchase_payments_id: 0,
                    id: 0,
                    establishment_id: null,
                    document_type_id: null,
                    series: null,
                    number: null,
                    date_of_issue: moment().format('YYYY-MM-DD'),
                    time_of_issue: moment().format('HH:mm:ss'),
                    arrival_date: null,
                    supplier_id: null,
                    payment_method_type_id:'01',
                    currency_type_id: null,
                    purchase_order: null,
                    exchange_rate_sale: 0,
                    total_prepayment: 0,
                    total_charge: 0,
                    total_discount: 0,
                    total_exportation: 0,
                    total_free: 0,
                    total_taxed: 0,
                    total_unaffected: 0,
                    total_exonerated: 0,
                    total_igv: 0,
                    total_base_isc: 0,
                    total_isc: 0,
                    total_base_other_taxes: 0,
                    total_other_taxes: 0,
                    total_taxes: 0,
                    total_value: 0,
                    total: 0,
                    perception_date: null,
                    perception_number: null,
                    total_perception: 0,
                    date_of_due: moment().format('YYYY-MM-DD'),
                    items: [],
                    charges: [],
                    discounts: [],
                    attributes: [],
                    payments: [],
                    guides: [],
                    customer_id: null,
                    has_client: false,
                    has_payment: false,
                    has_import: false,
                    fixed_asset: false,
                    payment_condition_id: '01',
                    fee: [],
                    type_basimp: null,
                    date_periodo : moment().format('YYYY-MM-DD'),
                    title: '',
                    note: {
                        id:null,
                        series:null,
                        number:null,
                        purchase_id:null,
                        note_type: null,
                        note_credit_type_id :null,
                        note_debit_type_id : null,
                        note_description : null,
                        affected_purchase_id :null
                    },
                    lading: {
                        date: null,
                        provider_transport: null,
                        invoice: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        price: 0
                    },
                    insurace: {
                        date: null,
                        insurace_transport: null,
                        invoice: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        price: 0
                    },
                    import_costs: {
                        commission_pen: 0,
                        commission_usd: 0,
                        commission_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        stow_unstow_pen: 0,
                        stow_unstow_usd: 0,
                        stow_unstow_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        handling_pen: 0,
                        handling_usd: 0,
                        handling_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        transport_pen: 0,
                        transport_usd: 0,
                        transport_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        antidumping_pen: 0,
                        antidumping_usd: 0,
                        antidumping_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        storage_pen: 0,
                        storage_usd: 0,
                        storage_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        dispatch_service_pen: 0,
                        dispatch_service_usd: 0,
                        dispatch_service_serie: {
                            list: [],
                            attached: null,
                            temp_path: null
                        },
                        others: [],
                        total_usd: 0,
                        total_pen: 0
                    },
                    fees_settlements: {
                        customs_law: 0,
                        isc: 0,
                        igv: 0,
                        ipm: 0,
                        dispatch_fee: 0,
                        uit: 0,
                        perception: 0,
                        import_costs_total: 0,
                        tc: 0,
                        utility: 0
                    },
                    total_lading: 0,
                    total_insurace: 0,
                    total_cost: 0,
                    total_tax_customs_law: 0,
                    total_tax_isc: 0,
                    total_tax_igv: 0,
                    total_tax_ipm: 0,
                    total_tax_customs_fee: 0,
                    total_tax_rights: 0,
                    total_tax_perception: 0,
                    total_import_expenses_without_igv: 0,
                    total_igv_expenses_usd: 0,
                    total_ipm_expenses_usd: 0,
                    total_import_expenses: 0,
                    total_import_cost: 0,
                    total_tax_credit: 0

                }

                // this.clickAddPayment()
                this.initInputPerson()
                this.readonly_date_of_due = false
            },
            resetForm() {
                this.initForm()
                this.form.currency_type_id = (this.currency_types.length > 0)?this.currency_types[0].id:null
                this.form.establishment_id = this.establishment.id
                this.form.document_type_id = (this.document_types.length > 0)?this.document_types[0].id:null

                this.changeDateOfIssue()
                this.changeDocumentType()
                this.changeCurrencyType()
            },
            changePaymentCondition(){

                this.form.fee = []
                this.form.payments = []

                if (this.form.payment_condition_id === '01') {

                    this.clickAddPayment()
                    this.initDataPaymentCondition()

                }
                if (this.form.payment_condition_id === '02') {
                    this.clickAddFeeNew()
                    this.readonly_date_of_due = true
                }
                if (this.form.payment_condition_id === '03') {
                    this.clickAddFee()
                    this.initDataPaymentCondition()
                }

            },
            changeDateOfIssue() {
                this.form.date_of_due = this.form.date_of_issue
                this.searchExchangeRateByDate(this.form.date_of_issue).then(response => {
                    this.form.exchange_rate_sale = response
                })
            },
            changeDocumentType() {
                this.filterSuppliers()
            },
            addRow(row, index) {
				if(index != null) this.form.items[index] = row
				else this.form.items.push(row)
				this.calculateTotal()
			},
            clickAddItem() {
                this.recordItem = null
                this.showDialogAddItem = true
            },
            clickEditItem(index) {
				this.recordItem =  {data: {... this.form.items[index]}, index: index}
				this.showDialogAddItem = true
			},
            clickRemoveItem(index) {
                const itemId = this.form.items[index].id;
                this.destroy(`/purchases/destroy_purchase_item/${itemId}`).then(() =>
                    this.form.items.splice(index, 1)
                )

                this.calculateTotal()
            },
            changeCurrencyType() {
                this.currency_type = _.find(this.currency_types, {'id': this.form.currency_type_id})
                let items = []
                this.form.items.forEach((row) => {
                    let item_push = {...row, ...calculateRowItem(row, this.form.currency_type_id, this.form.exchange_rate_sale)}
                    items.push(item_push)
                });
                this.form.items = items
                this.calculateTotal()
            },
            calculateTotal() {
                let total_discount = 0
                let total_charge = 0
                let total_exportation = 0
                let total_taxed = 0
                let total_exonerated = 0
                let total_unaffected = 0
                let total_free = 0
                let total_igv = 0
                let total_value = 0
                let total = 0

                this.form.items.forEach((row) => {
                    total_discount += parseFloat(row.total_discount)
                    total_charge += parseFloat(row.total_charge)

                    if (row.affectation_igv_type_id === '10') {
                        total_taxed += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '20') {
                        total_exonerated += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '30') {
                        total_unaffected += parseFloat(row.total_value)
                    }
                    if (row.affectation_igv_type_id === '40') {
                        total_exportation += parseFloat(row.total_value)
                    }
                    if (['10', '20', '30', '40'].indexOf(row.affectation_igv_type_id) < 0) {
                        total_free += parseFloat(row.total_value)
                    }

                    total_value += parseFloat(row.total_value)
                    total_igv += parseFloat(row.total_igv)
                    total += parseFloat(row.total)
                });

                this.form.total_exportation = _.round(total_exportation, 2)
                this.form.total_taxed = _.round(total_taxed, 2)
                this.form.total_exonerated = _.round(total_exonerated, 2)
                this.form.total_unaffected = _.round(total_unaffected, 2)
                this.form.total_free = _.round(total_free, 2)
                this.form.total_igv = _.round(total_igv, 2)
                this.form.total_value = _.round(total_value, 2)
                this.form.total_taxes = _.round(total_igv, 2)
                this.form.total = _.round(total, 2)

                this.calculatePerception()
                // this.setTotalDefaultPayment()
                this.calculatePayments()
                this.calculateFee()
                this.calculateImport()
            },
            calculateImport() {

                let total_lading = parseFloat(this.form.lading.price)
                let total_insurace = parseFloat(this.form.insurace.price)
                let total_cost = parseFloat(this.form.total + total_lading + total_insurace)
                let total_tax_customs_law = parseFloat((this.form.fees_settlements.customs_law/100) * total_cost)
                let total_tax_isc = parseFloat((this.form.fees_settlements.isc_sol/100)*(total_cost+total_tax_customs_law))
                let total_tax_igv = parseFloat((this.form.fees_settlements.igv_sol/100)*(total_cost+total_tax_customs_law+total_tax_isc))
                let total_tax_ipm = parseFloat((this.form.fees_settlements.ipm_sol/100)*(total_cost+total_tax_customs_law+total_tax_isc))
                let total_tax_customs_fee = this.form.fees_settlements.tc != 0 ? parseFloat(((this.form.fees_settlements.dispatch_fee_sol/100)*this.form.fees_settlements.uit)/this.form.fees_settlements.tc) : 0
                let total_tax_rights = parseFloat(total_tax_customs_law+total_tax_isc+total_tax_igv+total_tax_ipm+total_tax_customs_fee)
                let total_tax_perception = parseFloat((total_tax_rights+total_cost)*(this.form.fees_settlements.perception_sol/100))
                let total_import_expenses_without_igv = parseFloat(this.form.import_costs.total_usd-(this.form.import_costs.total_usd*((this.form.fees_settlements.igv_sol+this.form.fees_settlements.ipm_sol)/100)))
                let total_igv_expenses_usd = parseFloat((this.form.import_costs.total_usd)*(this.form.fees_settlements.igv_sol/100))
                let total_ipm_expenses_usd = parseFloat((this.form.import_costs.total_usd)*(this.form.fees_settlements.ipm_sol/100))
                let total_import_expenses = parseFloat(total_import_expenses_without_igv+total_igv_expenses_usd+total_ipm_expenses_usd)
                let total_import_cost = parseFloat(total_cost+total_tax_rights+total_tax_perception+total_import_expenses)
                let total_tax_credit = parseFloat(total_tax_igv+total_tax_ipm+total_tax_perception+total_igv_expenses_usd+total_ipm_expenses_usd)


                this.form.total_lading = _.round(total_lading, 2)
                this.form.total_insurace = _.round(total_insurace, 2)
                this.form.total_cost = _.round(total_cost, 2)
                this.form.total_tax_customs_law = _.round(total_tax_customs_law, 2)
                this.form.total_tax_isc = _.round(total_tax_isc, 2)
                this.form.total_tax_igv = _.round(total_tax_igv, 2)
                this.form.total_tax_ipm = _.round(total_tax_ipm, 2)
                this.form.total_tax_customs_fee = _.round(total_tax_customs_fee, 2)
                this.form.total_tax_rights = _.round(total_tax_rights, 2)
                this.form.total_tax_perception = _.round(total_tax_perception, 2)
                this.form.total_import_expenses_without_igv = _.round(total_import_expenses_without_igv, 2)
                this.form.total_igv_expenses_usd = _.round(total_igv_expenses_usd, 2)
                this.form.total_ipm_expenses_usd = _.round(total_ipm_expenses_usd, 2)
                this.form.total_import_expenses = _.round(total_import_expenses, 2)
                this.form.total_import_cost = _.round(total_import_cost, 2)
                this.form.total_tax_credit = _.round(total_tax_credit, 2)
            },
            calculatePerception(){

                let supplier = _.find(this.all_suppliers,{'id':this.form.supplier_id})

                if(supplier){

                    if(supplier.perception_agent) {

                        let total_perception = 0
                        let quantity_item_perception = 0
                        let total_amount = 0
                        this.form.total_perception = 0

                        this.form.perception_date = moment().format('YYYY-MM-DD')

                        this.form.items.forEach((row) => {
                            quantity_item_perception += (row.item.has_perception) ? 1:0
                            total_perception += (row.item.has_perception) ? (parseFloat(row.unit_price) * parseFloat(row.quantity) * (parseFloat(row.item.percentage_perception)/100)) : 0
                        });

                        this.is_perception_agent = (quantity_item_perception > 0) ? true : false
                        this.form.total_perception = _.round(total_perception,2)
                        total_amount = this.form.total + parseFloat(this.form.total_perception)
                        this.total_amount = _.round(total_amount, 2)

                    }else{

                        this.is_perception_agent = false
                        this.form.perception_date = null
                        this.form.perception_number = null
                        this.form.total_perception = null

                    }

                }


            },
            validatePaymentDestination(){

                let error_by_item = 0

                this.form.payments.forEach((item)=>{
                    if(item.payment_destination_id == null) error_by_item++;
                })

                return  {
                    error_by_item : error_by_item,
                }

            },
            async submit() {


                let validate = await this.validate_payments()
                if(!validate.success) {
                    return this.$message.error(validate.message);
                }

                let validate_payment_destination = await this.validatePaymentDestination()

                if(validate_payment_destination.error_by_item > 0) {
                    return this.$message.error('El destino del pago es obligatorio');
                }

                this.loading_submit = true
                // await this.changePaymentMethodType(false)
                await this.$http.post(`/${this.resource}/update`, this.form)
                    .then(response => {

                        if (response.data.success) {
                            this.resetForm()
                            this.purchaseNewId = response.data.data.id
                            this.showDialogOptions = true

                            this.$emit('getInvoices');
                            this.$emit('closeDialog');
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            this.$message.error(error.response.data.message)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                if (this.isModal) {
                    this.$emit('closeDialog')
                } else {
                    location.href = '/purchases'
                }
            },
            reloadDataSuppliers(supplier_id) {

                this.$http.get(`/${this.resource}/table/suppliers`).then((response) => {

                    this.aux_supplier_id = supplier_id
                    this.all_suppliers = response.data
                    this.filterSuppliers()

                })
            },
            getHasDocument(){

                this.$http.get(`/${this.resource}/has-document/${this.form.note.series}/${this.form.note.number}`)
                    .then(response => {

                        if(response.data.success){

                            this.affected_documents = response.data.data;
                            this.form.note.affected_purchase_id=this.affected_documents.id;

                            if(this.form.document_type_id === '07'){
                                this.form.note.note_type='credit';
                            }
                            else if(this.form.document_type_id === '08'){
                                this.form.note.note_type='debit';
                            }

                            let message = `<strong>El comprobante ${ this.affected_documents.series }-${ this.affected_documents.number } fué encontrado</strong><br/>`

                            this.$notify({
                                title: "",
                                dangerouslyUseHTMLString: true,
                                message: message,
                                type: "warning",
                                duration: 6000
                            })
                        }

                    })

            },
            getPurchaseRef(){

                this.$http.get(`/${this.resource}/has-document-ref/${this.form.note.affected_purchase_id}`)
                    .then(response => {
                        if(response.data.success){
                            this.form.note.series=response.data.data.series;
                            this.form.note.number=response.data.data.number;
                        }

                    })
                if(this.form.document_type_id === '07'){
                    this.form.note.note_type='credit';
                }
                else if(this.form.document_type_id === '08'){
                    this.form.note.note_type='debit';
                }
            },
            processImportCosts(row) {
                this.form.lading = {... row.lading}
                this.form.insurace = {... row.insurace}
                this.form.import_costs = {... row.import_costs}
                this.form.fees_settlements = {... row.fees_settlements}
                this.calculateTotal()
            },
			showPurchaseNew(data){
				this.recordData = data
				this.showDialogPurchaseNew = true
			}
        }
    }
</script>
