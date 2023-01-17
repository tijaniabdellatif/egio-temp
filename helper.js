//define readlineSync
var readlineSync = require('readline-sync');
// define fs
var fs = require('fs');
// define path
var path = require('path');
// use dependencies to connect to mysql
var mysql = require('mysql');

function MySqlToLaravelMigration(sql_filepath) {

    let allowe_types = [
        'int', 'varchar',
        'text', 'float',
        'double', 'tinyint',
        'timestamp', 'multipoint',
        'point', 'multipolygon',
        'bigint', 'longtext',
        'json', 'date',
        'datetime', 'time',
        'year', 'decimal',
        'char', 'enum',
        'set', 'tinytext',
        'mediumtext', 'longtext',
        'tinyint', 'mediumint',
        'bigint', 'int',
        'float', 'double',
        'decimal', 'tinyint',
        'smallint', 'mediumint',
    ];
    let allowe_types_regex = allowe_types.join('|');

    let table_to_convert_types = [];

    // allowed options
    let allowed_options = [
        'unsigned', 'not null', 'null',
        'default', 'primary key',
        'auto_increment', 'unique',
        'index', 'foreign key',
        'constraint', 'collate',
        'on update', ''
    ];

    console.log("Importing the sql file " + sql_filepath + "...");
    // read sql_filepath
    var sql_file = fs.readFileSync(sql_filepath, 'utf8');

    console.log("Lowercasing the sql file...");
    // convert all to lower case
    sql_file = sql_file.toLowerCase();
    // replace all new lines & tabs with space
    sql_file = sql_file.replace(/\n|\t/g, " ");
    // replace all multiple spaces with single space
    sql_file = sql_file.replace(/\s+/g, " ");

    // separate tables using regex and get groups
    let regex = new RegExp(`create\\s+table[\\s\\n\\r]([a-zA-Z0-9_-]+)\\s+\\(([\\s\\n\\r\\w\\(\\)0-9,\\'\\"\\-_:]+)\\)\\;`, "g");
    var tables = [...sql_file.matchAll(regex)];


    let prepared_tables = [];

    // example of prepared_tables
    let pre = {
        name: "users",
        columns: [
            {
                name: "col",
                type: "type",
                options: [
                    {
                        value: "unsigned",
                        options: []
                    },
                    {
                        value: "auto_increment",
                    },
                    {
                        value: "primary",
                        options: []
                    },
                    {
                        value: "not_null",
                        options: []
                    },
                    {
                        value: "null",
                        options: []
                    },
                    {
                        value: "default",
                        options: [{
                            value: "value",
                        }]
                    },
                    {
                        value: "unique",
                        options: []
                    },
                    {
                        value: "index",
                        options: []
                    },
                    {
                        value: "foreign",
                        options: [{
                            value: "references",
                            options: [{
                                value: "table",
                                options: []
                            }, {
                                value: "column",
                                options: []
                            }]
                        }]
                    },
                    {
                        value: "on update",
                        options: [{
                            value: "value",
                        }]
                    },
                    {
                        value: "collate",
                        options: [{
                            value: "value",
                        }]
                    }
                ]
            }
        ]
    }

    // loop through tables
    tables.forEach((table) => {

        //
        let prepared_table = {};

        // table name
        prepared_table.name = table[1];

        // table body
        let table_body = table[2];

        console.log()

        // get columns
        let regex = new RegExp(`([a-zA-Z0-9\\-_]+)\\s+(${allowe_types_regex})(\\(([0-9]+)\\))?\\s+([a-zA-Z0-9_\\s\\-\\'\\"]+)\\s*(,|(\\s*$))\\s*`, "g")
        let table_columns = [...table_body.matchAll(regex)];

        prepared_table.columns = [];

        table_columns.forEach((column) => {

            let column_name = column[1];
            let column_type = column[2];
            let column_length = column[4];
            let column_options = column[5];

            let prepared_column = {
                name: column_name,
                type: column_type,
                length: column_length,
                options: [],
                options_as_string: column_options
            };

            prepared_table.columns.push(prepared_column);

        });

        prepared_tables.push(prepared_table);

    });

    console.log(...prepared_tables);

    // get all types
    // let types = prepared_tables.map((table) => {
    //     return table.columns.map((column) => {
    //         return column.type;
    //     });
    // });

    // // convert array to be one dimensions
    // types = types.reduce((a, b) => a.concat(b), []);

    // // remove duplicates
    // types = [...new Set(types)];

    // console.log(types);

}

