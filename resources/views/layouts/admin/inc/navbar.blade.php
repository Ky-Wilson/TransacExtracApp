<!-- NAVBAR -->
		<nav>
			<i class='bx bx-menu' ></i>
			<a href="#" class="nav-link">Categories</a>
			<form action="#">
				<div class="form-input">
					<input type="search" placeholder="Search...">
					<button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="notification">
				<i class='bx bxs-bell' ></i>
				<span class="num">8</span>
			</a>
			<a href="{{ route('admin.dashboard') }}" class="profile">
    <img src="{{ asset(auth()->user()->avatar ?? 'assets/avatars/default.png') }}" alt="Avatar de {{ auth()->user()->name }}" class="rounded-circle" style="width: 40px; height: 40px;">
</a>
		</nav>
		<!-- NAVBAR -->