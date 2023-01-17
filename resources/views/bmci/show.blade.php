@extends('v2.layouts.default')

@section('title', 'BMCI')

@section('custom_head')

<link rel="stylesheet" href="{{ asset('bmci/bmci.styles.css') }}" />
<link rel="stylesheet" href="{{ asset('bmci/vendor.styles.css') }}" />

<link rel="stylesheet" href=" {{ asset('assets/css/v2/intlTelInput.css') }}">
<script class="u-script" src="{{ asset('bmci/script.min.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.15/js/intlTelInput.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="application/ld+json">{
		"@context": "http://schema.org",
		"@type": "Organization",
		"name": ""
}</script>
    <meta name="theme-color" content="#ffb8b8">
    <meta property="og:title" content="BMCI">
    <meta property="og:type" content="website">

    <style>

        #title-bmci{

          color:#1C9D64;
          font-weight: 800;
          letter-spacing: 3px;

        }

        #desc-bmci {

           letter-spacing: 3px;
           color:#d2d2d2;
           font-weight: 600;
           font-size: 30px;
           opacity: 0.8;

        }

        .iti {

          width:100% !important;
        }

        #partner{
          color:#1C9D64;
          font-weight: 800;
          letter-spacing: 3px;
          text-align: center;

        }

        .conditions-item{

          width: 80%;
         margin: auto;
        text-align: center;
        font-size: 10px;
        margin-bottom: 5px;
         color: gray;
        }

        #checking{

            display:flex;
            justify-content: space-evenly;


        }

        .checking-content{


         margin: auto;
        text-align: center;
        font-size: 15px;
         margin-bottom: 5px;
         color: gray;
        }

        .mylabel{

          display: flex;
          align-items: center;
           border-radius: 8px;
            padding: 8px;
          cursor: pointer;
        }

        .mylabel:hover{

          background-color: #dfdfdf;
        }

        #accept{

          color:#FD7A0B;
          font-weight: 400;
          text-decoration: underline;
          cursor:pointer;
          transition: all 0.2s ease;
        }

        #accept:hover{

          opacity:0.4;
        }

    </style>
@endsection



@section('content')

