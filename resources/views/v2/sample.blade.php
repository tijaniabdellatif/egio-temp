<div class="loader" data-item-id="false"></div>
 <div class="result"></div>

<div class="multisteps-form my-5 pt-5">
    <h1 class="text-center mb-3">Tout commence par une bonne estimation</h1>

    <!--progress bar-->
    <div class="row">
      <div class="col-12 col-lg-8 m-auto mb-5 py-2">
        <div class="multisteps-form__progress">
          <button class="multisteps-form__progress-btn js-active" type="button" title="User Info">
            <i class="multilist-icons multilist-location me-1"></i>
            Géolocalisation
        </button>
          <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-regular fa-building me-1"></i>
            Type
          </button>
          <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-solid fa-house-circle-check me-1"></i>
            Informations
          </button>
        <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-solid fa-list-check me-1"></i>
            Caractéristiques
        </button>
          <button class="multisteps-form__progress-btn" type="button" title="">
            <i class="fa-solid fa-circle-check me-1"></i>
            Résultat
          </button>
        </div>
      </div>
    </div>
    <!--form panels-->
    <div class="row">
      <div class="col-12 col-lg-8 m-auto">
        <form class="multisteps-form__form">
          <!--single form panel-->
          <div class="multisteps-form__panel shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
            <div class="title_holder">
                <h3 class="multisteps-form__title">{{ ucfirst('adresse '.ucfirst('du bien').' '.ucfirst('immobilier')) }}</h3>
            </div>

            <div class="multisteps-form__content">

            <div class="holder">
                <div class="form-row mt-4">
                    <div class="col-12 col-sm-6 mb-3">
                        <h5>Selectioner la ville</h5>
                        <select value="" id="select1" class="w-100 mb-3" name="region">
                            <option selected="selected" disabled>Selectioner la region</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 mb-3">
                        <h5>Selectioner le quartier</h5>
                        <select id="select2" class="w-100 mb-3" name="commune">
                            <option value="" selected="selected" disabled>Selectioner la commune</option>
                        </select>
                    </div>
                  </div>

                  <div class="form-row mt-4 grasp">
                     <div class="grasp-content">
                                <p>
                                    Il faut garder à l’esprit que votre logement a
                                    une valeur de marché, qui ne correspond pas toujours au prix auquel
                                    vous l’avez acheté. En effet, cette valeur dépend de
                                     l’évolution des prix de l’immobilier, de l’offre et
                                      de la demande au moment de la mise en vente.
                                </p>
                     </div>
                  </div>
            </div>


              <div class="button-row d-flex mt-4">
                <button disabled="true" id="firestStep" class="btn btn-primary ml-auto js-btn-next stepper-btn" type="button" title="Suivant">
                    Suivant
                    <i class="fa-solid fa-angle-right"></i>
                </button>
              </div>
            </div>
          </div>
          <!--single form panel-->

          <!--single form panel-->

          <!--single form panel-->

        <!--single form panel-->

        </form>
       </div>
    </div>
  </div>
