@extends('v2.layouts.default')

@section('title', __("general.Données personnelles"))

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


<section @if (Session('lang')=='ar')
    dir="rtl"
@else
    dir="ltr"
@endif class="p-1 m-1 container m-auto mt-3 w-100 mb-3 animate__animated animate__zoomIn my-5">
        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  {{ __('general.Vos données personnelles') }}
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __("general.MULTILIST IMMO respecte votre vie privée et nous entendons communiquer en toute transparence sur les différents types de données à caractère personnel que nous collectons à votre sujet ainsi que sur l’utilisation que nous en faisons. Dans la présente Note relative au respect de la vie privée, nous expliquons comment nous partageons et utilisons les données à caractère personnel que nous collectons quand vous visitez une plateforme Web, et comment vous pouvez exercer vos droits au respect de la vie privée.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.Le site Web que vous visitez est géré par la société MULTILIST, qui est le responsable du traitement de toutes les données à caractère personnel collectées via un site Web.") }}
                        </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  {{ __("general.Types de données à caractère personnel que nous collectons et pourquoi nous les collectons") }}
                </button>
              </h2>

              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p class="mx-2">{{ __("general.Nous collectons divers types de données à caractère personnel sur notre site Web. Les informations personnelles que nous pouvons recueillir à votre sujet relèvent généralement des catégories suivantes :") }}</p>
                  <p class="mx-2">{{ __("general.Il se peut que dans certaines parties de notre site Web vous soyez invité à fournir volontairement des informations personnelles, notamment :") }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                        </li>
                        <li class="list-group-item">
                          {{ __("general.vos coordonnées professionnelles") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.vos informations de localisation") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.vos liens vers vos réseaux sociaux ou autres sites web vous appartenant") }}
                        </li>
                    </ul>

                    <p>{{ __("general.Les données à caractère personnel que vous êtes invité à fournir, et les raisons pour lesquelles vous y êtes invité vous seront clairement indiquées au moment où nous vous demanderons de les fournir.") }}</p>
                    <p>{{ __("general.Quand vous visitez notre site Web, nous pouvons collecter certaines informations automatiquement à partir de votre appareil. Dans certains pays, y compris dans les pays de l’Espace économique européen, ces informations peuvent être considérées comme des données à caractère personnel en vertu des lois applicables sur la protection des données.") }}</p>
                    <p>{{ __("general.Plus précisément, les informations que nous collectons automatiquement peuvent inclure des informations telles que votre adresse IP, le type d’appareil, vos numéros d’identification uniques, le type de navigateur, la localisation géographique générale (par ex. pays ou ville) et d’autres informations techniques. Nous pouvons également collecter des informations sur la manière dont votre appareil a interagi avec notre site Web, notamment les pages consultées et les liens sur lesquels vous avez cliqué.") }}                   </p>
                    <p>{{ __("general.La collecte de ces informations nous permet de mieux comprendre qui sont les visiteurs qui visitent notre site Web, d'où ils viennent, et quel contenu de notre site Web les intéresse. Nous utilisons ces informations à des fins d’analyses internes, ainsi que pour améliorer la qualité et la pertinence de notre site Web auprès de nos visiteurs.") }}</p>
                    <p>{{ __("general.Nous utilisons des cookies et une technologie de traçage similaire (collectivement les « Cookies ») pour collecter et utiliser les données à caractère personnel vous concernant. Pour plus d’informations sur les types de Cookies que nous utilisons, pourquoi et comment vous pouvez contrôler ces Cookies, nous vous invitons à consulter notre Note d’information sur les Cookies.") }}</p>
                    <p>{{ __("general.Nous utilisons des cookies et une technologie de traçage similaire (collectivement les « Cookies ») pour collecter et utiliser les données à caractère personnel vous concernant. Pour plus d’informations sur les types de Cookies que nous utilisons, pourquoi et comment vous pouvez contrôler ces Cookies, nous vous invitons à consulter notre Note d’information sur les Cookies.") }}</p>
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                  {{ __("general.À quelles fins utilisons-nous vos données à caractère personnel ?") }}
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>{{ __('general.Nous utilisons les données à caractère personnel que nous recueillons auprès de vous aux fins suivantes :') }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __("general.créer et gérer votre compte sur notre site Web ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.communiquer efficacement avec vous, en particulier par emails") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.administrer et surveiller la stabilité et la performance de notre site Web afin de l’améliorer ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.veiller à la pertinence du contenu de notre site Web à votre égard et nous assurer qu’il vous est présenté de la manière la plus efficace ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.vous envoyer des informations ou des promotions sur nos produits et les services proposés ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.vous inscrire à notre newsletter ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.comprendre vos préférences et améliorer votre expérience et votre satisfaction de client lorsque vous visitez notre site et nos services ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.effectuer des analyses statistiques sur l’utilisation de ce site Web et la fréquence des visites ;") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.que nous vous indiquerons au moment où nous recueillons vos données à caractère personnel.") }}                        </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                    {{ __("general.Avec qui partageons-nous vos données à caractère personnel ?") }}
                  </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <p>{{ __("general.Nous pouvons divulguer vos données à caractère personnel aux catégories de destinataires suivantes :") }}</p>
                      <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                              {{ __("general.à des prestataires de services extérieurs qui nous fournissent des prestations de traitement des données (par exemple, fournir des fonctionnalités, ou aider à améliorer la sécurité de notre site Web), ou qui traitent vos données à caractère personnel d’une autre manière à des fins décrites dans la présente") }}
                            </li>
                            <li class="list-group-item">
                              {{ __("general.à un organisme d’application de la loi compétent, un organisme de contrôle, un organisme gouvernemental, un tribunal ou à un autre tiers, lorsque nous considérons que cette divulgation est nécessaire (i) pour respecter les lois ou règlements applicables, (ii) pour exercer, établir ou défendre nos droits légaux, ou, (iii) pour protéger vos intérêts vitaux ou ceux de toute autre personne ;") }}
                            </li>
                            <li class="list-group-item">
                              {{ __("general.à toute autre personne avec votre consentement à la divulgation.") }}
                            </li>
                      </ul>
                  </div>
                </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFive">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                  {{ __("general.Durée de conservation de vos données à caractère personnel") }}
                </button>
              </h2>
              <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item">
                        {{ __("general.Nous conservons vos données à caractère personnel aussi longtemps que nécessaire pour nous permettre de respecter les objectifs décrits dans la présente Note d’information et tant que nous avons un besoin légitime de le faire (par exemple pour vous fournir un service que vous avez demandé).") }}
                      </li>
                      <li class="list-group-item">
                        {{ __("general.Lorsque la conservation de vos données est devenue sans objet, ou lorsque la durée de conservation a expiré, ou si vous nous demandez de supprimer vos données à caractère personnel, il se peut que nous continuions à stocker ces données à caractère personnel pendant une durée limitée si nous sommes tenus de respecter des exigences légales, fiscales ou comptables.") }}
                      </li>
                      <li class="list-group-item">
                        {{ __("general.Lorsque nous n’aurons plus de raison de traiter vos données à caractère personnel, nous nous assurerons qu’elles sont supprimées ou anonymisées.") }}
                      </li>
                    </ul>
                </div>
              </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingSix">
              <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                {{ __("general.Comment protégeons-nous vos données à caractère personnel ?") }}
              </button>
            </h2>
            <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                  <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __("general.Nous nous engageons à assurer la protection de vos données à caractère personnel. Nous utilisons de solides mesures de protection pour protéger la confidentialité et la sécurité des données à caractère personnel sur notre site Web, en employant des mesures de sécurité technologiques, physiques et administratives, par exemple, lorsque vous entrez des informations confidentielles (telles que vos identifiants de connexion ou des informations soumises à partir du site Web). Nous cryptons la transmission de ces informations en utilisant la technologie Secure Socket Layer (SSL). Ces technologies, procédures et autres mesures sont utilisées pour veiller à ce que vos données à caractère personnel restent sûres et en sécurité, et qu’elles soient uniquement mises à votre disposition et à celle des personnes que vous avez autorisées à y accéder. Toutefois, aucune transmission par Internet, par e-mail ou autre transmission électronique n’est jamais totalement sécurisée ou exempte d’erreurs. Vous devez donc prendre soin de décider quelles informations vous nous transmettez par ce biais.") }}
                        </li>
                  </ul>
              </div>
            </div>
        </div>
        <div class="accordion-item">
          <h2 class="accordion-header" id="flush-headingSeven">
            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
              {{ __("general.Vos droits") }}
            </button>
          </h2>
          <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
            <div class="accordion-body">
                <ul class="list-group list-group-flush">
                      <li class="list-group-item">
                        {{ __("general.Les services que nous fournissons sur ce site Web sont accessibles à toute personne sauf explicitement indiqué lors de la première visite par une demande de consentement") }}
                      </li>
                </ul>
            </div>
          </div>
      </div>
      <div class="accordion-item">
        <h2 class="accordion-header" id="flush-headingNine">
          <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine">
            {{ __("general.Quels sont vos droits en matière de confidentialité des données et comment les exercer ?") }}
          </button>
        </h2>
        <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
          <div class="accordion-body">
            <h4>{{ __("general.Vous disposez des droits suivants en vertu des lois applicables à la protection des données :") }}  </h4>
              <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                      {{ __("general.Vous pouvez demander à accéder à vos données à caractère personnel et en obtenir une copie.") }}
                    </li>
                    <li class="list-group-item">
                      {{ __("general.Vous pouvez également demander que toute information personnelle inexacte ou incomplète soit rectifiée ou complétée. Il est rappelé que vous avez accès en ligne à toute modification de votre profil") }}
                    </li>
                    <li class="list-group-item">
                      {{ __("general.En outre, vous pouvez vous opposer au traitement de vos données à caractère personnel, nous demander d’en limiter le traitement ou demander la portabilité de vos données à caractère personnel dans certaines conditions légales.") }}
                    </li>
                    <li class="list-group-item">
                      {{ __("general.Vous avez le droit de vous désinscrire à tout moment et sans frais des communications marketing électroniques que nous vous envoyons. Vous pouvez exercer ce droit en cliquant sur le lien « désabonnement » ou « désinscription » dans les e-mails marketing que nous vous envoyons. Pour refuser d’autres formes de marketing (comme le marketing postal ou le télémarketing), veuillez nous contacter en utilisant les coordonnées indiquées dans la section « Nous contacter »") }}
                    </li>
                    <li class="list-group-item">
                      {{ __("general.De la même manière, si nous collectons et traitons vos données à caractère personnel avec votre consentement, vous pouvez retirer votre consentement à tout moment. Le fait de retirer votre consentement n’affectera pas la légalité de tout traitement réalisé avant votre retrait sur des bases légales autres que le consentement.") }}
                    </li>
                    <li class="list-group-item">
                      {{ __("general.Vous pouvez demander à accéder à vos données à caractère personnel et en obtenir une copie.") }}
                    </li>
                    <li class="list-group-item">
                      {{ __("general.Vous avez la possibilité de formuler une réclamation sur la collecte et l’utilisation que nous avons faites de vos données à caractère personnel auprès d’une autorité chargée de la protection des données.") }}
                    </li>
              </ul>
          </div>
        </div>
    </div>

    </div>
  </section>
@endsection
