<?php
// execute : composer dump-autoload

use App\Models\cats;
use App\Models\cities;
use App\Models\neighborhoods;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

function rand_string($length)
 {

        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, $length);
}

function queryFilter($data, $query, $joins = [], $validation = [], $as_json = false){

    // check if data is an array
    $as_json = !is_array($data);

    if ($as_json) {
        // convert json to php array
        $data = json_decode($data, true);
    }

    if (empty($data)) {
        return $query;
    }

    // loop through joins
    foreach ($joins as $key => $value) {

        //check if foreign_key primary_key op are not set
        if (!isset($value['type']) | !isset($value['foreign_key']) | !isset($value['primary_key']) | !isset($value['op'])) {
            continue;
        }

        if ($value['type'] == 'leftJoin') {
            $query = $query->leftJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'innerJoin') {
            $query = $query->innerJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'rightJoin') {
            $query = $query->rightJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'crossJoin') {
            $query = $query->crossJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'join') {
            $query = $query->join($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        }
    }


    // loop through $data
    foreach ($data as $key => $value) {

        //check if the value has subWhere
        if (isset($value['subWhere']) && isset($value['type'])) {
            // if $value['type'] is where
            if ($value['type'] == 'where') {
                $query->where(function ($query) use ($value, $validation) {
                    queryFilter($value['subWhere'], $query, [], $validation, false);
                });
            }
            // if $value['type'] is orWhere
            if ($value['type'] == 'orWhere') {
                $query->orWhere(function ($query) use ($value, $validation) {
                    queryFilter($value['subWhere'], $query, [], $validation, false);
                });
            }
        } elseif (isset($value['col']) && isset($value['op']) && isset($value['val']) && isset($value['type'])) {
            //check if the col is allowed
            if (!isset($validation[$value['col']])) {
                continue;
            }

            $validationCol = $validation[$value['col']];

            // validate the type of the value
            if (isset($validationCol['type'])) {
                if ($validationCol['type'] == 'int') {
                    if (!is_numeric($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'string') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'date') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'datetime') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'time') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'timestamp') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'boolean') {
                    if (!is_bool($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'float') {
                    if (!is_float($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'double') {
                    if (!is_double($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'decimal') {
                    if (!is_double($value['val'])) {
                        continue;
                    }
                }
            }

            // check if the operator is allowed
            if (isset($validationCol['operators'])) {
                if (!in_array($value['op'], $validationCol['operators'])) {
                    continue;
                }
            }

            // if $value['type'] is where
            if ($value['type'] == 'where') {
                $query->where($value['col'], $value['op'], $value['val']);
            }
            // if $value['type'] is orWhere
            if ($value['type'] == 'orWhere') {
                $query->orWhere($value['col'], $value['op'], $value['val']);
            }
        }
    }

    return $query;
}

function queryFilter2($req, $query, $joins = [], $validation = []){

    $data = $req->where;
    // check if data is an array
    $as_json = !is_array($data);

    if ($as_json) {
        // convert json to php array
        $data = json_decode($data, true);
    }

    if (empty($data)) {
        return $query;
    }

    // loop through joins
    foreach ($joins as $key => $value) {

        //check if foreign_key primary_key op are not set
        if (!isset($value['type']) | !isset($value['foreign_key']) | !isset($value['primary_key']) | !isset($value['op'])) {
            continue;
        }

        if ($value['type'] == 'leftJoin') {
            $query = $query->leftJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'innerJoin') {
            $query = $query->innerJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'rightJoin') {
            $query = $query->rightJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'crossJoin') {
            $query = $query->crossJoin($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        } elseif ($value['type'] == 'join') {
            $query = $query->join($key, $value['foreign_key'], $value['op'], $value['primary_key']);
        }
    }


    // loop through $data
    foreach ($data as $key => $value) {

        //check if the value has subWhere
        if (isset($value['subWhere']) && isset($value['type'])) {
            // if $value['type'] is where
            if ($value['type'] == 'where') {
                $query->where(function ($query) use ($value, $validation) {
                    queryFilter($value['subWhere'], $query, [], $validation, false);
                });
            }
            // if $value['type'] is orWhere
            if ($value['type'] == 'orWhere') {
                $query->orWhere(function ($query) use ($value, $validation) {
                    queryFilter($value['subWhere'], $query, [], $validation, false);
                });
            }
        } elseif (isset($value['col']) && isset($value['op']) && isset($value['val']) && isset($value['type'])) {
            //check if the col is allowed
            if (!isset($validation[$value['col']])) {
                continue;
            }

            $validationCol = $validation[$value['col']];

            // validate the type of the value
            if (isset($validationCol['type'])) {
                if ($validationCol['type'] == 'int') {
                    if (!is_numeric($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'string') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'date') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'datetime') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'time') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'timestamp') {
                    if (!is_string($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'boolean') {
                    if (!is_bool($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'float') {
                    if (!is_float($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'double') {
                    if (!is_double($value['val'])) {
                        continue;
                    }
                }

                if ($validationCol['type'] == 'decimal') {
                    if (!is_double($value['val'])) {
                        continue;
                    }
                }
            }

            // check if the operator is allowed
            if (isset($validationCol['operators'])) {
                if (!in_array($value['op'], $validationCol['operators'])) {
                    continue;
                }
            }

            // if $value['type'] is where
            if ($value['type'] == 'where') {
                $query->where($value['col'], $value['op'], $value['val']);
            }
            // if $value['type'] is orWhere
            if ($value['type'] == 'orWhere') {
                $query->orWhere($value['col'], $value['op'], $value['val']);
            }
        }
    }

    return $query;
}

function ddQuery($query){

    //replace all parameters with ?

    $sql = $query->toSql();

    $bindings = $query->getBindings();

    // loop through bindings
    foreach ($bindings as $key => $value) {
        // if the value is string
        if (is_string($value)) {
            // replace the value with ?
            $value = "'".$value."'";
        }
        $sql = str_replace('?', $value, $sql);
    }

    dd($sql);

}

function getCurrentUser(){
    if(isset($_COOKIE['jwt'])&&$_COOKIE['jwt']&&isset($_COOKIE['user'])){
        $user = User::find(json_decode(Crypt::decryptString($_COOKIE['user']))->id);
        return $user;
    }
    return null;
}

function makeAdKeyWords($req){
    $keywords = '';
    $cat = cats::select('keywords')->find($req->catid);
    $city = cities::select('name')->find($req->loccity);
    $neighborhood = neighborhoods::select('name')->find($req->locdept);
    if($cat&&$cat->keywords) $keywords .= $cat->keywords;
    if($city) $keywords .= '#'.$city->name;
    if($neighborhood) $keywords .= '#'.$neighborhood->name;
    if($req&&$req->surface) $keywords .= '#'.$req->surface;
    return $keywords;
}
