<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Table to CSV Generator</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th v-for="column in columns">
                                    <input type="text"
                                           class="form-control"
                                           :value="column.key"
                                           @input="updateColumnKey(column, $event)"
                                    />

                                    <div type="button"
                                         class="btn delete-column-button"
                                         @click="removeColumn(column.key)"
                                    >Delete column</div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(row, index) in data">
                                <td v-for="(dataColumn, columnName) in row">
                                    <input type="text" class="form-control" v-model="row[columnName]"/>
                                </td>
                                <td>
                                    <div class="delete-row-button"
                                         @click="removeRow(index)"
                                    ><i class="fas fa-trash"></i></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <button type="button"
                                class="btn btn-secondary"
                                @click="addColumn()"
                        >Add Column</button>

                        <button type="button"
                                class="btn btn-secondary"
                                @click="addRow()"
                        >Add Row</button>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary" type="button" @click="submit()">Export</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "CSVGenerator",

        data() {
            return {
                defaultColumnName: "New column",
                defaultRowValue: "-",
                data: [
                    {
                        "First Name": "John",
                        "Last Name": "Doe",
                        "Email Address": "john.doe@example.com"
                    },
                    {
                        "First Name": "John",
                        "Last Name": "Doe",
                        "Email Address": "john.doe@example.com"
                    },
                ],
                columns: [
                    {key: "First Name"},
                    {key: "Last Name"},
                    {key: "Email Address"},
                ]
            }
        },

        methods: {
            addRow() {
                let newRow = {};

                this.columns.forEach(
                    (row) => {
                        newRow[row.key] = this.defaultRowValue;
                    }
                );

                this.data = [...this.data, newRow];
            },

            removeRow(rowIndex) {
                if (this.data.length === 1) {
                    this.data = [];
                }

                let refreshedData = [];

                this.data.forEach(
                    (row, index) => {
                        if (index !== rowIndex) {
                            refreshedData.push(row);
                        }
                    }
                )

                this.data = refreshedData;
            },

            addColumn() {
                const newColumnExists = !!this.columns.find(column => column.key === this.defaultColumnName);

                if (!newColumnExists) {
                    this.columns.push({key: this.defaultColumnName});
                    this.data.forEach(
                        (row) => {
                            row[this.defaultColumnName] = this.defaultRowValue;
                        }
                    );
                }
            },

            removeColumn(columnName) {
                if (this.columns.length === 1) {
                    return;
                }

                let refreshedColumns = [];

                this.columns.forEach(
                    (column) => {
                        if (column.key !== columnName) {
                            refreshedColumns.push(column);
                        }
                    }
                );

                this.data.forEach(
                    (row) => {
                        delete row[columnName];
                    }
                );

                this.columns = refreshedColumns;
            },

            updateColumnKey(column, event) {
                let oldKey = column.key;

                const columnKeyExists = !!this.columns.find(
                    column => column.key.toLowerCase() === event.target.value.toLowerCase()
                );

                column.key = event.target.value;

                if (columnKeyExists) {
                    column.key = event.target.value.substring(0, event.target.value.length - 1);
                    return;
                }

                this.data.forEach(
                    (row) => {
                        if (row[oldKey]) {
                            row[column.key] = row[oldKey];
                            delete row[oldKey];
                        }
                    }
                )
            },

            async submit() {
                const requestData = {
                    data: this.data
                };

                try {
                    const { data } = await axios.patch("/api/v1/export/csv", requestData);
                    const success = data.success;
                    const downloadLink = data.downloadLink;

                    if (success && typeof downloadLink !== "undefined") {
                        window.location.href = downloadLink;
                    }
                } catch (error) {
                    console.log(error);
                    alert(error.message);
                }
            }
        },

        watch: {
        }
    }
</script>

<style scoped>
    table > thead input,
    table > thead input:focus,
    table > thead input:active {
        border: none;
        border-radius: 0;
        border-bottom: 2px solid rgb(255,255,255);
        background: none;
        outline: none !important;
    }
    .delete-row-button {
        color: rgb(230,70,80);
        font-size: 16px;
        cursor: pointer;
        margin-top: 7px;
        padding: 0;
        transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
        -webkit-transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
        -moz-transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
        -o-transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
    }
    .delete-row-button:hover {
        color: rgb(230,120,120);
    }
    .delete-column-button {
        padding: 5px;
        margin-bottom: 2px;
        margin-top: 10px;
        border: 2px solid rgb(230,70,80);
        font-size: 10px;
        color: rgb(230,70,80);
        width: 100%;
        transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
        -webkit-transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
        -moz-transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
        -o-transition:
            background-color 0.1s ease-in-out,
            color 0.1s ease-in-out;
    }
    .delete-column-button:hover {
        color: rgb(230,120,120);
    }
</style>
