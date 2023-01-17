@extends('v2.layouts.default')

@section('title', 'Informations')

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
<section class="p-1 m-1 container m-auto mt-3 w-100 mb-3" @if (Session('lang')=='ar')
    dir='rtl'
@else
    dir='ltr'
@endif>


        <h2 class="mb-5">{{ __("general.Informations légales et Conditions Générales d’Utilisation (CGU)") }}</h2>
        <h3>{{ __("general.Informations légales") }}</h3>
        <p>{{__("general.Le site Internet, ci-après dénommé « MULTILIST IMMO » est un site communautaire de petites annonces immobilières entre particuliers et professionnels.")}}</p>

        <p>{{ __("general.Le site est édité et est la propriété de MULTILIST GROUP. Pour toute question sur l'entreprise, vous pouvez nous contacter en utilisant l’un des moyens de contact.") }}</p>

        <p>{{ __("general.Les présentes Conditions Générales d'Utilisation ont pour objet de définir les termes et conditions dans lesquelles MULTILIST IMMO fournit ses Services aux Utilisateurs. Toute utilisation de l'un des Services offerts par MULTILIST IMMO, l'accès au site, sa consultation et son utilisation sont subordonnés à l'acceptation sans réserve des présentes Conditions Générales d'Utilisation. La dernière modification de ces conditions d'utilisation a eu lieu en décembre 2021") }}</p>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                    {{ __("general.1. Propriété intellectuelle") }}
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                        <p>{{ __("general.1.1. Droits d'auteur et droits voisins") }}</p>
                            <p>{{ __("general.1.1. Droits d'auteur et droits voisins-body") }}</p>
                        </li>
                        <li class="list-group-item">
                        <p>{{ __("general.1.2. Droits du producteur de base de données") }}</p>

                        {{ __("general.MULTILIST GROUP est le producteur de la base de données constituée par le site MULTILIST IMMO. Toute extraction ou utilisation du contenu de la base non expressément autorisée peut engager la responsabilité civile et/ou pénale de son auteur. MULTILIST GROUP se réserve la possibilité de saisir toutes voies de droit à l'encontre des personnes qui n'auraient pas respecté cette interdiction.") }}
                        </li>
                        <li class="list-group-item">
                        <p>{{ __("general.1.3. Droit de marque") }}</p>
                        <p>{{ __("general.La dénomination et le logotype MULTILIST IMMO sont des marques déposées, propriétés de MULTILIST GROUP. Toute utilisation non expressément autorisée peut engager la responsabilité civile et/ou pénale de son auteur. MULTILIST GROUP se réserve la possibilité d'exercer toutes voies de droit à l'encontre des personnes qui porteraient atteinte à ses droits.") }}</p>
                        </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                    {{ __("general.2. Conditions d'accès aux services") }}
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <p>{{ __("general.2.1. Conditions d'accès aux services libres") }}</p>
                            <p>{{ __("general.L'accès au Contenu Éditorial du Site MULTILIST IMMO est totalement libre et gratuit.") }}</p>
                            </li>
                        <li class="list-group-item">
                            <p>{{ __("general.2.2. Conditions d'accès aux Services Spécifiques gratuits") }}</p>

                            <p>{{ __("general.L'utilisation des Services Spécifiques suppose le respect par l'Utilisateur d'une procédure d'inscription par laquelle ce dernier doit fournir ses coordonnées. L'Utilisateur s'engage à ce que les informations communiquées, notamment les informations personnelles, soient exactes, complètes et à jour et à effectuer les modifications nécessaires à cette fin.") }}</p>
                            <p>{{ __("general.Dans le cadre de cette procédure, l'Utilisateur déclare avoir pris connaissance et avoir accepté expressément les présentes Conditions Générales d'Utilisation en vigueur au jour de la souscription aux Services Spécifiques par un clic sur l'icône intitulée 'J'accepte les Conditions Générales d'Utilisation'. Toute acceptation exprimée par l'Utilisateur par un clic vaut une signature au même titre que sa signature manuscrite. Par la réalisation de ce clic, l'Utilisateur est réputé avoir donné son accord irrévocablement.") }}</p>
                        </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                    {{ __("general.3. Protection des données personnelles") }}
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            {{ __("general.Les données à caractère personnel sont protégées par MULTILIST GROUP suivant les articles de la loi n° 09-08 du dahir n° 1-09-15 du 22 safar 1430 (18 février 2009).") }}
                        </li>
                        <li class="list-group-item">
                            {{ __("general.Vous disposez à tout moment d'un droit d'accès et de rectification des données vous concernant. Ce droit peut être exercé par courrier électronique (email) auprès de MULTILIST GROUP. Il est demandé un délai de 15 jours maximum avant la prise en compte de la demande de l'Utilisateur.") }}
                        </li>
                        <li class="list-group-item">
                            {{ __("general.MULTILIST GROUP s'engage à faire ses meilleurs efforts pour protéger les Données Personnelles, afin d'empêcher qu'elles ne soient déformées, endommagées ou communiquées à des tiers non autorisés.") }}
                        </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                    {{__("general.4. Responsabilités")}}
                  </button>
                </h2>
                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    {{ __("general.MULTILIST GROUP décline toute responsabilité :") }}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __("general.en cas d'interruption de MULTILIST IMMO pour des opérations de maintenance techniques ou d'actualisation des informations publiées,") }}
                        </li>
                        <li class="list-group listgroup-flush">
                          {{ __("general.en cas d'impossibilité momentanée d'accès à MULTILIST IMMO (et/ou aux sites lui étant liés) en raison de problèmes techniques et ce quelles qu'en soient l'origine et la provenance,") }}
                        </li>
                        <li class="list-group listgroup-flush">
                          {{ __("general.en cas de dommages directs ou indirects causés à l'utilisateur, quelle qu'en soit la nature, résultant du contenu, de l'accès, ou de l'utilisation de MULTILIST IMMO (et/ou des sites qui lui sont liés),") }}
                        </li>
                        <li class="list-group listgroup-flush">
                          {{ __("general.en cas d'utilisation anormale ou d'une exploitation illicite de MULTILIST IMMO. L'utilisateur de MULTILIST IMMO est alors seul responsable des dommages causés aux tiers et des conséquences des réclamations ou actions qui pourraient en découler. L'utilisateur renonce également à exercer tout recours contre MULTILIST GROUP dans le cas de poursuites diligentées par un tiers à son encontre du fait de l'utilisation et/ou de l'exploitation illicite du site,") }}
                        </li>
                        <li class="list-group listgroup-flush">
                          {{ __("general.en cas de perte par le membre de MULTILIST IMMO de son identifiant et/ou de son mot de passe ou en cas d'usurpation de son identité.") }}
                        </li>
                    </ul>
                  </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSix">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                    {{__("general.5. Liens hypertexte")}}
                  </button>
                </h2>
                <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {{ __("general.5.1. Liens à partir du site MULTILIST IMMO") }}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            {{ __("general.5.1 Toutes contestations qui pourraient survenir à l'occasion de l'interprétation, de l'acceptation et de l'exécution des présentes quel que soit le lieu de souscription, ou de règlement, feront l'objet d'une tentative de règlement amiable que les parties s'engagent à rechercher. À défaut d'y parvenir dans un délai de trois (3) mois, les tribunaux de 1ère instance seront seuls compétents même en cas d'appel en garantie ou de pluralité de défendeurs, pour les procédures d'urgence ou conservatoires, en référé ou par requête.") }}
                            </li
                        </ul>
                    </div>
                    <div class="accordion-body">
                            {{ __("general.5.2. Liens vers le site MULTILIST IMMO") }}
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            {{ __("general.Aucun lien hypertexte ne peut être créé vers le site MULTILIST IMMO sans l'accord préalable de MULTILIST GROUP.") }}
                            </li>
                        </ul>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            {{ __("general.Si un internaute ou une personne morale désire créer, à partir de son site, un lien hypertexte vers le site MULTILIST IMMO, ils doivent préalablement prendre contact avec MULTILIST GROUP. L'admissibilité ou non d'une telle demande sera transmise à l'intéressé.") }}
                            </li>
                        </ul>
                  </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingSeven">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven" aria-expanded="false" aria-controls="flush-collapseSeven">
                    {{__("general.6. Cookies")}}
                  </button>
                </h2>
                <div id="flush-collapseSeven" class="accordion-collapse collapse" aria-labelledby="flush-headingSeven" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {{ __("general.Afin de faciliter la navigation sur le site MULTILIST IMMO, des cookies peuvent être implantés dans votre ordinateur afin par exemple de conserver les critères de recherche. Si vous ne souhaitez pas accepter l'implantation de cookies, vous pouvez régler votre navigateur afin de les refuser. Cependant, l'utilisation de MULTILIST IMMO peut en être perturbée.") }}
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingEight">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseEight" aria-expanded="false" aria-controls="flush-collapseEight">
                    {{__("general.7. Acceptation des risques de l'internet")}}
                  </button>
                </h2>
                <div id="flush-collapseEight" class="accordion-collapse collapse" aria-labelledby="flush-headingEight" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {{ __("general.L'Utilisateur déclare bien connaître Internet, ses caractéristiques et ses limites et en particulier reconnaît :") }}

                    </div>
                    <div class="accordion-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                {{ __("general.Que l'Internet est un réseau ouvert non maîtrisable par MULTILIST IMMO et que les échanges de données circulant sur Internet ne bénéficient que d'une fiabilité relative, et ne sont protégés notamment contre les risques de détournements ou de piratages éventuels ;") }}
                            </li>
                        </ul>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            {{ __("general.- Que la communication par l'Utilisateur d'informations à caractère sensible est donc effectuée à ses risques et périls ;") }}
                            </li>
                        </ul>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            {{ __("general.- Avoir connaissance de la nature du réseau Internet et en particulier de ses performances techniques et des temps de réponse, pour consulter, interroger ou transférer les données d'informations.") }}
                            </li>
                        </ul>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                            {{ __("general.MULTILIST IMMO ne peut garantir que les informations échangées ne seront pas interceptées par des tiers, et que la confidentialité des échanges sera garantie. MULTILIST IMMO informe l'Utilisateur de l'existence de règles et d'usage en vigueur sur Internet connu sous le nom de Netiquette ainsi que de différents codes de déontologie et notamment la Charte Internet accessible sur Internet.") }}
                            </li>
                        </ul>

                  </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingNine">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseNine" aria-expanded="false" aria-controls="flush-collapseNine">
                    {{__("general.8. Modifications des Conditions Générales d'Utilisation")}}
                  </button>
                </h2>
                <div id="flush-collapseNine" class="accordion-collapse collapse" aria-labelledby="flush-headingNine" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {{ __("general.MULTILIST GROUP se réserve le droit de modifier, librement et à tout moment, les Conditions Générales d'Utilisation du site MULTILIST IMMO. Chaque internaute se connectant au site MULTILIST IMMO est invité à consulter régulièrement les présentes conditions d'utilisation afin de prendre connaissance de changements éventuels. L'utilisation renouvelée du site au fur et à mesure de la modification de ces conditions d'utilisation constitue l'acceptation, par chaque utilisateur, des Conditions Générales d'Utilisation en vigueur.") }}

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-heading9">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse9" aria-expanded="false" aria-controls="flush-collapse9">
                    {{__("general.9. Attribution de juridiction – Loi applicable")}}
                  </button>
                </h2>
                <div id="flush-collapse9" class="accordion-collapse collapse" aria-labelledby="flush-heading9" data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body">
                        {{ __("general.Toutes contestations qui pourraient survenir au sujet de la validité, de l'interprétation, de l'acceptation et de l'exécution des présentes quel que soit le lieu de souscription, ou de règlement, feront l'objet d'une tentative de règlement amiable que les parties s'engagent à rechercher. A défaut d'y parvenir dans un délai de trois (3) mois, les tribunaux de 1ère instance seront seuls compétents même en cas d'appel en garantie ou de pluralité de défendeurs, pour les procédures d'urgence ou conservatoires, en référé ou par requête.") }}
                    </div>
                    <br>
                    <div class="accordion-body">
                        {{ __("general.Les présentes conditions générales sont soumises au droit marocain, qui détermine, au cas par cas, la loi applicable. En l'absence de toute disposition impérative contraire ou en présence d'un choix dans la détermination de la loi applicable, la loi marocaine sera appliquée.") }}
                    </div>
                </div>
            </div>

        </div>
</section>
@endsection
