@extends('v2.layouts.dashboard')

@section('title', 'Logs')

@section('custom_head')

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<link href="https://unpkg.com/tabulator-tables@5.4.0/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.4.0/dist/js/tabulator.min.js"></script>

@endsection

@section('content')

    <div class="pagetitle">
        <h1>Logs : </h1>
    </div>
    <section id="app">
        <div class="row">

            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-body">


                        <div id="logsTab">
                            <h1>In construction...</h1>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

<script>

window.addEventListener('load', function() {



    const useToken = () => {
     return localStorage.getItem('token') || ''
    }
    const tok = useToken();

    const config = {
      headers: { Authorization: `Bearer ${tok}` }
    };
    axios
      .get('/api/v2/logActivities', {}, config)
      .then(({ data: isData }) => {

        var logsData = isData
        console.log(logsData)

        var table = new Tabulator("#logsTab", {
        data:logsData, //set initial table data
        columns:[
            {title:"UserName", field:"username"},
            {title:"FirstName", field:"firstname"},
            {title:"Subject/Action", field:"createOption"},
            {title:"Method", field:"method"},
            {title:"Region", field:"region"},
            {title:"Date", field:"created_at", sorter:"date", sorterParams:{
                format:"YYYY-mm-dd",
                alignEmptyValues:"top",
            }}
        ]}
        )
})





})
</script>

@section('custom_foot')


@endsection
