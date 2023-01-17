<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
	<!-- Google font -->
	<link href="https://fonts.googleapis.com/css?family=Raleway:400,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Passion+One:900" rel="stylesheet">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/v2/errors.styles.css') }}" />

</head>
<body>

	<div id="notfound">
		<div class="notfound">
            <div class="logo">
                <img width="150px" src="{{ @asset("images/logo.png") }}">

            </div>
			<div class="notfound-404">
				{{-- <h1>:)</h1> --}}
			</div>
            <br/>
			<h2 class="err-message">@yield('message')</h2>
			<p class="desc">@yield('description')</p>
			<p class="descend">@yield('end')</p>


			{{-- <a href="/">home page</a> --}}

            <img
            src="{{ asset("images/Laptop_new.png") }}"
            width="100%"

            />

		</div>


	</div>

<style>
.notfound .notfound-404 {
  position: absolute;
  left: 0;
  top: 0;
  width: 150px;
  height: 150px;
  align-items: center;
    display: flex;
}
.logo{
    display: flex;
    justify-content: center;
    margin-bottom: 2rem;
}
.desc{
    text-align: center;
}
.descend{
    padding: 1rem;
    font-weight: 700px;
    font-weight: bold !important;
    text-align: center;
    letter-spacing: 2px
}
.err-message{
    text-align: center
}
.notfound{
    padding: 2.5rem !important;
}

</style>


</body>
</html>
