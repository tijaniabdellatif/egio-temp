<div class="cookie-consent-banner cookies-consent js-cookie-consent animate__animated animate__bounceInDown animate__delay-1s" @if (Session('lang')=='ar')
    dir="rtl"
@else
    dir="ltr"
@endif>

    <div class="cookie-consent-banner__header">
        <h3>{!! trans('cookieConsent::texts.title') !!}</h3>

    </div>
    <div class="cookie-consent-banner__inner">
      <div class="cookie-consent-banner__copy">
        <div class="cookie-consent-banner__description ">
            <h6 class="cookie-consent__message">
                {!! trans('cookieConsent::texts.message') !!}
            </h6>

            </div>
      </div>

      <div class="cookie-consent-banner__actions">
        <a href="javascript:void(0)" class="cookie-consent-banner__cta js-cookie-consent-agree cookie-consent__agree">
            {{ Str::upper(trans('cookieConsent::texts.agree')) }}
        </a>

        <a href="/privacy"  target="_blank" class="cookie-consent-banner__cta cookie-consent-banner__cta--secondary">
          {!! trans('cookieConsent::texts.plus') !!}
        </a>
      </div>
    </div>
</div>

