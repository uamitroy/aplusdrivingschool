<ul class="nav nav-pills">
	<li <?= Request::segment(1) == 'home' ? 'class="active"' : ''?>><a href="{{ route('home') }}"><i class="fas fa-long-arrow-alt-right"></i>Profile</a></li>
	<li <?= Request::segment(1) == 'profile' ? 'class="active"' : ''?>><a href="{{ route('user.profile') }}"><i class="fas fa-long-arrow-alt-right"></i>Edit Profile</a></li>
	<li <?= Request::segment(1) == 'change-email' ? 'class="active"' : ''?>><a href="{{ route('user.email') }}"><i class="fas fa-long-arrow-alt-right"></i>Change Email</a></li>
	<li <?= Request::segment(1) == 'change-password' ? 'class="active"' : ''?>><a href="{{ route('user.change_password') }}"><i class="fas fa-long-arrow-alt-right"></i>Change Password</a></li>
	<li <?= Request::segment(1) == 'bookings' ? 'class="active"' : ''?>><a href="{{ route('transactions') }}"><i class="fas fa-long-arrow-alt-right"></i>Bookings</a></li>
</ul>