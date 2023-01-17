@extends('v2.layouts.default')

@section('title', __("general.Favoris"))

@section('custom_head')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/list.styles.css') }}">
    <script src="{{ asset('js/components/slider.vue.js') }}"></script>


    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

@endsection

@section('content')


    <div id="list-section" class="d-none">

        <section class="container-md list-section">
            <div class="row">
                <div class="col-12 col-lg-9">

                    <div class="section-heading translates">
                        <h1>{{ __('general.Favoris') }} :</h1>
                        <div class="heading-underline"></div>
                    </div>

                    <div class="list-container">

                        <a v-for="l of list" class="list-item" @click="toItemPage($event,l)">

                            <div class="list-item-container" style="position: relative;">

                                <div class="item-body">
                                    <div class="media">
                                        <div v-if="l.usertype == 3 || l.usertype == 4" class="avatar">
                                            <img :src="l.avatar ? '/storage' + l.avatar :
                                                'http://styleguide.europeana.eu/images/fpo_avatar.png'"
                                                alt="">
                                        </div>
                                        <Slider :images="l.images"></Slider>
                                    </div>

                                    <div class="content">
                                        <i :class="favoris.find((e) => e == l.id) ? 'fas primary-c' : 'far'"
                                            class=" btn fa-star favori-ico" style="align-self: self-end;"
                                            @click="toggleFavoris(l.id)"></i>
                                        <div class="title">
                                            <h3>
                                                <span>@{{ l.title }}</span>
                                            </h3>
                                        </div>

                                        <div class="categorie">
                                            <p>
                                                <span>@{{ l.categorie }}</span>
                                            </p>
                                        </div>

                                        <div class="localisation">
                                            <span>
                                                <i class="multilist-icons multilist-location"></i>
                                                @{{ localisation(l) }}
                                            </span>
                                        </div>

                                        <div class="price">
                                            <span>@{{ price(l) }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="item-info">
                                    <span class="publication-date">
                                        <i class="fa-regular fa-calendar-alt me-2"></i>
                                        <span>Publié le @{{ formatDateTime(l.date) }}</span>
                                    </span>
                                    <span class="features">

                                        <span v-if="l.bedrooms" title="chambres">
                                            <i class="multilist-icons multilist-bed"></i>
                                            @{{ l.bedrooms }}
                                        </span>

                                        <span v-if="l.bathrooms" title="Salles de bain">
                                            <i class="multilist-icons multilist-bath"></i>
                                            @{{ l.bathrooms }}
                                        </span>

                                        <span v-if="l.surface" title="Supérficie">
                                            <i class="multilist-icons multilist-surface"></i>
                                            @{{ l.surface }}m²
                                        </span>

                                    </span>
                                </div>

                            </div>

                        </a>

                    </div>

                    <div v-if="list.length == 0&&!loader" class="text-muted translates" style="text-align: center;">
                        <span>{{ __('general.Aucun résultat trouvé') }}</span>
                    </div>

                    <div v-if="loader" class="loader"></div>

                </div>
                <div class="d-none d-lg-flex col-3"></div>
            </div>
        </section>
    </div>

    <script>
        var lastScrollTop = 0;
        let listingApp = createApp({
            data() {
                return {
                    loader: false,
                    list: [],
                    favoris: localStorage.favoris ? JSON.parse(localStorage.favoris) : [],
                }
            },
            computed: {},
            mounted() {
                //loadData
                this.loadListing();
            },
            components: {
                'Slider': Slider
            },
            methods: {
                toggleFavoris(id) {
                    if (localStorage.getItem('favoris')) {
                        if (Array.isArray(JSON.parse(localStorage.getItem('favoris')))) {
                            ids = JSON.parse(localStorage.getItem('favoris'));
                            const idIndex = ids.indexOf(id);
                            if (idIndex == -1) {
                                ids.push(id);
                                localStorage.favoris = JSON.stringify(ids);
                                this.favoris = ids;
                            } else {
                                ids.splice(idIndex, 1);
                                localStorage.favoris = JSON.stringify(ids);
                                this.favoris = ids;
                            }
                        } else {
                            localStorage.favoris = JSON.stringify([id]);
                            this.favoris = [id];
                        }
                    } else {
                        localStorage.setItem('favoris', '[' + id + ']');
                        this.favoris = [id];
                    }
                },
                loadListing() {
                    let ids = [];
                    if (localStorage.getItem('favoris')) {
                        if (Array.isArray(JSON.parse(localStorage.getItem('favoris')))) {
                            ids = JSON.parse(localStorage.getItem('favoris'));
                        }
                    }
                    //univer type
                    axios.get("/api/v2/getFavorisAds", {
                            params: {
                                ids: ids
                            }
                        })
                        .then((response) => {
                            this.loader = false;
                            this.list = response.data.data;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                formatDateTime(value) {
                    return moment(value).format('DD MMM. YYYY HH:mm');
                },
                localisation(data) {
                    let r = '';
                    if (data.neighborhood) {
                        r = data.neighborhood + ', ' + data.city;
                    } else {
                        if (data.locdept2) {
                            r = data.locdept2 + ', ' + data.city;
                        } else r = data.city;
                    }
                    return r;
                },
                price(data) {
                    let r = '';

                    if (data.price) {
                        data.price = data.price.toLocaleString(undefined, {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        if (data.price <= 0) r = 'Prix à consulter';
                        else {
                            if (data.price_curr) {
                                r = data.price + ' ' + data.price_curr;
                            } else r = data.price;
                        }
                    } else r = 'Prix à consulter';
                    return r;
                },
                toItemPage(e, l) {
                    if (e.target.classList.contains('btn') || e.target.parentElement.classList.contains('btn')) {
                        return;
                    }
                    window.location.href = (multilistType!="multilist"?'/'+multilistType:'') + '/item/' + l.id + '/' + l.title;
                }
            }
        }).mount('#list-section');
        document.getElementById('list-section').classList.remove('d-none');
    </script>

@endsection

@section('custom_foot')

    <script src=" {{ asset('assets/js/v2/list.scripts.js') }}"></script>



@endsection