<section class="u-clearfix u-container-align-center u-image u-section-1" id="sec-2db0" data-image-width="400" data-image-height="265">
      <div class="u-clearfix u-sheet u-sheet-1">
        <h2 id='title-bmci' class="u-align-center u-text u-text-default u-text-1">BMCI</h2>
        <p  id='desc-bmci' class="u-align-center u-text u-text-default u-text-2">Expert Crédit Immobilier</p>
      </div>
    </section>
    <section class="u-align-center u-clearfix u-section-2" id="bmci">
      <div class="u-clearfix u-sheet u-sheet-1">
        <div class="u-clearfix u-expanded-width u-layout-wrap u-layout-wrap-1">
          <div class="u-layout">
            <div class="u-layout-row">
              <div class="u-align-left u-container-style u-layout-cell u-size-30 u-layout-cell-1">
                <div class="u-container-layout u-container-layout-1">
                  <h2 class="u-align-left u-custom-font u-font-montserrat u-text u-text-default u-text-1" data-lang-en="​Get in tou​ch!"> Contact</h2>
                  <div class="u-expanded-width u-form u-form-1">
                    <form action="https://forms.nicepagesrv.com/Form/Process" class="u-clearfix u-form-spacing-50 u-form-vertical u-inner-form" source="email" name="form" style="padding: 0px;">
                      <div class="u-form-group u-form-name u-label-none">
                        <label class="u-label" data-lang-en="Name">Name</label>
                        <input type="text" placeholder="Entrez votre nom complet" id="name-2ee9" name="name" class="u-border-1 u-border-black u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" required="" data-lang-en="Enter your Name">
                      </div>
                      <div class="u-form-group u-label-none u-form-group-2">
                        <label for="text-a32d" class="u-label">Telephobe</label>
                        <input id="phone" type="tel" placeholder="Saisissez votre numéro de téléphone : 6XX.XX.XX.XX" id="text-a32d" name="tel" class="u-border-1 u-border-black u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" required="required">
                      </div>
                      <div class="u-form-email u-form-group u-label-none">
                        <label class="u-label" data-lang-en="Email">Email</label>
                        <input type="email" placeholder="exemple@xxx.com" id="email-2ee9" name="email" class="u-border-1 u-border-black u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" required data-lang-en="Enter a valid email address">
                      </div>
                      <div class="u-form-group u-form-message u-label-none">
                        <label class="u-label" data-lang-en="Message">Message</label>
                        <textarea placeholder="Précisez la nature de votre message" id="message-2ee9" name="message" class="u-border-1 u-border-black u-border-no-left u-border-no-right u-border-no-top u-input u-input-rectangle" required="" data-lang-en="Enter your message"></textarea>
                      </div>


                      <div class="u-form-group u-form-message u-label-none">
                        <div class="conditions-item">
                          Conformément à la loi 09-08, vous disposez d’un droit d’accès, de rectification et d’opposition au traitement de vos données personnelles. Ce traitement a fait l’objet d’une demande d’autorisation auprès de la CNDP.

                          </div>
                      </div>

                      <div id="checking" class="u-form-group u-form-message u-label-none">
                         <div class="checking">
                          <label class="mylabel"><input type="checkbox" /></label>
                         </div>
                         <div class="checking-content">
                          Je souhaite être contacté par la <span style="color:#1C9D64;">BMCI</span> et accepte de recevoir des
                          <a id="accept" @click="accept()">offres commerciales</a>
                         </div>
                      </div>


                      <div class="u-align-center u-form-group u-form-submit u-label-none">
                        <a href="#" class="u-border-none u-btn u-btn-submit u-button-style u-palette-2-base u-btn-1" data-lang-en="{&quot;content&quot;:&quot;Valider&quot;,&quot;href&quot;:&quot;#&quot;}">Valider</a>
                        <input type="submit" value="Valider" class="u-form-control-hidden">
                      </div>
                      {{-- <div class="u-form-send-message u-form-send-success" data-lang-en="
            Thank you! Your message has been sent.
        "> Thank you! Your message has been sent. </div>
                      <div class="u-form-send-error u-form-send-message" data-lang-en="
            Unable to send your message. Please fix errors then try again.
        "> Unable to send your message. Please fix errors then try again. </div>
                      <input type="hidden" value="" name="recaptchaResponse">
                      <input type="hidden" name="formServices" value="08a7707d9eb313d95b0580372b711405"> --}}
                    </form>
                  </div>
                </div>
              </div>
              <div class="u-container-style u-grey-5 u-layout-cell u-size-30 u-layout-cell-2">
                <div class="u-container-layout u-valign-top u-container-layout-2">
                  <h2 id="partner" class="u-custom-font u-font-montserrat u-text u-text-default u-text-2">BMCI  - Votre partenaire financier
                  </h2>
                  <div class="u-accordion u-expanded-width u-faq u-spacing-20 u-accordion-1">
                    <div class="u-accordion-item">
                      <a class="active u-accordion-link u-button-style u-text-active-palette-2-base u-text-body-color u-accordion-link-1" id="link-" data-lang-en="
            <span class=&quot;u-accordion-link-text&quot;>​Phasellus sed efficitur dolor?</span>
        <span class=&quot;u-accordion-link-icon u-file-icon u-icon u-icon-1&quot; style=&quot;height: 14px; width: 14px&quot;><img src=&quot;images/271210.png&quot; alt=&quot;&quot; data-original-src=&quot;images/271210.png&quot; data-color=&quot;#000000&quot;></span>">
                        <span class="u-accordion-link-text"> Phasellus sed efficitur dolor?</span><span class="u-accordion-link-icon u-file-icon u-icon u-icon-1"><img src="{{ asset('images/bmci.png') }}" alt=""></span>
                      </a>
                      <div class="u-accordion-active u-accordion-pane u-container-style u-accordion-pane-1" aria-labelledby="link-">
                        <div class="u-container-layout u-container-layout-3">
                          <div class="fr-view u-clearfix u-rich-text u-text">
                            <p data-lang-en="Answer. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur id suscipit ex. Suspendisse rhoncus laoreet purus quis elementum. Phasellus sed efficitur dolor, et ultricies sapien. Quisque fringilla sit amet dolor commodo efficitur. Aliquam et sem odio. In ullamcorper nisi nunc, et molestie ipsum iaculis sit amet.">
                              Que vous soyez en recherche active d’une propriété ou d’un terrain, d’un partenaire financier ou simplement à l’écoute du marché immobilier, les Conseillers <span style="color:#1C9D64;">BMCI</span> répondent à vos questions, vous orientent et vous proposent les solutions adaptées à votre situation
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="u-accordion-item">
                      <a class="u-accordion-link u-button-style u-text-active-palette-2-base u-text-body-color u-accordion-link-2" id="link-" data-lang-en="
            <span class=&quot;u-accordion-link-text&quot;>​Quisque fringilla sit amet dolor?</span>
        <span class=&quot;u-accordion-link-icon u-file-icon u-icon u-icon-2&quot; style=&quot;height: 14px; width: 14px&quot;><img src=&quot;images/271210.png&quot; alt=&quot;&quot; data-original-src=&quot;images/271210.png&quot; data-color=&quot;#000000&quot;></span>">
                        <span class="u-accordion-link-text"> Quisque fringilla sit amet dolor?</span><span class="u-accordion-link-icon u-file-icon u-icon u-icon-2"><img src="{{ asset('images/bmci.png') }}" alt=""></span>
                      </a>
                      <div class="u-accordion-pane u-container-style u-accordion-pane-2" aria-labelledby="link-">
                        <div class="u-container-layout u-container-layout-4">
                          <div class="fr-view u-clearfix u-rich-text u-text">
                            <p data-lang-en="Answer. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur id suscipit ex. Suspendisse rhoncus laoreet purus quis elementum. Phasellus sed efficitur dolor, et ultricies sapien. Quisque fringilla sit amet dolor commodo efficitur. Aliquam et sem odio. In ullamcorper nisi nunc, et molestie ipsum iaculis sit amet.">
                              Découvrez vite les solutions de financement de la <span style="color:#1C9D64;">BMCI</span> et profitez d’une offre adaptée répondant à vos besoins !
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="u-accordion-item">
                      <a class="u-accordion-link u-button-style u-text-active-palette-2-base u-text-body-color u-accordion-link-3" id="link-" data-lang-en="
            <span class=&quot;u-accordion-link-text&quot;>​Aliquam et sem odio?</span>
        <span class=&quot;u-accordion-link-icon u-file-icon u-icon u-icon-3&quot; style=&quot;height: 14px; width: 14px&quot;><img src=&quot;images/271210.png&quot; alt=&quot;&quot; data-original-src=&quot;images/271210.png&quot; data-color=&quot;#000000&quot;></span>">
                        <span class="u-accordion-link-text"> Aliquam et sem odio?</span><span class="u-accordion-link-icon u-file-icon u-icon u-icon-3"><img src="{{ asset('images/bmci.png') }}" alt=""></span>
                      </a>
                      <div class="u-accordion-pane u-container-style u-accordion-pane-3" aria-labelledby="link-">
                        <div class="u-container-layout u-container-layout-5">
                          <div class="u-clearfix u-rich-text u-text">
                            <p data-lang-en="Answer. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur id suscipit ex. Suspendisse rhoncus laoreet purus quis elementum. Phasellus sed efficitur dolor, et ultricies sapien. Quisque fringilla sit amet dolor commodo efficitur. Aliquam et sem odio. In ullamcorper nisi nunc, et molestie ipsum iaculis sit amet.">
                              Nos experts sont joignables à tout moment et vous recontacteront dans les plus brefs délais.
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

