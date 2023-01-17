@extends('v2.layouts.default')

@section('title', 'Conditions')

@section('custom_head')
<script src="{{ asset('js/anime.min.js') }}"></script>

<link rel="stylesheet" href="{{ secure_asset('assets/css/v2/conditions.styles.css') }}">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
  .calculate{

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    background: #fff;
    /* display: flex; */
    width: 60%;
    border-radius: 3px;

  }

  @media screen and (max-width: 697px){

    .term-wrap .tabs-lists{

        width:180px !important;
    }

    .term-wrap .tabs-content{

        width:100% !important;
    }
  }

  @media screen and (max-width: 446px){

    .f-align-justify{

        padding: 0 !important;
    }

    .tabs-head h2{

        font-size: 18px;
    }

  }


  @media screen and (max-width: 400px){

.f-align-justify{

    padding: 0 !important;
}

.tabs-lists{

    font-size: 10px;
}

}
</style>

@endsection

@section('content')

<div id="stepping">





     <section class="term-wrapper f-align-justify">

        <div class="term-wrap">
          <div class="tabs-lists">
          <div class="tab-item">
            <ul>
              <li v-for="(item,index) of terms" @click="activeIndexTab(item.id)" :class="activeTab === item.id ? 'active':''">@{{ item.title }}</li>
            </ul>
          </div>
          </div>
          <div class="tabs-content">
            <div class="tabs-head">

                    <h2>@{{ headTitle.toUpperCase() }}</h2>
            </div>
            <div class="tabs-body">

            <div :class="'tab-item-body tab_item_'+item.id" :key="item.id" v-for="item in terms" :style="activeTab === item.id ? 'display:block':'display:none'">
              <h3>@{{ item.title }}</h3>
              <p>
                 @{{ item.text }}
                </p>
            </div>
            </div>
            <div class="tabs-footer">
              <button class="accept" @click="acceptCondition">Accepter
                <div class="spinner-border spinner-border-sm ms-2" v-if="loading"  role="status">
                  <span class="sr-only"></span>
              </div>
              </button>
              <button class="decline" @click="declineCondition">Decliner
                <div class="spinner-border spinner-border-sm ms-2" v-if="loading" role="status">
                  <span class="sr-only"></span>
              </div>
              </button>
            </div>
          </div>

        </div>


     </section>



</div>
    <script>

        let stepper = createApp({

            data(){

                return {

                    activeTab:'1',
                    prospect:[],
                    headTitle:"Condition d'utilisation",
                    params : '',
                    loading: false,
                    terms:[

                       {
                        id:'1',
                        title:"Condition d'utilisation du contenu",
                        text: `in qui dolore vel reprehenderit facilis dolorem eveniet
                              perferendis dolores illum dicta nemo quam tempora, at vitae placeat?
                              Lorem ipsum dolor sit amet consectetur adipisicing elit.
                              Explicabo impedit maiores aliquam. Cumque, beatae. Eaque voluptate assumenda,
                              435u
                              3doloribus, labore libero odit nobis
                              fugiat nesciunt consequuntur facilis officia autem, nemo nostrum.
                              in qui dolore vel reprehenderit facilis dolorem eveniet
                              perferendis dolores illum dicta nemo quam tempora, at vitae placeat?
                              Lorem ipsum dolor sit amet consectetur adipisicing elit.
                              Explicabo impedit maiores aliquam. Cumque, beatae. Eaque voluptate assumenda,
                              435u
                              3doloribus, labore libero odit nobis
                              fugiat nesciunt consequuntur facilis officia autem, nemo nostrum`
                       },

                       {

                        id:'2',
                        title:"Intelectual proprety rights",
                        text: `in qui dolore vel reprehenderit facilis dolorem eveniet
                              perferendis dolores illum dicta nemo quam tempora, at vitae placeat?
                              Lorem ipsum dolor sit amet consectetur adipisicing elit.
                              Explicabo impedit maiores aliquam. Cumque, beatae. Eaque voluptate assumenda,
                              435u
                              3doloribus, labore libero odit nobis
                              fugiat nesciunt consequuntur facilis officia autem, nemo nostrum.`
                       },
                       {

                          id:'3',
                          title:"Clause",
                          text: `in qui dolore vel reprehenderit facilis dolorem eveniet
                                perferendis dolores illum dicta nemo quam tempora, at vitae placeat?
                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                Explicabo impedit maiores aliquam. Cumque, beatae. Eaque voluptate assumenda,
                                435u
                                3doloribus, labore libero odit nobis
                                fugiat nesciunt consequuntur facilis officia autem, nemo nostrum.`
                          }

                    ]

                }

            },

          created() {
          let uri = window.location.search.substring(1);
          let params = new URLSearchParams(uri);
           this.params = params.get('token');
          },

          mounted(){
            this.getSingleProspect();

          },

            methods:{
                 activeIndexTab(i){

                     this.activeTab = i;

                 },

                 getSingleProspect(){

                      axios.get('/api/getsingle?token='+this.params).then((response) => {
                        this.prospect.push(response.data.data[0]);
                      }).catch(error => {

                          console.log(error);
                      })

                },

                declineCondition(){
                   this.loading=true;
                 Swal.fire({
                    title: 'Merci pour votre confiance',
                    confirmButtonText: "Page d'accueil",
                    position:"top-end"
                  }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                      window.location.replace("https://multilist.immo");
                    }
                  })


                },

                 acceptCondition(){
                  this.loading = true;
                     Swal.fire({
                      title: "Conditions d'utilisation",
                    html:
                    '<h5>En donnant votre accord</h5>, ' +
                    'Vous accepterez<b> la publication de vos annonces </b>, ' +
                    "<span style='color:#4DC5D5'>l'equipe Multilist</span>",
                    icon:  'info',
                    confirmButtonText: 'Accepter',
                    reverseButtons: true
                    }).then((result) => {
                      /* Read more about isConfirmed, isDenied below */
                      if (result.isConfirmed) {
                        this.loading = true;
                  axios.patch('/api/acceptconditions',{
                     id:this.prospect[0].id,
                     accepted:true
                  }).then(response =>{

                    window.location.replace("https://multilist.immo");

                  }).catch(error =>{

                      console.log(error);
                  })
                      } else if (result.isDenied) {
                        Swal.fire('Changes are not saved', '', 'info')
                      }
                    })

                 }
            }





        }).mount('#stepping')


    </script>
@endsection

@section('custom_foot')


@endsection
