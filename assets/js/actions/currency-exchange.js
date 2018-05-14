//"use strict";
function __action(){
/**/
	var GetCurrencyAttribute = Section.getCurrencyAttr;

	var apis = [
		{
			code : "fixerio",
			name : "Fixer.io"
		},{
			code : "currencylayer",
			name : "Currency Layer"
		},{
			code : "openexchange",
			name : "OpenExchange"
		},{
			code : "xecurrency",
			name : "XeCurrency"
		},{
			code : "freecurrency",
			name : "FreeCurrency"
		},{
			code : "jsonrates",
			name : "JSON-Rates"
		},{
			code : "currencyapi",
			name : "CurrencyAPI"
		},{
			code : "xignite",
			name : "Xignite"
		},{
			code : "getexchangerates",
			name : "GetExchangeRates"
		}
	];

	var classes = [
		"default",
		"primary",
		"success",
		"info",
		"warning",
		"danger",
		"link"
	]

	function getExchange(code){
		$("#configs_apis").children().hide(App.TIME_FOR_HIDE);
		$("#configs_apis").show(App.TIME_FOR_SHOW);
		$("#configs_"+code).show(App.TIME_FOR_SHOW);
	}

	for(var i in apis){
		var btn = document.createElement("button");
		btn.className = "btn btn-"+classes[i % classes.length];
		btn.innerHTML = apis[i].name;
		btn.setAttribute("data-code", apis[i].code);
		btn.onclick = function(){
			getExchange(this.getAttribute("data-code"));
		};
		$("#buttons_list_apis").append(btn);
	}

	$("#fixerio_currencies_1, #fixerio_currencies_2").select2();
	$(".select2-container").css("width", "100%");

	$("#fixerio_exchange").click(function(){
		var arr1 = Array();
		var arr2 = Array();

		if($("#fixerio_selectall_1").prop("checked")){
			$("#fixerio_currencies_1").children().each(function(){
				arr1.push($(this).attr("data-code").toUpperCase());
			});
		}else{
			var tmp = $("#fixerio_currencies_1").val();

			if(tmp == null){
				return;
			}

			$("#fixerio_currencies_1").children().each(function(){
				if(tmp.indexOf($(this).val()) != -1){
					arr1.push($(this).attr("data-code").toUpperCase());
				}
			});
		}

		if($("#fixerio_selectall_2").prop("checked")){
			$("#fixerio_currencies_2").children().each(function(){
				arr2.push($(this).attr("data-code").toUpperCase());
			});
		}else{
			var tmp = $("#fixerio_currencies_2").val();

			if(tmp == null){
				return;
			}

			$("#fixerio_currencies_2").children().each(function(){
				if(tmp.indexOf($(this).val()) != -1){
					arr2.push($(this).attr("data-code").toUpperCase());
				}
			});
		}

		$("#fixerio_list_exchanges").empty();
		var counter = 0;

		for(var i in arr1){
			for(var j in arr2){
				if(arr1[i] != arr2[j]){
					counter++;
					add_exchange_fixerio({
						origin : 	arr1[i],
						destiny : 	arr2[j],
						value : 	App.terms.str_getting_exchange
					});
				}
			}
		}

		App.ShowLoading(App.terms.str_requesting_exchanges_from_fixerio);

		for(var i in arr1){
			for(var j in arr2){
				if(arr1[i] != arr2[j]){
					App.HTTP.get({
						url : "http://api.fixer.io/latest?callback=?&base="+arr1[i]+"&symbols="+arr2[j],
						success : function(d, e, f){
							counter--;
							var c1 = d.data.base.toLowerCase();
							var c2, val;
							$.each(d.data.rates, function(key, value){
								c2 = key.toLowerCase();
								val = value;
							});
							$("#fixerio_exchange_value_"+c1+"_"+c2).html(val).attr("data-fixerio_exchange_value", "1");
							$("#fixerio_apply_value_"+c1+"_"+c2).attr("data-type-button", "apply_value").show(App.TIME_FOR_SHOW);

							if(counter==0){
								App.HideLoading();
								$("td[data-fixerio_exchange_value='0']").html(App.terms.str_no_value);
							}
						},
						error : function(x, y, z){
							counter--;

							if(counter==0){
								App.HideLoading();
								$("td[data-fixerio_exchange_value='0']").html(App.terms.str_no_value);
							}
						},
						log_ui_msg : false
					});
				}
			}
		}
	});

	$("#fixerio_select_all").change(function(){
		$("#fixerio_list_exchanges").find("input[type='checkbox']").prop("checked", $(this).prop("checked"));
	});

	$("#fixerio_use_selected").click(function(){
		var mult = 0;
		$("button[data-type-button='apply_value']").each(function(){
			var t = $(this);
			setTimeout(function(){
				t.trigger("click");
			}, (++mult)*1000);
		});
	});

	$("#exchanges_currencies_1, #exchanges_currencies_2").select2();
	$(".select2-container").css("width", "100%");

	function add_exchange_fixerio(data){
		var row = document.createElement("tr");

		var td1 = document.createElement("td");
		var td2 = document.createElement("td");
		var td3 = document.createElement("td");
		var td4 = document.createElement("td");
		var td5 = document.createElement("td");
		var td6 = document.createElement("td");

		td1.align = td2.align = td3.align = td4.align = td5.align = td6.align = "center";

		var checkbox = document.createElement("input");
		checkbox.type = "checkbox";
		checkbox.checked = $("#fixerio_select_all").prop("checked");
		td1.appendChild(checkbox);

		//td2.innerHTML = "<strong>"+data.origin.toUpperCase() + " (" + $("#db_currencies").find("option[data-code='"+data.origin.toLowerCase()+"']").html().trim() +")</strong>";
		td2.innerHTML =   "<strong>"+data.origin.toUpperCase() + " (" + GetCurrencyAttribute("name", "code", data.origin, "low") +")</strong>";
		td3.innerHTML = "<strong>=</strong>";
		td4.innerHTML = "<strong>"+data.value+"</strong>";
		td4.id = "fixerio_exchange_value_"+data.origin.toLowerCase()+"_"+data.destiny.toLowerCase();
		td4.style.fontWeight = "bold";
		td4.setAttribute("data-fixerio_exchange_value", "0");
		//td5.innerHTML = "<strong>"+data.destiny.toUpperCase() + " (" + $("#db_currencies").find("option[data-code='"+data.destiny.toLowerCase()+"']").html().trim() +")</strong>";
		td5.innerHTML = "<strong>"+data.destiny.toUpperCase() + " (" + GetCurrencyAttribute("name", "code", data.destiny, "low") +")</strong>";

		var btnapplyvalue = document.createElement("button");
		btnapplyvalue.className = "btn btn-info";
		btnapplyvalue.innerHTML = App.terms.str_use_value;
		btnapplyvalue.id = "fixerio_apply_value_"+data.origin.toLowerCase()+"_"+data.destiny.toLowerCase();
		btnapplyvalue.style.display = "none";
		btnapplyvalue.onclick = function(){
			var aux = $("#exchange_value_"+data.origin.toLowerCase()+"_"+data.destiny.toLowerCase());

			if(aux.length == 0){
				aux = $("#exchange_value_"+data.destiny.toLowerCase()+"_"+data.origin.toLowerCase());

				if(aux.length > 0){
					//aplicar inversa
					aux.val(1 / Number(td4.innerHTML.trim()));
				}
			}else{
				aux.val(td4.innerHTML.trim());
			}

			var v = $("#save_exchange_"+data.destiny.toLowerCase()+"_"+data.origin.toLowerCase()+", #save_exchange_"+data.origin.toLowerCase()+"_"+data.destiny.toLowerCase());
			if(v.length > 0){
				v = v[0];
				$(v).trigger("click");
			}else{
				//crearlo

				AUTOMATIC_EXCHANGE = {
					code_c1 : data.origin.trim().toLowerCase(),
					code_c2 : data.destiny.trim().toLowerCase(),
					value : td4.innerHTML.trim()
				}
				add_exchange_to_dom();
			}

			$(btnapplyvalue).remove();
		}
		td6.appendChild(btnapplyvalue);


		row.appendChild(td1);
		row.appendChild(td2);
		row.appendChild(td3);
		row.appendChild(td4);
		row.appendChild(td5);
		row.appendChild(td6);

		$("#fixerio_list_exchanges").append(row);
	}

	var AUTOMATIC_EXCHANGE = null;

	$("#show_hide_apis_controls").click(function(){
		$("#configs_apis").toggle(App.TIME_FOR_SHOW);
	});

	function add_exchange_to_dom(data){
		var created = typeof data != "undefined";
		var row = document.createElement("tr");
		var td1 = document.createElement("td");
		var td2 = document.createElement("td");
		var td3 = document.createElement("td");
		var td4 = document.createElement("td");
		var td5 = document.createElement("td");
		var td6 = document.createElement("td");

		td1.align = td2.align = td3.align = td4.align = td5.align = td6.align = "center";

		var select_currency_1 = document.createElement("select");
		//select_currency_1.innerHTML = $("#db_currencies").html();
		select_currency_1.innerHTML = Section.generateCurrenciesSelect();

		var select_currency_2 = document.createElement("select");
		//select_currency_2.innerHTML = $("#db_currencies").html();
		select_currency_2.innerHTML = Section.generateCurrenciesSelect();

		var inputvalue = document.createElement("input");
		inputvalue.className = "form-control";
		inputvalue.type = "text";
		inputvalue.style.textAlign = "center";

		if(created){
			//inputvalue.id = "exchange_value_"+$("#db_currencies").find("option[value='"+data.currency_1+"']").attr("data-code").toLowerCase()+"_"+$("#db_currencies").find("option[value='"+data.currency_2+"']").attr("data-code").toLowerCase();
			inputvalue.id = "exchange_value_"+  GetCurrencyAttribute("code", "id", data.currency_1, undefined, "low")+"_"+ GetCurrencyAttribute("code", "id", data.currency_2, undefined, "low");
			inputvalue.value = data.value;
		}

		var btnsave = document.createElement("button");
		btnsave.className = "btn btn-info";
		btnsave.innerHTML = App.terms.str_save;
		var saving_exchange = false;

		btnsave.onclick = function(){
			if(saving_exchange){
				return;
			}

			saving_exchange = btnsave.disabled = true;

			if(created){
				App.HTTP.update({
					url : App.WEB_ROOT + "/exchange/"+data.id,
					data : {
						value : condreverse? 1 / Number(inputvalue.value.trim()) : inputvalue.value.trim()
					},success : function(d, e, f){
						data.value = inputvalue.value.trim();
					},after : function(x, y, z){
						btnsave.disabled = false;
						saving_exchange = false;
					}
				});
			}else{
				App.HTTP.create({
					url : App.WEB_ROOT + "/exchange",
					data : {
						currency_1 : select_currency_1.value,
						currency_2 : select_currency_2.value,
						value : inputvalue.value
					},success : function(d, e, f){
						created = true;
						var c1 = select_currency_1.value;
						var c2 = select_currency_2.value;
						$(select_currency_1).remove();
						$(select_currency_2).remove();
						//td2.innerHTML = "<strong>"+ $("#db_currencies").find("option[value='"+c1+"']").attr("data-code").toUpperCase() + " (" + $("#db_currencies").find("option[value='"+c1+"']").html().trim() +")</strong>";
						td2.innerHTML = "<strong>"+ GetCurrencyAttribute("code", "id", c1, undefined, "up") + " (" + GetCurrencyAttribute("name", "id", c1) +")</strong>";
						//td5.innerHTML = "<strong>"+ $("#db_currencies").find("option[value='"+c2+"']").attr("data-code").toUpperCase() + " (" + $("#db_currencies").find("option[value='"+c2+"']").html().trim() +")</strong>";
						td5.innerHTML = "<strong>"+ GetCurrencyAttribute("code", "id", c2, undefined, "up") + " (" + GetCurrencyAttribute("name", "id", c2) +")</strong>";

						$(btnsave).hide(App.TIME_FOR_HIDE);
						data = {
							value : inputvalue.value.trim(),
							id : d.data.item.id,
							currency_1 : c1,
							currency_2 : c2
						};

						//inputvalue.id = "exchange_value_"+$("#db_currencies").find("option[value='"+c1+"']").attr("data-code").toLowerCase()+"_"+$("#db_currencies").find("option[value='"+c2+"']").attr("data-code").toLowerCase();
						inputvalue.id = "exchange_value_"+ GetCurrencyAttribute("code", "id", c1, undefined, "low") +"_"+GetCurrencyAttribute("code", "id", c2, undefined, "low");

						//btnsave.id = "save_exchange_"+$("#db_currencies").find("option[value='"+c1+"']").attr("data-code").toLowerCase()+"_"+$("#db_currencies").find("option[value='"+c2+"']").attr("data-code").toLowerCase();
						btnsave.id = "save_exchange_"+ GetCurrencyAttribute("code", "id", c1, undefined, "low") +"_"+ GetCurrencyAttribute("code", "id", c2, undefined, "low");

						row.setAttribute("data-exchanges", JSON.stringify(Array(String(data.currency_1), String(data.currency_2))));
						App.TimeInterval(detectchange, 1000);

						row.setAttribute("data-show", "1");
						row.id = "exchange_"+d.data.item.id;
						var thecode = GetCurrencyAttribute("code", "id", data.currency_1, undefined, "up");//$("#db_currencies").find("option[value='"+data.currency_1+"']").attr("data-code").toUpperCase();
						td2.setAttribute("data-code-currency-1", thecode.toLowerCase());
						thecode = GetCurrencyAttribute("code", "id", data.currency_2, undefined, "up");//$("#db_currencies").find("option[value='"+data.currency_2+"']").attr("data-code").toUpperCase();
						td5.setAttribute("data-code-currency-2", thecode.toLowerCase());
						$(btnreverse).show(App.TIME_FOR_SHOW);
						$(reset).show(App.TIME_FOR_SHOW);
						btnreverse.id = "reverse_exchange_"+data.currency_1+"_"+data.currency_2;
					},after : function(x, y, z){
						btnsave.disabled = false;
						saving_exchange = false;
					}
				});
			}
		}

		if(created){
			//btnsave.id = "save_exchange_"+$("#db_currencies").find("option[value='"+data.currency_1+"']").attr("data-code").toLowerCase()+"_"+$("#db_currencies").find("option[value='"+data.currency_2+"']").attr("data-code").toLowerCase();
			btnsave.id = "save_exchange_"+ GetCurrencyAttribute("code", "id", data.currency_1, undefined, "low") +"_"+ GetCurrencyAttribute("code", "id", data.currency_2, undefined, "low");
			btnsave.style.display = "none";
		}

		function detectchange(){
			if(	inputvalue.value.trim().length > 0 && 
				(	(!condreverse && inputvalue.value.trim() != String(data.value).trim()) ||
					(condreverse && 1 / Number(inputvalue.value.trim()) != Number(String(data.value).trim())))){
				$(btnsave).show(App.TIME_FOR_SHOW);
			}else{
				$(btnsave).hide(App.TIME_FOR_HIDE);
			}
		}

		if(created){
			App.TimeInterval(detectchange, 1000);
		}

		var btnremove = document.createElement("button");
		btnremove.className = "btn btn-danger";
		btnremove.innerHTML = App.terms.str_remove;

		if(created){
			var thecode = GetCurrencyAttribute("code", "id", data.currency_1, undefined, "up");//$("#db_currencies").find("option[value='"+data.currency_1+"']").attr("data-code").toUpperCase();
			//td2.innerHTML = "<strong>"+ thecode + " (" + $("#db_currencies").find("option[value='"+data.currency_1+"']").html().trim() +")</strong>";
			td2.innerHTML = "<strong>"+ thecode + " (" + GetCurrencyAttribute("name", "id", data.currency_1) +")</strong>";
		}else{
			td2.appendChild(select_currency_1);
		}

		td3.innerHTML = "<strong>=</strong>";


		var btnreverse = document.createElement("button");
		btnreverse.className = " btn btn-link";
		btnreverse.innerHTML = "<strong><-></strong>";
		var condreverse = false;

		btnreverse.onclick = function(){
			condreverse = !condreverse;
			var reverse = 1 / Number(inputvalue.value);
			inputvalue.value = String(reverse);
			var tmp = td2.innerHTML;
			td2.innerHTML = td5.innerHTML;
			td5.innerHTML = tmp;
		}

		btnreverse.style.display = created?"":"none";

		if(created){
			btnreverse.id = "reverse_exchange_"+data.currency_1+"_"+data.currency_2;
		}

		var reset = document.createElement("button");
		reset.className = " btn btn-link";
		reset.innerHTML = "<strong>Restore</strong>";

		reset.onclick = function(){
			condreverse = false;
			inputvalue.value = data.value;

			var thecode = GetCurrencyAttribute("code", "id", data.currency_1, undefined, "up");//$("#db_currencies").find("option[value='"+data.currency_1+"']").attr("data-code").toUpperCase();
			//td2.innerHTML = "<strong>"+ thecode + " (" + $("#db_currencies").find("option[value='"+data.currency_1+"']").html().trim() +")</strong>";
			td2.innerHTML = "<strong>"+ thecode + " (" + GetCurrencyAttribute("name", "id", data.currency_1) +")</strong>";
			//thecode = $("#db_currencies").find("option[value='"+data.currency_2+"']").attr("data-code").toUpperCase();
			thecode = GetCurrencyAttribute("code", "id", data.currency_2, undefined, "up");
			//td5.innerHTML = "<strong>"+ thecode + " (" + $("#db_currencies").find("option[value='"+data.currency_2+"']").html().trim() +")</strong>";
			td5.innerHTML = "<strong>"+ thecode + " (" + GetCurrencyAttribute("name", "id", data.currency_2) +")</strong>";
		}
		reset.style.display = created?"":"none";

		td4.appendChild(inputvalue);
		td4.appendChild(btnreverse);
		td4.appendChild(document.createElement("br"));
		td4.appendChild(reset);

		if(created){
			var thecode = GetCurrencyAttribute("code", "id", data.currency_2, undefined, "up");//$("#db_currencies").find("option[value='"+data.currency_2+"']").attr("data-code").toUpperCase();
			//td5.innerHTML = "<strong>"+ thecode + " (" + $("#db_currencies").find("option[value='"+data.currency_2+"']").html().trim() +")</strong>";
			td5.innerHTML = "<strong>"+ thecode + " (" + GetCurrencyAttribute("name", "id", data.currency_2) +")</strong>";
		}else{
			td5.appendChild(select_currency_2);
		}

		td6.appendChild(btnsave);

		var deleting = false;

		function deleteItem(){
			if(!triggerDelete || deleting){
				return;
			}

			var counterdelete = 0;
			$(btnremove).hide(App.TIME_FOR_HIDE);
			deleting = condelete=true;

			function  no_delete(){
				deleting = btnremove.disabled = btnsave.disabled = false;
				triggerDelete = condelete=false;
				$(pdeletetimer).hide(App.TIME_FOR_HIDE);
				$(anulardelete).hide(App.TIME_FOR_HIDE);
				$(btnremove).show(App.TIME_FOR_SHOW);
				App.DOM_Disabling(row);
			}

			(function deleteItem(){
				if(condelete){
					btnremove.disabled = btnsave.disabled = true;
					counterdelete++;

					if(counterdelete < App.TIME_FOR_DELETE_ITEM){
						pdeletetimer.innerHTML = "<br>" + App.terms.str_deleting_in + " " +(App.TIME_FOR_DELETE_ITEM - counterdelete)+ "...";
						$(pdeletetimer).show(App.TIME_FOR_SHOW);
						$(anulardelete).show(App.TIME_FOR_SHOW);
						setTimeout(deleteItem, 1000);
					}else{
						$(anulardelete).hide(App.TIME_FOR_HIDE);
						pdeletetimer.innerHTML = App.terms.str_deleting+"...";

						App.HTTP.delete({
							url:App.WEB_ROOT+"/exchange/"+data.id,
							success:function(d, e, f){
								$(row).remove();
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

		var triggerDelete = false;

		btnremove.onclick = function(){
			if(created){
				triggerDelete = true;
				deleteItem();
			}else{
				$(row).remove();
			}
		}

		var pdeletetimer = document.createElement("p");
		pdeletetimer.style.display = "none";
		pdeletetimer.style.fontWeight = "bold";

		var anulardelete = document.createElement("a");
		anulardelete.style.fontWeight = "bold";
		anulardelete.style.display = "none";
		anulardelete.innerHTML = App.terms.str_abort;
		anulardelete.href = "javascript:;";
		anulardelete.onclick = function(){
			condelete=false;
			$(pdeletetimer).hide(App.TIME_FOR_HIDE);
			$(anulardelete).hide(App.TIME_FOR_HIDE);
			$(btnremove).show(App.TIME_FOR_SHOW);
		}

		td6.appendChild(btnremove);
		td6.appendChild(pdeletetimer);
		td6.appendChild(anulardelete);

		row.appendChild(td1);
		row.appendChild(td2);
		row.appendChild(td3);
		row.appendChild(td4);
		row.appendChild(td5);
		row.appendChild(td6);

		row.setAttribute("data-exchanges", JSON.stringify(created?Array(String(data.currency_1), String(data.currency_2)):Array("all")));
		row.setAttribute("data-show", "2");
		row.setAttribute("data-reverse", "0");

		if(created){
			row.id = "exchange_"+data.id;
		}

		$("#list_exchanges").append(row);

		if(AUTOMATIC_EXCHANGE){
			//$(select_currency_1).val($("#db_currencies").find("option[data-code='"+AUTOMATIC_EXCHANGE.code_c1+"']").val());
			$(select_currency_1).val(GetCurrencyAttribute("id", "code", AUTOMATIC_EXCHANGE.code_c1));

			//$(select_currency_2).val($("#db_currencies").find("option[data-code='"+AUTOMATIC_EXCHANGE.code_c2+"']").val());
			$(select_currency_2).val(GetCurrencyAttribute("id", "code", AUTOMATIC_EXCHANGE.code_c2));

			inputvalue.value = AUTOMATIC_EXCHANGE.value;
			$(btnsave).trigger("click");
			AUTOMATIC_EXCHANGE = null;
		}

		$(select_currency_1).select2();
		$(select_currency_2).select2();
		document.getElementById("parent_list_exchanges").scrollTop = 10000000000;
	}

	$("#add_exchange").click(function(){
		add_exchange_to_dom();
	});

	App.HTTP.read({
		url : App.WEB_ROOT + "/exchanges",
		success : function(d, e, f){
			$.each(d.data.items, function(k, v){
				add_exchange_to_dom(v);
			});
			document.getElementById("parent_list_exchanges").scrollTop = 0;
		},error : function(x, y, z){
		},log_ui_msg : false
	});

	App.TimeInterval(function(){
		var arr1 = $("#exchanges_currencies_1").val();
		var arr2 = $("#exchanges_currencies_2").val();

		if(arr1 == null){
			arr1 = Array();
		}

		if(arr2 == null){
			arr2 = Array();
		}

		if($("#selectall_1").prop("checked") && arr1.length == 0){
			arr1 = Array();
			/*
			$("#db_currencies").find("option").each(function(){
				arr1.push($(this).val());
			});
			*/
			$.each(Section.Currencies_byCode, function(k, v){
				arr1.push(v.id);
			});
		}

		if($("#selectall_2").prop("checked") && arr2.length == 0){
			arr2 = Array();
			/*
			$("#db_currencies").find("option").each(function(){
				arr2.push($(this).val());
			});
			*/
			$.each(Section.Currencies_byCode, function(k, v){
				arr2.push(v.id);
			});
		}

		$("#list_exchanges").find("tr[data-show='1']").attr("data-show", "0");

		for(var i in arr1){
			for(var j in arr2){
				if(arr1[i] != arr2[j]){
					var aux = $("#list_exchanges").find("tr[data-exchanges='"+JSON.stringify(Array(arr1[i], arr2[j]))+"']");
					if(aux.length > 0){
						aux.attr("data-show", "1");
					}else{
						aux = $("#list_exchanges").find("tr[data-exchanges='"+JSON.stringify(Array(arr2[j], arr1[i]))+"']");

						if(aux.length > 0){
							aux.attr("data-show", "1");
							//aqui no se como hacer, lo pensaré cuando haga menos calor, el funcionamiento, la interfaz no es intuitivo, pero no es erroneo, que es mas importante al final
							//$("#reverse_exchange_"+arr1[i]+"_"+arr2[j]+", #reverse_exchange_"+arr2[j]+"_"+arr1[i]).trigger("click");
						}
					}
				}
			}
		}

		$("#list_exchanges").find("tr[data-show='1']").show(App.TIME_FOR_SHOW);
		$("#list_exchanges").find("tr[data-show='0']").hide(App.TIME_FOR_HIDE);
	}, 1000);

	function change_select2(idselect, flag){
		if(flag){
			$("#s2id_"+idselect).hide(App.TIME_FOR_HIDE);
		}else{
			$("#s2id_"+idselect).show(App.TIME_FOR_SHOW);
		}
	}

	$("#fixerio_selectall_1").change(function(){
		change_select2("fixerio_currencies_1", $(this).prop("checked"));
	});

	$("#fixerio_selectall_2").change(function(){
		change_select2("fixerio_currencies_2", $(this).prop("checked"));
	});

	$("#selectall_1").change(function(){
		change_select2("exchanges_currencies_1", $(this).prop("checked"));
	});

	$("#selectall_2").change(function(){
		change_select2("exchanges_currencies_2", $(this).prop("checked"));
	});
}