@endsection


@section('custom_foot')

<script>

    let bmci = createApp({

         data(){

            return {


            }
         },

         methods:{

           accept(){

            Swal.fire({
            title: " Note d'information",
            icon: 'info',
            html:
              '<p>Les offres commerciales pourront se faire à travers tout moyen (Appel Téléphonique, SMS,...), dans le cadre des opérations marketing de la <span style="color:#1C9D64;">BMCI</span> </p>',
            showCloseButton: true,
            showCancelButton: true,
            focusConfirm: false,
            confirmButtonText:
              '<i class="fa fa-thumbs-up"></i> Accepter ',
            confirmButtonAriaLabel: 'Thumbs up, Accepter!',
            cancelButtonText:
              '<i class="fa fa-thumbs-down"></i>',
            cancelButtonAriaLabel: 'Decliner'
          })
           }
         },

         mounted(){

          var input = document.querySelector("#phone");
                window.intlTelInput(input, {
                    allowDropdown: true,
                    initialCountry: 'MA',
                    excludeCountries: ['EH'],
                    utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js',
                    preferredCountries: ['MA', 'FR', 'BE', 'UK', 'US', 'AE', 'CA', 'NL', 'ES', 'DE', 'IT',
                        'GB', 'CH', 'CI', 'SN', 'DZ', 'MR', 'TN', 'PT', 'TR', 'SA', 'SE', 'GA', 'LU',
                        'QA', 'IN', 'NO', 'GN', 'CG', 'ML', 'EG', 'IL', 'IE', 'RO', 'RE', 'CM', 'DK',
                        'HU'
                    ],

                              });

                var iti = window.intlTelInputGlobals.getInstance(input);

                input.addEventListener('input', function() {
                    var fullNumber = iti.getNumber();
                    document.getElementById('phone').value = fullNumber;
                });
         }
    }).mount('#bmci')
</script>

@endsection
