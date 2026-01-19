<div class="section-menu-left">
    <div class="box-logo">
        <a href="#" id="site-logo-inner">
            <img class="" id="logo_header" alt="" src="{{ asset('assetsv2/images/logo/logo.png') }}"
                data-light="{{ asset('assetsv2/images/logo/logo.png') }}"
                data-dark="{{ asset('assetsv2/images/logo/logo.png') }}">
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
                        <div class="icon"><i class="icon-shopping-cart"></i></div>
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
                        <div class="icon"><i class="icon-layers"></i></div>
                        <div class="text">Brand</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">New Brand</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">Brands</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-layers"></i></div>
                        <div class="text">Category</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">New Category</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">Categories</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item has-children">
                    <a href="javascript:void(0);" class="menu-item-button">
                        <div class="icon"><i class="icon-file-plus"></i></div>
                        <div class="text">Order</div>
                    </a>
                    <ul class="sub-menu">
                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">Orders</div>
                            </a>
                        </li>
                        <li class="sub-menu-item">
                            <a href="#" class="">
                                <div class="text">Order tracking</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a href="#" class="">
                        <div class="icon"><i class="icon-image"></i></div>
                        <div class="text">Slider</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#" class="">
                        <div class="icon"><i class="icon-grid"></i></div>
                        <div class="text">Coupns</div>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="#" class="">
                        <div class="icon"><i class="icon-user"></i></div>
                        <div class="text">User</div>
                    </a>
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
document.addEventListener('DOMContentLoaded', function () {
    const logoutLink = document.querySelector('.logout');

    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
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
