<!DOCTYPE html>
<html
  lang="en"
  xmlns="http://www.w3.org/1999/xhtml"
  xmlns:o="urn:schemas-microsoft-com:office:office"
>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="x-apple-disable-message-reformatting" />

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;600;800;900&display=swap"
    rel="stylesheet">

    <!--[if mso]>
      <style>
        table {
          border-collapse: collapse;
          border-spacing: 0;
          border: none;
          margin: 0;
        }
        div,
        td {
          padding: 0;
        }
        div {
          margin: 0 !important;
        }
      </style>
      <noscript>
        <xml>
          <o:OfficeDocumentSettings>
            <o:PixelsPerInch>96</o:PixelsPerInch>
          </o:OfficeDocumentSettings>
        </xml>
      </noscript>
    <![endif]-->
    <style>
      table,
      td,
      div,
      h1,
      p {
        font-family: Montserrat, Arial, sans-serif;
      }
      @media screen and (max-width: 530px) {
        .unsub {
          display: block;
          padding: 8px;
          margin-top: 14px;
          border-radius: 6px;
          background-color: #ffffff;
          text-decoration: none !important;
          font-weight: bold;
        }
        .col-lge {
          max-width: 100% !important;
        }
      }
      @media screen and (min-width: 531px) {
        .col-sml {
          max-width: 27% !important;
        }
        .col-lge {
          max-width: 73% !important;
        }
      }
    </style>
  </head>
  <body
    style="
      margin: 0;
      padding: 0;
      word-spacing: normal;
      background-color: #ffffff;
    "
  >
    <div
      role="article"
      aria-roledescription="email"
      lang="en"
      style="
        text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
        -ms-text-size-adjust: 100%;
        background-color: #ffffff;

      "
    >
      <table
        role="presentation"
        style="width: 100%; border-spacing: 0;"
      >
        <tr>
          <td align="center" style="padding: 0">
            <!--[if mso]>
          <table role="presentation" align="center" style="width:600px;">
          <tr>
          <td>
          <![endif]-->
            <table
              role="presentation"
              style="
                width: 100%;
                max-width: 600px;
                text-align: left;
                font-family: Arial, sans-serif;
                font-size: 16px;
                line-height: 22px;
                color: #363636;
                border: 4px groove #706f6f0f;
                border-radius : 10px;
                font-family: Montserrat, Arial;
              "
            >
              <tr>
                <td
                  style="

                    text-align: center;
                    font-size: 24px;
                    font-weight: bold;
                  "
                >
                  <img
                    src="{{ asset("images/Header.png") }}"
                    width="100%"
                    alt="Logo"
                    style="
                      width: 100%;
                      height: auto;
                      border: none;
                      text-decoration: none;
                      color: #ffffff;
                    "
                    class="manip"
                  />
                </td>
              </tr>
              <tr>
                <td style="padding: 30px; background-color: #ffffff">
                  <h1
                    style="
                      margin-top: 0;
                      margin-bottom: 16px;
                      font-size: 26px;
                      line-height: 32px;
                      font-weight: bold;
                      letter-spacing: -0.02em;
                      text-align: center;
                      color:#4CC6D6;
                    "
                  >
                    Bonjour {{ $username }}
                  </h1>
                  <p style="margin: 0; text-align: center; line-height: 35px;
                  ">
                  Vous avez une une nouvelle réservation pour votre bien !
                  </p>
                </td>
              </tr>
              <tr>
                <td
                  style="
                    padding: 30px;
                    font-size: 24px;
                    line-height: 28px;
                    font-weight: bold;
                    background-color: #ffffff;
                    border-bottom: 1px solid #f0f0f5;
                    border-color: rgba(201, 201, 207, 0.35);
                    text-align:center;
                  "
                >

                      <div style=" display: inline-block;
                      height: 350px;
                      width: 300px;
                      padding: 10px;
                      overflow: hidden;
                      border: groove 4px #706f6f0f;
                      border-radius: 10px;
                      position: relative;">
                        <div class="img" style="width: 100%;
                        height: 60%;
                        background: url('http://multilist.immolist.co/storage/{{ $image }}') !important;
                        background-size: cover !important;
                        background-position: center !important;
                        background-repeat: no-repeat !important;
                        border : solid 2px groove #706f6f0f;
                        border-radius: 5px;"></div>

                        <div style=" display: inline-block;
                        width: 100%;
                        height: 40%;
                        padding: 10px;">
                            <div class="title" style="margin-bottom: 2px;  font-weight: 600;
                            color: #000;
                            text-transform: uppercase;
                            width: calc(100% - 30px);">
                                <h1 style="font-size: 12px;
                                text-transform: uppercase;
                                font-weight: 600;
                                box-sizing: border-box;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                overflow: hidden;
                                -webkit-box-orient: vertical;
                                text-overflow: ellipsis;
                                color: #373736;">
                                    {{ $title }}
                                </h1>
                            </div>
                            <div class="location" style="margin-bottom: 10px; font-size: 12px;
                            color: rgb(58, 58, 58);
                            margin-left: 1px;
                            font-weight: normal;
                            margin-bottom: 3px;">
                            <img
                            src="{{ asset("images/location.png") }}"
                            width="50"
                            height="20"
                            alt="Booklist"
                            style="display: block; color: #cccccc; object-fit:contain; float:left; margin-inline: -10px;
                            "
                          />
                                    <span style="
                                    box-sizing: border-box;
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    overflow: hidden;
                                    -webkit-box-orient: vertical;
                                    text-overflow: ellipsis;
                                    color: #a3a39f;">
                                        {{ $city }}
                                    </span>

                                </span>
                            </div>

                            <div style="font-weight: 600;
                            font-size: 15px;
                            color: var(--secondary-color);
                            margin-left: auto;
                            margin-top: 20px;
                            margin-right: 10px;
                            float: right;">
                                <span style="
                                box-sizing: border-box;
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                overflow: hidden;
                                -webkit-box-orient: vertical;
                                text-overflow: ellipsis;
                                color: #4CC6D6;
                                ">
                                    {{ $price }} MAD
                                </span>
                            </div>
                        </div>
                      </div>
                </td>
              </tr>
              <tr>
                <td
                  style="
                    padding: 35px 30px 11px 30px;
                    font-size: 0;
                    background-color: #ffffff;
                    border-bottom: 1px solid #f0f0f5;
                    border-color: rgba(201, 201, 207, 0.35);
                    display: flex;
                  "
                >
                  <!--[if mso]>
                <table role="presentation" width="100%">
                <tr>
                <td style="width:145px;" align="left" valign="top">
                <![endif]-->

                  <!--[if mso]>
                </td>
                <td style="width:395px;padding-bottom:20px;" valign="top">
                <![endif]-->
                  <div
                    class="col-lge"
                    style="
                      display: inline-block;
                      width: 100%;
                      text-align: center;
                      margin: 0 auto;
                      vertical-align: top;
                      padding-bottom: 20px;
                      font-family: Arial, sans-serif;
                      font-size: 16px;
                      line-height: 22px;
                      color: #363636;
                    "
                  >

