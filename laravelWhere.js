// let laravelWhere = [
//     { type: 'where', col: 'name', op: '=', val: 'name' },
//     { type: 'where', col: 'age', op: '=', val: 16 },
//     {
//         type: 'where',
//         subWhereObj: [
//             { type: 'where', col: 'name', op: '=', val: 'name' },
//             { type: 'where', col: 'age', op: '=', val: 16 },
//             { type: 'orWhere', col: 'name', op: '=', val: 'name' },
//             {
//                 type: 'orWhere',
//                 subWhere: [
//                     { type: 'where', col: 'name', op: '=', val: 'name' },
//                     { type: 'orWhere', col: 'age', op: '=', val: 16 },
//                 ]
//             }
//         ]
//     },
// ]

// let query = `name='name'@age=16@(name='name'@age=16|name='name'|(name='name'@age=16))`;

// get query from args
// let query = process.argv[2];

// let LaravelWhere = compileWhere(query);
// let LaravelWhereJson = JSON.stringify(LaravelWhere);
// console.log(LaravelWhereJson);

//function to compile WHEREsql to url
function compileWhere(query) {

    //decode string
    let out = decodeStr(query, /'[^']*'/g, 'str', false);
    query = out.str;
    let strArr = out.arr;

    // decode conditions
    var out = decodeStr(query, /([a-z-A-Z0-9_]+)({REGEXP}|{LIKE}|{NOT LIKE}|<=|>=|!=|=|<|>)(str\[[0-9]+\]|[0-9]+\.[0-9]+|[0-9]+)/g, 'cond', true , {
        '{LIKE}':'LIKE',
        '{?OT LIKE}':'NOT LIKE',
        '{REGEXP}':'REGEXP'
    });
    query = out.str;
    let condArr = out.arr;

    // decode operations
    var out = decodeStr(query, /(@|\|)/g, 'op');
    query = out.str;
    let opArr = out.arr;

    // decode parenthesis
    var out = decodeStr(query, /\(((op|cond|prth)\[[0-9]+\])+\)/g, 'prth');
    query = out.str;
    let prthArr = out.arr;

    // check if str is valid
    if (query == '') {
        return laravelWhere;
    }

    regex = /(cond|op|prth)\[[0-9]+\]/g;
    testQuery = query.replace(regex, '');
    if (testQuery.length > 0) {
        console.log('Invalid WHERE query');
        return;
    }


    return laravelWhere(query);


    function laravelWhere(query) {

        let laravelWhereArr = [];

        // put everything together in one object
        regex = /^(cond|op|prth)\[[0-9]+\]/g;
        let i = 0;
        let type = 'where';

        while (query.match(regex)) {
            let match = query.match(regex)[0];

            query = query.replace(regex, '');

            if (match.includes('cond')) {

                //get index of condition
                let index = parseInt(match.replace('cond[', '').replace(']', ''));

                //check if the value is str
                if (condArr[index][3].includes('str')) {
                    let strVal = strArr[parseInt(condArr[index][3].replace('str[', '').replace(']', ''))];

                    //remove first and last quotes
                    strVal = strVal.substring(1, strVal.length - 1);

                    condArr[index][3] = strVal;
                }

                laravelWhereArr.push({
                    type: type,
                    col: condArr[index][1],
                    op: condArr[index][2],
                    val: condArr[index][3]
                })
            }
            else if (match.includes('op')) {
                type = opArr[parseInt(match.replace('op[', '').replace(']', ''))];
                if (type == '@') {
                    type = 'where';
                }
                else if (type == '|') {
                    type = 'orWhere';
                }
                else {
                    console.log('Invalid WHERE query');
                    return;
                }
            }
            else if (match.includes('prth')) {
                let index = parseInt(match.replace('prth[', '').replace(']', ''));

                //remove forst and last parenthesis
                let subQuery = prthArr[index].substring(1, prthArr[index].length - 1);

                let subWhere = laravelWhere(subQuery);

                laravelWhereArr.push({
                    type: type,
                    subWhere: subWhere
                })
            }
        }

        return laravelWhereArr;
    }

    function decodeStr(str, regex, prefix, usingGroups = false, replace = {}) {

        let arr = [];
        while (str.match(regex)) {
            let match = str.match(regex)[0];

            if (usingGroups) {
                let matchGroup = regex.exec(str);

                let parts = [];
                for (let i = 0; i < matchGroup.length; i++) {

                    if (replace[matchGroup[i]])
                        matchGroup[i] = replace[matchGroup[i]];

                    parts.push(matchGroup[i]);
                }
                arr.push(parts);
            }
            else {
                if (replace[match])
                        match = replace[match];

                arr.push(match);
            }
            str = str.replace(match, `${prefix}[${arr.length - 1}]`);
        }
        return { str, arr };
    }

}

// export the function
module.exports = function (query) {
    return compileWhere(query);
}




