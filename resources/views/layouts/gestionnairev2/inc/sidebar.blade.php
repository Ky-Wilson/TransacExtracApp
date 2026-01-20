<div class="section-menu-left">
    <div class="box-logo">
        <a href="#" id="site-logo-inner">
            <img class="" id="logo_header" alt="" src=""
                data-light=""
                data-dark="">
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>
    <div class="center">
        <div class="center-item">
            <div class="center-heading">TransacExtract</div>
            <ul class="menu-list">
                <li class="menu-item">
                    <a href="{{ route('gestionnaire.dashboard') }}" class="">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Dashboard</div>
                    </a>
                </li>
            </ul>
        </div>
        <div class="center-item">
            <ul class="menu-list">
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-smartphone"></i></div>
                        <div class="text">Orange</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('manager.orange.form') }}" class="">
                                <div class="text">Upload Transactions Orange</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('manager.orange.transactions') }}" class="">
                                <div class="text">Transactions Orange</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-smartphone"></i></div>
                        <div class="text">MTN</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="{{ route('manager.mtn.form') }}" class="">
                                <div class="text">Upload Transactions MTN</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="{{ route('manager.mtn.transactions') }}" class="">
                                <div class="text">Transactions MTN</div>
                            </a>
                        </li>
                    </ul>
                </li>
                
                <li class="menu-item">
                    <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                        @csrf
                        @method('POST') <!-- optionnel mais explicite -->
                    </form>

                    <a href="#" class="logout d-flex align-items-center">
                        <i class='bx bxs-log-out-circle'></i>
                        <span class="text ms-2">Déconnexion</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutLink = document.querySelector('.logout');

        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Déconnexion',
                    text: 'Voulez-vous vraiment vous déconnecter ?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Oui, déconnecter',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('logout-form').submit();
                    }
                });
            });
        }
    });
</script>
