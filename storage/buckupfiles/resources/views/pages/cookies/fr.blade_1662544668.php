@extends('v2.layouts.default')

@section('title', 'Cookies')

@section('custom_head')

    <style>
        body {

            background-image:linear-gradient(
               to bottom,
               rgba(0,0,0,0.3),
               transparent
             ),url({{url('images/bg-register.jpg')}});
            background-size:cover;
            background-repeat:no-repeat;
           }
    </style>
@endsection

@section('content')
<section class="p-1 m-1 container m-auto mt-3 w-100 mb-3">
        <h2 class="mb-5">Cookies</h2>
        <li>Dans le cadre de la protection de la vie privée, cette rubrique à pour objectif de vous informer de l'usage des cookies sur notre plateforme.  </li>
        <li>La majorité de ceux-ci nous permettent de collecter des données anonymes sur l'usage de notre plateforme, celles-ci nous aident à personnaliser et à améliorer continuellement votre expérience utilisateur.</li>
        <li>C'est pour cette raison que le site que vous visitez utilise des traceurs (cookies). Ainsi, le site est susceptible d'accéder à des informations déjà stockées dans votre équipement terminal de communications électroniques et d'y inscrire des informations.</li>
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  Définition des cookies
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          Les cookies sont des petits fichiers placés sur le disque dur de votre terminal (ordinateur, smartphone ou tablette). L’utilisation des cookies permet de vous proposer des services et d’assurer la gestion de votre compte.
                        </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  Les cookies de notre site
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          Il s'agit essentiellement de cookies dits « cookies de session », à savoir des cookies automatiquement supprimés de votre disque dur dès que votre session est terminée (c'est-à-dire quand vous cliquez sur le lien "Déconnexion" ou lorsque vous fermez votre navigateur Internet préféré).
                        </li>
                        <li class="list-group-item">
                          Certains « cookies de préférences», permettent de mémoriser vos préférences
                        </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                  Les cookies des réseaux sociaux
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          Ils sont générés par les boutons de partage de réseaux sociaux lorsqu'ils collectent des données personnelles sans consentement des personnes concernées.
                        </li>
                        <li class="list-group-item">
                          Exemple : le bouton «J’aime» de Facebook, de Twitter ou le « +1 » de Google.
                        </li>
                        <li class="list-group-item">
                          Quand vous vous rendez sur une page internet sur laquelle se trouve un de ces boutons, le réseau social peut associer cette visualisation à votre profil. Et cela même si vous ne cliquez pas sur le bouton et si vous n'êtes pas connecté sur ce réseau ! Le réseau social peut ainsi adapter sa publicité par rapport aux sites que vous avez visités et vous proposer des " groupes " adaptés aux centres d'intérêt déduits de votre navigation sur internet. Il peut aussi vous proposer de devenir fan de la page web que vous visitez le plus.
                        </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                    Les cookies de mesure d'audience
                  </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                      <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                              Ils permettent de générer des statistiques anonymes.
                            </li>
                            <li class="list-group-item">
                              Ces informations sont analysées par nos équipes. Elles permettent de connaître l'utilisation et les performances de notre site et d'en améliorer l’intérêt et l’ergonomie des services.
                            </li>
                      </ul>
                  </div>
                </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFive">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                  Accepter ou refuser les cookies
                </button>
              </h2>
              <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                          <li class="list-group-item">
                            Il est précisé que tout utilisateur conserve la possibilité de s'opposer à l'implémentation de "cookies" sur son terminal en configurant son navigateur Internet.
                          </li>
                          <li class="list-group-item">
                            Vous trouverez à cette adresse des solutions pour gérer et désactiver les cookies liés à la publicité depuis la plateforme : youronlinechoices.com
                          </li>
                          <li>
                            Il est également précisé qu'en choisissant la désactivation des "cookies", l'utilisateur peut ne plus avoir accès à certaines sections du site voire d'utiliser certains services.
                          </li>
                    </ul>
                </div>
              </div>
          </div>

          </div>
</section>

@endsection
