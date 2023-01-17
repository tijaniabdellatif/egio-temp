<!-- ======= head ======= -->
@include('head')

<body>

    <!-- ======= Header ======= -->
    @include('header')

    <!-- ======= Sidebar ======= -->
    @include('sidebar')

    <!-- ======= main ======= -->
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Utilisateurs</h1>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-body">


                            <a href="/addannonces" type="button" class="btn btn-multi"
                                style="margin: 10px 0;">Ajouter</a>

                            <fieldset class="filter_cnt">

                                <legend>Filter:</legend>

                                <div class="row col-sm-4">
                                    <label class="col-sm-4 col-form-label">Etat</label>
                                    <div class="col-sm-8">
                                        <select id="etat-filter" class="form-select"
                                            aria-label="Default select example">
                                            <option value selected>tous</option>
                                            <option value="10">publiées</option>
                                            <option value="20">bouillons</option>
                                            <option value="30">review</option>
                                            <option value="40">pay pend</option>
                                            <option value="50">rejec</option>
                                            <option value="60">expire</option>
                                            <option value="70">delete</option>
                                        </select>
                                    </div>
                                </div>

                            </fieldset>

                            <!-- Table with stripped rows -->
                            <div class="table-tools">
                                <div class="row">
                                    <div class="row col-sm-2">
                                        <select id="count-filter"
                                            style="padding: 0;padding-right: 35px;padding-left: 5px;width: fit-content;"
                                            class="form-select" aria-label="Default select example">
                                            <option value="20" selected>20</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="250">250</option>
                                            <option value="500">250</option>
                                        </select>
                                    </div>

                                    <div class="row col-sm-4" style="margin-left: auto;">
                                        <label for="inputTitle" class="col-sm-2 col-form-label"
                                            style="text-align: end;padding: 3px 0;font-size: 13px;"><i
                                                class="bi bi-search"></i></label>
                                        <div class="col-sm-10">
                                            <input id="table-search" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Nom complet</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Type</th>
                                        <th scope="col">Date de création</th>
                                        <th scope="col">Type Auth</th>
                                        <th scope="col">Etat</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tr id="table-loader" style="display: none;">
                                    <td colspan="10">
                                        <div class="d-flex justify-content-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr id="no-data" style="display: none;">
                                    <td colspan="10" style="text-align: center;">Aucun donnée trouvée!</td>
                                </tr>
                                <tbody id="table-data">
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                            <div class="table-pagination">
                                <span id="table-page-nbr">page: 1</span>
                                <button style="display: none;" id="table-prev-page"><i
                                        class="bi bi-caret-left"></i></button>
                                <button style="display: none;" id="table-next-page"><i
                                        class="bi bi-caret-right"></i></button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Update Modal -->
        <div class="modal fade modal-close" id="updateModal" data-id="updateModal">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier l'annonce</h5>
                        <button type="button" class="btn-close modal-close" data-id="updateModal"></button>
                    </div>
                    <div class="modal-body" style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">
                        <div id="up-loader">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <form onsubmit="event.preventDefault()" id="up-cnt">
                            <input name="id" id="up-id" type="hidden">
                            <div class="row mb-3">
                                <label for="inputTitle" class="col-sm-4 col-form-label">Title</label>
                                <div class="col-sm-8">
                                    <input name="title" id="up-title" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputDesc" class="col-sm-4 col-form-label">Descripton</label>
                                <div class="col-sm-8">
                                    <textarea name="desc" id="up-desc" class="form-control" style="height: 100px"></textarea>
                                </div>
                            </div>

                            <div class="input-group mb-3">
                                <label for="inputPrice" class="col-sm-4 col-form-label">Prix</label>
                                <input name="price" id="up-price" type="text" class="form-control"
                                    style="margin-left: 8px;">
                                <span class="input-group-text">DHS</span>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Catégorie</label>
                                <div class="col-sm-8">
                                    <select name="catid" id="up-catid" class="form-select"
                                        aria-label="Default select example">
                                        <option value selected>Choisir une Catégorie</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputLoc" class="col-sm-4 col-form-label">Localisation</label>
                                <div class="col-sm-8">
                                    <input id="loc-search" type="text" class="form-control">
                                </div>
                            </div>

                            <div id="loc-cnt">

                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Région</label>
                                    <div class="col-sm-8">
                                        <input name="region" id="loc-reg" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputVille" class="col-sm-4 col-form-label">Ville</label>
                                    <div class="col-sm-8">
                                        <input name="city" id="loc-city" type="text" class="form-control">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="inputTitle" class="col-sm-4 col-form-label">Quartier</label>
                                    <div class="col-sm-8">
                                        <input name="dept" id="loc-dept" type="text" class="form-control">
                                    </div>
                                </div>

                                <input name="country" id="loc-pays" type="hidden">
                            </div>


                            <div class="row mb-3">
                                <label for="inputRooms" class="col-sm-4 col-form-label">Pièces</label>
                                <div class="col-sm-8">
                                    <input name="rooms" id="up-rooms" type="number" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputBedrooms" class="col-sm-4 col-form-label">Chambres</label>
                                <div class="col-sm-8">
                                    <input name="bedrooms" id="up-bedrooms" type="number" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputBathrooms" class="col-sm-4 col-form-label">Salle de baine</label>
                                <div class="col-sm-8">
                                    <input name="bathrooms" id="up-bathrooms" type="number" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputSurface" class="col-sm-4 col-form-label">Supérficie</label>
                                <div class="col-sm-8">
                                    <input name="surface" id="up-surface" type="number" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Type de bien</label>
                                <div class="col-sm-8">
                                    <select name="property" id="up-property" class="form-select"
                                        aria-label="Default select example">
                                        <option value selected>Choisir un Type de bien</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label">Standing</label>
                                <div class="col-sm-8">
                                    <select name="standing" id="up-standing" class="form-select"
                                        aria-label="Default select example">
                                        <option value selected>Choisir standing</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputRef" class="col-sm-4 col-form-label">Référence</label>
                                <div class="col-sm-8">
                                    <input name="ref" id="up-ref" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="inputVr" class="col-sm-4 col-form-label">Lien de visite virtuelle</label>
                                <div class="col-sm-8">
                                    <input name="vr" id="up-vr" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="meuble" id="up-meuble">
                                    <label class="form-check-label" for="gridCheck1">
                                        Meublé
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_project"
                                        id="up-is_project">
                                    <label class="form-check-label" for="gridCheck2">
                                        C'est un projet?
                                    </label>
                                </div>

                            </div>
                        </form>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modal-close"
                            data-id="updateModal">Close</button>
                        <button id="update" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Update Modal-->

        <!-- Update status Modal -->
        <div class="modal fade modal-close" id="updateStatusModal" data-id="updateStatusModal">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier l'état de l'annonce</h5>
                        <button type="button" class="btn-close modal-close" data-id="updateStatusModal"></button>
                    </div>
                    <div class="modal-body"
                        style="overflow: auto;max-height: calc(100vh - 190px);padding: 20px 50px;">
                        <input name="id" id="ups-id" type="hidden">
                        <div class="col-sm-12">
                            <select id="ups-etat" class="form-select" aria-label="Default select example">
                                <option value="10">publiées</option>
                                <option value="20">bouillons</option>
                                <option value="30">à valider</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary modal-close"
                            data-id="updateStatusModal">Close</button>
                        <button id="updateStatus" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Update Status Modal-->

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    @include('footer')

    <!-- ======= Scripts ======= -->
    @include('scripts')

    <script>
        var etat = null;
        var from = 0;
        var search = '';
        var page = 1;

         loadData();

        $('#updateStatus').on('click', function() {
            $('#globalLoader').removeClass('H');
            const d_id = $('#ups-id').val();
            const d_status = $('#ups-etat').val();
            $.ajax({
                type: "POST",
                url: "{{ route('api.users.updateStatus') }}",
                data: {
                    id: d_id,
                    status: d_status
                },
                dataType: "json",
                success: function(data) {
                    if (data.success) {
                        hideModal('updateStatusModal');
                        showAlert('success', 'done', 'users status succefully updated!');
                        $('#globalLoader').addClass('H');
                        loadData();
                    } else {
                        hideModal('updateStatusModal');
                        $('#globalLoader').addClass('H');
                        showAlert('danger', 'erreur', data.msg);
                    }
                },
                error: function(xhr, responseText) {
                    $('#globalLoader').addClass('H');
                    showAlert('danger', 'erreur', "error " + xhr.status + ": " + xhr.statusText);
                },
            });
        });

        $('#table-search').keyup(function(e) {
            if (e.keyCode == 13) {
                from = 0;
                page = 1;
                search = $('#table-search').val();
                loadData();
            }
        });

        $('#table-next-page').on('click', function() {
            page++;
            from += parseInt($('#count-filter').val());
            if (from > 0) $('#table-prev-page').show();
            $('#table-page-nbr').html('page: ' + page);
            loadData();
        });

        $('#table-prev-page').on('click', function() {
            page--;
            from -= parseInt($('#count-filter').val());
            if (from < 0) from = 0;
            if (page < 1) page = 1;
            if (from == 0) $('#table-prev-page').hide();
            $('#table-page-nbr').html('page: ' + page);
            loadData();
        });

        $('#count-filter').on('change', function() {
            page = 1;
            from = 0;
            search = '';
            $('#table-search').val('');
            loadData();
        });

        $('#etat-filter').on('change', function() {
            if (!$(this).val()) {
                etat = null;
            } else {
                etat = $(this).val();
            }
            page = 1;
            from = 0;
            search = '';
            $('#table-search').val('');
            loadData();
        });

        function loadData() {
            $('#table-data').html('');
            $('#table-loader').show();
            $('#no-data').hide();
            $('#table-next-page').attr('disabled', 'disabled');
            $('#table-prev-page').attr('disabled', 'disabled');
            var count = $('#count-filter').val();
            $.ajax({
                type: "POST",
                url: "{{ route('api.users.loadUsers') }}",
                data: {
                    status: etat,
                    from: from,
                    count: count,
                    search: search.trim()
                },
                dataType: "json",
                success: function(data) {
                    console.log(data);
                    if (data.success) {
                        var t = '';
                        for (const d of data.data) {
                            var created_date = new Date(d.created_at);
                            t += `
                <tr>
                    <td>${d.id}</td>
                    <td><div  class="table-long-text">${d.firstname+' '+d.lastname} </div></td>
                    <td>${d.username}</td>
                    <td>${d.email}</td>
                    <td>${d.phone}</td>
                    <td>${d.usertype}</td>
                    <td>${created_date.toLocaleDateString()+' '+ created_date.toLocaleTimeString().substr(0,5)}</td>
                    <td>${d.authtype}</td>
                    <td><div class="status_box s_${d.status}">${status_obj[d.status]}</div></td>
                    <td>
                    <i class="bi bi-pencil-square table-action showUpdateModal" data-id="${d.id}"></i>
                    <i class="bi bi-gear-fill table-action showUpdateStatusModal" data-id="${d.id}" data-status="${d.status}"></i>
                    </td>
                </tr>
                `;
                        }
                        $('#table-data').html(t);
                        $('.showUpdateModal').off('click');
                        $('.showUpdateModal').on('click', function() {
                            var id = $(this).attr('data-id');
                            loadOneData(id);
                        });
                        $('.showUpdateStatusModal').off('click');
                        $('.showUpdateStatusModal').on('click', function() {
                            var id = $(this).attr('data-id');
                            var status = $(this).attr('data-status');
                            $('#ups-id').val(id);
                            $('#ups-etat').val(status);
                            showModal('updateStatusModal');
                        });
                        if (data.data.length == 0) $('#no-data').show();
                        $('#table-loader').hide();
                        $('#table-next-page').hide();
                        if ((from + data.data.length) < data.total) $('#table-next-page').show();
                        $('#table-next-page').removeAttr('disabled');
                        $('#table-prev-page').removeAttr('disabled');
                    }
                },
                error: function(xhr, responseText) {
                    console.log("The ajax call returned error " + xhr.status + ": " + xhr.statusText);
                },
            });
        }

    </script>

</body>

</html>
