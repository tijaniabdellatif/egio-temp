<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('assets/css/v2/bootstrap.min.css') }}">
    <link href=" {{ asset('assets/fontawesome/css/all.css') }}" rel="stylesheet" type="text/css">

    <style>
        .main{
            height: 100vh;
            width: 100%;
            display: flex;
            align-items: center;
            background: url(https://avaelgo.ro/wp-content/uploads/2017/07/Landing-page-background.jpg);
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        .contact-owner {
            background: white;
            border: solid 1px rgb(221, 221, 221);
            border-radius: 15px;
            box-shadow: 0px 0px 10px 0px rgb(107 107 107);
            overflow-y: clip;
            width: 500px;
            margin: auto;
        }
        .contact-owner .sendmail{
            padding: 15px;
        }
        .contact-owner .sendmail .form-control-sm {
            box-shadow: 0px 2px 3px 0 #dedede;
            border: 0 !important;
            border-radius: 10px !important;
            font-size: 12px !important;
        }
        .conditions-item {
            width: 80%;
            margin: auto;
            text-align: center;
            font-size: 10px;
            margin-bottom: 5px;
            color: gray;
        }
        .contact-owner .sendmail .btn {
            background: #4cc6d6;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
            font-weight: 600;
        }
        .cnt{
            width: 100%;
        }
        .cnt h1{
            max-width: 800px;
            margin: 20px auto;
            text-align: center;
            font-size: 30px;
            text-shadow: 0 0 2px white;
        }
        .sendmail h2{
            font-size: 20px;
            text-align: center;
            margin: 15px auto;
            width: 85%;
            color: #4dc5d6;
        }
    </style>
</head>

<body>

    <div class="main">
        <div class="cnt">

            <h1>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            </h1>

            <div class="contact-owner">
                <div class="sendmail">
                    <h2>Lorem ipsum dolor sit amet, consectetur adipiscing elit</h2>
                    <div class="mb-3 translates">
                        <input type="text" name="" id="" class="form-control form-control-sm"
                            placeholder="Votre nom complet">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 translates"><input type="text" name="" id=""
                            class="form-control form-control-sm" placeholder="Votre email">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 translates"><input type="text" name="" id=""
                            class="form-control form-control-sm" placeholder="Votre téléphone">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-2 translates">
                        <textarea name="" id="" rows="5" class="form-control form-control-sm" placeholder="Votre message"></textarea>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="translates">
                        <div class="conditions-item"> En cliquant sur « contactez-moi » j’autorise multilist.immo à collecter
                            mes données de contact. </div><button class="btn btn-sm tagManager-sendMail"><i
                                class="fa fa-paper-plane me-2" aria-hidden="true"></i> Contacter
                            <!--v-if-->
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>


</body>

</html>
