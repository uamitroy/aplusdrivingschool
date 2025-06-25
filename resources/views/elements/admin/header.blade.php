        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <div class="navbar-header">

                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">

                    <span class="sr-only">Toggle navigation</span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                    <span class="icon-bar"></span>

                </button>

                <a class="navbar-brand" href="{{ route('admin.dashboard') }}">{{ $setting->title }} Admin Panel</a>

				{{-- @if (Auth::guard('admin')->user()->google2fa_secret)

				<a href="{{ route('admin.2fa.disable') }}" class="btn btn-warning pull-right">Disable 2FA</a>

				@else

				<a href="{{ route('admin.2fa.enable') }}" class="btn btn-primary pull-right">Enable 2FA</a>

				@endif --}}

            </div>

           

            <ul class="nav navbar-right top-nav">

                <li class="dropdown">

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> {{ Auth::guard('admin')->user()->name }} <b class="caret"></b></a>

                    <ul class="dropdown-menu">

                        <li>

                            <a href="{{ route('admin.profile') }}"><i class="fa fa-fw fa-user"></i> Profile</a>

                        </li>

                        <li>

                            <a href="{{ route('admin.change_password') }}"><i class="fa fa-fw fa-gear"></i> Password </a>

                        </li>

                        <li>

                            <a target="_blank" href="{{  url('/') }}"><i class="fa fa-fw fa-gear"></i> Visit Site</a>

                        </li>

                        <li class="divider"></li>

                        <li>

                            <a href="{{ route('admin.logout') }}"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>