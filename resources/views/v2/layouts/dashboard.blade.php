<!-- ======= head ======= -->
@include('head')

<body>

  <!-- ======= Header ======= -->
  @include('header')

  <!-- ======= Sidebar ======= -->
  @include('sidebar')

  <main id="main" class="main">

    @yield('content')

  </main>
  <!-- End #main -->

   <!-- ======= Footer ======= -->
   @include('footer')

   <!-- ======= Scripts ======= -->
   @include('scripts')

</body>

</html>


<script>

    {{--  window.addEventListener('DOMContentLoaded', (e) => {


                const body = document.querySelector('body')
                const selectedLocal = '<?= session('lang') ?>'
                const dropDownMenu = document.querySelector('.dropdown-menu1')
                const langueSwitcher = document.querySelector('.langueSwitcher')

                const arBtn = document.querySelector('.langswitcher .ar')
                const frBtn = document.querySelector('.langswitcher .fr')
                const enBtn = document.querySelector('.langswitcher .en')

                console.log('langue is:',selectedLocal)
                if(selectedLocal == 'ar'){
                    body.setAttribute('dir','rtl')
                    // dropDownMenu.style.left = "25px"

                }else{
                    body.setAttribute('dir','ltr')
                    // dropDownMenu.style.right = "25px"

            }
    })  --}}

</script>
