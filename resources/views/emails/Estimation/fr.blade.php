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

    <link rel="stylesheet" href="{{ asset('assets/css/v2/bootstrap.min.css') }}">
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
        line-height: 35px;
        font-size:12px;
        color: #383838;
      }


      .container {
  width: 100%;
  height: 100vh;
  background-color: #232323;
  display: flex;
  justify-content: center;
  align-items: center;
}
.container .post {
  width: 350px;
  height: 500px;
  display: flex;
  overflow: hidden;
  flex-direction: column;
  position: relative;
}
.container .post:hover .header_post {
  margin-top: -20px;
}
.container .post:hover .body_post {
  height: 50%;
}
.container .post:hover img {
  transform: translatey(-10px) translatex(-5px) scale(1.05);
}
.container .post .header_post {
  width: 100%;
  height: 40%;
  background: #ddd;
  position: absolute;
  top: 0;
  -webkit-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -moz-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -ms-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -o-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
}
.container .post .header_post img {
  max-width: 100%;
  height: auto;
  transition: ease-in-out 600ms;
}
.container .post .body_post {
  width: 100%;
  height: 60%;
  background: #fff;
  position: absolute;
  bottom: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  -webkit-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -moz-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -ms-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -o-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  cursor: pointer;
}
.container .post .body_post .post_content {
  width: 80%;
  height: 80%;
  background: #fff;
  position: relative;
}
.container .post .body_post .post_content h1 {
  font-size: 20px;
  font-weight: bold;
}
.container .post .body_post .post_content p {
  font-size: 14px;
  font-weight: normal;
}
.container .post .body_post .post_content .container_infos {
  width: 100%;
  display: flex;
  justify-content: space-between;
  position: absolute;
  bottom: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.2);
  padding-top: 25px;
}
.container .post .body_post .post_content .container_infos .postedBy {
  display: flex;
  flex-direction: column;
  text-transform: uppercase;
  letter-spacing: 1px;
  font-size: 12px;
}
.container .post .body_post .post_content .container_infos .postedBy span {
  font-size: 12px;
  text-transform: uppercase;
  opacity: 0.5;
  letter-spacing: 1px;
  font-weight: bold;
}
.container .post .body_post .post_content .container_infos .container_tags {
  display: flex;
  flex-direction: column;
}
.container .post .body_post .post_content .container_infos .container_tags span {
  font-size: 12px;
  text-transform: uppercase;
  opacity: 0.5;
  letter-spacing: 1px;
  font-weight: bold;
}
.container .post .body_post .post_content .container_infos .container_tags .tags ul {
  display: flex;
}
.container .post .body_post .post_content .container_infos .container_tags .tags ul li {
  font-size: 12px;
  letter-spacing: 2px;
  list-style: none;
  margin-left: 8px;
  text-transform: uppercase;
  position: relative;
  z-index: 1;
  display: flex;
  justify-content: center;
  cursor: pointer;
}
.container .post .body_post .post_content .container_infos .container_tags .tags ul li:first-child {
  margin-left: 0px;
}
.container .post .body_post .post_content .container_infos .container_tags .tags ul li:before {
  content: "";
  text-align: center;
  width: 100%;
  height: 5px;
  background: #FC6042;
  opacity: 0.5;
  position: absolute;
  bottom: 0;
  z-index: -1;
  padding: 0px 1px;
  -webkit-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -moz-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -ms-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  -o-transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
  transition: cubic-bezier(0.68, -0.55, 0.27, 1.55) 320ms;
}
.container .post .body_post .post_content .container_infos .container_tags .tags ul li:hover:before {
  height: 18px;
}

footer {
  width: 350px;
  height: 80px;
  background: #17A16F;
  position: absolute;
  right: 0;
  bottom: -80px;
  display: flex;
  justify-content: center;
  align-items: center;
  animation: top 0.8s forwards;
}
footer span {
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  color: #fff;
  font-family: "Poppins";
}
footer span i {
  margin-right: 25px;
  font-size: 22px;
  color: #fff;
  animation: icon 2s forwards;
  opacity: 0;
}

