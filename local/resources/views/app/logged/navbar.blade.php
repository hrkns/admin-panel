<body>
	<section class="body">
		<header class="header">
			<div class="logo-container">
				{{-- link to the root of the system --}}
					<a href="{!! WEB_ROOT !!}" class="logo">
						{{-- main logo of the system --}}
						<img src = "{!! AP_Asset("assets/images/logos/".$userPreferences["logo"]) !!}" style = "height:30px;" id = "original_global_logo">
					</a>

				{{-- control that show/hide the side menu in the mobile view --}}
					<div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
						<i class="fa fa-bars" aria-label="Toggle sidebar" id = "toggle_sidebar"></i>
					</div>
			</div>
			<div class="header-right">
				<i class="em em-some-emoji"></i>

				{{-- form to search any content --}}
					<form action="pages-search-results.html" class="search nav-form" onsubmit = "return false;">
						<div class="input-group input-search">
							{{-- keywords input --}}
								<input type="text" class="form-control" name="q" id="q" placeholder="{!! translate("str_search", true) !!}">
								<span class="input-group-btn">
									<button class="btn btn-default" type="submit"><i class="fa fa-search"></i></button>
								</span>
						</div>
					</form>

				<span class="separator"></span>

				{{-- notifications preview controls --}}
					<ul class="notifications" style = "display:non;">
						<li>
							<a href="javascript:;" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-tasks"></i>
								<span class="badge">3</span>
							</a>
							<div class="dropdown-menu notification-menu large">
								<div class="notification-title">
									<span class="pull-right label label-default">3</span>
									Tasks
								</div>
								<div class="content">
									<ul>
										<li>
											<p class="clearfix mb-xs">
												<span class="message pull-left">Generating Sales Report</span>
												<span class="message pull-right text-dark">60%</span>
											</p>
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
											</div>
										</li>
										<li>
											<p class="clearfix mb-xs">
												<span class="message pull-left">Importing Contacts</span>
												<span class="message pull-right text-dark">98%</span>
											</p>
											<div class="progress progress-xs light">
												<div class="progress-bar" role="progressbar" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100" style="width: 98%;"></div>
											</div>
										</li>
										<li>
											<p class="clearfix mb-xs">
												<span class="message pull-left">Uploading something big</span>
												<span class="message pull-right text-dark">33%</span>
											</p>
											<div class="progress progress-xs light mb-xs">
												<div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;"></div>
											</div>
										</li>
									</ul>
								</div>
							</div>
						</li>
						<li>
							<a href="javascript:;" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-envelope"></i>
								<span class="badge">4</span>
							</a>
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default">230</span>
									Messages
								</div>
								<div class="content">
									<ul>
										<li>
											<a href="javascript:;" class="clearfix">
												<figure class="image">
													<img src="{!! AP_Asset("assets/img/!sample-user.jpg") !!}" alt="Joseph Doe Junior" class="img-circle" />
												</figure>
												<span class="title">Joseph Doe</span>
												<span class="message">Lorem ipsum dolor sit.</span>
											</a>
										</li>
										<li>
											<a href="javascript:;" class="clearfix">
												<figure class="image">
													<img src="{!! AP_Asset("assets/img/!sample-user.jpg") !!}"" alt="Joseph Junior" class="img-circle" />
												</figure>
												<span class="title">Joseph Junior</span>
												<span class="message truncate">Truncated message. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam, nec venenatis risus. Vestibulum blandit faucibus est et malesuada. Sed interdum cursus dui nec venenatis. Pellentesque non nisi lobortis, rutrum eros ut, convallis nisi. Sed tellus turpis, dignissim sit amet tristique quis, pretium id est. Sed aliquam diam diam, sit amet faucibus tellus ultricies eu. Aliquam lacinia nibh a metus bibendum, eu commodo eros commodo. Sed commodo molestie elit, a molestie lacus porttitor id. Donec facilisis varius sapien, ac fringilla velit porttitor et. Nam tincidunt gravida dui, sed pharetra odio pharetra nec. Duis consectetur venenatis pharetra. Vestibulum egestas nisi quis elementum elementum.</span>
											</a>
										</li>
										<li>
											<a href="javascript:;" class="clearfix">
												<figure class="image">
													<img src="{!! AP_Asset("assets/img/!sample-user.jpg") !!}" alt="Joe Junior" class="img-circle" />
												</figure>
												<span class="title">Joe Junior</span>
												<span class="message">Lorem ipsum dolor sit.</span>
											</a>
										</li>
										<li>
											<a href="javascript:;" class="clearfix">
												<figure class="image">
													<img src="{!! AP_Asset("assets/img/!sample-user.jpg") !!}" alt="Joseph Junior" class="img-circle" />
												</figure>
												<span class="title">Joseph Junior</span>
												<span class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam.</span>
											</a>
										</li>
									</ul>
									<hr />
									<div class="text-right">
										<a href="javascript:;" class="view-more">View All</a>
									</div>
								</div>
							</div>
						</li>
						<li>
							<a href="javascript:;" class="dropdown-toggle notification-icon" data-toggle="dropdown">
								<i class="fa fa-bell"></i>
								<span class="badge">3</span>
							</a>
							<div class="dropdown-menu notification-menu">
								<div class="notification-title">
									<span class="pull-right label label-default">3</span>
									Alerts
								</div>
								<div class="content">
									<ul>
										<li>
											<a href="javascript:;" class="clearfix">
												<div class="image">
													<i class="fa fa-thumbs-down bg-danger"></i>
												</div>
												<span class="title">Server is Down!</span>
												<span class="message">Just now</span>
											</a>
										</li>
										<li>
											<a href = "javascript:;" class="clearfix">
												<div class="image">
													<i class="fa fa-lock bg-warning"></i>
												</div>
												<span class="title">User Locked</span>
												<span class="message">15 minutes ago</span>
											</a>
										</li>
										<li>
											<a href="javascript:;" class="clearfix">
												<div class="image">
													<i class="fa fa-signal bg-success"></i>
												</div>
												<span class="title">Connection Restaured</span>
												<span class="message">10/10/2014</span>
											</a>
										</li>
									</ul>
									<hr />
									<div class="text-right">
										<a href="javascript:;" class="view-more">View All</a>
									</div>
								</div>
							</div>
						</li>
					</ul>

				<span class="separator"></span>

				{{-- main user data, and controls to my profile section, lock screen and logout --}}
					<div id="userbox" class="userbox">
						<a href="javascript:;" data-toggle="dropdown">
							{{-- profile image --}}
								<figure class="profile-picture">
									<img id = "user_profile_img_dropdown_navbar_{!! $userData["id"] !!}" data-navbar-user-profile-img = "" src="{!! AP_Asset("assets/images/profile/".$userData["profile_img"]) !!}" alt="{!! $userData["fullname"] !!}" class="img-circle" data-lock-picture="{!! AP_Asset("assets/img/!logged-user.jpg") !!}" />
								</figure>

							{{-- profile info (fullname and rolename) --}}
								<div class="profile-info">
									<span class="name" id = "user_fullname_dropodown_navbar_{!! $userData["id"] !!}" data-navbar-user-fullname = "">{!! $userData["fullname"] !!}</span>
									<span class="role" id = "my_role">{!! translate($roleData["name"]) !!}</span>
								</div>

							<i class="fa custom-caret"></i>
						</a>
						{{-- controls --}}
							<div class="dropdown-menu">
								<ul class="list-unstyled">
									<li class="divider"></li>
									{{-- 'my profile'  section link --}}
										<li>
											<a role="menuitem" tabindex="-1" href="{!! WEB_ROOT."/my-profile" !!}"><i class="fa fa-user"></i>&nbsp;{!! term("str_my_profile", true) !!}</a>
										</li>

									{{-- lock screen --}}
										<li>
											<a role="menuitem" tabindex="-1" href = "{!! WEB_ROOT !!}/lock-screen"><i class="fa fa-key"></i>&nbsp;{!! term("str_lock_screen", true) !!}</a>
										</li>

									{{-- logout --}}
										<li>
											<a role="menuitem" tabindex="-1" href = "{!! WEB_ROOT !!}/sign-out" id = "click_logout"><i class="fa fa-remove"></i>&nbsp;{!! term("str_logout", true) !!}</a>
										</li>
								</ul>
							</div>
					</div>
			</div>
		</header>