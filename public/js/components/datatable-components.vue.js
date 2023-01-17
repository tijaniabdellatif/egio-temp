const DatatableComponent = {
    /*html*/
    template: `

    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns datatable"
        id="datatable">

        <!-- modal to edit the table -->
        <div class="modal fade" :id="'table-modal-'+id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit datatable :</h5>
                        <button type="button" id="btnClose" @click="closeBtn" class="close btn p-0 m-0 text-danger" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-times" data-dismiss="modal" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label ><small>columns :</small></label>
                                    <div class="columns-editor-container" >
                                        <div v-for="cl in datatable.columns" class="column-editor-item" >
                                            <input type="checkbox" class="me-2" name="checkbox" id="checkbox" @change="cl.hide=!$event.target.checked" :checked="!cl.hide" />
                                            <div class="column-editor-item-name">
                                                {{ cl.name }}
                                            </div>
                                            <button class="btn btn-sm ms-auto" type="button" @click="columnUp(cl)" >Up</button>
                                            <button class="btn btn-sm" type="button" @click="columnDown(cl)" >Down</button>
                                            <!-- checkbox show/hide -->
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="form-group" >
                                    <label><small>pagination :</small></label>
                                    <input type="checkbox" v-model="pagination.enabled" />
                                </div>
                                <div class="form-group" >
                                    <label><small>sélectionnable :</small></label>
                                    <input type="checkbox" v-model="selectable" />
                                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dataTable-top d-flex flex-column">

            <!--<div class="search-tags d-flex">
                <span @click="deleteSearchColumn(clmns)" class="span-search search-tag"
                    v-for="clmns in datatable.search.columns">
                    {{ clmns.name }}
                </span>
            </div>-->

            <!--<div class="dataTable-search d-flex  mb-4">
                <input v-modal="datatable.search.value" class="dataTable-input w-100"
                    placeholder="Search..." type="text" />
            </div>-->


            <!--<div class="filters">
                <label for="" class="me-2 font-weight-bold">filters :</label>
                <div v-for="(flt,key) in datatable.filters" @click.self="editFilter(key)"
                    class="filter">
                    {{ flt.column }} : <span
                        class="font-weight-bold">{{ flt.value }}</span>
                    <button @click="deleteFilter(key)" class="close-btn">
                        <i class="fa-solid fa-xmark" />
                    </button>
                </div>
                <button class="btn btn-sm btn-light" @click="addFilter()">+</button>
            </div>-->

            <div class="d-flex" >
                <button v-if="datatable.show_controls?.export_json"  class="btn-export-table-header btn ms-auto mb-2" @click="exportToJSON()" >
                    <span> <i class="fa-solid fa-database me-2"></i> JSON Export </span>
                </button>
                <button v-if="datatable.show_controls?.export_xlsx"  class="btn-export-table-header btn ms-2 mb-2" @click="exportToXLSX2()" >
                    <span> <i class="fa-solid fa-file-export me-2"></i> XLSX Export </span>
                </button>
                <button v-if="datatable.show_controls?.settings" class="btn-edit-table-header btn ms-2 mb-2" @click="editDatatableModal()">
                    <span><i class="fa-solid fa-gear me-2"></i> Settings</span>
                </button>
            </div>

        </div>

        <div class="dataTable-container table-responsive">
            <table class="table" :id="'table-'+datatableKey">
                <thead>
                    <tr>
                        <th v-if="datatable.selectable || datatable.selectable_multiple" >
                        </th>
                        <template v-for="cl in datatable.columns" >
                            <th v-if="!cl.hide" @click="sort(cl.field)" scope="col">
                                <div class="datatable-column noselect">
                                    {{ cl.name }}
                                    <template v-if="datatable.sort.column == cl.field">
                                        <i v-if="datatable.sort.direction == 'asc'"
                                            class="fa-solid ms-auto fa-arrow-down-short-wide"></i>
                                        <i v-if="datatable.sort.direction == 'desc'"
                                            class="fa-solid ms-auto fa-arrow-up-short-wide"></i>
                                    </template>
                                </div>
                            </th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-loader" :class="loading ? '' : 'd-none'">
                        <td colspan="99">
                            <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="empty-table" :class="datatable.rows.length==0 && !loading? '' : 'd-none'">
                        <td colspan="99">
                            <div class="message d-flex justify-content-center">
                            There is no data to display
                            </div>
                        </td>
                    </tr>
                    <tr v-for="(rw,rkey) in datatable.rows" :class="(datatable.selected==rw?'selected':'') + ' ' + (!loading ? '' : 'd-none')" >
                        <td v-if="datatable.selectable_multiple" >
                            <input type="checkbox" class="form-check-input" @change="selectRow(rw,$event)" :checked="datatable.selected?.includes(rw)">
                        </td>
                        <td v-if="datatable.selectable" >
                            <input type="checkbox" class="form-check-input" @change="selectRow(rw,$event)" :checked="rw == datatable.selected">
                        </td>
                        <template v-for="(cl,ckey) in datatable.columns" scope="col">
                            <td v-if="!cl.hide" >
                                <template v-if="rw[cl.field]!==null && !cl.customize" >
                                    <template v-if="cl.type == 'date'">
                                        {{ formatDate(rw[cl.field])  }}
                                    </template>
                                    <template v-else-if="cl.type == 'datetime'">
                                        {{ formatDateTime(rw[cl.field])  }}
                                    </template>
                                    <template v-else>
                                        {{ rw[cl.field] }}
                                    </template>
                                </template>
                                <template v-if="cl.customize" >
                                    <slot :name="cl.field" v-bind="{row:{ key:rkey,value:rw }, column:{ key:ckey,value:rw[cl.field] }}" ></slot>
                                </template>
                            </td>
                        </template>
                        <div class="loadingrow" v-if="isRowLoading(rkey)" >
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </tr>

                </tbody>
            </table>
        </div>
        <div class="dataTable-bottom">

            <nav v-if="datatable.pagination.enabled && datatable.show_controls?.pagination_buttons" aria-label="Page navigation example">
                <ul class="pagination">

                    <li class="page-item" v-for="(p,key) in pageButtonsToDisplay"
                        :class="{'active': p == datatable.pagination.page}">
                        <button class="page-link btn" @click="changePage(p)">{{ p }}</button>
                    </li>
                </ul>
            </nav>

        </div>

    </div>
    `,
    props: {
        datatable: {
            type: Object,
            // Object or array defaults must be returned from
            // a factory function. The function receives the raw
            // props received by the component as the argument.
            default(rawProps) {
                return {
                    key: "",
                    api: "",
                    loadingrows: [],
                    columns: [],
                    rows: [],
                    filters: [],
                    sort: {
                        column: "",
                        order: "",
                    },
                    pagination: {
                        enabled: true,
                        page: 1,
                        per_page: 30,
                        total: 0,
                        links: [],
                    },
                    selectable: false,
                    selectable_multiple: false,
                    selected: null,
                    show_controls: {
                        settings: false,
                        export_xlsx: false,
                        export_json: false,
                        search_input: false,
                        pagination_selection: false,
                        pagination_buttons: false,
                    },
                };
            },
        },
    },
    data() {
        return {
            id: null,
            loading: false,
        };
    },
    computed: {
        datatableSearch() {
            return this.datatable.search.value;
        },
        datatableFilters() {
            return this.datatable.filters;
        },
        datatableColumns() {
            return this.datatable.columns;
        },
        datatablePerPage() {
            return this.datatable.pagination.per_page;
        },
        datatableSort() {
            return this.datatable.sort;
        },
        pageButtonsToDisplay() {
            let total = Math.ceil(
                this.datatable.pagination.total /
                    this.datatable.pagination.per_page
            );
            let current = this.datatable.pagination.page;

            let pages = [];

            if (total <= 5) {
                for (let i = 1; i <= total; i++) {
                    pages.push(i);
                }
            } else {
                let start = current - 2;
                let end = current + 2;

                if (start < 1) {
                    start = 1;
                    end = 5;
                } else if (end > total) {
                    end = total;
                    start = total - 4;
                }

                for (let i = start; i <= end; i++) {
                    pages.push(i);
                }
            }

            return pages;
        },
        datatableKey() {
            // return this.datatable.api.replace(/[^a-zA-Z0-9_]/g, '_');
            return this.datatable.key;
        },
        datatableSelected() {
            return this.datatable.selected;
        },
    },
    watch: {
        datatableFilters: {
            handler(newVal, oldVal) {
                this.loadData();
            },
            deep: true,
        },
        datatablePerPage: {
            handler(newVal, oldVal) {
                this.loadData();
            },
            deep: true,
        },
        datatableSort: {
            handler(newVal, oldVal) {
                this.loadData();
            },
            deep: true,
        },
        datatableSelected: {
            handler(newVal, oldVal) {
                // add event listener to selected
                this.$emit("selectedchanged", newVal);
            },
            deep: true,
        },
        datatableColumns: {
            handler(newVal, oldVal) {
                // this.loadData();
                // save state of columns to local storage

                let datatable_col_settings = newVal;

                if (this.datatableKey)
                    localStorage.setItem(
                        "datatable_col_settings_" + this.datatableKey,
                        JSON.stringify(datatable_col_settings)
                    );
            },
            deep: true,
        },
    },
    mounted() {
        this.loadData();
        this.id = Math.random().toString(36).substr(2, 9);

        if (!this.datatableKey) {
            displayToast("datatable propriety key is not found", "#f00");
        } else {
            // get default_datatable_col_settings from local storage
            let default_datatable_col_settings = JSON.parse(
                localStorage.getItem(
                    "default_datatable_col_settings_" + this.datatableKey
                )
            );

            if (default_datatable_col_settings) {
                let same = true;
                for (
                    let i = 0;
                    i < default_datatable_col_settings.length;
                    i++
                ) {
                    let col = default_datatable_col_settings[i];
                    let col2 = this.datatable.columns[i];
                    if (col.name != col2.name || col.value != col2.value) {
                        same = false;
                        break;
                    }
                }

                if (same) {
                    // get data from local storage
                    let datatable_col_settings = localStorage.getItem(
                        "datatable_col_settings_" + this.datatableKey
                    );

                    if (datatable_col_settings)
                        this.datatable.columns = JSON.parse(
                            datatable_col_settings
                        );
                } else {
                    // set default columns configuration to local storage
                    default_datatable_col_settings = this.datatable.columns;

                    localStorage.setItem(
                        "default_datatable_col_settings_" + this.datatableKey,
                        JSON.stringify(default_datatable_col_settings)
                    );
                }
            } else {
                // set default columns configuration to local storage
                default_datatable_col_settings = this.datatable.columns;

                localStorage.setItem(
                    "default_datatable_col_settings_" + this.datatableKey,
                    JSON.stringify(default_datatable_col_settings)
                );
            }
        }
    },
    methods: {
        loadData() {
            if (this.loading) {
                let sto = setInterval(() => {
                    if (!this.loading) {
                        clearInterval(sto);
                        this.loadData();
                    }
                }, 100);

                return;
            }

            if (!this.datatable) return;

            let laravelWhere = this.datatable.filters;

            // convert laravelObject to json string
            let laravelWhereJson = JSON.stringify(laravelWhere);

            formData = new FormData();
            formData.append("where", laravelWhereJson);

            let url = this.datatable.api;

            let additionalUrl = "";
            if (this.datatable.pagination.enabled) {
                if (additionalUrl == "") additionalUrl = "?";
                else additionalUrl += "&";
                additionalUrl +=
                    "page=" +
                    this.datatable.pagination.page +
                    "&per_page=" +
                    this.datatable.pagination.per_page;
            }

            if (this.datatable.sort.order && this.datatable.sort.column) {
                if (additionalUrl == "") additionalUrl = "?";
                else additionalUrl += "&";
                additionalUrl +=
                    "sort=" +
                    this.datatable.sort.column +
                    "&order=" +
                    this.datatable.sort.order;
            }

            url += additionalUrl;

            let config = {
                method: "post",
                url: `${url}`,
                data: formData,
            };

            console.log(config);

            this.loading = true;
            this.datatable.rows = [];

            axios(config)
                .then((response) => {
                    this.loading = false;
                    console.log(response.data.data);
                    if (response.data.success) {
                        if (this.datatable.pagination.enabled) {
                            this.datatable.pagination.total =
                                response.data.data.total;
                            this.datatable.rows = response.data.data.data;
                        } else {
                            this.datatable.rows = response.data.data;
                        }

                        this.$emit("loaded", this.datatable.rows);
                    }
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        closeBtn() {
            $("#table-modal-" + this.id).modal("hide");
        },
        deleteSearchColumn(column) {
            this.datatable.search.columns =
                this.datatable.search.columns.filter((col) => col != column);
        },
        sort(column) {
            this.datatable.sort = {
                column: column,
                direction:
                    this.datatable.sort.column == column
                        ? this.datatable.sort.direction == "asc"
                            ? "desc"
                            : "asc"
                        : "asc",
            };

            this.datatable.rows.sort((a, b) => {
                if (this.datatable.sort.direction == "asc") {
                    return a[this.datatable.sort.column] >
                        b[this.datatable.sort.column]
                        ? 1
                        : -1;
                } else {
                    return a[this.datatable.sort.column] <
                        b[this.datatable.sort.column]
                        ? 1
                        : -1;
                }
            });
        },
        changePage(page) {
            this.datatable.pagination.page = page;
            this.loadData();
        },
        selectRow(row, val) {
            if (this.datatable.selectable_multiple) {
                // check if this.datatable.selected exists and its an array
                if (
                    !(
                        this.datatable.selected &&
                        Array.isArray(this.datatable.selected)
                    )
                ) {
                    this.datatable.selected = [];
                }

                // check if this.datatable.selected contains row
                if (this.datatable.selected.includes(row)) {
                    // remove row from this.datatable.selected
                    this.datatable.selected = this.datatable.selected.filter(
                        (sel) => sel != row
                    );
                } else {
                    // add row to this.datatable.selected
                    this.datatable.selected.push(row);
                }
            } else if (this.datatable.selectable) {
                if (this.datatable.selected != row) {
                    this.datatable.selected = row;
                } else {
                    this.datatable.selected = {};
                }
            }
        },
        isRowLoading(id) {
            return this.datatable.loadingrows?.find((row) => row == id) !==
                undefined
                ? true
                : false;
        },
        editDatatableModal() {
            // show #dataTable-modal modal using js
            $("#table-modal-" + this.id).modal("show");
        },
        columnUp(column) {
            // find index of column
            let index = this.datatable.columns.findIndex(
                (col) => col.name == column.name
            );
            // if index is not 0, move column up
            if (index > 0) {
                // move column
                let temp = this.datatable.columns[index];
                this.datatable.columns[index] =
                    this.datatable.columns[index - 1];
                this.datatable.columns[index - 1] = temp;
            }
        },
        columnDown(column) {
            // find index of column
            let index = this.datatable.columns.findIndex(
                (col) => col.name == column.name
            );
            // if index is not last, move column down
            if (index < this.datatable.columns.length - 1) {
                // move column
                let temp = this.datatable.columns[index];
                this.datatable.columns[index] =
                    this.datatable.columns[index + 1];
                this.datatable.columns[index + 1] = temp;
            }
        },
        exportToCSV() {
            // load data without pagination

            let colFields = this.datatable.columns.map((col) => col.field);

            // convert colFilds to object value => value
            let colFieldsObj = {};
            colFields.forEach((field) => {
                colFieldsObj[field] = field;
            });

            // export table-create_users_datatable to csv

            let rows = this.datatable.rows;
            // push colFieldsObj on top of rows
            rows.unshift(colFieldsObj);
            let csv = rows
                .map((row) => {
                    // row : {id: 8, firstname: '', lastname: '', username: 'ced', email: 'ced@email.com', …}
                    let row_csv = [];
                    colFields.forEach((field) => {
                        row_csv.push(row[field]);
                    });

                    return row_csv;
                })
                .map((row) => {
                    return row.join(",");
                })
                .join("\n");

            // download csv file
            let blob = new Blob([csv], { type: "text/csv" });
            let url = window.URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "export.csv";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },
        exportToCSV2() {
            let laravelWhere = this.datatable.filters;

            // convert laravelObject to json string
            let laravelWhereJson = JSON.stringify(laravelWhere);

            formData = new FormData();
            formData.append("where", laravelWhereJson);

            let url = this.datatable.api;
            let additionalUrl = "";

            if (this.datatable.sort.order && this.datatable.sort.column) {
                if (additionalUrl == "") additionalUrl = "?";
                else additionalUrl += "&";
                additionalUrl +=
                    "sort=" +
                    this.datatable.sort.column +
                    "&order=" +
                    this.datatable.sort.order;
            }

            url += additionalUrl;

            var config = {
                method: "post",
                url: `${url}`,
                data: formData,
            };

            axios(config)
                .then((response) => {
                    this.loading = false;
                    if (response.data.success) {
                        let rows = response.data.data;
                        let colFields = this.datatable.columns.map(
                            (col) => col.field
                        );
                        // convert colFilds to object value => value
                        let colFieldsObj = {};
                        colFields.forEach((field) => {
                            colFieldsObj[field] = field;
                        });
                        // push colFieldsObj on top of rows
                        rows.unshift(colFieldsObj);
                        let csv = rows
                            .map((row) => {
                                // row : {id: 8, firstname: '', lastname: '', username: 'ced', email: '
                                let row_csv = [];
                                colFields.forEach((field) => {
                                    row_csv.push(row[field]);
                                });
                                return row_csv;
                            })
                            .map((row) => {
                                return row.join(",");
                            })
                            .join("\n");

                        // download csv file
                        let blob = new Blob([csv], { type: "text/csv" });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement("a");
                        a.href = url;
                        a.download = "export.csv";
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        exportToXLS() {
            // load data without pagination

            let colFields = this.datatable.columns.map((col) => col.field);

            // convert colFilds to object value => value
            let colFieldsObj = {};
            colFields.forEach((field) => {
                colFieldsObj[field] = field;
            });

            // export table-create_users_datatable to csv

            let rows = this.datatable.rows;
            // push colFieldsObj on top of rows
            rows.unshift(colFieldsObj);

            // create table element
            let table = document.createElement("table");

            // create table header
            let thead = document.createElement("thead");

            // create table header row
            let theadRow = document.createElement("tr");

            // create table header cells
            let theadCells = colFields.map((field) => {
                let theadCell = document.createElement("th");
                theadCell.innerText = field;
                return theadCell;
            });

            // append table header cells to table header row
            theadRow.append(...theadCells);

            // append table header row to table header
            thead.append(theadRow);

            // append table header to table
            table.append(thead);

            // create table body
            let tbody = document.createElement("tbody");

            // create table body rows
            let tbodyRows = rows.map((row) => {
                // row : {id: 8, firstname: '', lastname: '', username: 'ced', email: 'email', …}
                let tbodyRow = document.createElement("tr");

                // create table body cells
                let tbodyCells = colFields.map((field) => {
                    let tbodyCell = document.createElement("td");
                    tbodyCell.innerText = row[field];
                    return tbodyCell;
                });

                // append table body cells to table body row
                tbodyRow.append(...tbodyCells);

                // append table body row to table body
                tbody.append(tbodyRow);

                return tbodyRow;
            });

            // append table body to table
            table.append(tbody);

            // download xls file
            let blob = new Blob([table.outerHTML], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            });
            let url = window.URL.createObjectURL(blob);
            let a = document.createElement("a");
            a.href = url;
            a.download = "export.xls";
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },
        exportToXLSX() {
            // load data without pagination

            let colFields = this.datatable.columns.map((col) => col.field);

            // convert colFilds to object value => value
            let colFieldsObj = {};
            colFields.forEach((field) => {
                colFieldsObj[field] = field;
            });

            // export table-create_users_datatable to csv

            let rows = this.datatable.rows;

            // create table element
            let table = document.createElement("table");

            // create table header
            let thead = document.createElement("thead");

            // create table header row
            let theadRow = document.createElement("tr");

            // create table header cells
            let theadCells = colFields.map((field) => {
                let theadCell = document.createElement("th");
                theadCell.innerText = field;
                return theadCell;
            });

            // append table header cells to table header row
            theadRow.append(...theadCells);

            // append table header row to table header
            thead.append(theadRow);

            // append table header to table
            table.append(thead);

            // create table body
            let tbody = document.createElement("tbody");

            // create table body rows
            let tbodyRows = rows.map((row) => {
                // row : {id: 8, firstname: '', lastname: '', username: 'ced', email: 'email', …}
                let tbodyRow = document.createElement("tr");

                // create table body cells
                let tbodyCells = colFields.map((field) => {
                    let tbodyCell = document.createElement("td");
                    tbodyCell.innerText = row[field];
                    return tbodyCell;
                });

                // append table body
                tbodyRow.append(...tbodyCells);

                // append table body row to table body
                tbody.append(tbodyRow);

                return tbodyRow;
            });

            // append table body to table
            table.append(tbody);

            // download xlsx file using  ExcellentExport

            // create a tag
            let a = document.createElement("a");

            // append table and a to document
            document.body.appendChild(table);
            document.body.appendChild(a);

            // exported + key + timestamp
            let filename =
                "export_" + this.datatable.key + "_" + new Date().getTime();

            // set on click event and pass table element to it
            a.onclick = (e) => {
                return ExcellentExport.convert(
                    {
                        anchor: a,
                        filename: filename,
                        format: "xlsx",
                    },
                    [
                        {
                            name: "asdadadasdsd",
                            from: {
                                table: table,
                            },
                            /* except columns */
                            removeColumns: [colFields.indexOf("action")],
                        },
                    ]
                );
            };

            // click the a
            a.click();

            // remove a and table from document
            document.body.removeChild(a);
            document.body.removeChild(table);
        },
        exportToXLSX2() {
            swal({
                text: "Combien de lignes voulez-vous exporter ?",
                content: "input",
                button: {
                    text: "Exporter",
                },
            }).then((limit) => {
                if (!limit) return;

                // load data without pagination
                let laravelWhere = this.datatable.filters;

                // convert laravelObject to json string
                let laravelWhereJson = JSON.stringify(laravelWhere);

                formData = new FormData();
                formData.append("where", laravelWhereJson);

                let url = this.datatable.api;
                let additionalUrl = "";

                if (this.datatable.sort.order && this.datatable.sort.column) {
                    if (additionalUrl == "") additionalUrl = "?";
                    else additionalUrl += "&";
                    additionalUrl +=
                        "sort=" +
                        this.datatable.sort.column +
                        "&order=" +
                        this.datatable.sort.order;
                }

                // limit
                if (additionalUrl == "") additionalUrl = "?";
                else additionalUrl += "&";
                additionalUrl += "limit=" + limit;

                url += additionalUrl;

                var config = {
                    method: "post",
                    url: `${url}`,
                    data: formData,
                };

                axios(config)
                    .then((response) => {
                        this.loading = false;
                        if (response.data.success) {
                            let colFields = this.datatable.columns.map(
                                (col) => col.field
                            );

                            // convert colFilds to object value => value
                            let colFieldsObj = {};
                            colFields.forEach((field) => {
                                colFieldsObj[field] = field;
                            });

                            // export table-create_users_datatable to csv

                            let rows = response.data.data;

                            // create table element
                            let table = document.createElement("table");

                            // create table header
                            let thead = document.createElement("thead");

                            // create table header row
                            let theadRow = document.createElement("tr");

                            // create table header cells
                            let theadCells = colFields.map((field) => {
                                let theadCell = document.createElement("th");
                                theadCell.innerText = field;
                                return theadCell;
                            });

                            // append table header cells to table header row
                            theadRow.append(...theadCells);

                            // append table header row to table header
                            thead.append(theadRow);

                            // append table header to table
                            table.append(thead);

                            // create table body
                            let tbody = document.createElement("tbody");

                            // create table body rows
                            let tbodyRows = rows.map((row) => {
                                // row : {id: 8, firstname: '', lastname: '', username: 'ced', email: 'email', …}
                                let tbodyRow = document.createElement("tr");

                                // create table body cells
                                let tbodyCells = colFields.map((field) => {
                                    let tbodyCell =
                                        document.createElement("td");
                                    tbodyCell.innerText = row[field];
                                    return tbodyCell;
                                });

                                // append table body
                                tbodyRow.append(...tbodyCells);

                                // append table body row to table body
                                tbody.append(tbodyRow);

                                return tbodyRow;
                            });

                            // append table body to table
                            table.append(tbody);

                            // download xlsx file using  ExcellentExport

                            // create a tag
                            let a = document.createElement("a");

                            // append table and a to document
                            document.body.appendChild(table);
                            document.body.appendChild(a);

                            // exported + key + timestamp
                            let filename =
                                "export_" +
                                this.datatable.key +
                                "_" +
                                new Date().getTime();

                            // set on click event and pass table element to it
                            a.onclick = (e) => {
                                return ExcellentExport.convert(
                                    {
                                        anchor: a,
                                        filename: filename,
                                        format: "xlsx",
                                    },
                                    [
                                        {
                                            name: "data",
                                            from: {
                                                table: table,
                                            },
                                            /* except columns */
                                            removeColumns: [
                                                colFields.indexOf("action"),
                                            ],
                                        },
                                    ]
                                );
                            };

                            // click the a
                            a.click();

                            // remove a and table from document
                            document.body.removeChild(a);
                            document.body.removeChild(table);
                        }
                    })
                    .catch((error) => {
                        this.loading = false;
                    });
            });
        },
        exportToJSON() {
            // load data without pagination
            let laravelWhere = this.datatable.filters;

            // convert laravelObject to json string
            let laravelWhereJson = JSON.stringify(laravelWhere);

            formData = new FormData();
            formData.append("where", laravelWhereJson);

            let url = this.datatable.api;
            let additionalUrl = "";

            if (this.datatable.sort.order && this.datatable.sort.column) {
                if (additionalUrl == "") additionalUrl = "?";
                else additionalUrl += "&";
                additionalUrl +=
                    "sort=" +
                    this.datatable.sort.column +
                    "&order=" +
                    this.datatable.sort.order;
            }

            url += additionalUrl;

            var config = {
                method: "post",
                url: `${url}`,
                data: formData,
            };

            axios(config)
                .then((response) => {
                    this.loading = false;
                    if (response.data.success) {
                        let rows = response.data.data;
                        let json = JSON.stringify(rows);
                        let blob = new Blob([json], {
                            type: "application/json",
                        });
                        let url = window.URL.createObjectURL(blob);
                        let a = document.createElement("a");
                        a.href = url;
                        a.download =
                            "export_" +
                            this.datatable.key +
                            "_" +
                            new Date().getTime() +
                            ".json";
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                    }
                })
                .catch((error) => {
                    this.loading = false;
                });
        },
        formatDate(value) {
            return moment(value).format("DD MMM. YYYY");
        },
        formatDateTime(value) {
            return moment(value).format("DD MMM. YYYY HH:mm");
        },
    },
};
