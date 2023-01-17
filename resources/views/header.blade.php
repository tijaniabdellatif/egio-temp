<script>
    /**@abstract */
    window.notify = [];

</script>
<header id="header" class="header fixed-top d-flex align-items-center" dir="ltr">
    @if(!auth()->guest())
    <script>
        window.userId = <?php echo auth()->user()->id; ?>
    </script>
    @endif
    <div class="d-flex align-items-center justify-content-between">
        <a href="/" class="logo d-flex align-items-center">
            <img src="{{ @asset("images/logo.png") }}" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
        <a class="mx-2" href="/dashboard"><i class="fa-solid fa-gauge-high mx-1 fa-lg"></i></a>

    </div><!-- End Logo -->
    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">
            <li class="nav-item dropdown" id="Notify">
                <a class="nav-link nav-icon animate__animated animate__bounce" href="#" data-bs-toggle="dropdown">
                    <i class = "bi bi-bell"></i>
                    <span class="badge bg-primary badge-number animate__animated animate__heartBeat animate__infinite infinite" v-if="notif.length">@{{ notif.length }}</span>
                </a><!-- End Notification Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications" style="width: 350px;">
                    <li class="dropdown-header">
                        <span> <strong>Notifications <u>non lues</u> </strong></span>
                        <!-- <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">Voir tous</span></a> -->
                        <span v-if="notif.length" class="badge bg-info text-light mx-2">@{{notif.length}}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <div v-if="!notif.length" class="text-warning m-5 text-center fw-bold fs-5">Toutes les notifications sont lues !</div>
                    <div>
                        <li class="notification-item border-0 border-start border-5 border-primary my-1 shadow-sm opacity-75" v-for="(notification,key) in notif">
                            <span class="me-1" v-if="notification.data.notification_flag == 'secondary'">
                                <i class="fa-solid fa-triangle-exclamation text-secondary"></i>
                            </span>
                            <span class="me-1" v-if="notification.data.notification_flag == 'orange'">
                                <i class="fa-solid fa-triangle-exclamation text-warning"></i>
                            </span>
                            <span class="me-1" v-if="notification.data.notification_flag == 'info'">
                                <i class="bi bi-exclamation-circle text-info"></i>
                            </span>
                            <span class="me-1" v-if="notification.data.notification_flag == 'warning'">
                                <i class="bi bi-exclamation-circle text-warning"></i>
                            </span>
                            <span class="me-1" v-if="notification.data.notification_flag == 'success'">
                                <i class="bi bi-exclamation-circle text-success"></i>
                            </span>
                            <span class="me-1" v-if="notification.data.notification_flag == 'alert'">
                                <i class="bi bi-exclamation-circle text-alert"></i>
                            </span>
                            <span class="me-1" v-if="notification.data.notification_flag == 'danger'">
                                <i class="bi bi-exclamation-circle text-danger"></i>
                            </span>
                            <div class="position-relative w-100">
                                <button @click="markNotifAsRead(notification.id)" class="d-flex btn btn-outline-primary align-items-center justify-content-center rounded-circle d-flex justify-content-end position-absolute end-0 top-0" style="width: 24px;height:24px">
                                    <i class="fa fa-check mx-1 p-1" style="font-size: 12px;"></i>
                                </button>
                                <h4 class="me-4">@{{ notification.data.body }}</h4>
                                <p> <a :href="notification.data.url">@{{ notification.data.text }}</a></p>
                                <p>@{{ dateFormat(notification.created_at) }}</p>
                            </div>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <div class="d-flex justify-content-end my-1">
                            <button v-on:click="markAllAsRead" class="m-1 m-auto btn btn-outline-primary">
                                <i class="fa-solid fa-check-double"></i>
                                Marqué lues
                            </button>
                            <a href="/admin/settings/notifications" class="m-1 m-auto btn btn-outline-primary">
                                <i class="fa-solid fa-list"></i>
                                Voir toutes notifications
                            </a>
                        </div>
                    </div>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                </ul><!-- End Notification Dropdown Items -->
            </li><!-- End Notification Nav -->
            <li class="nav-item dropdown pe-3">
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                    <span id="auth-name" class="d-none d-md-block dropdown-toggle ps-2">authentificated user</span>
                </a><!-- End Profile Iamge Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6 id="auth-name-dropdown">authentificated user</h6>
                        <span id="auth-type">type</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a href="https://multilist.immo/translations" class="dropdown-item d-flex align-items-center" id="translate-btn" target="_blank">
                            <i class="fa-solid fa-language"></i>
                            <span>Traducteur</span>
                        </a>
                    </li>
                    <li>
                        <button class="dropdown-item d-flex align-items-center" id="logout-btn">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Se déconnecter</span>
                        </button>
                    </li>


                    <script>
                        //axios post /api/v2/logout
                        document.getElementById('logout-btn').addEventListener('click', function(e) {
                            e.preventDefault();
                            console.log('logged out')
                            axios.post('/api/v2/logout')
                                .then(function(response) {
                                    // delete auth & token from localStorage
                                    localStorage.removeItem('auth');
                                    localStorage.removeItem('token');
                                    // delete jwt from cookie
                                    document.cookie = "jwt=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                                    // redirect to login page
                                    window.location.href = '/login';
                                    console.log(response);
                                })
                                .catch(function(error) {
                                    console.log(error);
                                });
                            // clear jwt token from storage and cookies
                            localStorage.removeItem('jwt_token');
                            document.cookie = "jwt_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
                        });


                    </script>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->
        </ul>
    </nav><!-- End Icons Navigation -->
    <script>
        // get cookies
        function getCookie(name) {
            var value = "; " + document.cookie;
            var parts = value.split("; " + name + "=");
            if (parts.length == 2) return parts.pop().split(";").shift();
        }
        // get the current authenticated user from local storage
        let auth = JSON.parse(localStorage.getItem('auth'));
        // if the user is authenticated
        if (auth) {
            console.log(auth);
            // set the name in the navbar
            document.getElementById('auth-name').innerHTML = auth.username;
            document.getElementById('auth-name-dropdown').innerHTML = auth.username;
            // set the type in the navbar
            // check if user.users_types is exist
            if (auth.users_types) {
                if (auth.users_types.designation) {
                    document.getElementById('auth-type').innerHTML = auth.users_types.designation;
                }
            }
        }
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script>
    <script type="text/javascript">
        const headerApp = Vue.createApp({
            data() {
                return {
                    notifications: [],
                    toastNotifications: [],
                    notif:window.notify,
                }
            },
            methods: {
                markAllAsRead() {
                    const options = {
                        url: '/api/v2/makeNotificationsAsRead',
                        method: 'GET',
                    }
                    axios(options)
                        .then(response => {
                            this.notif = [];
                        })
                },
                remove(id) {
                    this.notif = this.notif.filter(item => {
                        if (item.id !== id) {
                            return true;
                        }
                    })
                },
                markNotifAsRead(id) {
                    const options = {
                        url: '/api/v2/markNotificationAsRead/' + id,
                        method: 'GET',
                    }
                    axios(options)
                        .then(response => {
                            this.remove(id);
                        }).catch(error => {
                            console.log(error);
                        })
                },
                checkForExpiredAds() {
                    const options = {
                        url: '/api/v2/sendExpiredAdNotif',
                        method: 'GET',
                    }
                    axios(options)
                        .then(response => {
                            console.log(response)
                        })
                },
                pushData(data){
                    this.notifications.push(data);
                },
                runPusher() {
                    var pusher = new Pusher('b85d8369e644cd99e2d9', {
                        cluster: 'eu'
                    });
                    var channel = pusher.subscribe('my-channel');
                    channel.bind('my-event',  (data) => {
                        const options = {
                        url: '/api/v2/getUnreadNotifications',
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    }
                    axios(options)
                        .then(response => {
                            axios(options).then(response => {
                                this.notif = [...response.data.data];
                            })
                        })
                        let bg = null;
                        if (window.userId == data.userId) {
                            window.textColor = '#fff'
                            if (data.flag == 'danger') {
                                window.bg = 'rgba( 224, 21, 21 ,0.7)'
                            }
                            if (data.flag == 'success') {
                                window.bg = 'rgba(0, 200, 0, 0.68) '
                            }
                            if (data.flag == 'info') {
                                window.bg = 'rgba(0,132,238,0.7)'
                            }
                            if (data.flag == 'warning') {
                                window.bg = 'rgba(255, 199, 20, 0.7)'
                            }
                            if (data.flag == 'alert') {
                                window.bg = '#ffa500b0'
                            }
                            if (data.flag == 'secondary') {
                                window.bg = '#343a40'
                            }
                            Toast.fire({
                                icon: 'info',
                                title: data.message,
                                background: window.bg,
                                color: textColor,
                                position: 'top',
                                showClass: {
                                    popup: 'animate__animated animate__fadeInDown'
                                },
                                hideClass: {
                                    popup: 'animate__animated animate__fadeOutUp'
                                }
                            })
                         }
                    });
                },
                getUnreadNotifications() {
                    const options = {
                        url: '/api/v2/getUnreadNotifications',
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                    }
                    axios(options)
                        .then(response => {
                            axios(options).then(response => {
                                 this.notif = [...response.data.data];
                                //  console.log("this is response2",this.notif);
                            })
                        })
                },
                dateFormat(val) {
                    return moment(val).format('DD MMM. YYYY HH:mm');
                }
            },
            mounted() {
                this.getUnreadNotifications();
                setTimeout(() => {
                    this.runPusher();
                    console.log('pusher run..')
                },900)
                this.checkForExpiredAds();
            },
        })
        headerApp.mount('#Notify');
    </script>
</header><!-- End Header -->



