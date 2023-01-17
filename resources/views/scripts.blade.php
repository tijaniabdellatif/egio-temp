<!-- Vendor JS Files -->
<script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/chart.js/chart.min.js"></script>
<script src="/assets/vendor/echarts/echarts.min.js"></script>
<script src="/assets/vendor/quill/quill.min.js"></script>
<script src="/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/assets/vendor/php-email-form/validate.js"></script>
<script src="/assets/vendor/jquery.min.js"></script>
<script src="/assets/vendor/select2.min.js"></script>
<script src="/assets/vendor/jquery.validate.min.js"></script>
<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
</script>
<script>
    hljs.highlightAll();
</script>

<!-- Template Main JS File -->
<script src="/assets/js/main.js"></script>

<!-- Multilist additional JS File -->
<script src="/js/script.js"></script>

@if(session()->has('error'))
    <script>
        // swal({
        //     title: "{{ session()->get('error') }}",
        //     text: "",
        //     type: "error",
        //     confirmButtonText: "OK"
        // });
        displayToast('{{ session()->get("error") }}', 'red');
    </script>
@endif

@yield('custom_foot')
