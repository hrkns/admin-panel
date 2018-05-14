<?php
	use App\Models\UserSectionAmountTimesVisited;
	use App\Models\PanelAdminSection;
?>

<div style = "margin:1.5%;">
	<h1>
		{!! term("str_welcome_to_admin_panel_base") !!}
	</h1>

	<?php
		$times = UserSectionAmountTimesVisited::where("id_user", "=", Request::session()->get("iduser"))->orderBy("amount_times_visited", "desc")->get();
		$n = count($times);

		if($n > 0){
			?>
				<div class = "row">
					<div class = "col-sm-12">
						<h3>
							{!! term("str_last_visited_section") !!}
						</h3>
					</div>
					<div class = "col-sm-12">
						<?php
							$maxdate = "";
							$item = null;

							foreach ($times as $key => $value) {
								if($value->moment > $maxdate || ($item != null && $item->route_name == "home")){
									$tmp = $value;
									$section = PanelAdminSection::where("id", "=", $value->id_section)->get()[0];

									if($section->route_name != "home" || $item == null){
										$item = $section;
										$maxdate = $value->moment;
									}
								}
							}
						?>
						<a href = "javascript:;" onclick = "$('#lateral_menu').find('a[data-id={!! $item->id !!}]').trigger('click');" style = "color:black;text-decoration:none;"><button class = "btn btn-block"><i class = "{!! $item->icon !!}"></i>&nbsp;{!! translate($item->name) !!}</button></a>
					</div>
					<div class = "col-sm-12">
						<br>
						<h3>
							{!! term("str_most_visited_sections") !!}
						</h3>
					</div>
					<?php
						for ($i=0; $i < ($n < 6?$n:6); $i++) { 
							$section = PanelAdminSection::where("id", "=", $times[$i]->id_section)->get()[0];
							?>
							<div class = "col-sm-2">
								<a href = "javascript:;" onclick = "$('#lateral_menu').find('a[data-id={!! $section->id !!}]').trigger('click');" style = "color:black;text-decoration:none;"><button class = "btn btn-block"><i class = "{!! $section->icon !!}"></i>&nbsp;{!! translate($section->name) !!}</button></a>
							</div>
							<?php
						}
					?>
				</div>
			<?php
		}
	?>

	<hr>

	<p>
		{!! term("str_this_template_provides") !!}
	</p>
	<ul>
		<li>
			<a href = "javascript:;" onclick = "$('#sub_options_1').toggle(App.TIME_FOR_SHOW);">
				<strong>
					{!! term("str_users_registration") !!}
				</strong>
			</a>
			<ul id = "sub_options_1" style = "display:none;">
				<li>
					{!! term("str_public_registration") !!}
				</li>
				<li>
					{!! term("str_by_an_admin") !!}
				</li>
			</ul>
		</li>
		<br>
		<li>
			<a href = "javascript:;" onclick = "$('#sub_options_2').toggle(App.TIME_FOR_SHOW);">
				<strong>
					{!! term("str_login") !!}
				</strong>
			</a>
			<ul id = "sub_options_2" style = "display:none;">
				<li>
					{!! term("str_session_duration_timelimit") !!}
					({!! term("str_optional") !!}, {!! term("str_see") !!} <strong><a href = "{!! WEB_ROOT !!}/settings" target = "_blank">
					{!! term("str_preferences") !!}
					</a></strong>)
				</li>
				<li>
					{!! term("str_inactivity_timelimit") !!}
					({!! term("str_optional") !!}, {!! term("str_see") !!} <strong><a href = "{!! WEB_ROOT !!}/settings" target = "_blank">
					{!! term("str_preferences") !!}
					</a></strong>)
				</li>
			</ul>
		</li>
		<br>
		<li>
			<a href = "javascript:;" onclick = "$('#sub_options_3').toggle(App.TIME_FOR_SHOW);">
				<strong>
					{!! term("str_account_recovering") !!}
				</strong>
			</a>
			<div id = "sub_options_3" style = "display:none;">
				<ul>
					<li>
						{!! term("str_through_link") !!}
					</li>
					<li>
						{!! term("str_sending_new_password") !!}
					</li>
					<li>
						{!! term("str_manual_by_an_admin") !!}
					</li>
				</ul>
				{!! term("str_see2") !!} <strong><a href = "{!! WEB_ROOT !!}/settings" target = "_blank">{!! term("str_preferences") !!}</a></strong>
			</div>
		</li>
		<br>
		<li>
			<a href = "javascript:;" onclick = "$('#sub_options_4').toggle(App.TIME_FOR_SHOW);">
				<strong>{!! term("str_roles_system") !!} </strong>
			</a>
			<div id = "sub_options_4" style = "display:none;">
				{!! term("str_roles_system_desc_1") !!} <strong>{!! term("str_administrator") !!}</strong> {!! term("str_roles_system_desc_2") !!} <em>{!! term("str_creating") !!}</em>, <em>{!! term("str_reading") !!}</em>, <em>{!! term("str_updating") !!}</em> {!! term("str_and") !!} <em>{!! term("str_deleting") !!}</em> {!! term("str_roles_system_desc_3") !!} <strong>{!! term("str_guest") !!}</strong> {!! term("str_roles_system_desc_4") !!} <em>{!! term("str_reading") !!}</em>.
				<ul>
					<li>
						<strong>{!! term("str_statuses") !!} </strong>{!! term("str_statuses_desc_1") !!}
					</li>
					<li>
						<strong>{!! term("str_actions") !!} </strong>{!! term("str_actions_desc_1") !!} <em> {!! term("str_read") !!}</em>, <em>{!! term("str_create") !!}</em>, <em>{!! term("str_update") !!}</em> y <em>{!! term("str_delete") !!}</em>; {!! term("str_actions_desc_2") !!}
					</li>
					<li>
						<strong>{!! term("str_sections") !!} </strong>{!! term("str_sections_desc_1") !!}
						<br>
						{!! term("str_if_you_dev") !!} <strong><a href = "javascript:;" onclick = "$('#developer_orientations_1').toggle(App.TIME_FOR_SHOW)">{!! term("str_here") !!}</a></strong>
						<div id = "developer_orientations_1" style = "display:none;padding:2.5%;border:solid 0.5px;margin:2%;">
							{!! term("str_if_you_dev_1") !!}
							 <em>*.blade.php</em>, {!! term("str_if_you_dev_2") !!} <strong><em>{!! PROJECT_SYSTEM_ROOT !!}/resources/views/app/sections</em></strong>. {!! term("str_if_you_dev_3") !!}
						</div>
					</li>
					<li>
						<strong>{!! term("str_roles") !!} </strong>{!! term("str_roles_desc_1") !!}
						<br>
						{!! term("str_if_you_dev") !!} <strong><a href = "javascript:;" onclick = "$('#developer_orientations_2').toggle(App.TIME_FOR_SHOW)">{!! term("str_here") !!}</a></strong>
						<div id = "developer_orientations_2" style = "display:none;padding:2.5%;border:solid 0.5px;margin:2%;">
							
							{!! term("str_if_you_dev_4") !!}
							<ul>
								<li>
									{!! term("str_if_you_dev_5") !!}
									<em><strong>/section/{idsection}</strong></em>
								</li>
								<br>
								<li>
									{!! term("str_if_you_dev_6") !!}
									 <strong>Section</strong> ({!! term("str_if_you_dev_7") !!} <strong>{!! PROJECT_SYSTEM_ROOT !!}/app/Http/Controllers</strong>), {!! term("str_if_you_dev_8") !!} <em>show</em> ({!! term("str_if_you_dev_9") !!} <strong>{!! PROJECT_SYSTEM_ROOT !!}/app/Http</strong>)
								</li>
								<br>
								<li>
									{!! term("str_if_you_dev_10") !!}
									 ({!! term("str_see") !!} <em>{!! term("str_if_you_dev_11") !!}</em>) {!! term("str_if_you_dev_12") !!}
									<ul>
										<li>
											{!! term("str_if_you_dev_13") !!}
											 <em>$terms</em>
										</li>
										<li>
											{!! term("str_if_you_dev_14") !!}
											 <em>$permises</em>. {!! term("str_if_you_dev_15") !!}
										</li>
									</ul>
								</li>
								<br>
								<li>
									{!! term("str_if_you_dev_16") !!}
									
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</li>
		<br>
		<li>
			<a href = "javascript:;" onclick = "$('#sub_options_5').toggle(App.TIME_FOR_SHOW);">
				<strong>{!! term("str_languages") !!} </strong>
			</a>
			<div id = "sub_options_5" style = "display:none;">
				{!! term("str_languages_desc_1") !!}
				<br>
				<strong>{!! term("str_admin_terms") !!}  </strong>{!! term("str_admin_terms_desc_1") !!} <em>{!! term("str_admin_terms_desc_2") !!}</em>) {!! term("str_admin_terms_desc_3") !!}
				<br>
				{!! term("str_admin_terms_desc_4") !!}
				 <em><strong>Maestros/Idiomas/Términos</strong></em>). 
				 {!! term("str_admin_terms_desc_5") !!}
				<br>
				{!! term("str_if_you_dev") !!} <strong><a href = "javascript:;" onclick = "$('#developer_orientations_3').toggle(App.TIME_FOR_SHOW)">{!! term("str_here") !!}</a></strong>
				<div id = "developer_orientations_3" style = "display:none;padding:2.5%;border:solid 0.5px;margin:2%;">
					{!! term("str_admin_terms_desc_6") !!} <em>*.blade.php</em> {!! term("str_admin_terms_desc_7") !!}  <em>{!! PROJECT_SYSTEM_ROOT !!}/resources/views/app/sections</em>. {!! term("str_admin_terms_desc_8") !!}<em> $terms</em>, {!! term("str_admin_terms_desc_9") !!}
					<p>
						<div style = "padding:2%;">
							<em>
								<span style = "color:green;font-weight:bold;">echo</span> term(code_term);
							</em>
						</div>
						<br>
						{!! term("str_admin_terms_desc_10") !!} <em>code_term</em>  {!! term("str_admin_terms_desc_11") !!}
					</p>
				</div>
			</div>
		</li>
	</ul>
	<br>
	<br>

	<p>{!! term("str_for_developers") !!}&nbsp;&nbsp;
	<a href = "javascript:;" onclick = '$("#devs").toggle(App.TIME_FOR_SHOW);'>
		<i class = "fa fa-plus"></i>
	</a></p>
	<ul id = "devs" style = "display:none;">
		<li>
			<p>
				<strong>{!! term("str_views_directory") !!} </strong><em>{!! PROJECT_SYSTEM_ROOT."/resources/views/app/" !!}</em>&nbsp;&nbsp;
				<a href = "javascript:;" onclick = '$("#devs_1").toggle(App.TIME_FOR_SHOW);'>
					<i class = "fa fa-plus"></i>
				</a>
			</p>
			<ul id = "devs_1" style = "display:none;">
				<li>
					{!! term("str_dev_desc_1") !!} <em>not-logged</em>, {!! term("str_dev_desc_2") !!}  <em>not-logged.blade.php</em>
				</li>
				<li>
					{!! term("str_dev_desc_3") !!} <em>logged</em>, {!! term("str_dev_desc_4") !!} <em>logged.blade.php</em>
				</li>
				<li>
					{!! term("str_dev_desc_5") !!} <em>lock-screen.blade.php</em>
				</li>
				<li>
					{!! term("str_dev_desc_6") !!} <em>sections</em>
				</li>
				<li>
					{!! term("str_the_folder") !!} <em>modals</em> {!! term("str_dev_desc_7") !!} <em>onModalRequest</em>. {!! term("str_see_file") !!} <em>{!! PROJECT_SYSTEM_ROOT."/app/Http/" !!}routes.php</em> {!! term("str_dev_desc_8") !!}
				</li>
			</ul>
		</li>

		<li>
			<p>
				{!! term("str_dev_desc_9") !!} <em>{!! PROJECT_WEB_ROOT."/js/" !!}</em>&nbsp;&nbsp;
				<a href = "javascript:;" onclick = '$("#devs_2").toggle(App.TIME_FOR_SHOW);'>
					<i class = "fa fa-plus"></i>
				</a>
			</p>
			<ul id = "devs_2" style = "display:none;">
				<li>
					{!! term("str_dev_desc_10") !!} <em>__languages__</em> {!! term("str_dev_desc_11") !!}
				</li>
				<li>
					{!! term("str_the_file") !!} <em>admin-panel.js</em> {!! term("str_dev_desc_12") !!}
				</li>
				<li>
					{!! term("str_dev_desc_10") !!} <em>sections</em> {!! term("str_dev_desc_13") !!}
				</li>
				<li>
					{!! term("str_dev_desc_10") !!} <em>actions</em> {!! term("str_dev_desc_14") !!}
				</li>
			</ul>
		</li>

		<li>
			<p>
				{!! term("str_dev_desc_15") !!} <em>{!! PROJECT_SYSTEM_ROOT."/app/Models/" !!}</em>&nbsp;&nbsp;
				<a href = "javascript:;" onclick = '$("#devs_3").toggle(App.TIME_FOR_SHOW);'>
					<i class = "fa fa-plus"></i>
				</a>
			</p>
			<ul id = "devs_3" style = "display:none;">
				<li>
					{!! term("str_dev_desc_16") !!}
				</li>
			</ul>
		</li>

		<li>
			<p>
				<strong>{!! term("str_dev_desc_17") !!} </strong><em>{!! PROJECT_SYSTEM_ROOT."/app/Http/Controllers" !!}</em>
			</p>
		</li>

		<li>
			<p>
				{!! term("str_dev_desc_18") !!} <strong><em>{!! PROJECT_SYSTEM_ROOT."/admin-panel-settings.php" !!}</em></strong>
			</p>
		</li>

		<li>
			<p>
				<strong>{!! term("str_dev_desc_19") !!} </strong><em>{!! PROJECT_SYSTEM_ROOT."/app/Http/routes.php" !!}</em>
			</p>
		</li>

		<li>
			<p>
				<strong>{!! term("str_dev_desc_20") !!} </strong><em>{!! PROJECT_SYSTEM_ROOT."/app/helpers.php" !!}</em>&nbsp;&nbsp;
				<a href = "javascript:;" onclick = '$("#devs_4").toggle(App.TIME_FOR_SHOW);'>
					<i class = "fa fa-plus"></i>
				</a>
			</p>
			<ul id = "devs_4" style = "display:none;">
				<li>
					{!! term("str_dev_desc_21") !!} (<em>constants_actions.php, constans_http_codes.php, constanst_http_messages.php, constants_statuses.php</em>).
				</li>
			</ul>
		</li>

		<li>
			{!! term("str_dev_desc_22") !!}  <em>{!! PROJECT_SYSTEM_ROOT."/storage/logs/laravel.log" !!}</em>
		</li>

		<li>
			{!! term("str_dev_desc_23") !!}  <em>{!! PROJECT_SYSTEM_ROOT."/config/database.php" !!}</em>
		</li>
	</ul>

	<br>
	<br>
	<br>
	<br>
	<br>
	@include("app.sections.include.section-resources")
</div>