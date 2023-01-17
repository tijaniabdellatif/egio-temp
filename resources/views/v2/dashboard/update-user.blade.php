@extends('v2.layouts.dashboard')

@section('title', 'dashboard')

@section('custom_head')
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>

    <style>
        .img-form-group {
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .img-form-group .user-img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            margin-right: 10px;
            background: rgb(231, 231, 231);
            margin: auto;
            cursor: pointer;
            justify-content: center;
            align-items: center;
            background-size: cover;
            background-repeat: no-repeat;
        }

        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
        }

        legend.scheduler-border {
            width: inherit;
            /* Or auto */
            padding: 0 10px;
            /* To give a bit of padding on the left and right */
            border-bottom: none;
        }

        .contact {
            padding: 10px;
            border: 1px solid rgb(194, 194, 194);
            background: rgb(250, 250, 250);
            position: relative;
        }

    </style>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div>

    <section class="section" id="app">
        <div class="row">

            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">create user : </h5>
                        <div class="form-group">
                            <label for="my-input"><small>Firstname</small></label>
                            <input id="my-input" class="form-control" placeholder="Firstname" type="text" name="">
                        </div>
                        <div class="form-group mt-2">
                            <label for="my-input"><small>Lastname</small></label>
                            <input id="my-input" class="form-control" placeholder="Lastname" type="text" name="">
                        </div>
                        <div class="form-group mt-2">
                            <label for="my-input"><small>Username</small></label>
                            <input id="my-input" class="form-control" placeholder="Username" type="text" name="">
                        </div>
                        <div class="form-group mt-2">
                            <label for="my-input"><small>Email</small></label>
                            <input id="my-input" class="form-control" placeholder="Email" type="text" name="">
                        </div>
                        <div class="form-group mt-2">
                            <label for="my-input"><small>Phone</small></label>
                            <input id="my-input" class="form-control" placeholder="Phone" type="text" name="">
                        </div>
                        <div class="form-group mt-2">
                            <label for=""><small>User type :</small></label>
                            <select name="property" class="form-select" id="property-select">
                                <option selected>Choisir un Type de bien</option>
                                <option v-for="type in proprety_types" :value="type.id">@{{ type.designation }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for=""><small>Password</small></label>
                            <input id="my-input" class="form-control" placeholder="Password" type="password" name="">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">user info : </h5>
                        <div class="img-form-group">
                            <label for=""><small>user image :</small></label>
                            <div @click="importImage()" class="user-img">
                            </div>
                            <!-- input image file with #user-image-file -->
                            <input type="file" id="user-image-file" style="display: none">
                        </div>
                        <div class="form-group">
                            <label for="my-select"><small>Gender :</small></label>
                            <select id="my-select" class="form-select" name="">
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="my-textarea"><small>Bio :</small></label>
                            <textarea id="my-textarea" class="form-control" name="" rows="3"></textarea>
                        </div>

                        <div class="form-group mt-2" v-for="(cnt,key) in contacts" :key="key" >
                            <label for="my-input"><small>@{{ cnt.type }} :</small></label>

                            <div class="contact phone-contact">
                                <div class="form-group mt-2 d-flex" v-for="(itm,key2) in cnt.values" :key="'phoneitem'+key+'_'+key2" >
                                    <input id="my-input" class="form-control" :placeholder="cnt.placeholder" v-model="itm.value" type="text" name="">
                                    <button type="button" class="btn btn-danger ms-2" @click="deleteContact(cnt.type,key2)" >x</button>
                                </div>
                                <button type="button" class="btn btn-success mt-2 w-100" @click="addAnotherContact(cnt.type)" >add another @{{ cnt.itemName }}</button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@section('custom_foot')


    <script type="text/javascript">
        // firstname
        // lastname
        // username
        // email
        // phone
        // usertype
        // status

        Vue.createApp({
            data() {
                return {
                    contacts : [
                        {
                            type : 'phones',
                            placeholder : '+212 060000000',
                            itemName : 'phone',
                            values : []
                        },
                        {
                            type : 'emails',
                            placeholder : 'email@gmail.com',
                            itemName : 'email',
                            values : []
                        },
                        {
                            type : 'whatsapp',
                            placeholder : '+212 060000000',
                            itemName : 'whatsapp',
                            values : []
                        }
                    ]
                }
            },
            components: {},
            watch: {},
            mounted() {

            },
            methods: {
                datatableSearch() {

                    this.datatable.filters = [{
                            type: 'where',
                            col: 'id',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'firstname',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'lastname',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'username',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'email',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'phone',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'usertype',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        },
                        {
                            type: 'orWhere',
                            col: 'status',
                            val: `%${this.search}%`,
                            op: 'LIKE'
                        }
                    ];

                },
                importImage() {

                    // filre reader on click on div and set it as background
                    $('#user-image-file').click();
                    $('#user-image-file').change(function() {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            $('.user-img').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    });

                },
                deleteContact(type,key) {

                    // search for the type in contacts and remove value from values at key
                    this.contacts.forEach(function(cnt,key2) {
                        if (cnt.type == type) {
                            cnt.values.splice(key,1);
                        }
                    });

                },
                addAnotherContact (type) {

                    // serach for the type in the contacts array and get values array
                    let values = this.contacts.filter(cnt => cnt.type === type)[0].values;

                    // add a new contact
                    values.push({
                        value : ''
                    });

                },
            },
        }).mount('#app')
    </script>

@endsection
