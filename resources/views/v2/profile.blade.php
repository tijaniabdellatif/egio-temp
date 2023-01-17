@extends('v2.layouts.default')

@section('title', 'Profile')

@section('custom_head')


    <link rel="stylesheet" href=" {{ asset('assets/css/v2/profile.styles.css') }}">

    {{-- Notosans font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@200;400;600&display=swap" rel="stylesheet">

    <!-- moment js to manage dates -->
    <script src="{{ asset('js/moment.js') }}"></script>

    <script src="{{ asset('js/components/slider.vue.js') }}"></script>



@endsection

@section('content')

    <div id="profile">

        <header>
        </header>

        <main>
        <div class="row" style="margin-bottom: 15px">
                <div class="col-lg-4" style="text-align: center;border-right: 1px solid #ededed;">
                    <div class="photo-left">
                        <img class="photo"
                        :src="user?.avatar?'/storage'+user?.avatar:'/images/default-logo.png'" />
                        <div class="active"></div>
                    </div>


                    <h4 class="name">@{{!user?.company?(user?.firstname + " " + user?.lastname):user?.company}}</h4>
                    <p class="info info-user-type text-capitalize">@{{user?.user_type}}</p>
                    <p v-if="user?.website" class="info">
                        <a :href="user?.website" style="color: inherit;text-decoration: underline;">
                            <i class="fa-solid fa-globe mx-1"> Website </i> <i class="fas fa-external-link"></i>
                        </a>
                    </p>

                    <div>

                        <div class="contact-me mt-2">
                            <div class="row d-flex" style="flex-direction: column;">
                                {{-- <div class="col-12 phone">
                                    <div class="d-flex align-center justify-content-center">
                                        <a :href="'tel: '+user?.phone" class="btn btn-phone d-flex">
                                            <i class="multilist-icons multilist-phone me-2"></i>
                                            @{{user?.phone}}
                                        </a>
                                    </div>
                                    <div v-if="user" v-for="p of user?.userPhones" class="d-flex align-center justify-content-center">
                                        <a :href="'tel: '+p" class="btn btn-phone d-flex">
                                            <i class="multilist-icons multilist-phone me-2"></i>
                                            @{{p}}
                                        </a>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-center justify-content-center">
                                        <a v-if="user" v-for="w of user?.userWtsps" :href="'https://wa.me/' + w" target="_blank" class="btn" >
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    </div>
                                </div> --}}

                                <div class="bordered" style="flex-direction: column;box-sizing: content-box;padding: 1rem 0 0;">
                                    <i class="fa fa-lg fa-phone"></i>
                                    <p style="border-bottom: 1px solid #6c757d;">Téléphone</p>
                                    <div class="d-flex align-center justify-content-center">
                                        <a :href="'tel: '+user?.phone" class="d-flex num">
                                            <i class="fa-solid fa-phone-volume mx-1" style="position:static;opacity:0"></i>
                                            <span class="mx-1"></span>
                                            @{{+user?.phone}}
                                        </a>
                                    </div>
                                    <div v-if="user" v-for="p of user?.userPhones" class="d-flex align-center justify-content-center">
                                        <a :href="'tel: '+p" class="d-flex num">
                                            <i class="fa-solid fa-phone-volume mx-1" style="position:static;opacity:0"></i>
                                            <span class="mx-1"></span>@{{p != 'null' ? p : ''}}
                                        </a>
                                    </div>
                                </div>



                                <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@200&display=swap" rel="stylesheet">
                                    <div v-if="user" v-for="w of user.userWtsps" :class="(w != 'null' ? bordered : 'd-none' )" class="d-flex align-center bordered justify-content-center" style="flex-direction: column;box-sizing: content-box;padding: 1rem 0 0;">
                                        <p style="border-bottom: 1px solid #6c757d;width:100%;margin:0 !important" class="mx-2">Whatsapp</p>
                                        <div  class=" border-1">
                                            <a  :href="'https://wa.me/' + w" target="_blank" class="num" >
                                                <i class="fab fa-whatsapp fa-lg"></i>
                                                <p class="num">
                                                    @{{ w != 'null' ? w : '' }}
                                                </p>
                                            </a>
                                        </div>

                                    </div>

                            </div>
                            <div class="mt-2 row">
                            </div>
                            <div v-if="user?.bio" class="projects-title">
                                <div></div>À propos<div></div>
                            </div>
                            <p class="desc">@{{user?.bio}}</p>

                        </div>

                    </div>
                </div>
                <div class="right col-lg-8">
                    <div v-if="user?.usertype!=3" class="row">
                        <div class="section-heading">
                            <h1>Annonces publiées:</h1>
                            <div class="heading-underline"></div>
                        </div>
                        <div class="multiads_cnt" style="margin-top: 0;">
                            <div v-for="s of ads" class="multiads hoverable mt-5" style="width: 280px;" @click="toItemPage($event,s)">
                                <div class="img media" style="height: 200px;">
                                    <Slider :images="s.images"></Slider>
                                </div>
                                <div class="content">
                                    <div class="title">
                                        <a href="#">
                                            <h1> @{{s.title}}</h1>
                                        </a>
                                    </div>
                                    <div class="mt-3" style="font-size: 14px;">
                                        <i class="fa-solid fa-layer-group mx-2"></i>
                                        <span>@{{ s.categorie }}</span>
                                    </div>
                                    <div class="location mt-2" style="color: gray;">
                                        <span>
                                            <span><i class="multilist-icons multilist-location mx-1" style="font-size: 1.7rem;"></i> @{{localisation(s)}} </span>
                                        </span>
                                    </div>
                                    <div class="price">
                                        <span> @{{price(s)}} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="ads.length == 0&&!loader" class="text-muted" style="text-align: center;">
                            <span>Aucun résultat trouvé</span>
                        </div>

                        <div v-if="ads.length < searchTotal&&!loader" style="text-align: center;">
                            <button @click="nextAds" class="btn btn-seemore" style="width: 150px;background-color: var(--multi_color);color: white;"> Voir plus </button>
                        </div>


                        <div v-if="loader" class="loader"></div>
                    </div>
                    <div v-else class="row">
                        <div class="section-heading">
                            <h1 class="text-capitalize">Projets</h1>
                            <div class="heading-underline"></div>
                        </div>
                        <div class="Projects_cnt">
                            <div class="list-container">
                                <a v-for="l of projects" class="list-item mt-2 hoverable" style="transition:all .3s ease;    background-color: #cecece3d;" @click="toItemPage($event,l)">
                                    <div class="list-item-container mb-5" style="position: relative;">
                                        <div class="item-body" >

                                            <div class="content">
                                                <div class="title">
                                                    <h3>
                                                        <span class="fw-bold fs-5 ms-2">@{{ l.title }}</span>
                                                    </h3>
                                                    <div class="title-bar"></div>
                                                </div>
                                                <div class="categorie">
                                                    <p>
                                                        <i class="fa-solid fa-layer-group mx-2"></i>
                                                        <span>@{{ l.categorie }}</span>
                                                    </p>
                                                </div>
                                                <div class="localisation">
                                                    <span>
                                                        <i class="multilist-icons multilist-location mx-1 fs-5"></i>
                                                        @{{ localisation(l) }}
                                                    </span>
                                                </div>
                                                <div class="price">
                                                    <span>@{{ price(l) }}</span>
                                                </div>
                                            </div>
                                            <div class="media">
                                                <Slider :images="l.images"></Slider>
                                            </div>
                                        </div>
                                        <div class="item-info">
                                            <span class="publication-date">
                                                <i class="fa-regular fa-calendar-alt me-2"></i>
                                                <span>Publié le @{{ formatDateTime(l.date) }}</span>
                                            </span>
                                            <span class="features">

                                                <span class="d-flex justify-content-start align-items-center" v-if="l.bedrooms" title="chambres">
                                                    <i class="multilist-icons multilist-bed mx-1"></i>
                                                    @{{ l.bedrooms }}
                                                </span>

                                                <span class="d-flex justify-content-start align-items-center" v-if="l.bathrooms" title="Salles de bain">
                                                    <i class="fa-solid fa-bath mx-1"></i>
                                                    @{{ l.bathrooms }}
                                                </span>

                                                <span class="d-flex justify-content-start align-items-center" v-if="l.surface" title="Supérficie">
                                                    <i class="fa-solid fa-expand mx-1"></i>
                                                    @{{ l.surface2?"De "+l.surface+"m² à "+ l.surface2+"m²":l.surface+"m²"}}
                                                </span>

                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div v-if="projects.length == 0&&!loader" class="text-muted" style="text-align: center;">
                                <span>Aucun résultat trouvé</span>
                            </div>

                            <div class="mt-5" v-if="projects.length < searchTotal&&!loader" style="text-align: center;">
                                <button @click="nextProjects" class="btn btn-seemore" style="width: 150px;background-color: var(--multi_color);color: white;"> Voir plus </button>
                            </div>
                            <button @click="topFunction()" id="myBtn" title="Go to top">
                                <i class="fa-solid fa-chevron-up mx-1"></i>
                            </button>


                            <div v-if="loader" class="loader"></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    </div>


    <script>
        const id = '{{$id}}';
        let profileApp = createApp({
            data() {
                return {
                    ads:[],
                    projects:[],
                    id:null,
                    loader: false,
                    searchTotal: 0,
                    projectsSearchTotal: 0,
                    filters:{
                        status: '10',
                        from: 0,
                        count: 20
                    },
                    projFilters:{
                        status: '10',
                        from: 0,
                        count: 20
                    },
                    user:null,
                }
            },
            components: {
                'Slider': Slider
            },
            mounted(){
                if(!id) window.location.href = "/";
                this.id = id;
                this.loadData();
                // top btn function
                setTimeout(() => {
                    // Get the button:
                let mybutton = document.getElementById("myBtn");

                // When the user scrolls down 20px from the top of the document, show the button
                window.onscroll = function() {scrollFunction()};

                function scrollFunction() {
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    mybutton.style.display = "block";
                } else {
                    mybutton.style.display = "none";
                }
                }
                }, 2000);

            },
            methods: {
                topFunction(){

                    // When the user clicks on the button, scroll to the top of the document
                    document.body.scrollTop = 0; // For Safari
                    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

                },
                loadData(){
                    axios.get(`/api/v2/profileData?id=${id}`)
                        .then((response) => {
                            this.user = response.data.data;
                            // console.log('sk test ',this.user.bio)
                            if(this.user?.usertype!=3){
                                this.loadAds();
                            }
                            else{
                                this.loadProjects();
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                loadAds(){
                    this.loader = true;
                    axios.get(`/api/v2/profileAds?id=${id}`,{params:this.filters})
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
                loadProjects(){
                    this.loader = true;
                    axios.get(`/api/v2/profileProjects?id=${id}`,{params:this.projFilters})
                        .then((response) => {
                            this.loader = false;
                            this.projects = this.projects.concat(response.data.data.data);
                            this.searchTotal = response.data.data.searchTotal;
                            // console.log('Projects: ',this.projects)
                        })
                        .catch(error => {
                            this.loader = false;
                            console.log(error);
                        });
                },
                nextAds(){
                    this.filters.from += this.filters.count;
                    this.loadAds();
                },
                nextProjects(){
                    this.projFilters.from += this.projFilters.count;
                    this.loadProjects();
                },
                toItemPage(e,l){
                    if(e.target.classList.contains('btn')||e.target.parentElement.classList.contains('btn')){
                        return;
                    }
                    //window.location.href = '/item/'+l.id+'/'+l.title;
                    window.open((multilistType!="multilist"?'/'+multilistType:'') + '/item/'+l.id+'/'+l.title, '_blank');
                },
                formatDateTime(value) {
                    return moment(value).format('DD MMM. YYYY HH:mm');
                },
                localisation(data){
                    let r = '';
                    if(data.neighborhood){
                        r = data.neighborhood + ', ' + data.city;
                    }
                    else{
                        if(data.locdept2){
                            r = data.locdept2 + ', ' + data.city;
                        }
                        else r = data.city;
                    }
                    return r;
                },
                price(data){
                    let r = '';

                    if(data.price){
                        data.price = data.price.toLocaleString(undefined, {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: 0
                        });

                        if(data.price <= 0) r = 'Prix à consulter';
                        else{
                            if(data.price_curr){
                                r = data.price + ' ' + data.price_curr;
                            }
                            else r = data.price;
                        }
                    }
                    else r = 'Prix à consulter';
                    return r;
                },
            }
        }).mount('#profile');
        document.getElementById('profile').classList.remove('d-none');
    </script>

    <style>

    .images_slider img {
        clip-path: polygon(0 0, 100% 0%, 100% 100%, 9% 100%);
        border-top-right-radius: 11px;
        transition: all 1s ease;
    }
    .Projects_cnt .list-item{
        border-left: 0;
        border-top: 0;
        box-shadow: 7px 5px 8px 0px #cececa;
    }
    /* .info-user-type{
        letter-spacing: 2px;
        color: #fff;
        background-color: #3d7a3d;
        width: fit-content;
        margin: 2rem auto;
        border-radius: 3px;
        padding: 0 5px;
    } */

    .bordered{
        margin: auto;
        /* font-family: 'Tajawal'; */
        border:1px solid #cececa;
        width:220px;
        padding:3px;
        font-size: 14px;
        /* height:100px; */
        border-radius:5px;
        position:relative;
        margin-top:1rem;
        background: radial-gradient(circle at 70.7% 3%, #f5f6f7 40%, rgb(255 255 255) 5%);

    }
    .bordered p{
        margin: auto;
        text-align: center;
    }
    .bordered i{
        position:absolute;
        left: 3px;
        top:-5px;
        background-color:#fff;
        padding:5px;
        border-radius:5px;
        color:var(--primary-color);
        }
        .num{
        /* color:var(--primary-color); */
        font-weight:700;
        letter-spacing:2px;
        text-align:center;
        margin: 0;
        background-color:var(--primary-color);;
        color:#fff;
        cursor:pointer;
        width:100%;
        transition: all .3s ease-in-out;
        }
        .num i{
            transition: all .5s ease;
        }

        .num:hover {
            background-color: deepskyblue;
            color: #fff;
            letter-spacing: 3px;
        }
        .num:hover i{
            opacity: 1 !important;
        }

.list-container {
    display: flex;
    flex-direction: column;
}

.list-container .list-item{
    display: flex;
    margin-bottom: 55px;
    background: transparent;
    border-radius: 5px;
    width: 90%;
    cursor: pointer;
}

.list-container .list-item  .list-item-container {
    display: flex;
    flex-direction: row;
    width: 100%;
    flex-direction: column;
    height:190px;
}

.list-container .list-item .list-item-container .item-body {
    box-shadow: 0 4px 21px -12px rgb(0 0 0 / 66%);
    border-top-left-radius: 5px;
    display: flex;
    flex: 1;
    height: 100%;
    z-index: 3;
    background: radial-gradient(circle at 18.7% 37.8%, rgb(250, 250, 250) 20%, rgb(225, 234, 238) 90%);
}

.list-container .list-item .list-item-container .media {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    width: 500px;
    margin-right: 0px !important;
    position: relative;
}

.list-container .list-item .list-item-container .media .images_slider {
    width: 100%;
    height: 100%;
}

.list-container .list-item .list-item-container .media .avatar {
    position: absolute;
    top: 5px;
    left: 5px;
    width: 65px;
    height: 65px;
    box-shadow: 0 0 8px 0px #868585;
    z-index: 10;
    background-color: #fff;
    border-radius: 50px;
}

.list-container .list-item .list-item-container .media .avatar img{
    object-fit: contain !important;
    border-radius: 50px;
}

.list-container .list-item .list-item-container .media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.list-container .list-item .list-item-container .content {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 190px;
    overflow: hidden;
    position: relative;
    padding: 10px;
}

.list-container .list-item .list-item-container .content .title {
    font-size: 20px;
    font-weight: 600;
    width: calc(100% - 50px);
}

.list-container .list-item .list-item-container .content .title span{
    font-size: 1rem !important;
    text-transform: uppercase;
    font-weight: 600;
    box-sizing: border-box;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    overflow: hidden;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
    color: #373736;
    letter-spacing: 2px;
}
    .title-bar{
        width: 50px;
        height: 3px;
        margin: 10px 0;
        border-radius: 5px;
        background-color: #424242;
        transition: width 0.2s ease;
        background-color: var(--primary-color);

    }
    .hoverable:hover .title-bar{
        width: 100px;
    }
.list-container .list-item .list-item-container .content .title a{
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 600;
    box-sizing: border-box;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    overflow: hidden;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
    color: #373736;
}

.list-container .list-item .list-item-container .content .categorie {
    font-size: 12px;
    color: gray;
}

.list-container .list-item .list-item-container .content .localisation {
    font-size: .9rem;
    color: gray;
}

.list-container .list-item .list-item-container .content .price {
    margin-top: auto;
    margin-left: auto;
    font-size: 15px;
    color: var(--secondary-color);
    font-weight: 600;
}

.list-container .list-item .list-item-container .item-info {
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    padding: 5px 20px;
    border-top: 1px solid var(--lightgray-border-color);
    background: rgb(247 247 247);
    color: gray;
}

.list-container .list-item .list-item-container .item-info .publication-date {
    font-size: 11px;
}


.list-container .list-item .list-item-container .content .features{
    padding: 10px 0;
    color: black;
    font-weight: 100;
}

.list-container .list-item .list-item-container .content .features .feature{
    margin: 0 10px;
    width: fit-content;
    text-align: center;
    display: inline-grid;
}

.list-container .list-item .list-item-container .content .features .feature .multilist-icons{
    font-size: 30px;
}

.list-container .list-item .list-item-container .content .features .feature div{
    font-size: 11px;
    width: fit-content;
}

.list-container .list-item .list-item-container .content .features .feature div span{
    font-weight: 600;
    margin: 0px;
}

@media (max-width: 425px){
    .row .right{
        padding: 0 !important;
    }

    .list-container .list-item .list-item-container .media{
        width: 100%;
    }
    .images_slider img {
        clip-path: none;
        border-radius: 0;
    }
    .list-container .list-item .list-item-container{
        height: 300px;
    }
    .Projects_cnt .list-item .list-item-container .item-info .features span {
    font-size: .7rem;
    display: flex;
    margin: 3px;
    }
    .list-container .list-item .list-item-container .content {
        height: 230px;
    }
    .Projects_cnt .list-item .list-item-container .media{
        margin-bottom: 0;
    }
    .list-container .list-item{
        width: 100%;
    }
    .list-container .list-item .list-item-container .item-body {
    box-shadow: 1px 14px 10px -12px rgb(0 0 0 / 66%);

    }
    #myBtn{
        background-color: transparent !important;
        border: 1px solid var(--primary-color) !important;
    }
    #myBtn i{
        color: var(--primary-color);
    }

}

/* top btn */
#myBtn {
  display: none; /* Hidden by default */
  position: fixed; /* Fixed/sticky position */
  bottom: 50px; /* Place the button at the bottom of the page */
  right: 11px; /* Place the button 30px from the right */
  z-index: 99; /* Make sure it does not overlap */
  border: none; /* Remove borders */
  outline: none; /* Remove outline */
  background-color: var(--primary-color); /* Set a background color */
  color: white; /* Text color */
  cursor: pointer; /* Add a mouse pointer on hover */
  padding: 15px; /* Some padding */
  border-radius: 10px; /* Rounded corners */
  font-size: 18px; /* Increase font size */
}

#myBtn:hover {
  background-color: #555; /* Add a dark-grey background on hover */
}

    </style>

@endsection

@section('custom_foot')

@endsection
