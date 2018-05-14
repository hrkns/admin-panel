//"use strict";
function module(){
/**/
	this.FLAGS = {};
	this.permises = {};
	this.items = {};
	this.idnodeparent = "";
	this.idnodediting = "";
	this.idnodedelete = "";

	var updating_menu = false;
	this.icons = ".ion-alert,.ion-alert-circled,.ion-android-add,.ion-android-add-circle,.ion-android-alarm-clock,.ion-android-alert,.ion-android-apps,.ion-android-archive,.ion-android-arrow-back,.ion-android-arrow-down,.ion-android-arrow-dropdown,.ion-android-arrow-dropdown-circle,.ion-android-arrow-dropleft,.ion-android-arrow-dropleft-circle,.ion-android-arrow-dropright,.ion-android-arrow-dropright-circle,.ion-android-arrow-dropup,.ion-android-arrow-dropup-circle,.ion-android-arrow-forward,.ion-android-arrow-up,.ion-android-attach,.ion-android-bar,.ion-android-bicycle,.ion-android-boat,.ion-android-bookmark,.ion-android-bulb,.ion-android-bus,.ion-android-calendar,.ion-android-call,.ion-android-camera,.ion-android-cancel,.ion-android-car,.ion-android-cart,.ion-android-chat,.ion-android-checkbox,.ion-android-checkbox-blank,.ion-android-checkbox-outline,.ion-android-checkbox-outline-blank,.ion-android-checkmark-circle,.ion-android-clipboard,.ion-android-close,.ion-android-cloud,.ion-android-cloud-circle,.ion-android-cloud-done,.ion-android-cloud-outline,.ion-android-color-palette,.ion-android-compass,.ion-android-contact,.ion-android-contacts,.ion-android-contract,.ion-android-create,.ion-android-delete,.ion-android-desktop,.ion-android-document,.ion-android-done,.ion-android-done-all,.ion-android-download,.ion-android-drafts,.ion-android-exit,.ion-android-expand,.ion-android-favorite,.ion-android-favorite-outline,.ion-android-film,.ion-android-folder,.ion-android-folder-open,.ion-android-funnel,.ion-android-globe,.ion-android-hand,.ion-android-hangout,.ion-android-happy,.ion-android-home,.ion-android-image,.ion-android-laptop,.ion-android-list,.ion-android-locate,.ion-android-lock,.ion-android-mail,.ion-android-map,.ion-android-menu,.ion-android-microphone,.ion-android-microphone-off,.ion-android-more-horizontal,.ion-android-more-vertical,.ion-android-navigate,.ion-android-notifications,.ion-android-notifications-none,.ion-android-notifications-off,.ion-android-open,.ion-android-options,.ion-android-people,.ion-android-person,.ion-android-person-add,.ion-android-phone-landscape,.ion-android-phone-portrait,.ion-android-pin,.ion-android-plane,.ion-android-playstore,.ion-android-print,.ion-android-radio-button-off,.ion-android-radio-button-on,.ion-android-refresh,.ion-android-remove,.ion-android-remove-circle,.ion-android-restaurant,.ion-android-sad,.ion-android-search,.ion-android-send,.ion-android-settings,.ion-android-share,.ion-android-share-alt,.ion-android-star,.ion-android-star-half,.ion-android-star-outline,.ion-android-stopwatch,.ion-android-subway,.ion-android-sunny,.ion-android-sync,.ion-android-textsms,.ion-android-time,.ion-android-train,.ion-android-unlock,.ion-android-upload,.ion-android-volume-down,.ion-android-volume-mute,.ion-android-volume-off,.ion-android-volume-up,.ion-android-walk,.ion-android-warning,.ion-android-watch,.ion-android-wifi,.ion-aperture,.ion-archive,.ion-arrow-down-a,.ion-arrow-down-b,.ion-arrow-down-c,.ion-arrow-expand,.ion-arrow-graph-down-left,.ion-arrow-graph-down-right,.ion-arrow-graph-up-left,.ion-arrow-graph-up-right,.ion-arrow-left-a,.ion-arrow-left-b,.ion-arrow-left-c,.ion-arrow-move,.ion-arrow-resize,.ion-arrow-return-left,.ion-arrow-return-right,.ion-arrow-right-a,.ion-arrow-right-b,.ion-arrow-right-c,.ion-arrow-shrink,.ion-arrow-swap,.ion-arrow-up-a,.ion-arrow-up-b,.ion-arrow-up-c,.ion-asterisk,.ion-at,.ion-backspace,.ion-backspace-outline,.ion-bag,.ion-battery-charging,.ion-battery-empty,.ion-battery-full,.ion-battery-half,.ion-battery-low,.ion-beaker,.ion-beer,.ion-bluetooth,.ion-bonfire,.ion-bookmark,.ion-bowtie,.ion-briefcase,.ion-bug,.ion-calculator,.ion-calendar,.ion-camera,.ion-card,.ion-cash,.ion-chatbox,.ion-chatbox-working,.ion-chatboxes,.ion-chatbubble,.ion-chatbubble-working,.ion-chatbubbles,.ion-checkmark,.ion-checkmark-circled,.ion-checkmark-round,.ion-chevron-down,.ion-chevron-left,.ion-chevron-right,.ion-chevron-up,.ion-clipboard,.ion-clock,.ion-close,.ion-close-circled,.ion-close-round,.ion-closed-captioning,.ion-cloud,.ion-code,.ion-code-download,.ion-code-working,.ion-coffee,.ion-compass,.ion-compose,.ion-connection-bars,.ion-contrast,.ion-crop,.ion-cube,.ion-disc,.ion-document,.ion-document-text,.ion-drag,.ion-earth,.ion-easel,.ion-edit,.ion-egg,.ion-eject,.ion-email,.ion-email-unread,.ion-erlenmeyer-flask,.ion-erlenmeyer-flask-bubbles,.ion-eye,.ion-eye-disabled,.ion-female,.ion-filing,.ion-film-marker,.ion-fireball,.ion-flag,.ion-flame,.ion-flash,.ion-flash-off,.ion-folder,.ion-fork,.ion-fork-repo,.ion-forward,.ion-funnel,.ion-gear-a,.ion-gear-b,.ion-grid,.ion-hammer,.ion-happy,.ion-happy-outline,.ion-headphone,.ion-heart,.ion-heart-broken,.ion-help,.ion-help-buoy,.ion-help-circled,.ion-home,.ion-icecream,.ion-image,.ion-images,.ion-information,.ion-information-circled,.ion-ionic,.ion-ios-alarm,.ion-ios-alarm-outline,.ion-ios-albums,.ion-ios-albums-outline,.ion-ios-americanfootball,.ion-ios-americanfootball-outline,.ion-ios-analytics,.ion-ios-analytics-outline,.ion-ios-arrow-back,.ion-ios-arrow-down,.ion-ios-arrow-forward,.ion-ios-arrow-left,.ion-ios-arrow-right,.ion-ios-arrow-thin-down,.ion-ios-arrow-thin-left,.ion-ios-arrow-thin-right,.ion-ios-arrow-thin-up,.ion-ios-arrow-up,.ion-ios-at,.ion-ios-at-outline,.ion-ios-barcode,.ion-ios-barcode-outline,.ion-ios-baseball,.ion-ios-baseball-outline,.ion-ios-basketball,.ion-ios-basketball-outline,.ion-ios-bell,.ion-ios-bell-outline,.ion-ios-body,.ion-ios-body-outline,.ion-ios-bolt,.ion-ios-bolt-outline,.ion-ios-book,.ion-ios-book-outline,.ion-ios-bookmarks,.ion-ios-bookmarks-outline,.ion-ios-box,.ion-ios-box-outline,.ion-ios-briefcase,.ion-ios-briefcase-outline,.ion-ios-browsers,.ion-ios-browsers-outline,.ion-ios-calculator,.ion-ios-calculator-outline,.ion-ios-calendar,.ion-ios-calendar-outline,.ion-ios-camera,.ion-ios-camera-outline,.ion-ios-cart,.ion-ios-cart-outline,.ion-ios-chatboxes,.ion-ios-chatboxes-outline,.ion-ios-chatbubble,.ion-ios-chatbubble-outline,.ion-ios-checkmark,.ion-ios-checkmark-empty,.ion-ios-checkmark-outline,.ion-ios-circle-filled,.ion-ios-circle-outline,.ion-ios-clock,.ion-ios-clock-outline,.ion-ios-close,.ion-ios-close-empty,.ion-ios-close-outline,.ion-ios-cloud,.ion-ios-cloud-download,.ion-ios-cloud-download-outline,.ion-ios-cloud-outline,.ion-ios-cloud-upload,.ion-ios-cloud-upload-outline,.ion-ios-cloudy,.ion-ios-cloudy-night,.ion-ios-cloudy-night-outline,.ion-ios-cloudy-outline,.ion-ios-cog,.ion-ios-cog-outline,.ion-ios-color-filter,.ion-ios-color-filter-outline,.ion-ios-color-wand,.ion-ios-color-wand-outline,.ion-ios-compose,.ion-ios-compose-outline,.ion-ios-contact,.ion-ios-contact-outline,.ion-ios-copy,.ion-ios-copy-outline,.ion-ios-crop,.ion-ios-crop-strong,.ion-ios-download,.ion-ios-download-outline,.ion-ios-drag,.ion-ios-email,.ion-ios-email-outline,.ion-ios-eye,.ion-ios-eye-outline,.ion-ios-fastforward,.ion-ios-fastforward-outline,.ion-ios-filing,.ion-ios-filing-outline,.ion-ios-film,.ion-ios-film-outline,.ion-ios-flag,.ion-ios-flag-outline,.ion-ios-flame,.ion-ios-flame-outline,.ion-ios-flask,.ion-ios-flask-outline,.ion-ios-flower,.ion-ios-flower-outline,.ion-ios-folder,.ion-ios-folder-outline,.ion-ios-football,.ion-ios-football-outline,.ion-ios-game-controller-a,.ion-ios-game-controller-a-outline,.ion-ios-game-controller-b,.ion-ios-game-controller-b-outline,.ion-ios-gear,.ion-ios-gear-outline,.ion-ios-glasses,.ion-ios-glasses-outline,.ion-ios-grid-view,.ion-ios-grid-view-outline,.ion-ios-heart,.ion-ios-heart-outline,.ion-ios-help,.ion-ios-help-empty,.ion-ios-help-outline,.ion-ios-home,.ion-ios-home-outline,.ion-ios-infinite,.ion-ios-infinite-outline,.ion-ios-information,.ion-ios-information-empty,.ion-ios-information-outline,.ion-ios-ionic-outline,.ion-ios-keypad,.ion-ios-keypad-outline,.ion-ios-lightbulb,.ion-ios-lightbulb-outline,.ion-ios-list,.ion-ios-list-outline,.ion-ios-location,.ion-ios-location-outline,.ion-ios-locked,.ion-ios-locked-outline,.ion-ios-loop,.ion-ios-loop-strong,.ion-ios-medical,.ion-ios-medical-outline,.ion-ios-medkit,.ion-ios-medkit-outline,.ion-ios-mic,.ion-ios-mic-off,.ion-ios-mic-outline,.ion-ios-minus,.ion-ios-minus-empty,.ion-ios-minus-outline,.ion-ios-monitor,.ion-ios-monitor-outline,.ion-ios-moon,.ion-ios-moon-outline,.ion-ios-more,.ion-ios-more-outline,.ion-ios-musical-note,.ion-ios-musical-notes,.ion-ios-navigate,.ion-ios-navigate-outline,.ion-ios-nutrition,.ion-ios-nutrition-outline,.ion-ios-paper,.ion-ios-paper-outline,.ion-ios-paperplane,.ion-ios-paperplane-outline,.ion-ios-partlysunny,.ion-ios-partlysunny-outline,.ion-ios-pause,.ion-ios-pause-outline,.ion-ios-paw,.ion-ios-paw-outline,.ion-ios-people,.ion-ios-people-outline,.ion-ios-person,.ion-ios-person-outline,.ion-ios-personadd,.ion-ios-personadd-outline,.ion-ios-photos,.ion-ios-photos-outline,.ion-ios-pie,.ion-ios-pie-outline,.ion-ios-pint,.ion-ios-pint-outline,.ion-ios-play,.ion-ios-play-outline,.ion-ios-plus,.ion-ios-plus-empty,.ion-ios-plus-outline,.ion-ios-pricetag,.ion-ios-pricetag-outline,.ion-ios-pricetags,.ion-ios-pricetags-outline,.ion-ios-printer,.ion-ios-printer-outline,.ion-ios-pulse,.ion-ios-pulse-strong,.ion-ios-rainy,.ion-ios-rainy-outline,.ion-ios-recording,.ion-ios-recording-outline,.ion-ios-redo,.ion-ios-redo-outline,.ion-ios-refresh,.ion-ios-refresh-empty,.ion-ios-refresh-outline,.ion-ios-reload,.ion-ios-reverse-camera,.ion-ios-reverse-camera-outline,.ion-ios-rewind,.ion-ios-rewind-outline,.ion-ios-rose,.ion-ios-rose-outline,.ion-ios-search,.ion-ios-search-strong,.ion-ios-settings,.ion-ios-settings-strong,.ion-ios-shuffle,.ion-ios-shuffle-strong,.ion-ios-skipbackward,.ion-ios-skipbackward-outline,.ion-ios-skipforward,.ion-ios-skipforward-outline,.ion-ios-snowy,.ion-ios-speedometer,.ion-ios-speedometer-outline,.ion-ios-star,.ion-ios-star-half,.ion-ios-star-outline,.ion-ios-stopwatch,.ion-ios-stopwatch-outline,.ion-ios-sunny,.ion-ios-sunny-outline,.ion-ios-telephone,.ion-ios-telephone-outline,.ion-ios-tennisball,.ion-ios-tennisball-outline,.ion-ios-thunderstorm,.ion-ios-thunderstorm-outline,.ion-ios-time,.ion-ios-time-outline,.ion-ios-timer,.ion-ios-timer-outline,.ion-ios-toggle,.ion-ios-toggle-outline,.ion-ios-trash,.ion-ios-trash-outline,.ion-ios-undo,.ion-ios-undo-outline,.ion-ios-unlocked,.ion-ios-unlocked-outline,.ion-ios-upload,.ion-ios-upload-outline,.ion-ios-videocam,.ion-ios-videocam-outline,.ion-ios-volume-high,.ion-ios-volume-low,.ion-ios-wineglass,.ion-ios-wineglass-outline,.ion-ios-world,.ion-ios-world-outline,.ion-ipad,.ion-iphone,.ion-ipod,.ion-jet,.ion-key,.ion-knife,.ion-laptop,.ion-leaf,.ion-levels,.ion-lightbulb,.ion-link,.ion-load-a,.ion-load-b,.ion-load-c,.ion-load-d,.ion-location,.ion-lock-combination,.ion-locked,.ion-log-in,.ion-log-out,.ion-loop,.ion-magnet,.ion-male,.ion-man,.ion-map,.ion-medkit,.ion-merge,.ion-mic-a,.ion-mic-b,.ion-mic-c,.ion-minus,.ion-minus-circled,.ion-minus-round,.ion-model-s,.ion-monitor,.ion-more,.ion-mouse,.ion-music-note,.ion-navicon,.ion-navicon-round,.ion-navigate,.ion-network,.ion-no-smoking,.ion-nuclear,.ion-outlet,.ion-paintbrush,.ion-paintbucket,.ion-paper-airplane,.ion-paperclip,.ion-pause,.ion-person,.ion-person-add,.ion-person-stalker,.ion-pie-graph,.ion-pin,.ion-pinpoint,.ion-pizza,.ion-plane,.ion-planet,.ion-play,.ion-playstation,.ion-plus,.ion-plus-circled,.ion-plus-round,.ion-podium,.ion-pound,.ion-power,.ion-pricetag,.ion-pricetags,.ion-printer,.ion-pull-request,.ion-qr-scanner,.ion-quote,.ion-radio-waves,.ion-record,.ion-refresh,.ion-reply,.ion-reply-all,.ion-ribbon-a,.ion-ribbon-b,.ion-sad,.ion-sad-outline,.ion-scissors,.ion-search,.ion-settings,.ion-share,.ion-shuffle,.ion-skip-backward,.ion-skip-forward,.ion-social-android,.ion-social-android-outline,.ion-social-angular,.ion-social-angular-outline,.ion-social-apple,.ion-social-apple-outline,.ion-social-bitcoin,.ion-social-bitcoin-outline,.ion-social-buffer,.ion-social-buffer-outline,.ion-social-chrome,.ion-social-chrome-outline,.ion-social-codepen,.ion-social-codepen-outline,.ion-social-css3,.ion-social-css3-outline,.ion-social-designernews,.ion-social-designernews-outline,.ion-social-dribbble,.ion-social-dribbble-outline,.ion-social-dropbox,.ion-social-dropbox-outline,.ion-social-euro,.ion-social-euro-outline,.ion-social-facebook,.ion-social-facebook-outline,.ion-social-foursquare,.ion-social-foursquare-outline,.ion-social-freebsd-devil,.ion-social-github,.ion-social-github-outline,.ion-social-google,.ion-social-google-outline,.ion-social-googleplus,.ion-social-googleplus-outline,.ion-social-hackernews,.ion-social-hackernews-outline,.ion-social-html5,.ion-social-html5-outline,.ion-social-instagram,.ion-social-instagram-outline,.ion-social-javascript,.ion-social-javascript-outline,.ion-social-linkedin,.ion-social-linkedin-outline,.ion-social-markdown,.ion-social-nodejs,.ion-social-octocat,.ion-social-pinterest,.ion-social-pinterest-outline,.ion-social-python,.ion-social-reddit,.ion-social-reddit-outline,.ion-social-rss,.ion-social-rss-outline,.ion-social-sass,.ion-social-skype,.ion-social-skype-outline,.ion-social-snapchat,.ion-social-snapchat-outline,.ion-social-tumblr,.ion-social-tumblr-outline,.ion-social-tux,.ion-social-twitch,.ion-social-twitch-outline,.ion-social-twitter,.ion-social-twitter-outline,.ion-social-usd,.ion-social-usd-outline,.ion-social-vimeo,.ion-social-vimeo-outline,.ion-social-whatsapp,.ion-social-whatsapp-outline,.ion-social-windows,.ion-social-windows-outline,.ion-social-wordpress,.ion-social-wordpress-outline,.ion-social-yahoo,.ion-social-yahoo-outline,.ion-social-yen,.ion-social-yen-outline,.ion-social-youtube,.ion-social-youtube-outline,.ion-soup-can,.ion-soup-can-outline,.ion-speakerphone,.ion-speedometer,.ion-spoon,.ion-star,.ion-stats-bars,.ion-steam,.ion-stop,.ion-thermometer,.ion-thumbsdown,.ion-thumbsup,.ion-toggle,.ion-toggle-filled,.ion-transgender,.ion-trash-a,.ion-trash-b,.ion-trophy,.ion-tshirt,.ion-tshirt-outline,.ion-umbrella,.ion-university,.ion-unlocked,.ion-upload,.ion-usb,.ion-videocamera,.ion-volume-high,.ion-volume-low,.ion-volume-medium,.ion-volume-mute,.ion-wand,.ion-waterdrop,.ion-wifi,.ion-wineglass,.ion-woman,.ion-wrench,.ion-xbox";
	/***********************************************************************************************************/

	this.update_menu = function(){
		if(updating_menu){
			return;
		}

		var data = {id:null, children:[]};

		function rec(node, d){
			if(node == null){
				return;
			}

			var chld = node.childNodes,
				nc = chld.length,
				pos = 0;

			for(var i = 0; i < nc; i++){
				if(chld[i].nodeType == 1 && chld[i].tagName.toLowerCase() == "li"){
					var json = {
						id:chld[i].getAttribute("data-id"),
						text:chld[i].getAttribute("data-text"),
						route:chld[i].getAttribute("data-route").substr(chld[i].getAttribute("data-route").lastIndexOf("/") != -1?chld[i].getAttribute("data-route").lastIndexOf("/")+1:0),
						icon:chld[i].getAttribute("data-icon"),
						pos:pos++,
						children:[]
					}

					rec(document.getElementById("node_ul_"+chld[i].getAttribute("data-id")), json);
					d.children.push(json);
				};
			}
		}

		rec(document.getElementById("dirs_str"), data);
		updating_menu = true;
		App.ShowLoading(App.terms.str_updating_menu);
		App.LockScreen();

		App.HTTP.update({
			url:App.WEB_ROOT+"/menu",
			data:data,
			success:function(d){
				document.getElementById("content_iframe").contentWindow.$("#reload_page").show();
				document.getElementById("content_iframe").contentWindow.$("#upd_menu").hide();
			},after:function(x, y, z){
				updating_menu = false;
				App.HideLoading();
				App.UnlockScreen();
			}
		});
	}

	this.move_node = function(idn, idp, position){
		var li = $("li[data-id='"+idn+"']")[0],
			ul = $($("li[data-id='"+idp+"']")[0]),
			nli = $(li).clone(true, true);

		$(li).remove();

		if(idp != "#"){
			if(ul.children().length == 0){
				var un = document.createElement("ul");
				un.id = "node_ul_"+idp;
				$(ul).append(un);
				ul = un;
				$(ul).append(nli);
			}else{
				ul = ul.children()[0];
				var xli = $(ul).children().first();
				position=Number(position);

				while(position-- > 0){
					xli = $(xli).next();
				}

				$(nli).insertBefore(xli);
			}
		}else{
			var x = $("#dirs_str").children().first();

			while(position--){
				x = $(x).next();
			}

			$(nli).insertBefore(x);
		}
	}

	this.icons = this.icons.split(",");

	this.createIcon = function(str){
		str = str.trim();
		var icon = document.createElement("i");
		icon.className = "icon "+str.substr(1);

		var aselect = document.createElement("a");
		aselect.href = "javascript:;";
		aselect.appendChild(icon);

		var column = document.createElement("div");
		column.className = "col-sm-1";
		column.appendChild(aselect);
		column.style.border = "solid 0.5px";
		column.style.backgroundColor = icon.className == "fa fa-folder"?"black":"";
		column.setAttribute("data-selected", icon.className == "fa fa-folder"?"1":"0");
		column.onclick = function(){
			$(this).parent().children().css("background-color", "white").attr("data-selected", "0");
			$(this).parent().find("i[class='"+icon.className+"']").parent().parent().css("background-color", "black").attr("data-selected", "1");
		}

		return column;
	}

	App.TimeInterval(function(){
		var thing = document.getElementById("content_iframe");

		if(thing != null){
			Section.FLAGS.LET_CHANGE_SECTION = thing.contentWindow.$("#upd_menu").css("display") == "none";
		}
	}, 1000);

	this.start = function(){
		if(this.permises["create"]){
			this.items["create"] = {
				"label": App.terms.str_add_access,
				"action": function (obj) {
					window.parent.Section.idnodeparent = obj.reference[0].id.substr(0, obj.reference[0].id.indexOf("_anchor"));
					window.parent.App.getView("access", "create");
				},
			};
		}

		if(this.permises["update"]){
			this.items["update"] = {
				"label": App.terms.str_update,
				"action": function (obj) {
					window.parent.App.getView("access", "edit", function(){
						window.parent.Section.idnodediting = obj.reference[0].id.substr(0, obj.reference[0].id.indexOf("_anchor"));
						window.parent.$("#modal_access_edit").find("input[name='name']").val(window.parent.$("li[data-id='"+window.parent.Section.idnodediting+"']").attr("data-text"));
						window.parent.$("#modal_access_edit").find("input[name='route']").val(window.parent.$("li[data-id='"+window.parent.Section.idnodediting+"']").attr("data-route"));
						window.parent.$("#access_update_list_icons").children().css("background-color", "white").attr("data-selected", "0");
						window.parent.$("#access_update_list_icons").find("i[class='"+window.parent.$("li[data-id='"+window.parent.Section.idnodediting+"']").attr("data-icon")+"']").parent().parent().css("background-color", "black").attr("data-selected", "1");
					});
				},
			};
		}

		if(this.permises["delete"]){
			this.items["delete"] = {
				"label": App.terms.str_remove,
				"action": function (obj) {
					window.parent.Section.idnodedelete = obj.reference[0].id.substr(0, obj.reference[0].id.indexOf("_anchor"));
					window.parent.App.getView("access", "delete");
				},
			};
		}
	}
}