<template>
    <div v-if="typeUser == 'admin' || typeUser == 'client'">
        <header
        class="page-header"
        style="display: flex; justify-content: space-between; align-items: center"
        >
            <div>
                <h2>Dashboard</h2>
            </div>
        </header>
        
		

        <div class="card mt-4 mb-0">
            <div class="row">
                <div class="col-12">
                    <section class="card card-dashboard">
                        <div class="card-body pt-2 pb-0">
                            <div class="row border-bottom mb-2 no-gutters">
                                <small class="col-12 text-muted text-center">Filtrar datos históricos</small>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-3 form-group">
                                    <label class="control-label">Establecimiento</label>
                                    <el-select v-model="form.establishment_id" @change="loadAll">
                                        <el-option
                                        v-for="option in establishments"
                                        :key="option.id"
                                        :value="option.id"
                                        :label="option.name"
                                        ></el-option>
                                    </el-select>
                                </div>
                                <div class="col-6 col-md-3">
                                    <label class="control-label">Periodo</label>
                                    <el-select v-model="form.period" @change="changePeriod">
                                        <el-option key="all" value="all" label="Todos"></el-option>
                                        <el-option key="last_week" value="last_week" label="Última semana"></el-option>
                                        <el-option key="month" value="month" label="Por mes"></el-option>
                                        <el-option key="between_months" value="between_months" label="Entre meses"></el-option>
                                        <el-option key="date" value="date" label="Por fecha"></el-option>
                                        <el-option key="between_dates" value="between_dates" label="Entre fechas"></el-option>
                                    </el-select>
                                </div>
                                <template v-if="form.period === 'month' || form.period === 'between_months'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Mes de</label>
                                        <el-date-picker
                                            v-model="form.month_start"
                                            type="month"
                                            @change="changeDisabledMonths"
                                            value-format="yyyy-MM"
                                            format="MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'between_months'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Mes al</label>
                                        <el-date-picker
                                            v-model="form.month_end"
                                            type="month"
                                            :picker-options="pickerOptionsMonths"
                                            @change="loadAll"
                                            value-format="yyyy-MM"
                                            format="MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'date' || form.period === 'between_dates'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Fecha del</label>
                                        <el-date-picker
                                            v-model="form.date_start"
                                            type="date"
                                            @change="changeDisabledDates"
                                            value-format="yyyy-MM-dd"
                                            format="dd/MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                                <template v-if="form.period === 'between_dates'">
                                    <div class="col-6 col-md-3">
                                        <label class="control-label">Fecha al</label>
                                        <el-date-picker
                                            v-model="form.date_end"
                                            type="date"
                                            :picker-options="pickerOptionsDates"
                                            @change="loadAll"
                                            value-format="yyyy-MM-dd"
                                            format="dd/MM/yyyy"
                                            :clearable="false"
                                        ></el-date-picker>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <section class="card card-dashboard">
                                <div class="card-body" v-if="loaders.sale_note">
                                    <template >
                                        <loader-graph :rows="4" :columns="1" :radius="50"></loader-graph>
                                    </template>
                                </div>
                                <div class="card-body" v-show="!loaders.sale_note">
                                    <div class="widget-summary">
                                        <div class="widget-summary-col" v-if="sale_note">
                                            <div class="row no-gutters">
                                                <div class="col-md-12 m-b-10">
                                                    <label>Notas de venta</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <x-graph type="pie" :all-data="sale_note.graph"></x-graph>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" v-show="!loaders.sale_note">
                                    <table class="table mb-0 table-sm">
                                        <tbody>
                                            <tr class="text-info text-bold">
                                                <td>Total Cobrado</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ sale_note.totals.total_payment }}</td>
                                            </tr>
                                            <tr class="text-danger text-bold">
                                                <td>Pendiente de cobro</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ sale_note.totals.total_to_pay }}</td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="">Total</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ sale_note.totals.total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="col-xl-3" v-if="soapCompany != '03'">
                            <section class="card card-dashboard">
                                <div class="card-body" v-if="loaders.document">
                                    <template >
                                        <loader-graph :rows="4" :columns="1" :radius="50"></loader-graph>
                                    </template>
                                </div>
                                <div class="card-body" v-show="!loaders.document">
                                    <div class="widget-summary">
                                        <div class="widget-summary-col" v-if="document">
                                            <div class="row no-gutters">
                                                <div class="col-md-12 m-b-10">
                                                    <label>Comprobantes</label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <x-graph type="doughnut" :all-data="document.graph"></x-graph>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" v-show="!loaders.document">
                                    <table class="table mb-0 table-sm">
                                        <tbody>
                                            <tr class="text-info text-bold">
                                                <td>Total Cobrado</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ document.totals.total_payment }}</td>
                                            </tr>
                                            <tr class="text-danger text-bold">
                                                <td>Pendiente de cobro</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ document.totals.total_to_pay }}</td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="">Total</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ document.totals.total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>

                        <div class="col-xl-6 col-md-6">
                            <section class="card card-dashboard">
                                <div class="card-body" v-if="loaders.general">
                                    <template >
                                        <loader-graph :rows="2" :columns="3" :radius="100"></loader-graph>
                                    </template>
                                </div>
                                <div class="card-body" v-show="!loaders.general">
                                    <div class="widget-summary">
                                        <div class="widget-summary-col" v-if="general">
                                            <div class="summary">
                                                <div class="row no-gutters">
                                                    <div class="col-md-12 m-b-10">
                                                        <label>Totales</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <x-graph-line :all-data="general.graph"></x-graph-line>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0" v-show="!loaders.general">
                                    <table class="table mb-0 table-sm">
                                        <tbody>
                                            <tr class="text-info text-bold">
                                                <td>Total notas de venta</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ general.totals.total_sale_notes }}</td>
                                            </tr>
                                            <tr class="text-danger text-bold">
                                                <td>Total comprobantes</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ general.totals.total_documents }}</td>
                                            </tr>
                                            <tr class="text-bold">
                                                <td class="">Total</td>
                                                <td class="text-right font-weight-bold">S/&nbsp;{{ general.totals.total }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <row-report></row-report>
        
    </div>
</template>
<style>
.widget-summary .summary {
  min-height: inherit;
}
.custom-badge {
  font-size: 15px;
  font-weight: bold;
}
.card.card-dashboard .table td,
.card.card-dashboard .table th {
    font-size: 0.9rem;
    font-weight: 500;
}
</style>
<script>
import RowReport from "./partials/report.vue";
import queryString from "query-string";
import LoaderGraph from "../components/loaders/l-graph.vue";
export default {
  props: ["typeUser", "soapCompany"],
  components: { RowReport, LoaderGraph },
  data() {
    return {
      loading_search: false,
      records_base: [],
      selected_customer: null,
      customers: [],
      resource: "dashboard",
      establishments: [],
      balance: {
        totals: {},
        graph: {},
      },
      document: {
        totals: {},
        graph: {},
      },
      sale_note: {
        totals: {},
        graph: {},
      },
      general: {
        totals: {},
        graph: {},
      },
      purchase: {
        totals: {},
        graph: {},
      },
      utilities: {
        totals: {},
        graph: {},
      },
      form: {},
      pickerOptionsDates: {
        disabledDate: (time) => {
          time = moment(time).format("YYYY-MM-DD");
          return this.form.date_start > time;
        },
      },
      pickerOptionsMonths: {
        disabledDate: (time) => {
          time = moment(time).format("YYYY-MM");
          return this.form.month_start > time;
        },
      },
      records: [],
      items_by_sales: [],
      top_customers: [],
      recordId: null,
      showDialogDocumentPayments: false,
      showDialogSaleNotePayments: false,
      filter_item: false,
      all_items: [],
      items: [],
      company: {},
      loaders: {},
    };
  },
  async created() {
    this.initForm();
    this.initLoaders();
    let response = await this.$http.get(`/${this.resource}/filter`);
	this.establishments = response.data.establishments;
    this.form.establishment_id = 1;//this.establishments.length > 0 ? this.establishments[0].id : null;
    await this.loadAll();
    await this.filterItems();
  },

  methods: {
    changeFilterItem() {
      this.form.item_id = null;
      this.loadDataUtilities();
    },
    searchRemoteItems(input) {
      if (input.length > 1) {
        this.loading_search = true;
        let parameters = `input=${input}`;
        this.$http
          .get(`/reports/data-table/items/?${parameters}`)
          .then((response) => {
            this.items = response.data.items;
            this.loading_search = false;

            if (this.items.length == 0) {
              this.filterItems();
            }
          });
      } else {
        this.filterItems();
      }
    },
    filterItems() {
      this.items = this.all_items;
    },
    calculateTotalCurrency(currency_type_id, exchange_rate_sale, total) {
      if (currency_type_id == "USD") {
        return parseFloat(total) * exchange_rate_sale;
      } else {
        return parseFloat(total);
      }
    },
    clickDownloadDispatch(download) {
      window.open(download, "_blank");
    },
    clickDownload(type) {
      let query = queryString.stringify({
        ...this.form,
      });
      window.open(`/reports/no_paid/${type}/?${query}`, "_blank");
    },
    initForm() {
      this.form = {
        item_id: null,
        establishment_id: 1,
        enabled_expense: null,
        enabled_move_item: false,
        enabled_transaction_customer: false,
        period: "last_week",
        date_start: moment().subtract(7, 'days').format("YYYY-MM-DD"),
        date_end: moment().format("YYYY-MM-DD"),
        month_start: moment().format("YYYY-MM"),
        month_end: moment().format("YYYY-MM"),
        customer_id: null,
      };
    },
    changeDisabledDates() {
      if (this.form.date_end < this.form.date_start) {
        this.form.date_end = this.form.date_start;
      }
      this.loadAll();
    },
    changeDisabledMonths() {
      if (this.form.month_end < this.form.month_start) {
        this.form.month_end = this.form.month_start;
      }
      this.loadAll();
    },
    changePeriod() {
      if (this.form.period === "month") {
        this.form.month_start = moment().format("YYYY-MM");
        this.form.month_end = moment().format("YYYY-MM");
      }
      if (this.form.period === "between_months") {
        this.form.month_start = moment().startOf("year").format("YYYY-MM"); //'2019-01';
        this.form.month_end = moment().endOf("year").format("YYYY-MM");
      }
      if (this.form.period === "date") {
        this.form.date_start = moment().format("YYYY-MM-DD");
        this.form.date_end = moment().format("YYYY-MM-DD");
      }
      if (this.form.period === "between_dates") {
        this.form.date_start = moment().startOf("month").format("YYYY-MM-DD");
        this.form.date_end = moment().endOf("month").format("YYYY-MM-DD");
      }
      this.loadAll();
    },
    loadAll() {
      this.loadData();
      // this.loadUnpaid();
      this.loadDataAditional();
      this.loadDataUtilities();
      //this.loadCustomer();
      this.loadCompany();
      this.changeStock();
    },
    changeStock() {
      this.$eventHub.$emit("changeStock", this.form.establishment_id);
    },
    loadCompany() {
      this.$http.get(`/companies/record`).then((response) => {
        this.company = response.data.data;
      });
    },
    initLoaders() {
      this.loaders = {
        document: true,
        sale_note: true,
        general: true,
        balance: true,
        utility: true,
        purchase: true,
        items_by_sales: true,
        top_customers: true,
      };
    },
    showLoadersLoadData() {
      this.loaders.document = true;
      this.loaders.sale_note = true;
      this.loaders.general = true;
      this.loaders.balance = true;
    },
    hideLoadersLoadData() {
      this.loaders.document = false;
      this.loaders.sale_note = false;
      this.loaders.general = false;
      this.loaders.balance = false;
    },
    loadData() {
      this.showLoadersLoadData();

      this.$http.post(`/${this.resource}/data`, this.form).then((response) => {
        this.document = response.data.data.document;
        // this.documents_quantity = response.data.data.quantity;
        this.balance = response.data.data.balance;
        this.sale_note = response.data.data.sale_note;
        this.general = response.data.data.general;
        this.customers = response.data.data.customers;
        this.items = response.data.data.items;
        this.hideLoadersLoadData();
      });
    },
    loadDataAditional() {
      this.showLoadersLoadDataAditional();

      this.$http
        .post(`/${this.resource}/data_aditional`, this.form)
        .then((response) => {
          this.purchase = response.data.data.purchase;
          this.items_by_sales = response.data.data.items_by_sales;
          this.top_customers = response.data.data.top_customers;
          this.hideLoadersLoadDataAditional();
        });
    },
    loadDataUtilities() {
      this.loaders.utility = true;

      this.$http
        .post(`/${this.resource}/utilities`, this.form)
        .then((response) => {
          this.utilities = response.data.data.utilities;
          this.loaders.utility = false;
        });
    },
    showLoadersLoadDataAditional() {
      this.loaders.purchase = true;
      this.loaders.items_by_sales = true;
      this.loaders.top_customers = true;
    },
    hideLoadersLoadDataAditional() {
      this.loaders.purchase = false;
      this.loaders.items_by_sales = false;
      this.loaders.top_customers = false;
    },
  },
};
</script>