<p style="margin-top: 0; margin-bottom: 12px; text-align:center">
                        Votre bien a été réservé pour la période de <b>{{ $datedebut }}</b> à <b>{{ $datefin }}</b></br>
                        <p style="margin-top: 0; margin-bottom: 12px">
                        Infos du client : </br>
                        </p>
                        <p style="margin-top: 0; margin-bottom: 12px">
                        <b>Nom : </b> {{ $name }}</br>
                        </p>
                        <p style="margin-top: 0; margin-bottom: 12px">
                        <b>Email : </b> {{ $email }}</br>
                        </p>
                        <p style="margin-top: 0; margin-bottom: 12px">
                        <b>Téléphone : </b> {{ $phone }}</br>
                        </p>
                    </p>
                    <p
                      style="
                        margin-top: 0;
                        margin-bottom: 18px;
                        font-weight: 600;
                      "
                    >
                      L’équipe Multilist,
                    </p>
                    <p style="margin: 0; padding:30px;">
                      <a
                      href="{{ url('dashboard/') }}"
                        style="
                          background: #4CC6D6;
                          text-decoration: none;
                          padding: 10px 25px;
                          color: #ffffff;
                          border-radius: 25px;
                          display: inline-block;
                          mso-padding-alt: 0;
                          text-underline-color: #ff3884;
                        "
                        ><!--[if mso
                          ]><i
                            style="
                              letter-spacing: 25px;
                              mso-font-width: -100%;
                              mso-text-raise: 20pt;
                            "
                            >&nbsp;</i
                          ><!
                        [endif]--><span
                          style="mso-text-raise: 10pt; font-weight: bold"
                          >Mon compte</span
                        ><!--[if mso
                          ]><i
                            style="letter-spacing: 25px; mso-font-width: -100%"
                            >&nbsp;</i
                          ><!
                        [endif]--></a
                      >
                    </p>
                  </div>
                  <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
                </td>
              </tr>



              <tr>
                <td
                  style="
                    padding: 15px;
                    text-align: center;
                    font-size: 12px;
                    color: #cccccc;
                    align-items: center;
                  "
                >
                  <p
                    style="
                          margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom:2px solid #B52483;
                    "
                  >
                    <a
                      href="#"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/booklistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Booklist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                      />
                    </a>
                  </p>
                  <p
                    style="
                         margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom:2px solid #F64D4B;
                    "
                  >
                    <a
                      href="#"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/homelistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Homelist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
                  </p>
                  <p
                    style="
                         margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom: 2px solid #F3BE2E;
                    "
                  >
                    <a
                      href="#"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/primelistmail.png") }}"
                        width="70"
                        height="40"
                        alt="primelist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
                  </p>
                  <p
                    style="
                          margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom: 2px solid #54C21B;
                    "
                  >
                    <a
                      href="#"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/landlistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Landlist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
                  </p>
                  <p
                    style="
                          margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom: 2px solid #156187;
                    "
                  >
                    <a
                      href="#"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/officelistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Officelist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
                  </p>
                </td>




              </tr>

               <tr>
                <td style=" padding: 10px;
                text-align: center;
                font-size: 12px;
                color: #cccccc;
                align-items: center;">
                  <p
                  style="
                        margin: 0 15px 0 15px;
                    display: inline-block;
                    justify-content: space-between;
                    align-items: center;
                  "
                >
                  <a
                    href="#"
                    style="text-decoration: none"
                    ><img
                      src="{{ asset("images/facebook.png") }}"
                      width="60"
                      height="30"
                      alt="Officelist"
                      style="display: inline-block; color: #b8abab; object-fit:contain"
                  /></a>
                </p>

                <p
                style="
                      margin: 0 15px 0 15px;
                  display: inline-block;
                  justify-content: space-between;
                  align-items: center;
                "
              >
                <a
                  href="#"
                  style="text-decoration: none"
                  ><img
                    src="{{ asset("images/instagram.png") }}"
                    width="60"
                    height="30"
                    alt="Booklist"
                    style="display: inline-block; color: #cccccc; object-fit:contain"
                  />
                </a>
              </p>

              <p
              style="
                    margin: 0 15px 0 15px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
              "
            >
              <a
                href="#"
                style="text-decoration: none"
                ><img
                  src="{{ asset("images/youtube.png") }}"
                  width="60"
                  height="30"
                  alt="Booklist"
                  style="display: inline-block; color: #cccccc; object-fit:contain"
                />
              </a>
            </p>

            <p
            style="
                  margin: 0 15px 0 15px;
              display: inline-block;
              justify-content: space-between;
              align-items: center;
            "
          >
            <a
              href="#"
              style="text-decoration: none"
              ><img
                src="{{ asset("images/linkedin.png") }}"
                width="60"
                height="30"
                alt="Booklist"
                style="display: inline-block; color: #cccccc; object-fit:contain"
              />
            </a>
          </p>

                </td>
              </tr>


              <tr>
                <td style=" padding: 10px;
                text-align: center;
                font-size: 12px;
                color: #b8abab;
                align-items: center;">

                 <a
                      href="#"
                      style="text-decoration: none;color: #b8abab;"
                      >
                 <p
                style="
                margin: 0 15px 0 15px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                    Mentions légales
                </p></a>

                <a
                href="#"
                style="text-decoration: none;color: #b8abab;"
                >
                <p
                style="
                margin: 0 15px 0 15px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                    Conditions d'utilisation
                </p></a>

                <a
                href="#"
                style="text-decoration: none;color: #b8abab;"
                >
                <p
                style="
                margin: 0 15px 0 15px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                    Nous contacter
                </p></a>

                </td>
              </tr>


            </table>


            <!--[if mso]>
          </td>
          </tr>
          </table>
          <![endif]-->

    </div>

  </body>
</html>