@keyframes top {
  0% {
    opacity: 0;
    bottom: -80px;
  }
  100% {
    opacity: 1;
    bottom: 0px;
  }
}
@keyframes icon {
  0% {
    opacity: 0;
    transform: scale(0);
  }
  50% {
    opacity: 1;
    transform: scale(1.3) rotate(-2deg);
  }
  100% {
    opacity: 1;
    bottom: 0px;
  }
}

@media screen and (max-width: 480px) {

	.social-panel-container.visible {
		transform: translateX(0px);
	}

	.floating-btn {
		right: 10px;
	}
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
                <td style="padding: 8px; background-color: #ffffff">
                  <h1
                    style="
                      margin-top: 5px;
                      margin-bottom: 10px;
                      font-size: 26px;
                      line-height: 30px;
                      font-weight: bold;
                      letter-spacing: -0.02em;
                      text-align: center;
                      color:#4CC6D6;
                    "
                  >
                    Votre estimation est prete
                  </h1>
                  <p style="margin: 0; text-align: center; line-height: 35px;
                  ">
                  Cher visiteur, <br/> {{ $firstname }}  {{ $lastname }}  {{ $phone }} {{ $host_name }}
                  Merci de choisir <a href="https://multilist.immo">Multillist</a>, Votre estimation Lorem ipsum dolor sit amet consectetur adipisicing elit. Eos eum quasi facilis eius explicabo expedita ad error, est quaerat?
				  Hic maxime aliquam assumenda iusto veniam alias quaerat nihil inventore veritatis!
                  </p>
                </td>
              </tr>
              <tr>
                <td
                  style="
                    font-size: 24px;
                    font-weight: bold;
                    background-color: #ffffff;
                    border-color: rgba(201, 201, 207, 0.35);
                    text-align:center;
                  "
                >
                <div style = "Margin:-50px 10px -50px 10px;">
                <a style="text-decoration: none" class='manip'
                ><img
                  src="./images/brand_awarenes.png"
                  width="540"
                  alt="Multilist"
                  title="Multilist"
                  style="
                    border: none;
                    text-decoration: none;
                    color: #363636;
                    position: relative;
                  "
              /></a></div>



                </td>
              </tr>
              <tr>
                <td
                  style="
                    padding: 0px !important;
                    font-size: 0;
                    background-color: #ffffff;
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
                      width: 90%;
                      text-align: center;
                      margin: 0 auto;
                      vertical-align: top;
                      padding-bottom: 20px;
                      font-family: Montserrat, Arial, sans-serif;
                      font-size: 16px;
                      line-height: 22px;
                      color: #363636;
                    "
                  >
                    <p style="margin-top: 0; margin-bottom: 12px; line-height: 30px !important;
                    ">

                    </p>
                    <p
                      style="
                        margin-top: 0;
                        margin-bottom: 18px;
                      "
                    >
                      L’équipe Multilist,
                    </p>

                  </div>
                  <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
                </td>
              </tr>


			  <tr style="text-align:center;">

				<td
				style="
				  margin: 0 15px 0 15px;

				">

<div class="container">
    <div class="post">
        <div class="header_post">
            <img src="https://images.pexels.com/photos/2529973/pexels-photo-2529973.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260" alt="">
        </div>

        <div class="body_post">
            <div class="post_content">

                <h1>Lorem Ipsum</h1>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci animi assumenda cumque deserunt
                    dolorum ex exercitationem.</p>

                <div class="container_infos">
                    <div class="postedBy">
                        <span>author</span>
                        John Doe
                    </div>

                    <div class="container_tags">
                        <span>tags</span>
                        <div class="tags">
                            <ul>
                                <li>design</li>
                                <li>front end</li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


			  </td>
			 </tr>


              <!-- Footer --->

              <tr style="text-align: center;">

                  <td
                    style="
                          margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom:2px solid #B52483;
                    "
                  >
                    <a
                      href="https://{{ $host_name }}/booklist"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/booklistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Booklist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                      />
                    </a>
                </td>
                  <td
                    style="
                         margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom:2px solid #F64D4B;
                    "
                  >
                    <a
                      href="https://{{ $host_name }}/homelist"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/homelistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Homelist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
            </td>
                  <td
                    style="
                         margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom: 2px solid #F3BE2E;
                    "
                  >
                    <a
                      href="https://{{ $host_name }}/primelist"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/primelistmail.png") }}"
                        width="70"
                        height="40"
                        alt="primelist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
                </td>
                  <td
                    style="
                          margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom: 2px solid #54C21B;
                    "
                  >
                    <a
                      href="https://{{ $host_name }}/landlist"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/landlistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Landlist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
            </td>
                  <td
                    style="
                          margin: 0 15px 0 15px;
                      display: inline-block;
                      justify-content: space-between;
                      align-items: center;
                      border-bottom: 2px solid #156187;
                    "
                  >
                    <a
                      href="https://{{ $host_name }}/officelist"
                      style="text-decoration: none"
                      ><img
                        src="{{ asset("images/officelistmail.png") }}"
                        width="70"
                        height="40"
                        alt="Officelist"
                        style="display: inline-block; color: #cccccc; object-fit:contain"
                    /></a>
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
                    margin: 0 5px 0 0px;
                    display: inline-block;
                    justify-content: space-between;
                    align-items: center;
                  "
                >
                  <a
                    href="https://www.facebook.com/Multilist.group/"
                    style="text-decoration: none"
                    ><img
                      src="{{ asset("images/facebbokgrey.png") }}"
                      width="60"
                      height="30"
                      alt="Facebook"
                      style="display: inline-block; color: #b8abab; object-fit:contain"
                  /></a>
                </p>


                <p
                style="
                      margin: 0 5px 0 0px;
                  display: inline-block;
                  justify-content: space-between;
                  align-items: center;
                "
              >
                <a
                  href="https://www.instagram.com/multilist.immo"
                  style="text-decoration: none"
                  ><img
                    src="{{ asset("images/instagrey.png") }}"
                    width="60"
                    height="30"
                    alt="Instagram"
                    style="display: inline-block; color: #cccccc; object-fit:contain"
                  />
                </a>
              </p>

              <p
              style="
                    margin: 0 5px 0 0px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
              "
            >
              <a
                href="https://www.youtube.com/channel/UCNjQB9JoAbAmQJDXrDIjn4w"
                style="text-decoration: none"
                ><img
                  src="{{ asset("images/youtubegrey.png") }}"
                  width="60"
                  height="30"
                  alt="Booklist"
                  style="display: inline-block; color: #cccccc; object-fit:contain"
                />
              </a>
            </p>

            <p
            style="
                  margin: 0 5px 0 0px;
              display: inline-block;
              justify-content: space-between;
              align-items: center;
            "
          >
            <a
              href="https://www.linkedin.com/company/multilist-immo/about/"
              style="text-decoration: none"
              ><img
                src="{{ asset("images/linkedingrey.png") }}"
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
                      href="https://{{ $host_name }}/roles"
                      style="text-decoration: none;color: #747272;"
                      >
                 <p
                style="
                margin: 0 10px 0 10px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                    Mentions légales
                </p></a>

                <p
                style="
                margin: 0 10px 0 10px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                     |
                </p>

                <a
                href=" https://{{ $host_name }}/info"
                style="text-decoration: none;color: #747272;"
                >
                <p
                style="
                margin: 0 10px 0 10px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                    Conditions d'utilisation
                </p></a>

                <p
                style="
                margin: 0 10px 0 10px;
                display: inline-block;
                justify-content: space-between;
                align-items: center;
            "
               >
                     |
                </p>

                <a
                href="mailto:contact@multilist.ma"
                style="text-decoration: none;color: #747272;"
                >
                <p
                style="
                margin: 0 10px 0 10px;
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

