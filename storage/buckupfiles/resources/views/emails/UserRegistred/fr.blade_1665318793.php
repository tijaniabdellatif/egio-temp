<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User registred</title>


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;500&display=swap" rel="stylesheet"></head>
    <script src="https://kit.fontawesome.com/9245f43cf7.js" crossorigin="anonymous"></script>

    <style>
        :root{
            --primary:#4cc6d6;
            --color:rgba(53, 53, 53, 0.884);
        }

        section{
            width: 80%;
            margin: auto;
        }
        .header{
            width: 100%;
        }
        .content{
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .content img{
            max-width: 550px;
        }
        .welcome-title{
            font-size: 2.4rem;
            font-family: ubuntu;
            color: var(--primary);
            letter-spacing: 2px;
        }
        .content p{
            font-size: 1.3rem;
            font-family: ubuntu;
            color: var(--color);
            font-weight: 300;
            line-height: 150%;
            text-align: center;
        }
        .action{
            background-color:var(--primary) ;
            border: none;
            padding: 1rem;
            color: #fff;
            font-size: 1.5rem;
            border-radius: 3rem;
            margin: 3rem auto;
        }
        .footer{
            width: 100%;
            padding: 1rem 0;
            background-color: var(--color);
            padding-bottom: 0;
        }
        .footer .univers{
            width: 100%;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
        .footer .univers img{
            margin: auto;
            padding: 1rem;
            width: 100px;
        }
        .social-links{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .social-links a{
            padding: 1rem;
            margin: 1rem;
        }
        .social-links a i{
            color:#fff;
            font-size: 3rem;
        }
        .pages .links {
            font-size: 1.3rem;
            display: flex;
            justify-content: center;
            width: 100%;
            margin: auto;
        }
        .pages .links a{
            text-decoration: none;
            color: #fff;
            margin: auto 5px ;
            padding: auto 5px;
            font-family: ubuntu;
            font-weight: 300;
            font-size: 1.2rem;
        }
        .pages .links :nth-child(1){
            border-right:1px solid #fff;
            width: 150px;
        }
        .pages .links :nth-child(2){
            border-right:1px solid #fff;
            width: 220px;
        }


        @media screen and (max-width: 546px){

            .pages .links{

                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                height:200px;
            }

            .pages .links :nth-child(1){
                border-right:0

            }
            .pages .links :nth-child(2){
                border-right:0;

            }
        }

    </style>
</head>
<body>
    <main>
        <section class="header">
            <img src="{{ asset('images/Header.svg') }}" alt="Multilist">
        </section>

        <section class="content">
            <h3 class="welcome-title">Bienvenue  {{ $username }}</h3>
            <p>Nous sommes ravis de vous compter parmi nous ! Faites vos premiers
                pas dès maintenant sur le 1er moteur de recherche de l’immobilier au
                Maroc.

                Déposez vos annonces immobilières sans f rais et en illimité en vous
                connectant à votre compte !
            </p>
            <img src="{{ asset('images/Laptop.svg') }}" alt="Multilist Plateforme">
            <p>Déposez vos annonces immobilières sans f rais et en illimité en vous
            connectant à votre compte !</p>
            <br>
            <p style="font-weight: 600;">L’équipe Multilist,</p>

            <button class="action">
                mon compte
            </button>
        </section>

        <section class="footer">
            <div class="univers">
                <img src="{{ asset('images/booklist-logo-white.png') }}" alt="Booklist">
                <img src="{{ asset('images/homelist-logo-white.png') }}" alt="Homelist">
                <img src="{{ asset('images/primelist-logo-white.png') }}" alt="Primelist">
                <img src="{{ asset('images/landist-logo-white.png') }}" alt="Landlist">
                <img src="{{ asset('images/officelist-logo-white.png') }}" alt="Officelist">
            </div>
            <div class="social-links">
                <a href="#">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-square-instagram"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-youtube"></i>
                </a>
                <a href="#">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </div>
            <div class="pages">
                <div class="links">
                    <a href="#">Mentions légales</a>
                    <a href="#">Conditions d‘utilisation</a>
                    <a href="#">Nous contacter</a>
                </div>

            </div>
        </section>

    </main>

</body>
</html>
