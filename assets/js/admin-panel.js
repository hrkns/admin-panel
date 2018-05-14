//"use strict";
function app(){
	/*
		variables and fields of the 'App' object
	*/
		/*
			this var tracks the navigation history of a user, after he loaded the page
		*/
		var locations_history = [];

		/*
			index used to reference the item (that is part of 'locations_history') where the user is located
		*/
		var index_location=-1;

		/*
			it contains the url slug after de host name
		*/
		this.WEB_ROOT = $("html")[0].getAttribute("data-web-root");

		/*
			fade time used to show and hide DOM elements
		*/
		this.TIME_FOR_SHOW = 200;
		this.TIME_FOR_HIDE = 200;

		/*
			JSON object used for saving the dictionary terms of a section
		*/
		this.terms;

		/*
			JSON object used for saving the general dictionary terms, available in all the system
		*/
		this.__GENERAL__;

		/*
			array that contains the setInterval being executed in a section, they are removed (clearInterval executed)
			when the user moves to another section
		*/
		var intervals_for_clear = [];

		/*
			web route to the profile image user's folder
		*/
		this.IMG_PROFILE_FOLDER_ROUTE = this.WEB_ROOT+"/assets/images/profile/";

		/*
			web route to the folder of images of organizations
		*/
		this.IMG_ORGANIZATION_FOLDER_ROUTE = this.WEB_ROOT+"/assets/images/organization/";

		/*
			web route to the folder of images of clients
		*/
		this.IMG_CLIENT_FOLDER_ROUTE = this.WEB_ROOT+"/assets/images/client/";

		/*
			var used for limiting the amount of parallel http requests being executed when it's executed the updating of 
			several elements using the "save all" button
		*/
		this.MAX_AMOUNT_PARALLELS_HTTP_REQUESTS_FOR_UPDATE = 2;

		/*
			retard of time when user submits a new item using the button to let immediatly load another

			check the create_item template function to learn more
		*/
		this.RETARD_MULTIPLE_LOAD = 50;

		/*
			route for the loading icon gif
		*/
		this.LOADING_ICON = this.WEB_ROOT + "/assets/img/loading.gif";

		/*
			boolean flag to show all items no matter the 'status' had by each item
		*/
		this.SEE_ITEM_NO_MATTER_THE_STATUS = false;

		/*
			time used by the regresive timer when a item is gonna be deleted (in seconds)
		*/
		this.TIME_FOR_DELETE_ITEM = 5;

		/*
			flag to choice printing debug messages in the browser console
		*/
		this.__DEBUG = true;

		/*
			it contains the id of the current section
		*/
		this.currentSection;

		/*
			used for some setTimeout instrutions...
		*/
		this.TIMEOUT_RETARD = 100;

		/*
			used for some setInterval instrutions...
		*/
		this.INTERVAL_RETARD = 100;

		/*
			used in interval functions that monitor the changes made on a item, in order to show/hide the "save changes of item" button
		*/
		this.CHECK_FOR_SAVING = 75;

		/*
			number of items for bringing in a request of list of items
				- list
				- search

			using progressive load or pagination format
		*/
		this.AMOUNT_ITEMS_PER_REQUEST = Number(document.getElementById("AMOUNT_ITEMS_PER_REQUEST") != null?document.getElementById("AMOUNT_ITEMS_PER_REQUEST").value:10);

		/*
			configured format to show items: pagination vs progressive load
		*/
		this.FORMAT_SHOW_ITEMS = $("meta[name='format_show_items']").attr("content");

		/*
			configured format to edit items: inline vs modal
		*/
		this.FORMAT_EDIT_ITEMS = $("meta[name='format_edit_items']").attr("content");

		/*
			route where the .json dictionary terms files are located
		*/
		var LANGUAGES_FOLDER = this.WEB_ROOT + "/assets/js/__languages__/";

		/*
			DOM element used to lock the screen, check the methods 'lock_screen' and 'unlock_screen'
		*/
		var div_lock_screen;

		/*
			var that keeps the route of the last section where the user has been in
		*/
		var	last_route_location;

		/*
			var that saves the AJAX sectioni request
		*/
		var sectionRequest = null;

		/*
			var that saves the language that the user is using to navigate
		*/
		var APP_LANGUAGE = $("html")[0].getAttribute("lang");

		/*
			it specifies if the user has enabled the timelimit inactivity monitor
		*/
		var USE_INACTIVIY_TIMELIMIT = $('meta[name=use_inactivity_time_limit]').attr('content');

		/*
			if the user has enabled the timelimit inactivity monitor, this value refers to that timelimit in seconds
		*/
		var INACTIVITY_TIMELIMIT = Number($('meta[name=inactivity_time_limit]').attr('content'));

		/*
			counter used if the user has enabled the timelimit inactivity monitor
		*/
		var inactivityCounter = 0;

		/*
			JSON object to keep a kind of cache of requested modals
		*/
		var MODALS_CACHE = {};

	/*
		@method : sectionTermsJSONUrl
		@description : build the route of the JSON dictionary file related to a section
		@parameters : 
			> id_section : the id of the section
				if it's undefined, the calling is referring to te general terms, availables in all the system

		@return : the URL of a JSON file
	*/
		function sectionTermsJSONUrl(id_section){
			if(typeof id_section == "undefined"){
				id_section = "";
			}

			return LANGUAGES_FOLDER + "section-" + id_section + "-terms.json"+"?_="+String(Math.random()).substr(2, 13);
		}

	/*
		@class : customAJAX

		@description : 	- it performs all the HTTP requests executed in the frontend
						- it handles the data and success/error/warning messages that comes from the server
						- manage a cache of requests (NOT IMPLEMENTED YET)
						- check if a session has already finished or if the user has locked the screen in order to 
							redirect him to the respective location

		@methods:
			> private
				* request : core method that performs the request

			> public
				* create : call the request(...) private method with arguments configurated to perform a POST request
				* read : call the request(...) private method with arguments configurated to perform a GET request
				* update : call the request(...) private method with arguments configurated to perform a PUT request
				* delete : call the request(...) private method with arguments configurated to perform a DELETE request
				* get : call the request(...) private method with arguments configurated to perform a GET request
				* post : call the request(...) private method with arguments configurated to perform a POST request
	*/
		function customAJAX() {
			/*
				@private method : request, described upper
			*/
				function request(settings, type, semantic) {
					/*
						this is printed in the browser console if the DEBUG flag is enabled
					*/
					App.__debug__("***************");
					App.__debug__("HTTP " + type.toUpperCase() + " Request:");
					App.__debug__(settings);

					/*
						> function_success : the function to be executed if the request is completed with a 2XX or 3XX status
						> function_error : the function to be executed if the request is completed with a 4XX or 5XX status
						> function_before : the function to be executed before the request is being sent
						> function_after : the function to be executed after the request is completed and 'function_success' or 'function_error' is executed
						> function_received : the function to be executed when the response from the server is received and before 'function_success' or 'function_error' is executed
					*/
					var function_success = settings.success,
						function_error = settings.error,
						function_before = settings.before,
						function_after = settings.after,
						function_received = settings.received;

					/*
						it indicates if we want a 'alertify' message being shown when the request is completed
					*/
					if(typeof settings.log_ui_msg == "undefined"){
						settings.log_ui_msg = true;
					}

					/*
						the process to be executed if the request is completed with a 2XX or 3XX status
					*/
					settings["success"] = function(data, textStatus, jqXHR) {
						/*
							before the success response is processed, function_received(...) is executed
						*/
						if (function_received){
							function_received(data, textStatus, jqXHR);
						}

						/*
							rutine to process the success response
						*/
						function executing_success(){
							var ret = null;

							/*
								if App.HTTP['METHOD'] is called with the 'success' field settled, the next conditional is executed
							*/
							if (typeof function_success != "undefined") {
								if (typeof function_success != "object") {
									ret = {
										data: data
									};
									function_success(ret, textStatus, jqXHR);
								} else {
									ret = Array();

									for (i in function_success) {
										ret.push({
											data: data
										}, textStatus, jqXHR);
										function_success[i]({
											data: data
										}, textStatus, jqXHR);
									}
								}
							}

							/*
								a lateral message ('alertify' plugin) is shown if the 'log_ui_msg' is active (settled as 'true')

								a default message is shown if the response of the server doesn't have the field 'message'

								the default message depends of the type of operation (CREATE, READ, UPDATE, DELETE, POST, PUT)
							*/
							var semantic_ref = {
									"CREATE" : "success",
									"READ" : "warning",
									"UPDATE" : "success",
									"DELETE" : "error",
									"POST" : "message",
									"GET" : "message",
								},
								semantic_default_message = {
									"CREATE" : 	App.__GENERAL__.http_message_default_create,
									"READ" : 	App.__GENERAL__.http_message_default_read,
									"UPDATE" : 	App.__GENERAL__.http_message_default_update,
									"DELETE" : 	App.__GENERAL__.http_message_default_delete,
									"POST" : 	App.__GENERAL__.http_message_default_post,
									"GET" : 	App.__GENERAL__.http_message_default_get,
								}

							if(settings.log_ui_msg){
								if(!jqXHR.responseJSON){
									jqXHR.responseJSON ={}
								};

								if(!jqXHR.responseJSON.message){
									jqXHR.responseJSON.message = semantic_default_message[semantic.toUpperCase()];
								}

								alertify[semantic_ref[semantic.toUpperCase()]](jqXHR.responseJSON.message);
							}

							App.__debug__(ret);

							return ret;
						}

						/*
							if the __DEBUG flag is active (dev enviroment), the executing_success(...) function is executed with no try...catch surrounding
							in order to check the errors

							if __DEBUG == false (production enviroment), the executing_success(...) function is executed inside a try...catch block
						*/

						var ret = null;

						if(App.__DEBUG){
							ret = executing_success();
						}else{
							try {
								ret = executing_success();
							} catch (e) {
								App.__debug__(e);
							}
						}

						/*
							function_after(...) is executed after to process the success response
						*/
						if (function_after) {
							function_after(data, textStatus, jqXHR);
						}

						return ret;
					}

					/*
						the process to be executed if the request is completed with a 4XX or 5XX status
					*/
					settings["error"] = function(jqXHR, textStatus, errorThrown) {
						/*
							before the success response is processed, function_received(...) is executed
						*/
						if (function_received) {
							function_received(jqXHR, textStatus, errorThrown);
						}

						/*
							conditional to perform actions if a 'unauthorized' response is received
						*/
						if(jqXHR.status == 401){
							if(jqXHR.responseJSON.hasOwnProperty("session")){
								if(jqXHR.responseJSON["session"] == "finished"){
									App.Alert(App.__GENERAL__.str_expired_session, function(){
										window.location.reload();
										return;
									});
									setTimeout(function(){
										window.location.reload();
									}, 3000);
								}else if(jqXHR.responseJSON["session"] == "locked"){
									window.location.href = App.WEB_ROOT + "/lock-screen";
								}
							}else{
								App.Alert(App.__GENERAL__.str_unauthorized_access_resource);
							}
						/*
							in other case...
						*/
						}else{
							/*
								if the respective flag is enabled, it's shown an 'alertify' message
							*/
							if(settings.log_ui_msg){
								/*
									if the message is being sent for the server
								*/
								if(jqXHR.responseJSON && jqXHR.responseJSON.hasOwnProperty("message")){
									alertify.error(jqXHR.responseJSON.message);
								/*
									in other case, we show a default error message
								*/
								}else{
									alertify.error(App.__GENERAL__.str_there_has_been_an_error_during_transaction);
								}
							}
						}

						/*
							this is printed in the browser console if the DEBUG flag is enabled
						*/
						App.__debug__("jqXHR:")
						App.__debug__(jqXHR);
						App.__debug__("textStatus:");
						App.__debug__(textStatus);
						App.__debug__("errorThrown");
						App.__debug__(errorThrown);

						/*
							if a function_error() has been provided, it's executed
						*/
						if (function_error){
							function_error(jqXHR.responseJSON?jqXHR.responseJSON:{message:App.__GENERAL__.sr_there_has_been_an_error_try_again}, textStatus, errorThrown);
						}

						/*
							if a function_after() has been provided, it's executed at the end of everything
						*/
						if (function_after){
							function_after(jqXHR, textStatus, errorThrown);
						}
					}

					/*
						the 'data' parameter is encapsulated in anoher object called data, how a field called 'data'
					*/
					if (settings.hasOwnProperty("data")){
						settings["data"] = {
							data: settings.data
						};
					}else{
						settings["data"] = {
							data: {}
						};
					}

					/*
						all the operations are gonna be handled under the 'JSON standar'
					*/
					settings["dataType"] = "JSON";

					/*
						function to execute before the request be sent
					*/
					if(function_before){
						settings.beforeSend = function_before;
					}

					/*
						returns an ajax (jQuery) object
					*/
					return $.ajax(settings);
				}

			/*
				the system works under the logic of an REST API
			*/
				/*
					public method of the class to perform an 'CREATE' operation
				*/
				this.create = function(settings) {
					settings["type"] = "post";

					return request(settings, "post", "create");
				}

				/*
					public method of the class to perform an 'READ' operation
				*/
				this.read = function(settings) {
					settings["type"] = "get";

					return request(settings, "get", "read");
				}

				/*
					public method of the class to perform an 'UPDATE' operation
				*/
				this.update = function(settings, f) {
					if(f){
						settings["type"] = "patch";
					}else{
						settings["type"] = "put";
					}

					return request(settings, f?"patch":"put", "update");
				}

				/*
					public method of the class to perform an 'DELETE' operation
				*/
				this.delete = function(settings) {
					settings["type"] = "delete";

					return request(settings, "delete", "delete");
				}

				/*
					public method of the class to perform an 'GET' operation
				*/
				this.get = function(settings) {
					settings["type"] = "get";

					return request(settings, "get", "get");
				}

				/*
					public method of the class to perform an 'POST' operation
				*/
				this.post = function(settings) {
					settings["type"] = "post";

					return request(settings, "post", "post");
				}

				/*
					public method of the class to perform an 'PUT' operation
				*/
				this.put = function(settings) {
					settings["type"] = "put";

					return request(settings, "put", "put");
				}
		}

	/*
		@attribute: HTTP
			all th http requests are handled by this attribute, calling it like this: 'App.HTTP.create', 'App.HTTP.read'... and so on
	*/
	this.HTTP = new customAJAX();

	/*
		@method: __debug__
			method to print in 'development' environment
	*/
		this.__debug__ = function(e){
			if(App.__DEBUG){
				console.log(e);
			}
		}

	/*
		the X-CSRF-Token Laravel header is configurated
	*/
		$.ajaxSetup({
			headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
		});

	/*
		@method : LockScreen
		@description : it creates a 'layer' over all the DOM elements in order to lock all the UI operations
	*/
		this.LockScreen = function(s){
			div_lock_screen = document.createElement("div");
			div_lock_screen.style = "cursor:wait;position:fixed;z-index:100000000;height:"+$(window).height()+"px;top:0px;left:0px;width:100%;"+s;
			div_lock_screen.style.display="none";
			document.getElementsByTagName("body")[0].appendChild(div_lock_screen);

			try{
				$(div_lock_screen).fadeIn();
			}
			catch(e){
				jQuery(div_lock_screen).fadeIn();	
			}
		}

	/*
		@method : UnlockScreen
		@description : it removes the 'layer' created in LockScreen(...)
	*/
		this.UnlockScreen = function(){
			try{
				$(div_lock_screen).fadeOut();
			}catch(e){
				jQuery(div_lock_screen).fadeOut();	
			}

			setTimeout(function(){
				try{
					div_lock_screen.parentNode.removeChild(div_lock_screen);
				}
				catch(e){
				}
			}, App.TIMEOUT_RETARD);
		}

	/*
		@method : UnlockScreen
		@description : it removes the 'layer' created in LockScreen(...)
	*/

	/*
		@var : searchingItems : is settled 'true' when the 'ExecuteSearch(...)' is being executed
	*/
	var searchingItems = false;

	/*
		@var : loadAllSearch : is settled 'true' when the final user decide to show all the results of a search, despite of load them progressively
			it is only used when the progressive load format is being used
	*/
	var loadAllSearch = false;

	/*
		@method : ExecuteSearch
		@description : performs a request to the server, asking for a list of items that fulfill a set of conditions
		@parameters:
			> flag : settled 'true' indicates that the load of items start again, from the zero index
	*/
		this.ExecuteSearch = function(flag){
			if(searchingItems){
				return;
			}

			var txt = $("#input_text_search").val().trim();

			if(txt.length == 0){
				return;
			}

			var	data = {
				keywords_search:txt,
				lng:Section.FLAGS.formConfigs["lng"],
				token:Section.FLAGS.TOKEN,
				type_request:"search",
				see_all : loadAllSearch
			};

			data["reset"] = App.isTrue(flag);
			$("#see_more_items_search").hide(App.TIME_FOR_HIDE);
			$("#loading_items_search").css("width", "100%").css("height", "100px").css("opacity", "1").show(App.TIME_FOR_SHOW);
			$("#main_div_search_results").show(App.TIME_FOR_SHOW);
			Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON_ON_SEARCH=true;
			$("#items_controls").hide(App.TIME_FOR_HIDE);
			App.DOM_Disabling($("#div_search_controls"));

			if(txt != Section.FLAGS.previous_value_on_input_text_search){
				$("#list_items_search").empty();
			}

			if(App.FORMAT_SHOW_ITEMS == "pagination"){
				data["page"] = Section.ACTUAL_PAGE["search"];
				$("#list_items_search").empty();
			}

			$("#load_all_items_search").hide(App.TIME_FOR_HIDE);
			toggle_pagination_controls("search", "list");
			searchingItems = true;

			App.HTTP.read({
				url:App.WEB_ROOT+Section.ENDPOINT_ITEMS_SEARCH,
				data:data,
				received : function(){
					App.DOM_Enabling($("#div_search_controls"));
					Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON_ON_SEARCH=false;
					$("#loading_items_search").hide(App.TIME_FOR_HIDE);
					$("#go_back_section_interface").show(App.TIME_FOR_SHOW);
				},
				success:function(d, e, f){
					Section.FLAGS.previous_value_on_input_text_search = txt;
					Section.FLAGS.onSearch=true;

					$.each(d.data.items, function(key, val){
						Section.add_item_form_to_dom(val);
					});

					if(Section.TOTAL_AMOUNT_ITEMS){
						Section.TOTAL_AMOUNT_ITEMS["search"] = Number(d.data.total);
					}else{
						Section.TOTAL_AMOUNT_ITEMS = {
							"search" : Number(d.data.total)
						}
					}

					if(App.FORMAT_SHOW_ITEMS == "pagination"){
						buildPaginationControls("top", "search");
						buildPaginationControls("bottom", "search");
					}

					if(d.data.items.length>=App.AMOUNT_ITEMS_PER_REQUEST){
						$("#see_more_items_search").show().css("width", "100%").parent().css("width", "100%");
					}
				},after:function(x, y, z){
					if(!loadAllSearch){
						$("#load_all_items_search").show(App.TIME_FOR_SHOW);
					}

					loadAllSearch = false;
					searchingItems = false;
				},
				log_ui_msg : false
			});
		}

	/*
		@method : toggle_pagination_controls
		@description : show/hide the respective pagination controls (when the pagination format has been choosen by the user)
		@parameters:
			> show : the context of the pagination controls to show
			> hide : the context of the pagination controls to hide

		context == 'list' || context == 'search'
	*/
		function toggle_pagination_controls(show, hide){
			$("#controls_pagination_format_top_"+hide+", #controls_pagination_format_bottom_"+hide).hide(App.TIME_FOR_HIDE);
			$("#controls_pagination_format_top_"+show+", #controls_pagination_format_bottom_"+show).show(App.TIME_FOR_SHOW);

			$("#pages_of_"+show).show();
			$("#pages_of_"+show).select2();

			$("#pages_of_"+hide).select2("destroy");
			$("#pages_of_"+hide).hide();
		}

	/*
		@method : ToggleRows
		@description : recursive method that show/hide the rows of a list of items, comparing the statuses choosen in the search_controls with the statuses of each item
		@parameters:
			> token : the 'token' (random identifier settled by the server) that identifies the section where the user is located
	*/
		this.ToggleRows = function(token){
			if(!Section.FLAGS.GETTING_ITEMS && !searchingItems){
				var t = $("#see_with_status").val(),
					chl = $("#list_items").children();

				$("#list_items_search").children().each(function(){
					chl.push(this);
				});

				$(chl).each(function(){
					App.showOrHideRow(this, t);
				});
			}

			if(typeof Section != "undefined" && typeof Section.FLAGS.TOKEN!= "undefined" && token == Section.FLAGS.TOKEN&& typeof Section.FLAGS.amountItems != "undefined"){
				setTimeout(function(){
					App.ToggleRows(token);
				}, (Section.FLAGS.amountItems+1)*App.CHECK_FOR_SAVING);
			}
		};

	/*
		@method : pendingForSaving_Monitor
		@description : 	recursive method used when the format of edition is settled as 'inline'
						it checks if the fields of edition of an item has been changed, in order to show/hide the button of "save all" and set as true/false a flag that show/not-show an confirmation box to leave the section with unsaved changes
		@parameters:
			> token : the 'token' (random identifier settled by the server) that identifies the section where the user is located
	*/
		function pendingForSaving_Monitor(token){
			var cant = $("#list_items").find("tr[data-save='1']").length+$("#list_items_search").find("tr[data-save='1']").length;

			if(typeof Section != "undefined"){
				if(cant > 0){
					Section.FLAGS.LET_CHANGE_SECTION = false;
					$("#update_all, #update_all_search").show(App.TIME_FOR_SHOW);
					$("#relleno_last_column, #relleno_last_column_search").hide(App.TIME_FOR_HIDE);
				}else{
					Section.FLAGS.LET_CHANGE_SECTION = true;
					$("#update_all, #update_all_search").hide(App.TIME_FOR_HIDE);
					$("#relleno_last_column, #relleno_last_column_search").show(App.TIME_FOR_SHOW);
				}

				if(	typeof Section.FLAGS.TOKEN!= "undefined" && 
					token == Section.FLAGS.TOKEN && 
					typeof Section.FLAGS.amountItems != "undefined"
				){
					setTimeout(function(){
						pendingForSaving_Monitor(token);
					}, (Section.FLAGS.amountItems+1)*App.CHECK_FOR_SAVING);
				}
			}
		};

	/*
		@method : messageNoItems
		@description : 	
		@parameters:
			> token : the 'token' (random identifier settled by the server) that identifies the section where the user is located
	*/
		function messageNoItems(token){
			var v = 0;
			var n1 = 0, n2 = 0;

			$("#list_items").children().each(function(){
				if($(this).css("display") != "none"){
					v++;
				}else{
					n1++;
				}
			});

			if(v==0 && typeof Section != "undefined" && typeof Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON != "undefined" && !Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON){
				$("#message_no_items").show(App.TIME_FOR_SHOW);
			}else{
				$("#message_no_items").hide(App.TIME_FOR_HIDE);
			}

			v=0;

			$("#list_items_search").children().each(function(){
				if($(this).css("display") != "none"){
					v++;
				}else{
					n2++;
				}
			});

			if(v==0 && typeof Section != "undefined" && typeof Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON_ON_SEARCH != "undefined" && !Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON_ON_SEARCH){
				$("#message_no_items_search").show(App.TIME_FOR_SHOW);
			}else{
				$("#message_no_items_search").hide(App.TIME_FOR_HIDE);
			}

			$("#message_amount_items_hidden").html(n1 + " " + App.__GENERAL__.str_hidden);
			$("#message_amount_items_hidden_search").html(n2 + " " + App.__GENERAL__.str_hidden);

			if(typeof Section != "undefined" && typeof Section.FLAGS.TOKEN!= "undefined" && token == Section.FLAGS.TOKEN&& typeof Section.FLAGS.amountItems != "undefined"){
				setTimeout(function(){
					messageNoItems(token);
				}, (Section.FLAGS.amountItems+1)*App.CHECK_FOR_SAVING);
			}
		}

	/*
		@method : paginationCell
		@description : 	returns an HTML string that represents a cell of the pagination control
		@parameters:
			> page : the page represented by the cell
				if page == undefined, it is a generic '...' cell, only for decoration
	*/
		function paginationCell(page){
			return ''+
				'<td data-pagination-remove = "" class = "pagination-cell" '+(page?"data-pagination-page='"+page+"'":"")+'>'+
				(page?page:"...")+
				'</td>'+
			'';
		}

	/*
		@method : buildPaginationControls
		@description : 	build the row of controls of pagination, indicating the position (top or bottom) and the context (list or search) of the controls
		@parameters:
			> position : at the 'top' or at the 'bottom'
			> context : on the generic 'list' or the results of 'search'
	*/
		function buildPaginationControls(position, context){
			var DOM_context = $("#controls_pagination_format_" + position+"_"+context);
			DOM_context.find("[data-pagination-remove]").remove();

			Section.TOTAL_PAGES[context] = Math.ceil(Section.TOTAL_AMOUNT_ITEMS[context] / App.AMOUNT_ITEMS_PER_REQUEST);
			var arrayPages = [];

			for(var i = 1; i <= Section.TOTAL_PAGES[context]; i++){
				arrayPages.push(i);
			}

			var middle = Section.ACTUAL_PAGE[context] - 1;
			var total = 1;
			var fixed_to = 9;
			var left = middle, right = middle;
			var top = Math.min(Section.TOTAL_PAGES[context], fixed_to);
			var show_dots_left = true, show_dots_right = true;
			var reference = DOM_context.find('[data-pagination-page="next"]')

			while(total < top && (left > 0 || right < Section.TOTAL_PAGES[context] - 1)){
				if(left > 0){
					left--;
					total++;
				}

				if(right < Section.TOTAL_PAGES[context] - 1){
					right++;
					total++;
				}
			}

			arrayPages = arrayPages.slice(left, right + 1);
			show_dots_left = arrayPages[0] != 1;
			show_dots_right = arrayPages[arrayPages.length - 1] != Section.TOTAL_PAGES[context];

			if(show_dots_left && Section.TOTAL_AMOUNT_ITEMS[context]){
				DOM_context.find('[data-pagination-page="first"]').show(App.TIME_FOR_SHOW);
				reference.before(paginationCell())
			}else{
				DOM_context.find('[data-pagination-page="first"]').hide(App.TIME_FOR_HIDE);
			}

			for(var i = 0, n = arrayPages.length; i < n; i++){
				reference.before(paginationCell(arrayPages[i]))
			}

			if(show_dots_right && Section.TOTAL_AMOUNT_ITEMS[context]){
				DOM_context.find('[data-pagination-page="last"]').show(App.TIME_FOR_SHOW);
				reference.before(paginationCell())
			}else{
				DOM_context.find('[data-pagination-page="last"]').hide(App.TIME_FOR_HIDE);
			}

			if(Section.ACTUAL_PAGE[context] == 1){
				DOM_context.find('[data-pagination-page="previous"]').hide(App.TIME_FOR_HIDE);
			}else if(Section.TOTAL_AMOUNT_ITEMS[context]){
				DOM_context.find('[data-pagination-page="previous"]').show(App.TIME_FOR_SHOW);
			}

			if(Section.ACTUAL_PAGE[context] == Section.TOTAL_PAGES[context]){
				DOM_context.find('[data-pagination-page="next"]').hide(App.TIME_FOR_HIDE);
			}else if(Section.TOTAL_AMOUNT_ITEMS[context]){
				DOM_context.find('[data-pagination-page="next"]').show(App.TIME_FOR_SHOW);
			}

			DOM_context.find('[data-pagination-page]').removeClass("pagination-cell-selected");
			DOM_context.find('[data-pagination-page="'+Section.ACTUAL_PAGE[context]+'"]').addClass("pagination-cell-selected");

			DOM_context.find('[data-pagination-page]').each(function(){
				if($(this).attr("data-has-click") != "1"){
					$(this).attr("data-has-click", 1);

					$(this).click(function(){
						if(Section.FLAGS.GETTING_ITEMS){
							return;
						}

						var option = $(this).attr("data-pagination-page");

						if(option == "first"){
							Section.ACTUAL_PAGE[context] = 1;
						}else if(option == "previous"){
							Section.ACTUAL_PAGE[context]--;
						}else if(option == "next"){
							Section.ACTUAL_PAGE[context]++;
						}else if(option == "last"){
							Section.ACTUAL_PAGE[context] = Section.TOTAL_PAGES[context];
						}else{
							if(Number(option) == Section.ACTUAL_PAGE[context]){
								return;
							}

							Section.ACTUAL_PAGE[context] = Number(option);
						}

						if(context == "list"){
							Section.getItems();
						}else{
							App.ExecuteSearch();
						}
					});
				}
			});

			$("#pages_of_"+context).empty().html((function(){
				var str = "";

				for(var i = 1; i <= Section.TOTAL_PAGES[context]; i++){
					str += '<option value = "'+i+'">'+i+'</option>';
				}

				return str;
			})()).show().find("option[value='"+Section.ACTUAL_PAGE[context]+"']").prop("selected", true);
			$("#pages_of_"+context).select2();
		}

	/*
		@method : initSectionValues
		@description : 	executes the generic starting configurations of a section, using some values provided by the server
		@parameters:
			> config : some configuration values, explanation inside the method
	*/
		this.initSectionValues = function(config){
			if(config){
				Section.CONFIGURATION = config;

				if(!App.isTrue(Section.CONFIGURATION.statuses.use)){
					$("[data-column='statuses'], #col_see_with_status").hide();
				}

				$.each(Section.CONFIGURATION.statuses.default, function(k, v){
					Section.CONFIGURATION.statuses.default[k] = Number(v);
				})

				$.each(Section.CONFIGURATION.statuses.permitted, function(k, v){
					Section.CONFIGURATION.statuses.permitted[k] = Number(v);
				})

				$("#see_with_status").find("option").each(function(){
					if(Section.CONFIGURATION.statuses.permitted.indexOf(Number($(this).val())) == -1){
						$(this).remove();
					}
				});
			}

			try{
				Section.permises = config.role_actions;//JSON.parse($("*[data-role='role_actions']").attr("data-val"));;

				if(!App.isTrue(Section.permises.language_controls)){
					$("[data-column='language']").hide();
				}
			}catch(e){
			}

			Section.FLAGS = {};
			Section.FLAGS.GETTING_ITEMS = false;
			Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON = false;
			Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON_ON_SEARCH = false;
			Section.FLAGS.LET_CHANGE_SECTION = true;
			Section.FLAGS.amountItems = 0;
			Section.FLAGS.onSearch=false;
			Section.FLAGS.previous_value_on_input_text_search = "";
			Section.FLAGS.TOKEN = config.token;
			Section.FLAGS.formConfigs = {
				lng:$("#select_set_user_session_lng").val()
			};
			Section.FLAGS.onCreation = false;

			var countdec = 0;
			var dec = document.getElementById("input_text_search");

			if(dec != null && !Section.avoid_input_text_search){
				dec.onclick =
				dec.onchange =
				dec.onfocus =
				dec.onblur =
				dec.onkeyup =
				dec.onkeydown = function(){
					setTimeout(function(){
						countdec++;
						$("#go_forward_results").hide(App.TIME_FOR_HIDE);

						setTimeout(function(){
							countdec--;

							if(countdec==0){
								var txt = dec.value.trim();

								if(txt != Section.FLAGS.previous_value_on_input_text_search && txt.length > 0){
									App.ExecuteSearch();
								}
								else if(Section.FLAGS.previous_value_on_input_text_search.length > 0 && txt.length > 0 && document.getElementById("main_div_search_results").style.display == "none"){
									$("#go_forward_results").show(App.TIME_FOR_SHOW);
								}
							}
						}, App.TIMEOUT_RETARD * 10);
					}, 50);
				}
			}

			$("#update_all, #update_all_search").click(function(){
				Section.globalUpdateElements = Array();

				$("#list_items").find("tr[data-save='1']").each(function(k, v){
					Section.globalUpdateElements.push(v);
				});

				$("#list_items_search").find("tr[data-save='1']").each(function(k, v){
					Section.globalUpdateElements.push(v);
				});

				var counter = App.MAX_AMOUNT_PARALLELS_HTTP_REQUESTS_FOR_UPDATE;

				while(Section.globalUpdateElements.length > 0 && counter-- > 0){
					var it = Section.globalUpdateElements[0];
					Section.globalUpdateElements = Section.globalUpdateElements.slice(1);
					$(it).find("button[data-btn-for-save]").trigger("click");
				}
			});

			$("#load_all_items").click(function(){
				see_all = true;
				Section.getItems();
			});

			var see_all = false;

			Section.getItems = function(reset){
				if(Section.FLAGS.GETTING_ITEMS){
					return;
				}

				Section.FLAGS.GETTING_ITEMS = Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON=true;

				var data = {
					token:Section.FLAGS.TOKEN,
					type_request:"list_items"
				};

				if(reset){
					data["reset"] = true;
					Section.FLAGS.formConfigs["lng"] = $("#set_items_language").val();
				}

				if(App.FORMAT_SHOW_ITEMS == "pagination"){
					data["page"] = Section.ACTUAL_PAGE["list"];
					$("#list_items").empty();
				}

				data["lng"] = Section.FLAGS.formConfigs["lng"];
				data["see_all"] = see_all;
				$("#see_more_items").hide(App.TIME_FOR_HIDE);
				$("#loading_items").show(App.TIME_FOR_SHOW);
				$("#div_load_all_items").hide(App.TIME_FOR_HIDE);

				App.DOM_Disabling("#div_search_controls");

				App.HTTP.read({
					url:App.WEB_ROOT+Section.ENDPOINT_ITEMS_INDEX,
					data:data,
					received : function(){
						App.DOM_Enabling("#div_search_controls");
					},
					success:function(d){
						for(x in d.data.items){
							Section.add_item_form_to_dom(d.data.items[x]);
							Section.FLAGS.amountItems++;
						}

						if(Section.TOTAL_AMOUNT_ITEMS){
							Section.TOTAL_AMOUNT_ITEMS["list"] = Number(d.data.total);
						}else{
							Section.TOTAL_AMOUNT_ITEMS = {
								"list" : Number(d.data.total)
							}
						}

						if(App.FORMAT_SHOW_ITEMS == "pagination"){
							buildPaginationControls("top", "list");
							buildPaginationControls("bottom", "list");
						}

						if(d.data.items.length>=App.AMOUNT_ITEMS_PER_REQUEST && !see_all){
							$("#see_more_items").show(App.TIME_FOR_SHOW);
							$("#div_load_all_items").show(App.TIME_FOR_SHOW);
						}

						$(".select2-container").css("width", "100%");
					},after:function(x, y, z){
						see_all = Section.FLAGS.GETTING_ITEMS = Section.FLAGS.onSearch=Section.FLAGS.EXCLUSION_MESSAGE_NO_ITEMS_LOADING_ICON=false;
						$("#loading_items").hide(App.TIME_FOR_HIDE);
					},
					log_ui_msg : false
				});
			}

			$("#see_more_items").click(function(){
				Section.getItems();
			});

			$("#see_with_status").select2();
			$(".select2-container").css("width", "60%");

			if(!Section.avoid_get_items_changed_language){
				$("#set_items_language").change(function(){
					$("#list_items").empty();
					$("#list_items_search").empty();
					Section.FLAGS.previous_value_on_input_text_search = "";
					Section.getItems(true);
					App.ExecuteSearch(true);
				});
			}

			Section.arrayStatusForDelete = [];

			$("#status_for_delete").children().each(function(k, v){
				Section.arrayStatusForDelete.push(v.value);
			});

			$("#go_back_section_interface").click(function(){
				toggle_pagination_controls("list", "search");

				$("#main_div_search_results").hide(App.TIME_FOR_HIDE);
				$("#items_controls").show(App.TIME_FOR_SHOW);
				$("#go_forward_results").show(App.TIME_FOR_SHOW);
				$("#go_back_section_interface").hide(App.TIME_FOR_HIDE);
			});

			$("#go_forward_results").click(function(){
				toggle_pagination_controls("search", "list");

				$("#go_forward_results").hide(App.TIME_FOR_HIDE);
				$("#items_controls").hide(App.TIME_FOR_HIDE);
				$("#main_div_search_results").show(App.TIME_FOR_SHOW);
				$("#go_back_section_interface").show(App.TIME_FOR_SHOW);
			});

			App.SEE_ITEM_NO_MATTER_THE_STATUS=false;

			App.ToggleRows(Section.FLAGS.TOKEN);
			messageNoItems(Section.FLAGS.TOKEN);

			if(App.FORMAT_EDIT_ITEMS == "inline"){
				pendingForSaving_Monitor(Section.FLAGS.TOKEN);
			}

			$("#see_all_items").change(function(){
				$("#see_with_status").select2("destroy");
				$("#see_with_status").children().prop("selected", this.checked);
				$("#see_with_status").select2();
				$(".select2-container").css("width", "100%");
			});

			/**********************************************/
			App.FORMAT_SHOW_ITEMS = $("meta[name='format_show_items']").attr("content");

			if(App.FORMAT_SHOW_ITEMS == "progressive"){
				$("#controls_progressive_format").show();
			}else{
				$("#col_select_page").show();
				toggle_pagination_controls("list", "search");
			}

			$("#pages_of_list").change(function(e){
				if(Section.FLAGS.GETTING_ITEMS){
					this.value = Section.ACTUAL_PAGE["list"];
					return;
				}

				Section.ACTUAL_PAGE["list"] = Number(this.value);
				Section.getItems();
			});
			$("#pages_of_search").change(function(){
				if(searchingItems){
					this.value = Section.ACTUAL_PAGE["search"];
					return;
				}

				Section.ACTUAL_PAGE["search"] = Number(this.value);
				App.ExecuteSearch();
			});

			/********************************************************/
			/*
				MAIN_DIV_SEARCH_RESULTS born
			*/


			$("#main_div_search_results").html($("#items_controls").html());
			$("#main_div_search_results").css("max-height", "500px");
			$("#main_div_search_results").css("overflow", "scroll");

			$("#main_div_search_results").find("*[id]").each(function(){
				$(this).attr("id", $(this).attr("id")+"_search");
			});

			$("#load_all_items_search").click(function(){
				loadAllSearch = true;
				App.ExecuteSearch();
			});

			$("#see_more_items_search").click(function(){
				App.ExecuteSearch();
			});

			/********************************************************/

			if(App.isset(Section.start)){
				Section.ACTUAL_PAGE = {
					list : 1,
					search : 1
				};
				Section.TOTAL_PAGES = {
					list : 0,
					search : 0
				}
				Section.start();
			}
		}

	/*
		@method : controls_for_delete_item
		@description :  it builds the DOM controls to delete an item of a list of items
		@parameters:
			> data : data of item to be deleted
			> row_selector : selector (like CSS or jQuery) to refer to the row of the item
			> column_controls : column of the row where the delete button is gonna be located
			> show_always : flag that if it's settled true, the button delete is always gonna be shown, no matter the status comprobation over the item (performed by ToggleRows(...))
	*/
		this.controls_for_delete_item = function(data, row_selector, column_controls, show_always){
			var let_delete = false;
			var button_delete = document.createElement("button");

			button_delete.className = "btn btn-danger";
			button_delete.innerHTML = "<i class = 'fa fa-remove'></i>";
			button_delete.title = App.terms.str_delete;

			if(!show_always){
				button_delete.id = "delete_item_"+(Section.FLAGS.onSearch?"_search":"")+"_"+data.id;
				button_delete.setAttribute("data-button-delete", data.id);
				button_delete.style.display = App.CommonsElements(Section.arrayStatusForDelete, data.status)?"":"none";
			}

			var deleting = false;

			button_delete.onclick = function(){
				if(deleting){
					return;
				}

				var regresive_count_for_delete = 0;
				$(button_delete).hide(App.TIME_FOR_HIDE);
				deleting = let_delete=true;
				App.DOM_Disabling(row_selector);;
				$(row_selector).attr("data-deleting", "1");

				function no_delete(){
					deleting = let_delete=false;
					$(text_timer_for_delete).hide(App.TIME_FOR_HIDE);
					$(abort_deleting_anchor).fadeOut(App.TIME_FOR_HIDE);//no funciona el hide, no se xq
					$(button_delete).show(App.TIME_FOR_SHOW);
					App.DOM_Enabling(row_selector);;
					$(row_selector).attr("data-deleting", "0");
				}

				(function deleteItem(){
					if(let_delete){
						regresive_count_for_delete++;

						if(regresive_count_for_delete < App.TIME_FOR_DELETE_ITEM){
							text_timer_for_delete.innerHTML = App.terms.str_deleting_in + " " +(App.TIME_FOR_DELETE_ITEM - regresive_count_for_delete)+ "...";
							$(text_timer_for_delete).show(App.TIME_FOR_SHOW);
							$(abort_deleting_anchor).show(App.TIME_FOR_SHOW);
							setTimeout(deleteItem, 1000);
						}else{
							$(abort_deleting_anchor).hide(App.TIME_FOR_HIDE);
							text_timer_for_delete.innerHTML = App.terms.str_deleting+"...";

							App.HTTP.delete({
								url:App.WEB_ROOT+Section.ENDPOINT_ITEM+data.id,
								success:function(d, e, f){
									$(row_selector).hide(App.TIME_FOR_HIDE, function(){ $(row_selector).remove(); });
									Section.FLAGS.amountItems--;
								},error:function(x, y, z){
									no_delete();
								}
							});
						}
					}else{
						no_delete();
					}
				})();
			}

			var text_timer_for_delete = document.createElement("p");
			text_timer_for_delete.style.display = "none";
			text_timer_for_delete.style.fontWeight = "bold";

			var abort_deleting_anchor = document.createElement("a");
			abort_deleting_anchor.style.fontWeight = "bold";
			abort_deleting_anchor.style.display = "none";
			abort_deleting_anchor.innerHTML = App.terms.str_abort;
			abort_deleting_anchor.href = "javascript:;";
			abort_deleting_anchor.onclick = function(){
				let_delete=false;
				$(text_timer_for_delete).hide(App.TIME_FOR_HIDE);
				$(abort_deleting_anchor).hide(App.TIME_FOR_HIDE);
				$(button_delete).show(App.TIME_FOR_SHOW);
			}

			var div_with_margin = document.createElement("div");
			div_with_margin.style.margin = "25px";
			div_with_margin.appendChild(text_timer_for_delete);
			div_with_margin.appendChild(abort_deleting_anchor);

			column_controls.appendChild(button_delete);
			column_controls.appendChild(div_with_margin);
		}

	/*
		@method : createLanguageSelector
		@description : 	build the language selector in order to be able to see an item in different languages
						this is only show when the user has a role with permises to handle language controls
		@parameters:
			> data : data of the item to see in other language representations
			> row_selector : selector (like CSS or jQuery) to refer to the row of the item
			> configs : configuration values about the item
			> function_after : function to be executed after the request to the server (asking for the values of item with the specified language) is completed
	*/
		this.createLanguageSelector = function(data, row_selector, configs, function_after){
			var column_language = document.createElement("td");
			var select_language = document.createElement("select");

			select_language.className = "form-control";
			select_language.innerHTML = $("#set_items_language").html();
			select_language.value = $("#set_items_language").val();
			var gettingItem = false;

			select_language.onchange = function(){
				if(gettingItem){
					return;
				}

				gettingItem = true;
				App.DOM_Disabling(row_selector);;
				$(row_selector).attr("data-getting", "1");

				App.HTTP.read({
					url:App.WEB_ROOT+Section.ENDPOINT_ITEM+data.id,
					data:{
						lng:select_language.value
					},received: function(){
						App.DOM_Enabling(row_selector);;
					}, success:function(d, e, f){
						configs.lng = $(select_language).val();
						d.data.item.row_selector = data.row_selector;
						Section.ITEMS[String(data.id)] = data;
						function_after(d);
					},after:function(x, y, z){
						gettingItem = false;
						$(row_selector).attr("data-getting", "0");
					}
				});
			}

			column_language.style.display = App.isTrue(Section.permises.language_controls)?"":"none";
			column_language.appendChild(select_language);
			return column_language;
		}

	/*
		@method : createSelectStatuses
		@description : it creates the DOM controls to manage the statuses of an item (usually/initially a 'select2')
		@parameters:
			> data : the data of the item
			> column_statuses : the column where the statuses controls is gonna be located
	*/
		this.createSelectStatuses = function(data, column_statuses){
			var select_statuses=document.createElement("select");

			select_statuses.multiple = App.isTrue(Section.CONFIGURATION.statuses.multiple)?"multiple":"";
			select_statuses.disabled = !Section.permises["update"];
			chl = $("#see_with_status").children();

			for(var i = 0; i < chl.length; i++){
				var op = document.createElement("option");
				op.value = chl[i].value;
				op.innerHTML = chl[i].innerHTML;
				op.selected = data.status.indexOf(Number(op.value)) != -1;
				select_statuses.appendChild(op);
			}

			return select_statuses;
		}

	/*
		@method : createButtonSaveChanges
		@description : create the controls to save the changes of an item (when the format of edition is 'inline')
		@parameters:
			> data : the original data of the item, before the changes
			> data_to_send : data to be sent to the server
			> function_after : function to be executed after the update request is successfully completed
			> select_statuses : controls of statuses of the item
			> row_selector : selector (like CSS or jQuery) to refer to the row of the item
	*/
		this.createButtonSaveChanges = function(data, data_to_send, function_after, select_statuses, row_selector){
			var button_save_changes = document.createElement("button");

			button_save_changes.className = "btn btn-primary";
			button_save_changes.innerHTML = "<i class = 'fa fa-save'></i>";
			button_save_changes.style.display = "none";
			button_save_changes.setAttribute("data-btn-for-save", "1");

			var updating = false;

			button_save_changes.onclick = function(){
				if(updating){
					return;
				}

				var statuses = $(select_statuses).val();

				if(typeof statuses != "object"){
					statuses = [statuses];
				}

				$(row_selector).attr("data-updating", "1");
				$("#update_all_search, #update_all").attr("disabled", true);

				App.HTTP.update({
					url:App.WEB_ROOT+Section.ENDPOINT_ITEM+data.id,
					data:data_to_send(statuses),
					before : function(){
						App.DOM_Disabling(row_selector);;
					},
					received:function(){
						App.DOM_Enabling(row_selector);;
						$("#update_all_search, #update_all").attr("disabled", false);
					},success:function(d){
						$(button_save_changes).hide(App.TIME_FOR_HIDE);
						function_after(d);
						var t = $(select_statuses).val();

						if(t == null){
							t = Array();
						}

						for(x in t){
							t[x] = Number(t[x]);
						}

						var it = Section.globalUpdateElements[0];
						data.status = t;
						($(row_selector)[0]).setAttribute("data-status", JSON.stringify(t));
						Section.globalUpdateElements = Section.globalUpdateElements.slice(1);
						$(it).find("button[data-btn-for-save]").trigger("click");
						($(row_selector)[0]).setAttribute("data-save", "0");

						if(App.CommonsElements(Section.arrayStatusForDelete, data.status)){
							$(($('[data-button-delete='+data.id+']')[0])).show(App.TIME_FOR_SHOW);
						}else{
							$(($('[data-button-delete='+data.id+']')[0])).hide(App.TIME_FOR_HIDE);
						}
						Section.ITEMS[String(data.id)] = data;
					},after:function(x, y, z){
						updating = false;
						$(row_selector).attr("data-updating", "0");
					}
				});
			}

			return button_save_changes;
		}

	/*
		@method : startConfigRow
		@description : initial configuration of a row of an item
		@parameters:
			> data : the data of the item
			> configs : some configuration values (explained inside the method)
	*/
		this.startConfigRow = function(data, configs){
			var row_selector = '[data-row-id='+data.id+']';
			configs = App.cloneObject(Section.FLAGS.formConfigs);

			if(typeof data.status != "undefined"){
				$.each(data.status, function(k, v){data.status[k]=Number(v);});
			}

			var row = document.createElement("tr");
			row.setAttribute("data-row-id", data.id);
			data.row_selector = row_selector;

			return row;
		}

	/*
		@method : finalConfigRow
		@description : final configuration of a row of an item
		@parameters:
			> row : the built row
			> data : the data of the item
			> select_statuses : the statuses controls of the item
			> watcher : the function that checks the made changes over an item, and show/hide the controls to save changes (it is executed only when the format of edition is settled as 'inline')
			> extra_function : function to be executed at the end of this method
	*/
		this.finalConfigRow = function(row, data, select_statuses, watcher, extra_function){
			var condDisplay = App.showOrHideRow(row, $("#see_with_status").val(), true);

			if(condDisplay){
				row.style.display = "none";
				$("#list_items"+(Section.FLAGS.onSearch?"_search":"")).append(row);
				$(row).show(App.TIME_FOR_SHOW);
			}else{
				$("#list_items"+(Section.FLAGS.onSearch?"_search":"")).append(row);
			}

			Section.ITEMS[String(data.id)] = data;

			if(typeof select_statuses != "undefined" && App.FORMAT_EDIT_ITEMS == "inline"){
				try{
					$(select_statuses).select2();
				}catch(e){}
			}

			if(Section.permises["update"] && typeof watcher != "undefined" && App.FORMAT_EDIT_ITEMS == "inline"){
				watcher(Section.FLAGS.TOKEN);
			}

			if(typeof extra_function != "undefined"){
				extra_function();
			}
		}

	/*
		@var : getting_section : is settled 'true' when an section (and complementary resources to it) is being requested, false when the process is ended
	*/
	var getting_section = false;

	/*
		@method : get_section
		@description : request the HTML view, complementary resources and configuration data related to a section
		@parameters:
			> idsection : id of the section to be requested
			> route : url shown in the url bar
			> stateObj : native History object that handles the history of user
	*/
		function get_section(idsection, route, stateObj){
			if(route){
				last_route_location=route;
			}

			if(sectionRequest != null){
				sectionRequest.abort();
			}

			var tkns = Array();

			try{
				tkns.push(Section.FLAGS.TOKEN);
			}catch(e){
			}

			$("#content").fadeOut(400);
			$("#loading_section").fadeIn(App.TIME_FOR_SHOW);
			$("#error_happened").fadeOut(App.TIME_FOR_SHOW);

			try{
				document.title = App.__GENERAL__.str_loading;
				$("#title_section, #loading_message").html(App.__GENERAL__.str_loading);
			}catch(e){

			}

			$("#reload_section").hide(App.TIME_FOR_HIDE).attr("disabled", true);
			App.currentSection = idsection;
			delete Section;

			if(stateObj){
				history.pushState(stateObj, "", last_route_location);
			}

			sectionRequest = App.HTTP.read({
				url:App.WEB_ROOT+"/section/"+idsection,
				success:function(data){
					$("#title_section").html(data.data.section_name);
					document.title = data.data.section_name;
					get_section_terms(data, stateObj, tkns);
				},
				error:function(){
					document.title = App.__GENERAL__.str_there_has_been_an_error;
					$("#loading_section").fadeOut(App.TIME_FOR_HIDE);
					$("#error_happened").fadeIn(App.TIME_FOR_SHOW);
					$("#title_section").empty();
					$("#reload_section").show(App.TIME_FOR_SHOW).attr("disabled", false);
				},
				log_ui_msg : false
			});
		}

	/*
		@method : get_section_terms
		@description : request the dictionary of terms of a section
		@parameters:
			> data : the data received from the server after the http request performed in get_section(...)
			> stateObj : native History object that handles the history of user
			> tokens : set of tokens of the section that is being replaced
	*/
		function get_section_terms(data, stateObj, tokens){
			App.HTTP.read({
				url : sectionTermsJSONUrl(App.currentSection),
				success : function(d, e, f){
					App.terms = {};

					$.each(d.data, function(k, v){
						if(typeof v.value == "string"){
							App.terms[k] = JSON.parse(v.value)[APP_LANGUAGE];
						}
					})

					get_general_terms(data, stateObj, tokens);
				},error : function(){
					$("#loading_section").fadeOut(App.TIME_FOR_HIDE);
					document.title = App.__GENERAL__.str_there_has_been_an_error;
					$("#error_happened").fadeIn(App.TIME_FOR_SHOW);
					$("#title_section").empty();
					$("#reload_section").show(App.TIME_FOR_SHOW).attr("disabled", false);
				},
				log_ui_msg : false
			});
		}

	/*
		@method : get_general_terms
		@description : request the dictionary of general terms, available in all the system
			this must be called only at the load of the page, but in development enviroment, it's called 
			each time the section is changed
		@parameters:
			> data : the data received from the server after the http request performed in get_section(...)
			> stateObj : native History object that handles the history of user
			> tokens : set of tokens of the section that is being replaced
	*/
		function get_general_terms(data, stateObj, tkns){
			App.HTTP.read({
				url : sectionTermsJSONUrl(),
				success : function(d2, e2, f2){
					App.__GENERAL__ = {};

					$.each(d2.data, function(k, v){
						if(typeof v.value == "string"){
							App.__GENERAL__[k] = JSON.parse(v.value)[APP_LANGUAGE];
						}
					})

					for(c in intervals_for_clear){
						clearInterval(intervals_for_clear[c]);
					}

					intervals_for_clear = [];

					App.HTTP.post({
						url:App.WEB_ROOT+"/tkns",
						data:{
							tkns:tkns
						},success:function(d){
						},error:function(x,y,z){
						},log_ui_msg : false
					});

					$("#content").fadeOut(400);

					setTimeout(function(){
						$("#content").html(data.data.view).fadeIn(400);
						App.ap_ui_init(document.getElementById("content"));
						locations_history[index_location]["location"] = document.location.pathname;

						try{
							document.getElementById("see_all_items").onclick = function(e){
								App.SEE_ITEM_NO_MATTER_THE_STATUS = this.checked;
							}
						}catch(e){
						}

						if(typeof module != "undefined"){
							Section = new module();
							App.initSectionValues(data.data.section_config);
						}
					}, App.TIMEOUT_RETARD);
				},error : function(){
					document.title = App.__GENERAL__.str_there_has_been_an_error;
					$("#error_happened").fadeIn(App.TIME_FOR_SHOW);
					$("#title_section").empty();
				},after : function(){
					$("#loading_section").fadeOut(App.TIME_FOR_HIDE);
					$("#reload_section").show(App.TIME_FOR_SHOW).attr("disabled", false);
					getting_section = false;
				},log_ui_msg : false
			});
		}

	/*
		@method : click_on_side_menu_option
		@description : proccess the click made over one of the options of the side menu
		@parameters:
			> DOM_side_menu_element : the element where the click has been made
	*/
		this.click_on_side_menu_option = function(DOM_side_menu_element){
			$("#toggle_sidebar").trigger("click");

			if(locations_history.length>0 && locations_history[locations_history.length-1].id == DOM_side_menu_element.getAttribute("data-id")){
				return;
			}

			index_location++;
			locations_history = locations_history.slice(0, index_location);
			locations_history.push({id:DOM_side_menu_element.getAttribute("data-id"), route:DOM_side_menu_element.getAttribute("data-route")});
			var stateObj = {};

			if(typeof Section != "undefined" && typeof Section.FLAGS.LET_CHANGE_SECTION != "undefined"){
				if(Section.FLAGS.LET_CHANGE_SECTION){
					get_section(DOM_side_menu_element.getAttribute("data-id"), DOM_side_menu_element.getAttribute("data-route"), stateObj);
				}else{
					alertify.confirm(App.__GENERAL__.str_unsaved_changes, function(){
						get_section(DOM_side_menu_element.getAttribute("data-id"), DOM_side_menu_element.getAttribute("data-route"), stateObj);
					});
				}
			}else{
				get_section(DOM_side_menu_element.getAttribute("data-id"), DOM_side_menu_element.getAttribute("data-route"), stateObj);
			}
		}

	/*
		@method : change_section
		@description : proccess the change triggered by the History object change (the 'back' and 'forward' buttons of the browser)
	*/
		function change_section(){
			var index;

			if(index_location>0 && locations_history[index_location-1].location == document.location.pathname){
				index = 1;
				index_location--;
			}else if(index_location<locations_history.length-1 && locations_history[index_location+1].location == document.location.pathname){
				index = -1;
				index_location++;
			}

			if(typeof Section != "undefined" && typeof Section.FLAGS.LET_CHANGE_SECTION != "undefined"){
				if(Section.FLAGS.LET_CHANGE_SECTION){
					get_section(locations_history[index_location].id);
				}else{
					alertify.confirm(App.__GENERAL__.str_unsaved_changes, function(){
						get_section(locations_history[index_location].id);
					}, function(){
						index_location+=index;

						if(locations_history[index_location]){
							history.pushState({}, "", locations_history[index_location].route);
						}else{
							index_location-=index;
						}
					});
				}
			}else{
				get_section(locations_history[index_location].id);
			}
		}

	/*
		@description : proccess the click on the 'forward' and 'back' buttons of the browser
	*/
		window.onpopstate = function(e){
			change_section();
		}

	/*
		@description : processes to be executed when the window is already loaded
	*/
		window.addEventListener("load", function(){
			App.HTTP.read({
				url : sectionTermsJSONUrl(),
				success : function(d2, e2, f2){
					App.__GENERAL__ = {};

					$.each(d2.data, function(k, v){
						if(typeof v.value == "string"){
							App.__GENERAL__[k] = JSON.parse(v.value)[APP_LANGUAGE];
						}
					})
				}, log_ui_msg : false
			});

			var cond = true,
				url_from = window.location.href.substr(window.location.href.indexOf(App.WEB_ROOT));

			$("a").each(function(){

				if(this.parentNode.tagName.toLowerCase() == "li" && this.getAttribute("data-route") == url_from){
					cond = false;
					$(this).trigger("click");
				}
			});

			if(cond){
				$("a[data-route='"+App.WEB_ROOT+"/home']").first().trigger("click");
			}

			setInterval(function(){
				if(typeof Section != "undefined" && typeof Section.FLAGS.LET_CHANGE_SECTION != "undefined" && !Section.FLAGS.LET_CHANGE_SECTION){
					window.onbeforeunload  = function(){
						return App.__GENERAL__.str_unsaved_content_will_be_lost;
					}
				}else{
					window.onbeforeunload = null;
				}
			}, App.INTERVAL_RETARD);
		});

	/*
		@method : requestView
		@description : request an view (different of a section because it's a modal)
		@parameters:
			> context : alias for 'model', the type of element of the system where it's gonna be worked
			> action : the action to perform over the 'context'
			> function_after : extra function (could be undefined) to execute after the view is received from the server
	*/
		function requestView(context, action, function_after){
			App.ShowLoading();
			MODALS_CACHE[context]={};
			MODALS_CACHE[context][action]=true;
			$("#modal_"+context+"_"+action).remove();

			App.HTTP.get({
				url:App.WEB_ROOT+"/"+context+"/"+action,
				data:{
					idsection:App.currentSection
				},
				success:function(data){
					var span = document.createElement("span");
					span.innerHTML = data.data.view.trim();
					$("#modal_"+context+"_"+action).remove();
					document.getElementById("called_resources").appendChild(span.childNodes[0]);
					var cantJsResourcesForCall = data.data.js.length;

					(function callRes(pos){
						if(pos < cantJsResourcesForCall){
							var res = document.createElement("script");
							res.type = "text/javascript";
							res.src = data.data.js[pos]+"?_="+String(Math.random()).substr(2, 13);
							res.onload = function(){
								callRes(pos+1);
							}
							document.getElementById("called_resources").appendChild(res);
						}else{
							var modal_name = "#modal_"+context+"_"+action;
							App.HideLoading();

							if(!App.isTrue(Section.CONFIGURATION.statuses.use)){
								$("[data-controls='statuses']").hide();
							}

							if(!App.isTrue(Section.permises.language_controls)){
								$("[data-column='language']").hide();
							}

							$(modal_name).find("[data-select-type='status'],[name='status']").each(function(){
								if($(this).attr("id") != "see_with_status"){
									$(this).find("option").each(function(){
										if(true || !App.isTrue(Section.CONFIGURATION.statuses.use)){
											$(this).prop("selected", Section.CONFIGURATION.statuses.default.indexOf(Number($(this).val())) != -1);
										}

										if(Section.CONFIGURATION.statuses.permitted.indexOf(Number($(this).val())) == -1){
											$(this).remove();
										}
									});
								}
							})

							$(modal_name).modal("show");
							Actions[context+"-"+action] = new __action();

							if(typeof function_after != "undefined"){
								function_after();
							}

							App.UnlockScreen();
						}
					})(0);
				},error:function(){
					App.Alert(App.__GENERAL__.str_there_has_been_an_error_during_transaction);
					App.HideLoading();
				},log_ui_msg:false
			});
		}

	/*
		@description : public interface for 'requestView' method
	*/
	this.getView = requestView;

	/*
		@method : modal_triggers
		@description : associate the request of a view over a set of DOM elements when a click is made on them
		@parameters:
			> container : the DOM container where the search of elements to link to them the trigger is gonna be made
	*/
		function modal_triggers(container){
			$(container).find("[data-modal]").click(function(){
				var context = this.getAttribute("data-modal").substr(0, this.getAttribute("data-modal").indexOf("@"));
				var action = this.getAttribute("data-modal").substr(this.getAttribute("data-modal").indexOf("@") + 1);
				requestView(context, action);
			});
		}

	/*
		@method : ap_ui_init
		@description : build a set of behaviors on DOM elements of a DOM container
		@parameters:
			> container : the DOM container where the search of elements (to link to them the behaviors) is gonna be made
	*/
		this.ap_ui_init = function(container){
			if(typeof container == "undefined"){
				container = window;
			}

			modal_triggers(container);
		}

	/*
		@method : flexible_equal_array
		@description : compare two unordered Arrays or JSON elements
		@parameters:
			> v1 : the first array
			> v2 : the second array
			> type : the type to map the elements of the arrays
	*/
		this.flexible_equal_array = function(v1, v2, type){
			if(typeof type == "undefined"){
				type = "numeric";
			}

			if(typeof v1 != "object"){
				v1 = [v1];
			}

			if(typeof v2 != "object"){
				v2 = [v2];
			}

			var arr1 = Array(),
				arr2 = Array(),
				val;

			for(d in v1){
				switch(type){
					case "numeric":{
						val = Number(v1[d]);
					}
					break;
				}

				arr1.push(val);
			}

			for(d in v2){
				switch(type){
					case "numeric":{
						val = Number(v2[d]);
					}
					break;
				}

				arr2.push(val);
			}

			arr1.sort();
			arr2.sort();

			if(arr1.length != arr2.length){
				return false;
			}

			var i = 0,
				n = arr1.length;

			while(i < n){
				if(arr1[i] != arr2[i]){
					return false;
				}

				i++;
			}

			return true;
		}

	/*
		@method : showOrHideRow
		@description : decides if to show or to hide the row of an item, based in a serie of conditions
		@parameters:
			> row : the first array
			> required_statuses : the second array
			> type : a flag with a use specified inside the method
	*/
		this.showOrHideRow = function(row, required_statuses, flag){
			if(required_statuses == null){
				required_statuses = Array();
			}

			/*
			for(x in required_statuses){
				required_statuses[x] = Number(required_statuses[x]);
			}
			*/
			required_statuses = required_statuses.map(function(v){return Number(v)});

			var tn = required_statuses.length,
				cond = false,
				i = -1,
				row_statuses = ($(row).attr("data-status"));

			function minishow(){
				if(!flag){
					$(row).show(App.TIME_FOR_SHOW);
				}else{
					row.style.display = "";
				}
			}
			function minihide(){
				if(!flag){
					$(row).hide(App.TIME_FOR_HIDE);
				}else{
					row.style.display = "none";
				}
			}

			if(typeof row_statuses != "undefined" && row_statuses != null && row_statuses.toLowerCase() != "null"){
				var n = row_statuses.length;
				row_statuses = JSON.parse(row_statuses);
				cond = row_statuses.indexOf(-1) != -1;
				var dont_show = row_statuses.indexOf(-2) != -1;

				if(dont_show){
					minihide();
					return false;
				}else if(cond){
					minishow();
					return true;
				}else if((n == 0 && tn == 0) || App.SEE_ITEM_NO_MATTER_THE_STATUS){
					minishow();
					return true;
				}else{
					while(!cond && ++i < n){
						cond = required_statuses.indexOf(row_statuses[i]) != -1;
					}

					if(cond){
						minishow();
						return true;
					}else{
						minihide();
						return false;
					}
				}
			}else{
				return true;
			}
		}

	/*
		@method : CommonsElements
		@description : decides if to show or to hide the row of an item, based in a serie of conditions
		@parameters:
			> arr1 : the first array
			> arr2 : the second array
			> cant : the amount of element that must have in common both arrays
			> type : the type that the elements of both arrays are gonna be mapped to
	*/
		this.CommonsElements = function(arr1, arr2, cant, type){
			if(typeof cant == "undefined"){
				cant=1;
			}

			if(typeof type == "undefined"){
				type="numeric";
			}

			try{
				if(arr1.length < cant || arr2.length < cant){
					return false;
				}
			}catch(e){
				return false;
			}


			switch(type){
				case "numeric":{
					/*
					for(x in arr1){
						arr1[x] = Number(arr1[x]);
					}

					for(x in arr2){
						arr2[x] = Number(arr2[x]);
					}
					*/
					arr1 = arr1.map(function(v){return Number(v)});
					arr2 = arr2.map(function(v){return Number(v)});
				}
				break;
			}

			var count = 0;

			for(v in arr1){
				var index = arr2.indexOf(arr1[v]);

				if(index != -1){
					count++;

					if(count == cant){
						return true;
					}
				}
			}

			return false;
		}

	/*
		@method : cloneObject
		@description : creates a copy of an object
		@parameters:
			> obj : the object to be copied/cloned
	*/
		this.cloneObject = function(obj){
			if(null == obj || "object" != typeof obj){
				return obj;
			}

			var copy = obj.constructor();

			for(var attr in obj){
				if(obj.hasOwnProperty(attr)){
					copy[attr] = App.cloneObject(obj[attr]);
				}
			}

			return copy;
		}

	/*
		@method : stringify_statuses
		@description : creates an string of name of statuses
		@parameters:
			> statuses : the array of id's of statuses
	*/
		this.stringify_statuses = function(statuses){
			try{
				var v = Array();

				for(z in statuses){
					v.push($("#see_with_status").find("option[value='"+statuses[z]+"']").html().trim());
				}

				v = v.join(", ");
				return v;
			}catch(e){
				return "";
			}
		}

	/*
		@description : proccess the click over the button that 
	*/
		$("#reload_section").click(function(){
			if(getting_section){
				return;
			}

			if(typeof Section != "undefined" && typeof Section.FLAGS.LET_CHANGE_SECTION != "undefined"){
				if(Section.FLAGS.LET_CHANGE_SECTION){
					get_section(App.currentSection);
				}else{
					alertify.confirm(App.__GENERAL__.str_unsaved_changes, function(){
						get_section(App.currentSection);
					});
				}
			}else{
				get_section(App.currentSection);
			}
		});

	/*
		@method : isTrue
		@description : check if a given value can be calificated as equivalent of boolean 'true' value
		@parameters:
			> value : the value to be checked
	*/
		this.isTrue = function(value){
			switch(typeof value){
				case "number":{
					return value == 1;
				}break;
				case "string":{
					return value.toLowerCase() == "true" || value.toLowerCase() == "1";
				}break;
				case "boolean":{
					return value;
				}break;
				case "undefined":{
					return false;
				}break;
				case "object":{
					return value != null;
				}break;
				default:{
					return true;
				}
			}
		}

	/*
		@method : inputTextMonitor
		@description : monitorize the changes over a input text field, executing some function after it considers there has been a 'significant' change
		@parameters:
			> id : id of the DOM element (not a selector with '#', only an string)
			> func : the function to be executed after the 'significant' change has been approved
			> retard : check the function definition to learn more
	*/
		this.inputTextMonitor = function(id, func, retard){
			try{
				if(typeof retard == "undefined"){
					retard = App.TIME_RETARD_SEARCH;
				}

				var dec = document.getElementById(id);
				var countdec = 0;
				dec.setAttribute("data-countdec", "0");
				dec.onclick =
				dec.onchange =
				dec.onfocus =
				dec.onblur =
				dec.onkeyup =
				dec.onkeydown = function(){
					setTimeout(function(){
						countdec++;
						$("#go_forward_results").hide(App.TIME_FOR_HIDE);

						setTimeout(function(){
							countdec--;

							if(countdec==0){
								func(dec);
							}
						}, retard);
					}, 50);
				}
			}catch(e){
			}
		}

	/*
		@description : proccess the request of an user to change the language of the interface that he uses
	*/
		$("#select_set_user_session_lng").change(function(){
			App.LockScreen();
			App.ShowLoading(App.__GENERAL__.str_changing_navigation_interface_language);

			App.HTTP.update({
				url:App.WEB_ROOT+"/language-session",
				data:{
					lng:$("#select_set_user_session_lng").val()
				},success:function(d, e, f){
					window.location.reload();
				},error:function(x, y, z){
					App.UnlockScreen();
					App.HideLoading();
				},log_ui_msg : false
			});
		});

	/*
		@method : updateSelect2
		@description : special method used to change a select multiple in different ways
			* adding a new 'option'
			* changing the innerHTML of one of the options
			* removing one of the options
		@parameters:
			> typeSelect : a kind of special value, to specify which 'selects' are gonna be affected
			> op : the operation that is gonna be executed (one of the three specified in the 'description')
			> data : the data to use to perform one of the three operations, it is a JSON object
	*/
		this.updateSelect2 = function(typeSelect, op, data){
			try{
				$("select[data-select-type='"+typeSelect+"']").select2("destroy");
			}catch(e){
				console.log(e);
			}

			switch(op){
				case "new":{
					$("select[data-select-type='"+typeSelect+"']").each(function(){
						var option = document.createElement("option");
						option.value = data.id;
						option.innerHTML = data.name;
						$(this).append(option);
					});
				}break;
				case "edit":{
					$("select[data-select-type='"+typeSelect+"']").each(function(){
						$(this).find("option[value='"+data.id+"']").html(data.name);
					});
				}break;
				case "remove":{
					$("select[data-select-type='"+typeSelect+"']").each(function(){
						$(this).find("option[value='"+data.id+"']").remove();
					});
				}break;
			}

			$("select[data-select-type='"+typeSelect+"']").select2();
			$(".select2-container").css("width", "100%");
		}


	/*
		@description : if it's enabled the monitor of inactivity, there are gonna be performed some operations
		in order to monitorize the inactivity of the user, and deploy a set of operations if the timelimit is 
		overpassed
	*/
		if(USE_INACTIVIY_TIMELIMIT != "no"){
			function resetInactivityCounter(){
				inactivityCounter = 0;
			}

			$(window 	).keydown(resetInactivityCounter).keyup(resetInactivityCounter).scroll(resetInactivityCounter).click(resetInactivityCounter).mousemove(resetInactivityCounter);
			$(document 	).keydown(resetInactivityCounter).keyup(resetInactivityCounter).scroll(resetInactivityCounter).click(resetInactivityCounter).mousemove(resetInactivityCounter);
			$("body" 	).keydown(resetInactivityCounter).keyup(resetInactivityCounter).scroll(resetInactivityCounter).click(resetInactivityCounter).mousemove(resetInactivityCounter);

			setInterval(function(){
				inactivityCounter++;

				if(inactivityCounter >= INACTIVITY_TIMELIMIT){
					window.location.href = App.WEB_ROOT + "/inactivity";
				}
			}, this.INTERVAL_RETARD);
		}

	/*
		@method : DOM_Disabling
		@description : it disables (set the 'disabled' attribute to 'true') all the children element of a root DOM element, even itself
		@parameters:
			> item : the root DOM element
	*/
		this.DOM_Disabling = function(item){
			if(!$(item).prop("disabled")){
				$(item).attr("data-enable", "1").prop("disabled", true);
			}
			$(item).children().each(function(){
				App.DOM_Disabling(this);
			});
		}

	/*
		@method : DOM_Enabling
		@description : it enables (set the 'disabled' attribute to 'false') all the children element of a root DOM element, even itself
		@parameters:
			> item : the root DOM element
	*/
		this.DOM_Enabling = function(item){
			if($(item).attr("data-enable") == "1"){
				$(item).prop("disabled", false);
			}
			$(item).children().each(function(){
				App.DOM_Enabling(this);
			});
		}

	/*
		@method : ShowLoading
		@description : shows a modal/lightbox with a icon of 'loading', with a message
		@parameters:
			> message : the message to be shown
	*/
		this.ShowLoading = function(message){
			if(message){
				$("#loading_message").html(message);
			}else{
				$("#loading_message").html(App.__GENERAL__.str_loading);
			}

			$("#modal_loading").modal("show");
		}

	/*
		@method : HideLoading
		@description : hides the modal/lightbox shown in ShowLoading
	*/
		this.HideLoading = function(){
			$("#modal_loading").modal("hide");
		}

	/*
		@description : it appends the 'loading' modal to the DOOM
	*/
		$("body").append(''+
			'   <div class = "modal fade" id = "modal_loading" align = "center" style = "padding:10%;">'+
			'		<div class = "modal-content" style = "width:50%;">'+
			'	   	<div class = "modal-body">'+
			'				<img src = "'+this.LOADING_ICON+'" style = "width:10%;"><br>'+
			'				<br><strong><p id = "loading_message"></p></strong>'+
			'	   	</div>'+
			'		</div>'+
			'  </div>'+
		'');

	/*
		@description : check if the object passed as parameter is 'undefined'
	*/
		this.isset = function(v){
			return typeof v != "undefined";
		}

	/*
		@description : proccess the click over the 'go back' custom control of Admin-Panel
		NOT IMPLEMENTED YET
	*/
		$("#go_back_section").click(function(){
			//return;
			//index_location--;
			//document.location.pathname = locations_history[index_location].location;
			//change_section();
		});

	/*
		@method : Alert
		@description : shows a custom 'alert'
		@parameters:
			> message : the message to be shown
	*/
		this.Alert = function(message){
			alertify.alert(message, function(){
			});
		}

	/*
		@method : Confirm
		@description : shows a custom 'confirm'
		@parameters:
			> message : the message to be shown
			> yes : the method to execute if the user decides 'yes' or 'ok', something like that
			> no : the method to execute if the user decides 'no' or 'cancel', something like that
	*/
		this.Confirm = function(message, yes, no){
			if(typeof yes == "undefined"){
				yes = function(){}
			}

			if(typeof no == "undefined"){
				no = function(){}
			}

			alertify.confirm(message, yes, no);
		}

	/*
		@method : addItemToDOM
		@description : method to call the custom method of a section intended to add a row on a list of items
		@parameters:
			> d : some data
	*/
		this.addItemToDOM = function(d){
			if(App.FORMAT_SHOW_ITEMS != "progressive"){
				return;
			}

			var tmp = Section.FLAGS.onSearch;
			Section.FLAGS.onSearch = false;
			Section.add_item_form_to_dom(d.data.item);
			Section.FLAGS.onSearch = tmp;
		}

	/*
		@method : TimeInterval
		@description : custom deployment of 'setInterval', attached to the existence of the section where it has been called from, when the section doesn't exist anymore (being replaced by another) it
			is executed a 'clearInterval' over the function previously passed to this method ('TimeInterval')
		@parameters:
			> _function_ : the function to be executed in interval
			> time : the time passed to setInterval
	*/
		this.TimeInterval = function(_function_, time){
			intervals_for_clear.push(setInterval(_function_, time));
		}

	/*
		@method : stringify
		@description : create a string of names of items
		@parameters: 
			> data : the id's of the items
			> reference : where the names are taken from
	*/
		this.stringify = function(data, reference){
			var str = [];

			$.each(reference, function(k, v){
				if(data.indexOf(Number(v.id)) != -1){
					str.push(v.name);
				}
			});

			return str.join(", ");
		}

	/*
		@method : GetMasterData
		@description : get a list of all the items of a model
		@parameters: 
			> endpoint : the API endpoint where the items are gonna be requested to
			> function_after : the function to execute once the success response from the server is received
	*/
		this.GetMasterData = function(endpoint, function_after){
			App.ShowLoading();
			App.HTTP.read({
				url : App.WEB_ROOT + "/" + endpoint,
				data : {
					token:Section.FLAGS.TOKEN,
					type_request:"list_items",
					reset:true,
					lng : Section.FLAGS.formConfigs["lng"],
					see_all : true,
				},
				success : function_after,
				error : function(x, y, z){
					App.HideLoading();
					App.Alert(App.__GENERAL__.sr_there_has_been_an_error_try_again);
				}, log_ui_msg : false
			});
		}

	/*
		@description: patch for modal scrolling
	*/
		setInterval(function(){
			$(".modal").css("overflow-y", "scroll");
			$(".modal-backdrop").css("height", $(window).height()+"px").css("position", "fixed");
		}, this.INTERVAL_RETARD);

	/*
		@method: sessionMonitor
		@description: it checks if a session has been already started or it has already finished
	*/
		var lastValSessionMonitor = null;
		var abortSessionMonitor = false;

		function sessionMonitor(){
			App.HTTP.get({
				url : App.WEB_ROOT + "/session-monitor",
				success : function(d, e, f){
					if(lastValSessionMonitor != null && lastValSessionMonitor != d.data.session){
						App.ShowLoading();
						window.location.reload();
					}else{
						lastValSessionMonitor = d.data.session;
					}
				},
				error : function(x, y, z){
				},
				after: function(){
					if(!abortSessionMonitor){
						setTimeout(sessionMonitor, 10000);
					}
				},
				log_ui_msg : false
			});
		}

		setTimeout(sessionMonitor, 1000);

		this.abort_session_monitor = function(){
			abortSessionMonitor = true;
		}

	/*
		@method: changeFormatShowItems
		@description: the format to show the items (progressive load vs pagination) is changed
		@parameters:
			> format: the choosen format
			> fsuccess: the function to be executed if the change is proccessed with no problems in the server
			> ferror : the function to be executed if we receive a 4xx error
			> fafter : the function to be executed when the error or success response is already processed
	*/
		this.changeFormatShowItems = function(format, fsuccess, ferror, fafter){
			App.HTTP.update({
				url : App.WEB_ROOT + "/format-show-items",
				data : {
					format : format
				}, success : function(d, e, f){
					$("meta[name='format_show_items']").attr("content", format)
					App.FORMAT_SHOW_ITEMS = format;
					fsuccess();
				}, error : ferror, after : fafter
			});
		}

		var changing_format_show_items = false;

		$("input[name='global_format_show_items']").change(function(){
			if(!this.checked || changing_format_show_items){
				return;
			}

			var thing = this;
			changing_format_show_items = true;
			$("input[name='global_format_show_items']").prop("disabled", true)
			App.LockScreen();
			App.ShowLoading();

			App.changeFormatShowItems(thing.value, function(){
				App.UnlockScreen();
				App.HideLoading();
				get_section(App.currentSection)
			}, function(){
				thing.checked = false;

				if(thing.value == "progressive"){
					$("input[value='pagination']").prop("checked", true)
				}else{
					$("input[value='progressive']").prop("checked", true)
				}
			}, function(){
				changing_format_show_items = false;
				$("input[name='global_format_show_items']").prop("disabled", false)
			})
		});

	/*
		@method: changeFormatEditItems
		@description: the format to edit the items (modal vs inline) is changed
		@parameters:
			> format: the choosen format
			> fsuccess: the function to be executed if the change is proccessed with no problems in the server
			> ferror : the function to be executed if we receive a 4xx error
			> fafter : the function to be executed when the error or success response is already processed
	*/
		this.changeFormatEditItems = function(format, fsuccess, ferror, fafter){
			App.HTTP.update({
				url : App.WEB_ROOT + "/format-edit-items",
				data : {
					format : format
				}, success : function(d, e, f){
					$("meta[name='format_edit_items']").attr("content", format)
					App.FORMAT_EDIT_ITEMS = format;
					fsuccess();
				}, error : ferror, after : fafter
			});
		}

		var changing_format_edit_items = false;

		$("input[name='global_format_edit_items']").change(function(){
			if(!this.checked || changing_format_edit_items){
				return;
			}

			var thing = this;
			changing_format_edit_items = true;
			$("input[name='global_format_edit_items']").prop("disabled", true)
			App.LockScreen();
			App.ShowLoading();

			App.changeFormatEditItems(thing.value, function(){
				App.UnlockScreen();
				App.HideLoading();
				get_section(App.currentSection)
			}, function(){
				thing.checked = false;

				if(thing.value == "inline"){
					$("input[value='modal']").prop("checked", true)
				}else{
					$("input[value='inline']").prop("checked", true)
				}
			}, function(){
				changing_format_edit_items = false;
				$("input[name='global_format_edit_items']").prop("disabled", false)
			})
		});

		this.monitorAmountItemsPerRequest = function(id_form, fbefore, fsuccess, fafter){
			if(typeof fsuccess == "undefined"){
				fsuccess = function(){}
			}

			if(typeof fafter == "undefined"){
				fafter = function(){}
			}

			if(typeof fbefore == "undefined"){
				fbefore = function(){}
			}

			var lastVal_amount_items_per_request = $("#"+id_form).find("input[type='text']").val().trim();

			function fmonitor(){
				if(!isNaN(this.value) && this.value != lastVal_amount_items_per_request && Number(this.value)>0 ){
					$("#"+id_form).find("input[type='submit']").show(App.TIME_FOR_SHOW);
				}else{
					$("#"+id_form).find("input[type='submit']").hide(App.TIME_FOR_HIDE);
				}
			}

			$("#"+id_form).find("input[type='text']").click(fmonitor).change(fmonitor).keyup(fmonitor).keydown(fmonitor).blur(fmonitor).focus(fmonitor);

			var saving_pr = false;

			$("#"+id_form).submit(function(e){
				e.preventDefault();

				if(saving_pr){
					return;
				}

				saving_pr = true;
				$("#"+id_form).find("input[type='submit']").attr("disabled", true);

				App.HTTP.update({
					url:App.WEB_ROOT+"/amount-items-progressive-requests",
					data:{
						val:$("#"+id_form).find("input[type='text']").val().trim()
					},success:function(d, e, f){
						App.AMOUNT_ITEMS_PER_REQUEST = lastVal_amount_items_per_request = Number($("#"+id_form).find("input[type='text']").val().trim());
						$("#"+id_form).find("input[type='submit']").hide(App.TIME_FOR_HIDE);
						fsuccess(d, e, f);
					},after:function(x, y, z){
						$("#"+id_form).find("input[type='submit']").attr("disabled", false);
						saving_pr = false;
						fafter(x, y, z);
					},before : fbefore
				});
			});
		}

		if(document.getElementById("global_form_amount_items_per_request") != null){
			this.monitorAmountItemsPerRequest("global_form_amount_items_per_request", function(){
				App.LockScreen();
				App.ShowLoading();
			}, function(){
				get_section(App.currentSection)
			}, function(){
				App.UnlockScreen();
				App.HideLoading();
			});
		}
}

var App = new app(), 
	Section, 
	Actions = {};