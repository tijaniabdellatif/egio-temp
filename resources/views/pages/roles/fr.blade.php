@extends('v2.layouts.default')

@section('title', __("general.Règles Générales de Diffusion des Annonces (RGDA)"))

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
    dir="rtl"
@else
    dir="ltr"
@endif>
        <h2 class="mb-5">{{ __("general.Règles Générales de Diffusion des Annonces (RGDA)") }} </h2>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                  {{ __("general.1. Acceptation des Règles Générales de Diffusion des Annonces") }}
                </button>
              </h2>
              <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __(`general.1.1 La diffusion d'une petite Annonce (ci-après " Annonce ") diffusée sur le site internet MULTILIST IMMO édité par MULTILIST GROUP, implique pour l'annonceur, l'acceptation sans réserve des présentes Règles Générales de Diffusion des Annonces (RGDA) à l'exclusion expresse de toutes conditions autres ou contraires des co-contractants de MULTILIST GROUP, insérées dans leurs documents d'information, lettres, contrats, etc., reçus ou à recevoir et de tous usages professionnels contraires aux présentes, lesquels conditions et usages seront considérés comme inopposables à MULTILIST GROUP.`) }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.1.2 MULTILIST IMMO apporte un service communautaire visant à protéger au maximum les échanges équitables et honnêtes entre les utilisateurs. La diffusion d'une Annonce est sous le contrôle de l'annonceur. La communauté MULTILIST IMMO est auto-constituée sans que MULTILIST GROUP puisse être tenu responsable de la qualité et des agissements de cette communauté. Tout utilisateur, annonceur ou titulaire de droits peut signaler les Annonces qui portent atteinte à leurs droits de propriété intellectuelle en optant pour l’un des moyens de contact.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.1.3 Toute adjonction, rature, modification ou suppression qui serait portée sur les présentes devra, pour être opposable à MULTILIST GROUP, être contresignée par celle-ci.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.1.4 Le fait que MULTILIST GROUP ne se prévale pas à un moment donné de l'une quelconque des dispositions des présentes conditions générales de diffusion ne peut être interprété comme valant renonciation à s'en prévaloir ultérieurement.") }}
                        </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingTwo">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                  {{ __("general.2. Diffusion") }}
                </button>
              </h2>
              <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __("general.2.1 L'annonceur reconnaît être l'auteur unique et exclusif du texte de l'Annonce. À défaut, il déclare disposer de tous les droits et autorisations nécessaires à la parution de celle-ci.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.2.2 L'Annonce est diffusée sous la responsabilité exclusive de l'annonceur.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.2.3 L'annonceur certifie que l'Annonce est conforme à l'ensemble des dispositions légales et réglementaires en vigueur et respecte les droits des tiers. En conséquence, l'annonceur relève MULTILIST GROUP, ses sous-traitants et ses fournisseurs, de toutes responsabilités, et les garantit contre toutes condamnations, frais judiciaires et extrajudiciaires, qui résulteraient de tout recours en relation avec la diffusion de l'Annonce et les indemnise pour tout dommage résultant de la violation de la présente disposition.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.2.4 Sans préjudice de l'application de la précédente clause, et sans que cela crée à sa charge une obligation de vérifier le contenu, l'exactitude ou la cohérence de l'Annonce, MULTILIST GROUP se réserve le droit de refuser à tout moment une Annonce pour tout motif légitime, et notamment des éléments de texte (mots, expressions, phrases, etc.), qui lui semblerait contraire aux dispositions légales ou réglementaires, aux bonnes mœurs, à l'esprit de la publication, ou susceptible de troubler ou choquer les lecteurs. Un tel refus ne fait naître au profit de l'annonceur aucun droit à indemnité.") }}
                        </li>
                    </ul>

                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingThree">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                  {{ __("general.3. Limitation de responsabilité") }}
                </button>
              </h2>
              <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                          {{ __("general.3.1 Sauf dol ou faute lourde, MULTILIST GROUP, ses sous-traitants et fournisseurs ne seront tenus en aucun cas à réparation, pécuniaire ou en nature, du fait d'erreurs ou d'omissions dans la composition ou la traduction d'une annonce, ou de défaut de parution de quelque nature que ce soit. En particulier, de tels événements ne pourront en aucun cas ouvrir droit à une indemnisation sous quelque forme que ce soit.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.3.2 Ni l'annonceur, d'une part, ni MULTILIST GROUP, ses sous-traitants ou ses fournisseurs, d'autre part, ne pourront être tenus pour responsables de tout retard, inexécution ou autre manquement à leurs obligations au titre des présentes qui (1) résulterait, directement ou indirectement, d'un événement échappant à leur contrôle raisonnable, et (2) n'aurait pas pu être évité à l'aide de mesures de précaution, solutions de remplacement ou autres moyens commercialement raisonnables.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.3.3 Ni l'annonceur, d'une part, ni MULTILIST GROUP, ses sous-traitants ou ses fournisseurs, d'autre part, ne pourront être tenus pour responsable des retards ou des impossibilités de remplir leurs obligations contractuelles, liées à des destructions de matériels, aux attaques ou au piratage informatiques, à la privation, à la suppression ou à l'interdiction, temporaire ou définitive, et pour quelque cause que ce soit - dont les pannes ou indisponibilités inhérentes aux serveurs d'hébergement -, de l'accès au réseau Internet.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.3.4 MULTILIST GROUP se réserve le droit de suspendre ou d'arrêter la diffusion de MULTILIST IMMO sans être tenu de verser à l'annonceur une indemnité de quelque nature que ce soit.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.3.5 MULTILIST IMMO est un site communautaire de publication de petites annonces immobilières et en aucun cas un intermédiaire entre offreur et demandeur. En d'autres termes, la responsabilité de la société MULTILIST GROUP ne saurait être engagée directement ou indirectement dans les transactions qui obéissent aux règles générales du Code Civil. MULTILIST GROUP se réserve la possibilité de saisir toutes voies de droit à l'encontre des personnes qui n'auraient pas respecté cette interdiction.") }}
                        </li>
                        <li class="list-group-item">
                          {{ __("general.3.6 Toute réclamation, pour être recevable, doit être transmise par lettre, ou e-mail, dans un délai de quarante-huit (48) heures à compter de la date de diffusion sur le site MULTILIST IMMO.") }}
                        </li>
                    </ul>
                </div>
              </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingFour">
                  <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFour" aria-expanded="false" aria-controls="flush-collapseFour">
                    {{ __("general.4. Sont interdits sur le site MULTILIST IMMO") }}
                  </button>
                </h2>

                <div id="flush-collapseFour" class="accordion-collapse collapse" aria-labelledby="flush-headingFour" data-bs-parent="#accordionFlushExample">
                  <div class="accordion-body">
                    <p>
                        {{ __("general.Toute Annonce ou message ne respectant pas ces interdictions sera supprimé sine die, sans préavis ou autre notification de MULTILIST IMMO. Ceci concerne aussi bien les Annonces sans contrepartie financière que les Annonces payantes.") }}
                    </p>
                      <ul class="list-group list-group-flush">
                            <li>
                              {{ __("general.Annonce trop courte, imprécise utilisant un vocabulaire confus ou chargé par trop d'abréviations.") }}
                            </li>
                            <li>
                              {{ __("general.Usage de médias (photos ou vidéos) n'ayant pas de lien direct avec l'article proposé.") }}
                            </li>
                            <li>
                              {{ __("general.Annonce exprimant l'offre ou la recherche de plusieurs biens immobiliers dans une même annonce.") }}
                            </li>
                            <li>
                              {{ __("general.La diffusion d'une même annonce dans de multiples catégories ou sous-catégories.") }}
                            </li>
                            <li>
                              {{ __("general.Créer et publier plusieurs annonces dont le contenu présente des similitudes.") }}
                            </li>
                            <li>
                              {{ __("general.Annonce faisant la promotion d'un site Web à caractère privé ou professionnel.") }}
                            </li>
                            <li>
                              {{ __("general.Tenir un langage raciste, diffamatoire, abusif, pornographique, obscène.") }}
                            </li>
                            <li>
                              {{ __("general.Annonce usurpant l'identité d'une personne ou d'une marque sans son consentement.") }}
                            </li>
                            <li>
                              {{ __("general.Contenu engageant la responsabilité ou prenant l'identité de MULTILIST GROUP ou de ses collaborateurs.") }}
                            </li>
                      </ul>
                  </div>
                </div>
            </div>

            <div class="accordion-item">
              <h2 class="accordion-header" id="flush-headingFive">
                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseFive" aria-expanded="false" aria-controls="flush-collapseFive">
                  {{ __("general.5. Attribution de juridiction – Loi applicable") }}
                </button>
              </h2>

              <div id="flush-collapseFive" class="accordion-collapse collapse" aria-labelledby="flush-headingFive" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body">
                  <p>
                    {{ __("general.Toute Annonce ou message ne respectant pas ces interdictions sera supprimé sine die, sans préavis ou autre notification de MULTILIST IMMO. Ceci concerne aussi bien les Annonces sans contrepartie financière que les Annonces payantes.") }}
                    </p>
                    <ul class="list-group list-group-flush">
                          <li>
                            {{ __("general.5.1 Toutes contestations qui pourraient survenir à l'occasion de l'interprétation, de l'acceptation et de l'exécution des présentes quel que soit le lieu de souscription, ou de règlement, feront l'objet d'une tentative de règlement amiable que les parties s'engagent à rechercher. À défaut d'y parvenir dans un délai de trois (3) mois, les tribunaux de 1ère instance seront seuls compétents même en cas d'appel en garantie ou de pluralité de défendeurs, pour les procédures d'urgence ou conservatoires, en référé ou par requête.") }}
                          </li>
                          <li>
                            {{ __("general.5.2 Les présentes conditions générales sont soumises à la loi marocaine.") }}
                          </li>
                    </ul>
                </div>
              </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingSix">
              <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSix" aria-expanded="false" aria-controls="flush-collapseSix">
                {{ __("general.6. Divers") }}
              </button>
            </h2>

            <div id="flush-collapseSix" class="accordion-collapse collapse" aria-labelledby="flush-headingSix" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                  <ul class="list-group list-group-flush">
                        <li>
                          {{ __("general.6.1 Les marques et logotypes MULTILIST IMMO et MULTILIST GROUP sont déposés par MULTILIST GROUP. Sans l'accord de cette dernière, toute reproduction ou utilisation est strictement interdite.") }}
                        </li>
                        <li>
                          {{ __("general.Les Règles Générales de Diffusion des Annonces (RGDA) constituent une extension des Conditions Générales d'Utilisation du site MULTILIST IMMO.") }}
                        </li>
                        <li>
                          {{ __("general.Cette liste est non exhaustive et MULTILIST GROUP se réserve le droit de supprimer sine die sans préavis d'aucune sorte ou notification toute Annonce ou message ne respectant pas ces interdictions.") }}
                        </li>
                  </ul>
              </div>
            </div>
        </div>

          </div>
      </div>
</section>

@endsection
