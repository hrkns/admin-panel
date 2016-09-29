<?php
	use App\Models\PanelAdminSection;
	use App\Models\PanelAdminRole;
	use App\Models\PanelAdminRoleSection;
	use App\Models\PanelAdminRoleSectionAction;
	use App\Models\UserRole;

	$tmp = GetForUse("PanelAdminAction");
	$id_read = null;

	foreach ($tmp as $key => $value){
		if($value->code == "read"){
			$id_read = $value->id;
			break;
		}
	}

	$sections_flag = array();
	$tmp = PanelAdminSection::all();

	foreach($tmp as $key => $value){
		$sections_flag[$value->id] = false;
	}

	$roles = UserRole::where("id_user", "=", Request::session()->get("iduser"))->get();

	foreach ($roles as $key => $value){
		$role_sections = PanelAdminRoleSection::where("id_panel_admin_role", "=", $value->id_role)->get();

		foreach ($role_sections as $key => $role_section) {
			$actions = PanelAdminRoleSectionAction::where("id_panel_admin_role_section", "=", $role_section->id)
												  ->where("id_action", "=", $id_read)->get();
		   	$sections_flag[strval($role_section->id_section)] = count($actions)>0;
		}
	}
?>

<div class="inner-wrapper">
	<aside id="sidebar-left" class="sidebar-left">
		<div class="sidebar-header">
			<div class="sidebar-title">
				
			</div>
			<div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
				<i class="fa fa-bars" aria-label="Toggle sidebar"></i>
			</div>
		</div>
		<div class="nano">
			<div class="nano-content">
				<nav id="menu" class="nav-main" role="navigation">
					<ul class="nav nav-main" id = "lateral_menu">
						<?php
							function print_item_menu($item, $sections_flag, $pre_route=""){
								if(!$sections_flag[$item->id])
									return false;

								$children = PanelAdminSection::where("id_parent", "=", $item->id)->orderBy("position", "asc")->get();
								$n = $children->count();
								$nameliclass = "nav-active".($n > 0?" nav-parent":"");
								?>
								<li class = "{!! $nameliclass !!}">
									<a onclick = "App.click_on_side_menu_option(this);return false;" href="{!! $pre_route.$item->route_name !!}" data-id="{!! $item->id !!}" data-route="{!! $pre_route.$item->route_name !!}">
										<i class="{!! $item->icon !!}"  style = "color:#abb4be;" aria-hidden="true"></i>
										<span style = "color:#abb4be;">{!! translate($item->name) !!}</span>
									</a>
									<?php
									if($n>0)
									{
										?>
										<ul class="nav nav-children">
											<?php
											foreach ($children as $key => $value)
												print_item_menu($value, $sections_flag, $pre_route.$item->route_name."/");
											?>
										</ul>
										<?php
									}
									?>
								</li>
								<?php
							}

							$items = PanelAdminSection::whereNull("id_parent")->orderBy("position", "asc")->get();

							foreach ($items as $key => $value){
								print_item_menu($value, $sections_flag, WEB_ROOT."/");
							}
						?>
					</ul>
				</nav>
				<hr class="separator" />
			</div>
		</div>
	</aside>