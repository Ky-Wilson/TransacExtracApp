<!-- SIDEBAR -->
<section id="sidebar">
    <a href="#" class="brand">
<i class='bx bx-scan'></i>
<span class="text">TransacExtract</span>
    </a>
    <ul class="side-menu top">
        <li class="{{ request()->routeIs('gestionnaire.dashboard') ? 'active' : '' }}">
            <a href="{{ route('gestionnaire.dashboard') }}">
                <i class='bx bxs-dashboard'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li
            class="{{ request()->routeIs('manager.orange.form') || request()->routeIs('manager.orange') ? 'active' : '' }}">
            <a href="{{ route('manager.orange.form') }}">
                <i class='bx bxs-cloud-upload'></i>
                <span class="text">Upload Transactions</span>
            </a>
        </li>

        <li class="{{ request()->routeIs('manager.orange.transactions') ? 'active' : '' }}">
            <a href="{{ route('manager.orange.transactions') }}">
                <i class='bx bx-history'></i>
                <span class="text">Transactions Orange</span>
            </a>
        </li>
    </ul>
    <ul class="side-menu">
       
        <li>
            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: inline;">
                @csrf
                <a href="#" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </form>
        </li>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.logout').addEventListener('click', function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Déconnexion',
            text: 'Voulez-vous vraiment vous déconnecter ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui',
            cancelButtonText: 'Non',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    });
});
        </script>
    </ul>
</section>
<!-- SIDEBAR -->