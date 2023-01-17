@extends('v2.layouts.dashboard')

@section('title', 'Profile')

@section('custom_head')
<link rel="stylesheet" href=" {{ asset('assets/css/v2/profile.styles.css') }}">
<link rel="stylesheet" href=" {{ asset('assets/css/v2/myprofile.styles.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

<!-- moment js to manage dates -->
<script src="{{ asset('js/moment.js') }}"></script>

<script src="{{ asset('js/components/slider.vue.js') }}"></script>

<style>
    .material-symbols-outlined {
      font-variation-settings:
      'FILL' 0,
      'wght' 400,
      'GRAD' 0,
      'opsz' 48
    }

    .badges {

         display:block;
         border-radius: 29px;

         background-color:#F3BE2E;
         color:white;
         width:25%;
         margin:0 auto;
    }



    .infos-list {

         display:flex;
         flex-direction: column;
         justify-content:center;
         align-items: center;
         width:100%;
         margin-top:15px;
         flex-wrap:wrap;
    }

    .emails {

         margin-bottom:13px;
    }

    .emails span{

          margin:0 0 0 3px;
    }

    .usernames span {
        margin:0 0 0 3px;
    }

    .emails i{


        color:#F3BE2E;
  }

  .usernames i {

    color:#F3BE2E;
  }

  .guttering{

    margin-top:13px;
  }

  .guttering i {
    color:#F3BE2E;

  }
  .heading-underline{

      width:6%;
      height:3px;
      background-color: #F3BE2E;
      margin:0 13px 0 0;
  }

  .multiads_cnt{

     margin-top:20px;
  }
  .section-heading{
    margin-top:13px;

  }
    </style>

@endsection

@section('content')


<div id="profile">

    <header :style="user?.banner ? `background: url('/storage${user?.banner}');` : `background: #F3BE2E`">


    </header>

        <div class="row">
            <div class="col-lg-4" style="text-align: center;border-right: 1px solid #ededed;">
                <div class="photo-left">
                    <img class="photo"
                    :src="user?.avatar ? '/storage' + user?.avatar :
                        '/images/default-logo.png'" />
                    <div class="active"></div>
                </div>

                <h4 class="name">@{{ !user?.company ? (user?.firstname + " " + user?.lastname) : user?.company }}</h4>
                <p class="badges">@{{ user?.user_type }}</p>
                <div class="infos-list">
                   <div class="emails">
                    <i class="fa-solid fa-envelope"></i>
                     <span>@{{ user?.email }}</span>
                   </div>
                   <div class="usernames"><i class="fa-solid fa-user"></i> <span>@{{ user?.username }}</span></div>
                </div>
                <p v-if="user?.website" class="info guttering">
                    <a :href="user?.website" style="color: inherit;text-decoration: underline;">
                        <i class="fas fa-external-link"></i>  @{{ user?.website }}
                    </a>
                </p>

                <div>


                    <div class="contact-me mt-2">
                        <div class="row">
                            <div class="col-12 phone">
                                <div class="d-flex align-center justify-content-center">
                                    <a :href="'tel: ' + user?.phone" class="btn btn-phone d-flex">
                                        <i class="fa-solid fa-phone"></i>
                                        @{{ user?.phone }}
                                    </a>
                                </div>
                                <div v-if="user" v-for="p of user?.userPhones"
                                        class="d-flex align-center justify-content-center">
                                        <a :href="'tel: ' + p" class="btn btn-phone d-flex">
                                            <i class="fas fa-phone me-2"></i>
                                            &nbsp; @{{ p }}
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


                        <div v-if="user?.bio" class="projects-title">
                            <div>{{__('general.À propos')}}</div>
                        </div>
                        <p class="desc">@{{ user?.bio }}</p>
                    </div>

                </div>
            </div>
            <div class="right col-lg-8">
                <div v-if="user?.usertype!=3" class="row">
                    <div class="section-heading">
                        <h1>{{__("general.Annonces")}}</h1>
                        <div class="heading-underline"></div>
                    </div>
                    <div class="multiads_cnt" dir="ltr">
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


</div>

@endsection

@section('custom_foot')

<script>
    const id = localStorage.getItem('profile_id');
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
                        console.log(response.data.data);
                        if (this.user?.usertype !== 3) {
                            this.loadAds();
                        } else {
                            this.loadProjects();
                        }
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
                        this.projectsSearchTotal = response.data.data.searchTotal;
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
                        console.log('this projects',this.projects);
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
                let categorie =  l.categorie.replace(/ /g, "-");
                categorie = categorie.replace(/à/g,'a');
                window.open(`https://multilist.immo/annonce/${categorie}/${l.city}/${l.id}`,'_blank');

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

</script>
@endsection