function MySqlToLaravelMigration2(sql_filepath) {

    console.log("Importing the sql file " + sql_filepath + "...");
    // read sql_filepath
    var sql_file = fs.readFileSync(sql_filepath, 'utf8');

    console.log("Lowercasing the sql file...");
    // convert all to lower case
    sql_file = sql_file.toLowerCase();
    // replace all new lines & tabs with space
    sql_file = sql_file.replace(/\n|\t/g, " ");
    // replace all multiple spaces with single space
    sql_file = sql_file.replace(/\s+/g, " ");

    // separate tables using regex and get groups
    let regex = new RegExp(`create\\s+table[\\s\\n\\r]([a-zA-Z0-9_-]+)\\s+\\(([\\s\\n\\r\\w\\(\\)0-9,\\'\\"\\-_:]+)\\)\\;`, "g");
    var tables = [...sql_file.matchAll(regex)];


    let prepared_tables = [];

    // loop through tables
    tables.forEach((table) => {

        //
        let prepared_table = {};

        // table name
        prepared_table.name = table[1];

        if (table[1] == 'pro_user_info')
            console.log(table[2]);

        // table body
        let table_body = table[2];

        // get columns
        let regex = new RegExp(`([a-zA-Z0-9\\-_]+)\\s+([a-zA-Z0-9\\-_]+)(\\(([0-9]+)\\))?\\s+([a-zA-Z0-9_\\s\\-\\'\\"]+)\\s*(,|(\\s*$))\\s*`, "g");
        let table_columns = [...table_body.matchAll(regex)];

        prepared_table.columns = [];

        table_columns.forEach((column) => {

            prepared_table.columns.push(column[0]);

        });

        prepared_tables.push(prepared_table);

    });

    // console.log(tables.map((table)=>table[0]));

    return;

    // get template.txt file
    let template = fs.readFileSync("generated_migrations/template.txt", 'utf8');

    // loop through prepared tables
    prepared_tables.forEach((table, i) => {

        let year = new Date().getFullYear();
        let month = new Date().getMonth() + 1;
        let day = new Date().getDate();

        // convert i to string with 6 characters
        let i_string = i.toString();
        while (i_string.length < 6) {
            i_string = "0" + i_string;
        }

        let filename = path.join(__dirname, "generated_migrations/" + year + "_" + month + "_" + day + "_" + i_string + "_create_" + table.name + "_table.php");


        // replace #migration_class_name with the migration class name
        // convert table.name first letter to upper case and remove _ and make the next letter upper case
        let migration_class_name = table.name.replace(/_([a-z])/g, function (g) { return g[1].toUpperCase(); }).replace(/_/g, "").replace(/^./, function (str) { return str.toUpperCase(); });
        let migration_file = template.replace(/#migration_class_name/g, migration_class_name);


        // replace #table_name# with table.name
        migration_file = migration_file.replace(/#table_name#/g, table.name);

        // replace #table_columns#
        commented_cols = table.columns.map((column) => { return "//" + column }).join('\n\t\t\t');
        migration_file = migration_file.replace(/#table_columns#/g, commented_cols);

        // create or replace
        fs.writeFileSync(filename, migration_file);


    });

    console.log(...prepared_tables);

    // get all types
    // let types = prepared_tables.map((table) => {
    //     return table.columns.map((column) => {
    //         return column.type;
    //     });
    // });

    // // convert array to be one dimensions
    // types = types.reduce((a, b) => a.concat(b), []);

    // // remove duplicates
    // types = [...new Set(types)];

    // console.log(types);

}

//
function renamemigrations(){


    // read files directory path from command line
    let files_path = process.argv[2];


    // get all files in
    let files = fs.readdirSync(path.join(files_path));

    files.forEach((file) => {
        if (file.startsWith("2022_7_7_")) {
            let new_file = file.replace(/^2022_7_7_/, "2022_07_07_");
            fs.renameSync(path.join(files_path, file), path.join(files_path, new_file));
        }
    });


}

async function main() {

}


main();
