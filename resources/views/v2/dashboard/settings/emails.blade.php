@extends('v2.layouts.dashboard')

@section('title', 'Catalogue des emails')

@section('custom_head')
    <link href="/assets/vendor/editor/editor.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/components/datatable-components.vue.css') }}">
    <script src="{{ asset('js/components/datatable-components.vue.js') }}"></script>
@endsection

@section('content')

    <div class="pagetitle">
        <h1>Catalogue des emails :</h1>
    </div>
    <section class="section" id="app">
        <div class="row" ref="div1">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div style="text-align: center;margin-top:20px;" v-if="loader">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                        <div class="show-edit">
                            <!-- TinyMCE Editor -->
                            <textarea id="tinymce-editor"></textarea><!-- End TinyMCE Editor -->
                            <div class="card">
                                <div class="card-body" style="text-align: center;padding-top:20px;">
                                    <button class="btn btn-success" @click="save" :disabled="saveLoader">
                                        <span>Sauvegarder</span>
                                        <div class="spinner-border spinner-border-sm ms-2" v-if="saveLoader" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </button>
                                    <button onclick="event.preventDefault();" @click="hideEdit()"
                                        class="btn btn-outline-success">Annuler</button>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns datatable"
                            id="datatable">
                            <div class="dataTable-container table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <div class="datatable-column noselect">Email template</div>
                                            </th>
                                            <th scope="col">
                                                <div class="datatable-column noselect">Action</div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="table-loader" class="d-none">
                                            <td colspan="99">
                                                <div class="d-flex justify-content-center">
                                                    <div class="spinner-border" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-for="d of data" class="">
                                            <td>@{{ d.name }} | @{{ d.lang }}</td>
                                            <td>
                                                <button @click="showEdit(d),goto('div1')" class="btn p-0 m-0 me-2"><i
                                                        class="fas fa-edit text-success"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="globalloader==true" id="globalLoader" class="globalLoader">
            <div
                style="margin: auto; text-align: center; color: #fff; background-color: rgba(34, 34, 34, 0.89); padding: 10px 50px; border-radius: 20px;">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div>Operation en cours...</div>
            </div>
        </div>
    </section>

@endsection

@section('custom_foot')

    <script src="/assets/vendor/tinymce/tinymce.min.js"></script>

    <script type="text/javascript">
        var useDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;

        tinymce.init({
            selector: '#tinymce-editor',
            plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
            imagetools_cors_hosts: ['picsum.photos'],
            menubar: 'file edit view insert format tools table help',
            toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
            toolbar_sticky: true,
            autosave_ask_before_unload: true,
            autosave_interval: '30s',
            autosave_prefix: '{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '2m',
            image_advtab: true,
            link_list: [{
                    title: 'My page 1',
                    value: 'https://www.tiny.cloud'
                },
                {
                    title: 'My page 2',
                    value: 'http://www.moxiecode.com'
                }
            ],
            image_list: [{
                    title: 'My page 1',
                    value: 'https://www.tiny.cloud'
                },
                {
                    title: 'My page 2',
                    value: 'http://www.moxiecode.com'
                }
            ],
            image_class_list: [{
                    title: 'None',
                    value: ''
                },
                {
                    title: 'Some class',
                    value: 'class-name'
                }
            ],
            importcss_append: true,
            file_picker_callback: function(callback, value, meta) {
                /* Provide file and text for the link dialog */
                if (meta.filetype === 'file') {
                    callback('https://www.google.com/logos/google.jpg', {
                        text: 'My text'
                    });
                }

                /* Provide image and alt text for the image dialog */
                if (meta.filetype === 'image') {
                    callback('https://www.google.com/logos/google.jpg', {
                        alt: 'My alt text'
                    });
                }

                /* Provide alternative source and posted for the media dialog */
                if (meta.filetype === 'media') {
                    callback('movie.mp4', {
                        source2: 'alt.ogg',
                        poster: 'https://www.google.com/logos/google.jpg'
                    });
                }
            },
            templates: [{
                    title: 'New Table',
                    description: 'creates a new table',
                    content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>'
                },
                {
                    title: 'Starting my story',
                    description: 'A cure for writers block',
                    content: 'Once upon a time...'
                },
                {
                    title: 'New list with dates',
                    description: 'New List with dates',
                    content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>'
                }
            ],
            template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
            template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
            height: 600,
            image_caption: true,
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            noneditable_noneditable_class: 'mceNonEditable',
            toolbar_mode: 'sliding',
            contextmenu: 'link image imagetools table',
            skin: /*useDarkMode ? 'oxide-dark' :*/ 'oxide',
            content_css: /*useDarkMode ? 'dark' :*/ 'default',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });


        Vue.createApp({
            data() {
                return {
                    globalloader: false,
                    loader: false,
                    saveLoader: false,
                    data: [],
                    show: false,
                    lang: null,
                    selected: null
                }
            },
            watch: {},
            mounted() {
                $('.show-edit').hide();
                this.globalloader = true;
                $.getJSON("/assets/statics/emails.json", (data) => {
                    this.globalloader = false;
                    this.data = data;
                });
            },
            methods: {
                goto(refName) {
             var element = this.$refs[refName];
             var top = element.offsetTop;

              window.scrollTo(0, top);
                },

                showEdit(data) {
                    this.loader = true;
                    axios.get('/api/v2/staticfiles?path=resources/views/emails/' + data.folder + '/' + data.lang +
                            '.blade.php')
                        .then(response => {
                            this.loader = false;
                            if (response.data.status == "success") {
                                this.selected = data.folder;
                                this.lang = data.lang;
                                tinymce.get("tinymce-editor").setContent(response.data.data);
                                $('.show-edit').show();
                            }
                        })
                        .catch(error => {
                            this.loader = false;
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });
                },
                hideEdit() {
                    this.selected = null;
                    this.lang = null;
                    $('.show-edit').hide();
                },
                save() {
                    this.saveLoader = true;
                    const content = tinymce.get("tinymce-editor").getContent();
                    console.log(content);
                    const headr = `
                    <!DOCTYPE html>
                        <html lang="${this.lang}">
                        <head>
                            <!-- email head -->
                            <meta charset="utf-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1">
                            <meta http-equiv="x-ua-compatible" content="ie=edge">
                        </head>

                        <body>
                    `;
                    const footer = '</body></html>';
                    const finalContent = headr + content + footer;
                    var config = {
                        method: 'post',
                        url: `/api/v2/staticfiles`,
                        data: {
                            path: 'resources/views/emails/' + this.selected + '/' + this.lang + '.blade.php',
                            text: finalContent
                        }
                    };
                    axios(config)
                        .then((response) => {
                            this.saveLoader = false;
                            if (response.data.success == true) {
                                this.hideEdit();
                                displayToast("Le modèle a été modifiée avec succès", '#0f5132');
                            }
                        })
                        .catch((error) => {
                            this.saveLoader = false;
                            var err = error.response.data.error;
                            displayToast(err, '#842029');
                        });
                },
            },
        }).mount('#app')
    </script>

@endsection
