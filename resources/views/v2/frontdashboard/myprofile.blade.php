@extends('v2.dashboard')

@section('title1', __("general.Mon profile"))

@section('custom_head1')

    <link rel="stylesheet" href=" {{ asset('assets/css/v2/profile.styles.css') }}">
    <link rel="stylesheet" href=" {{ asset('assets/css/v2/myprofile.styles.css') }}">

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <script src="{{ asset('js/components/slider.vue.js') }}"></script>

@endsection

@section('content1')


    <div id="profile" class="d-none" @if(Session::get('lang') === 'ar') dir="rtl" @endif>

        <header :style="user?.banner ? `background: url('/storage${user?.banner}');` : ''">
        </header>
        <main>
            <div class="row">
                <div class="col-lg-4" style="text-align: center;border-right: 1px solid #ededed;">
                    <div class="photo-left">
                        <img class="photo"
                            :src="user?.avatar ? '/storage' + user?.avatar :
                                '/images/default-logo.png'" />
                        <div class="active"></div>
                    </div>
                    <h4 class="name">@{{ !user?.company ? (user?.firstname + " " + user?.lastname) : user?.company }}</h4>
                    <p class="info">@{{ user?.user_type }}</p>
                    <p v-if="user?.website" class="info">
                        <a :href="user?.website" style="color: inherit;text-decoration: underline;">
                            @{{ user?.website }} <i class="fas fa-external-link"></i>
                        </a>
                    </p>

                    <div>

                        <div class="row contact-me">
                            <div class="col-12 phone">
                                <div class="d-flex align-center justify-content-center">
                                    <a href="/editprofile" class="btn d-flex">
                                        <i class="fas fa-edit mx-2"></i>
                                        {{__('general.Modifier mon profile')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="contact-me mt-2">
                            <div class="row">
                                <div class="col-12 phone">
                                    <div class="d-flex align-center justify-content-center">
                                        <a :href="'tel: ' + user?.phone" class="btn btn-phone d-flex">
                                            <i class="multilist-icons multilist-phone me-2"></i>
                                            @{{ user?.phone }}
                                        </a>
                                    </div>
                                    <div v-if="user" v-for="p of user?.userPhones"
                                        class="d-flex align-center justify-content-center">
                                        <a :href="'tel: ' + p" class="btn btn-phone d-flex">
                                            <i class="fas fa-phone me-2"></i>
                                            @{{ p }}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-center justify-content-center">
                                        <a v-if="user" v-for="w of user?.userWtsps" :href="'https://wa.me/' + w"
                                            target="_blank" class="btn">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 row">
                            </div>
                            <div class="projects-title">
                                <div></div>{{__('general.À propos')}}<div></div>
                            </div>
                            <p class="desc">@{{ user?.bio }}</p>

                        </div>

                    </div>
                </div>
                <div class="right col-lg-8">
                    <div v-if="user?.usertype!=3" class="row">
                        <div class="section-heading">
                            <h1>{{__("general.Mes annonces")}}</h1>
                            <div class="heading-underline"></div>
                        </div>
                        <div class="multiads_cnt" dir="ltr" style="margin-top: 0;">
                            <div v-for="s of ads" class="multiads" style="width: 280px;" @click="toItemPage($event,s)">
                                <div class="img media" style="height: 200px;">
                                    <Slider :images="s.images"></Slider>
                                </div>
                                <div class="content">
                                    <div class="title">
                                        <a href="#">
                                            <h1> @{{ s.title }}</h1>
                                        </a>
                                    </div>
                                    <div class="" style="font-size: 14px;">
                                        <span>@{{ s.categorie }}</span>
                                    </div>
                                    <div class="location" style="color: gray;">
                                        <span>
                                            <span><i class="multilist-icons multilist-location"></i> @{{ localisation(s) }} </span>
                                        </span>
                                    </div>
                                    <div class="price">
                                        <span> @{{ price(s) }} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="ads.length == 0&&!loader" class="text-muted" style="text-align: center;">
                            <span>{{__("general.Aucun résultat trouvé")}}</span>
                        </div>

                        <div v-if="ads.length < searchTotal&&!loader" style="text-align: center;">
                            <button @click="nextAds" class="btn btn-seemore"
                                style="width: 150px;background-color: var(--multi_color);color: white;"> Voir plus </button>
                        </div>

                        <div v-if="loader" class="loader"></div>
                    </div>
                    <div v-else class="row">
                        <div class="section-heading">
                            <h1>{{__("general.Les projets")}}	</h1>
                            <div class="heading-underline"></div>
                        </div>
                        <div class="Projects_cnt" dir="ltr">
                            <div class="list-container">
                                <a v-for="l of projects" class="list-item" @click="toItemPage($event,l)">
                                    <div class="list-item-container" style="position: relative;">
                                        <div class="item-body">
                                            <div class="media">
                                                <Slider :images="l.images"></Slider>
                                            </div>
                                            <div class="content">
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
                                                    <i class="fa-solid fa-bath"></i>
                                                    @{{ l.bathrooms }}
                                                </span>

                                                <span v-if="l.surface" title="Supérficie">
                                                    <i class="fa-solid fa-expand"></i>
                                                    @{{ l.surface2 ? "De " + l.surface + "m² à " + l.surface2 + "m²" : l.surface + "m²" }}
                                                </span>

                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div v-if="projects.length == 0&&!loader" class="text-muted" style="text-align: center;">
                                <span>Aucun résultat trouvé</span>
                            </div>

                            <div v-if="projects.length < projectsSearchTotal&&!loader" style="text-align: center;">
                                <button @click="nextProjects" class="btn btn-seemore"
                                    style="width: 150px;background-color: var(--multi_color);color: white;"> Voir plus
                                </button>
                            </div>

                            <div v-if="loader" class="loader"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>


    <script>
        var lastScrollTop = 0;
        const id = '{{ Auth()->user() ? Auth()->user()->id : '' }}';
        let profileApp = createApp({
            data() {
                return {
                    ads: [],
                    projects: [],
                    id: null,
                    loader: false,
                    searchTotal: 0,
                    projectsSearchTotal: 0,
                    filters: {
                        status: '10',
                        from: 0,
                        count: 20
                    },
                    projFilters: {
                        status: '10',
                        from: 0,
                        count: 20
                    },
                    user: null,
                }
            },
            components: {
                'Slider': Slider
            },
            mounted() {
                if (!id) window.location.href = "/";
                this.id = id;
                this.loadData();
            },
            methods: {
                loadData() {
                    axios.get(`/api/v2/profileData?id=${id}`)
                        .then((response) => {
                            this.user = response.data.data;
                            if (this.user?.usertype != 3) {
                                this.loadAds();
                            }
                            else {
                                this.loadProjects();
                            }
                            //infinite scroll
                            document.getElementById('main-scroll').onscroll = () => {
                                let id_down = false;
                                let scale = 500;
                                const thiselem = document.getElementById('main-scroll');
                                if (document.documentElement.clientWidth <= 767) {
                                    scale = 700;
                                }
                                if (thiselem.scrollTop > lastScrollTop) id_down = true;
                                lastScrollTop = thiselem.scrollTop;
                                let bottomOfWindow = thiselem.scrollTop + thiselem.clientHeight >
                                    thiselem.scrollHeight - scale;
                                if (id_down && bottomOfWindow) {
                                    if (this.user?.usertype != 3) {
                                        if (this.loader == false && this.searchTotal > this.ads.length) {
                                            this.filters.from += this.filters.count;
                                            this.loadAds();
                                        }
                                    }
                                    else {
                                        if (this.loader == false && this.searchTotal > this.projects.length) {
                                            this.filters.from += this.filters.count;
                                            this.loadProjects();
                                        }
                                    }
                                }
                            };
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadAds() {
                    this.loader = true;
                    axios.get(`/api/v2/profileAds?id=${id}`, {
                            params: this.filters
                        })
                        .then((response) => {
                            this.loader = false;
                            this.ads = this.ads.concat(response.data.data.data);
                            this.searchTotal = response.data.data.searchTotal;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                loadProjects() {
                    this.loader = true;
                    axios.get(`/api/v2/profileProjects?id=${id}`, {
                            params: this.projFilters
                        })
                        .then((response) => {
                            this.loader = false;
                            this.projects = this.projects.concat(response.data.data.data);
                            this.searchTotal = response.data.data.searchTotal;
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                nextAds() {
                    this.filters.from += this.filters.count;
                    this.loadAds();
                },
                nextProjects() {
                    this.projFilters.from += this.projFilters.count;
                    this.loadProjects();
                },
                toItemPage(e, l) {
                    if (e.target.classList.contains('btn') || e.target.parentElement.classList.contains('btn')) {
                        return;
                    }

                    //window.location.href = '/item/'+l.id+'/'+l.title;
                    window.open((multilistType!="multilist"?'/'+multilistType:'') + '/item/' + l.id + '/' + l.title, '_blank');
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
            }
        }).mount('#profile');
        document.getElementById('profile').classList.remove('d-none');
    </script>
@endsection


@section('custom_foot1')

@endsection
