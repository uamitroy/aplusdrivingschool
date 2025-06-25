<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-menu">



                <ul class="nav navbar-nav side-nav">
				<div class="profile clearfix">
					<div class="profile_pic">
                    @if(Auth::guard('admin')->user()->image)
                    <img src="{!! asset('uploads/'.Auth::guard('admin')->user()->image) !!}" alt="{{ Auth::guard('admin')->user()->name }}" class="img-circle profile_img">
                    @else
                    <img src="{!! asset('admin-design/images/dummy-profile.png') !!}" alt="{{  Auth::guard('admin')->user()->name }}" class="img-circle profile_img">
                    @endif
					</div>
					<div class="profile_info">
					<span>Welcome,</span>
					<h2>{{ Auth::guard('admin')->user()->name }}</h2>
					</div>
					</div>

                    <li>
                        <a href="{{ route('admin.dashboard') }}"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse2"><i class="fa fa-thumb-tack" aria-hidden="true"></i> Posts <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse2" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.posts.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.post.create') }}">Add</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.categories.list') }}">Categories</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.tags.list') }}">Tags</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse4"><i class="fa fa-picture-o" aria-hidden="true"></i> Media <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse4" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.resources.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.resources.create') }}">Add</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse1"><i class="menu-icon fa fa-file-text"></i> Pages <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse1" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.pages.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.page.create') }}">Add</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse3"><i class="fa fa-paint-brush" aria-hidden="true"></i> Appearence <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse3" class="collapse left-design">

                            <li>
                                <a href="{{ route('admin.widgets') }}">Widgets</a>
                            </li>
                            <li>
                            <a href="{{ route('admin.site.setting') }}">Settings</a>
                            </li>
							<li>
                            <a href="{{ route('admin.seo.setting') }}">Sitemap & Robots</a>
                            </li>
                        </ul>
                    </li>



                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse5"><i class="fa fa-user"></i> Users <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse5" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.users.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.user.create') }}">Add</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse11"><i class="fa fa-database" aria-hidden="true"></i> Segments <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse11" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.segments.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.segment.create') }}">Add</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse12"><i class="fa fa-thumb-tack" aria-hidden="true"></i> Packages <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse12" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.packages.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.package.create') }}">Add</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse13"><i class="fa fa-stack-exchange" aria-hidden="true"></i> Slots <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse13" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.slots.list') }}">List</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.slot.create') }}">Add</a>
                            </li>
                        </ul>
                    </li>

                     <li>
                    <a href="{{ route('admin.transactions') }}"><i class="fa fa-money" aria-hidden="true"></i> Bookings</a>
                     </li>

					<li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse9"><i class="fa fa-envelope-o" aria-hidden="true"></i> Contact Enquiries <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse9" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.contact.forms.list') }}">List</a>
                            </li>
                        </ul>
                    </li>

                    {{--<li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#collapse10"><i class="fa fa-paper-plane" aria-hidden="true"></i> Subscriber <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="collapse10" class="collapse left-design">
                            <li>
                                <a href="{{ route('admin.subscribers.list') }}">List</a>
                            </li>
                        </ul>
                    </li>--}}

                </ul>
            </div>

        </nav>
