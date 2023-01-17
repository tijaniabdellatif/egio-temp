
@extends('v2.layouts.default')

@section('title', 'Qui sommes nous | Multilist')

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
  <h2 class="mb-5">Qui sommes nous </h2>
  <div class="accordion accordion-flush" id="accordionFlushExample">
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingOne">
          <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
            Découvrez Multilist, votre premier moteur de recherche de l'immobilier au Maroc
          </button>
        </h2>
        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">

              <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    Multilist est né de la volonté de proposer à tous les acteurs de l’immobilier au Maroc une plateforme digitale globalisée afin de leur offrir un environnement complet pour la promotion de leurs projets et biens immobiliers.
                  </li>
                  <li class="list-group-item">
                    Ayant réussi à se frayer un chemin au sein du marché immobilier marocain, Multilist décide ainsi, en octobre 2021, de lancer le premier moteur de recherche de l’immobilier au Maroc, sous le nom de “multilist.immo”.
                  </li>
              </ul>

          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingTwo">
          <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
            La promesse d’une plateforme immobilière complète
          </button>
        </h2>
        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
              <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    Multilist a élargi ses ambitions avec le développement d’une offre complète et alignée avec les attentes de tout un chacun.
                  </li>
                  <li class="list-group-item">
                    Déployant une palette diversifiée d’univers : BookList, HomeList, PrimeList, Landlist et Officelist, le moteur de recherche “multilist.immo” répond aussi bien aux besoins des particuliers que professionnels, et ce, qu’il s’agisse d’achat, de vente ou de location de biens immobiliers.
                  </li>
                  <li class="list-group-item">
                    Location saisonnière, biens à usage d’habitation (neufs ou deuxième main), bureaux ou encore terrains, tant de choix sont mis entre les mains des offreurs et demandeurs de l’immobilier au Maroc.
                  </li>
                  <li class="list-group-item">
                    Aussi et grâce à son expertise reconnue dans le domaine, le groupe ne ménage pas ses efforts pour vous proposer un accompagnement personnalisé, englobant online et offline.
                  </li>

              </ul>

          </div>
        </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingThree">
          <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
            Une identité et des valeurs s'alignant avec le marché immobilier marocain
          </button>
        </h2>
        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
              <ul class="list-group list-group-flush">
                  <li class="list-group-item">
                    Multilist met la satisfaction des acteurs du secteur de l’immobilier au cœur de ses préoccupations. Dans ce sens, le groupe allie plusieurs valeurs afin de se créer et de maintenir une identité digne de la confiance de ses clients.
                  </li>
                  <li class="list-group-item">
                    Bienveillance, efficacité, proximité, diversification spécialisée et marocanité, c’est ce qui fait de ce groupe 100% marocain votre partenaire privilégié pour réussir vos projets immobiliers en toute sérénité.

                  </li>
              </ul>
          </div>
        </div>
      </div>
      <div class="accordion-item">
          <h2 class="accordion-header" id="flush-headingFour">
            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
              L'ambition de viser un plus grand nombre de visiteurs avec un ciblage pointu
            </button>
          </h2>
          <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
                <ul class="list-group list-group-flush">
                      <li class="list-group-item">
                        En vue de rapprocher le plus possible les offreurs et demandeurs des biens immobiliers au Maroc, Multilist a pour objectif de mobiliser tous les moyens permettant une génération de trafic ciblé et segmenté.
                      </li>
                      <li class="list-group-item">
                        Dans cette optique, le groupe s’efforce toujours d’optimiser l’acquisition des visiteurs de sa plateforme pour faire d’elle la référence de tout particulier ou professionnel souhaitant acheter, louer ou vendre plus rapidement un quelconque bien immobilier.
                      </li>
                </ul>
            </div>
          </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingFive">
          <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
            Une panoplie d’offres variées au service de vos projets immobiliers
          </button>
        </h2>
        <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
              <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      Afin de répondre aux attentes des professionnels de l’immobilier et être à la hauteur de leurs espérances, Multilist a mis en place différents abonnements pour que chacun puisse mettre en avant ses biens immobiliers selon ses besoins, sa stratégie et son budget.
                    </li>
                    <li class="list-group-item">
                      Pour plus d’informations sur le groupe ou sur ses services, prenez contact dès maintenant.

                    </li>
              </ul>
          </div>
        </div>
    </div>

    </div>
</section>
@endsection
