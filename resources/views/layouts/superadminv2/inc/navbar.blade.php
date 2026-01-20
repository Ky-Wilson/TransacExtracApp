<div class="header-dashboard">
    <div class="wrap">
        <div class="header-left">
            <a href="#">
                <img class="" id="logo_header_mobile" alt=""
                    src="{{ asset('assetsv2/images/logo/logo.png') }}" data-light="images/logo/logo.png"
                    data-dark="{{ asset('assetsv2/images/logo/logo.png') }}" data-width="154px" data-height="52px"
                    data-retina="{{ asset('assetsv2/images/logo/logo.png') }}">
            </a>
            <div class="button-show-hide">
                <i class="icon-menu-left"></i>
            </div>

        </div>
        <div class="header-grid">


            <div class="popup-wrap user type-header">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="header-user wg-user">
                            <span class="image">
                                <img src="{{ asset('assetsv2/images/products/17.png') }}" alt="">
                            </span>
                            <span class="flex flex-column">
                                <span class="body-title mb-2">{{ Auth::user()->name }}</span>
                                <span class="text-tiny">Super Administrateur</span>
                            </span>
                        </span>
                    </button>

                </div>
            </div>

        </div>
    </div>
</div>
