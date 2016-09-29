<?php
	$time_start = microtime(true);
	/*
	require_once "sql.php";
	$db = new SQL();
	define("PREFIX", $_POST["db"]["prefix"]);

	//conexion con base de datos
	do{
		$resultado_conexion = $db->start(
			array(
				"host" => $_POST["db"]["host"],
				"port" => $_POST["db"]["port"],
				"db" => $_POST["db"]["name"],
				"user" => $_POST["db"]["user"],
				"password" => $_POST["db"]["password"]
			)
		);

		if($resultado_conexion == SQL::UNKNOWN_HOST){
			exit("Host desconocido");
		}

		if($resultado_conexion == SQL::UNKNOWN_USER){
			exit("Usuario desconocido");
		}

		echo "<p>Conectado a base de datos...</p>";

		//verificar si ya existe la base de datos, y si no existe, crearla
		if($resultado_conexion == SQL::UNKNOWN_DATABASE){
			echo "<p>Creando base de datos...</p>";
			//algun parametro adicional para no solo crearla sino conectarte a ella tambien
			$resultado_creacion_db = $db->createDb(
				array(
					"db" => $_POST["db"]["name"]
				)
			);
		}
	}while($resultado_conexion != SQL::SUCCESS);

	echo "<p>Conexion a base de datos realizada!</p>";
	*/

	//crear tablas
	require_once "tables.php";

	echo "<p>Creando tablas...</p>";

	define("PREFIX", "ap");

	function modelNameFile($str){
		$i = 0;
		$n = strlen($str);
		$name = "";
		$cond = true;

		while($i < $n){
			if($cond){
				$name .= strtoupper($str[$i]);
				$cond = false;
			}else if($str[$i] != "_"){
				$name .= $str[$i];
			}else{
				$cond = true;
			}

			$i++;
		}

		return $name;
	}

	/*
		dilemas a solucionar en la siguiente funcion


		creacion: puedo crear uno o mas elementos

		lectura: los parametros de lectura (traerme a todos, solo a algunos, a uno solo por id, etc...)

		actualizacion: a quien estoy solucionando, a uno solo, a varios, actualizar a varios pasando por cada uno los datos a actualizar... etc

		eliminacion: combinaciones de lo que hablé anteriormente
	*/

	function writeCascade($nameTable, $cascade){
		$str = "";
		foreach($cascade as $key => $son){
			$str .= "\n\t//".$son."\n\t";
			//create
			$str .= "public function create_".	modelNameFile(/*$nameTable."_".*/$son)."(\$args){\n".
						"\t\tif(gettype(\$args) == \"object\"){\n".
							"\t\t\t\$args->__create__();\n".
							"\t\t\treturn \$args;\n".
						"\t\t}else{\n".
							"\t\t\tif(isAssoc(\$args)){\n".
								"\t\t\t\t\$obj = new ".modelNameFile($nameTable."_".$son).";\n".

								"\t\t\t\tforeach (\$args as \$field => \$value){\n".
									"\t\t\t\t\t\$obj->\$field = \$value;\n".
								"\t\t\t\t}\n".

								"\t\t\t\t\$obj->id_".($son != "status"?$nameTable:"item")." = \$this->id;\n".
								"\t\t\t\treturn \$obj->__create__();\n".
							"\t\t\t}else{\n".
								"\t\t\t\t\$ret = array();\n".
								"\t\t\t\tforeach (\$args as \$key => \$item) {\n".
									"\t\t\t\t\t\$obj = new ".modelNameFile($nameTable."_".$son).";\n".

									"\t\t\t\t\tforeach (\$item as \$field => \$value){\n".
										"\t\t\t\t\t\t\$obj->\$field = \$value;\n".
									"\t\t\t\t\t}\n".

									"\t\t\t\t\t\$obj->id_".($son != "status"?$nameTable:"item")." = \$this->id;\n".
									"\t\t\t\t\t\$obj->__create__();\n".
									"\t\t\t\t\tarray_push(\$ret, \$obj);\n".
								"\t\t\t\t}\n".
								"\t\t\t\treturn \$ret;\n".
							"\t\t\t}\n".
						"\t\t}\n".
					"\t}\n\t";
			//read
			$str .= "public function read_".	modelNameFile(/*$nameTable."_".*/$son)."(\$args = array()){\n".
						/*
						"\t\treturn \$this->belongsToMany('App\\Models\\".modelNameFile($nameTable."_".$son)."', '".(PREFIX."_".$nameTable."_".$son)."', 'id_".($son!= "status"?$nameTable:"item")."', 'id_".$son."');\n".
						*/
						"\t\treturn \$this->hasMany('App\\Models\\".modelNameFile($nameTable."_".$son)."', 'id_".($son!= "status"?$nameTable:"item")."');\n".
						/*
						"\t\treturn \$this->hasOne('App\\Models\\".modelNameFile($nameTable."_".$son)."', 'id_item');\n".
						*/
					"\t}\n\t";

			//update
			$str .= "public function update_".	modelNameFile(/*$nameTable."_".*/$son)."(\$args = array()){\n\t}\n\t";

			//delete
			$str .= "public function delete_".	modelNameFile(/*$nameTable."_".*/$son)."(\$args = array()){\n".
						"\t\t\$list = \$this->read_".modelNameFile($son).";\n".
						"\t\tforeach(\$list as \$item){\n".
						"\t\t\t\$item->__delete__();\n".
						"\t\t}\n".
					"\t}\n";
		}
		return $str;
	}

	function writeUseModels($nameTable, $classes){
		$str = "";

		foreach ($classes as $key => $class){
			$str .= "use App\Models\\".modelNameFile($nameTable."_".$class).";\n";
		}

		return $str;
	}

	function writePreviousDelete($nameTable, $classes){
		$str = "";

		foreach ($classes as $key => $class){
			$str .= "\t\t\$list = \$this->read_".modelNameFile($class).";\n".
					"\t\tforeach(\$list as \$item){\n".
					"\t\t\t\$item->__delete__();\n".
					"\t\t}\n"
			;
		}

		return $str;
	}

	function writeClass($f, $nameTable, $cascade=array(), $complement = false){
		fwrite($f, 	"<?php\n".
					"namespace App\Models;\n".
					"use Illuminate\Database\Eloquent\Model;\n".
					($complement?(
					"use App\\Models\\Created".modelNameFile($nameTable).";\n".
					"use App\\Models\\Updated".modelNameFile($nameTable).";\n".
					"use App\\Models\\Deleted".modelNameFile($nameTable).";\n".
					"use Request;\n".
					writeUseModels($nameTable, $cascade)
					):"").
					"class ".modelNameFile($nameTable)." extends Model\n".
					"{\n".
						"\tprotected \$table = '".($complement?PREFIX."_":"").$nameTable."';\n".
						"\tpublic \$timestamps = false;\n".
						(
						$complement?(
					    	"\tpublic function __create__(){\n".
					    		"\t\t\$this->save();\n".
					    		"\t\t\$info = \$this->toJson();\n".
					            "\t\t//insert created_{tablename_without_prefix} row[?]\n".
					            "\t\t/******/\n".
					            "\t\t/*your code*/\n".
								"\t\t\$complement = new Created".modelNameFile($nameTable).";\n".
								"\t\t\$complement->id_item = \$this->id;\n".
								"\t\t\$complement->info = \$info;\n".
								"\t\t\$complement->hash_operation = HASH_OPERATION;\n".
								"\t\t\$complement->created_by = Request::session()->has(\"iduser\")?Request::session()->get(\"iduser\"):null;\n".
								"\t\t\$complement->save();\n".
								"\t\treturn \$this;\n".
					            "\t\t/******/\n".
					    	"\t}\n".
					    	"\tpublic function __update__(){\n".
						    	"\t\t\$info = self::where(\"id\", \"=\", \$this->id)->get();\n".
						    	"\t\t\$info = count(\$info)>0?\$info[0]->toJson():\"\";\n".
					    		"\t\t\$this->save();\n".
					            "\t\t//insert updated_{tablename_without_prefix} row[?]\n".
					            "\t\t/******/\n".
					            "\t\t/*your code*/\n".
								"\t\t\$complement = new Updated".modelNameFile($nameTable).";\n".
								"\t\t\$complement->id_item = \$this->id;\n".
								"\t\t\$complement->info = \$info;\n".
								"\t\t\$complement->hash_operation = HASH_OPERATION;\n".
								"\t\t\$complement->updated_by = Request::session()->has(\"iduser\")?Request::session()->get(\"iduser\"):null;\n".
								"\t\t\$complement->save();\n".
					            "\t\t/******/\n".
					    	"\t}\n".
					    	"\tpublic function __delete__(){\n".
						    	"\t\t\$info = self::where(\"id\", \"=\", \$this->id)->get();\n".
						    	"\t\t\$info = count(\$info)>0?\$info[0]->toJson():\"\";\n".
						    	writePreviousDelete($nameTable, $cascade).
					    		"\t\t\$this->delete();\n".
					            "\t\t//insert deleted_{tablename_without_prefix} row[?]\n".
					            "\t\t/******/\n".
					            "\t\t/*your code*/\n".
								"\t\t\$complement = new Deleted".modelNameFile($nameTable).";\n".
								"\t\t\$complement->id_item = \$this->id;\n".
								"\t\t\$complement->info = \$info;\n".
								"\t\t\$complement->hash_operation = HASH_OPERATION;\n".
								"\t\t\$complement->deleted_by = Request::session()->has(\"iduser\")?Request::session()->get(\"iduser\"):null;\n".
								"\t\t\$complement->save();\n".
					            "\t\t/******/\n".
					    	"\t}\n"
					    ):""
				    	).
						writeCascade($nameTable, $cascade).
					"}");
	}

	foreach ($tables as $key => $table) {
		/*
			$db->createTable(
				array(
					"name" => $_POST["db"]["prefix"]."_".$table["name"],
					"fields" => $table["fields"]
				)
			);

			$db->alter("table", $_POST["db"]["prefix"]."_".$table["name"], "ADD PRIMARY KEY (`id`)");
			$db->alter("table", $_POST["db"]["prefix"]."_".$table["name"], "MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT");

			if(strlen(trim($table["alter"])) > 0)
				$db->alter("table", $_POST["db"]["prefix"]."_".$table["name"], $table["alter"]);

			if($table["created"]["condition"]){
				$db->createTable(
					array(
						"name" => "created_".$table["name"],
						"fields" => "
							`id` int(10) unsigned NOT NULL,
							`id_item` int(10) unsigned NOT NULL,
							`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							`created_by` int(10) unsigned DEFAULT NULL,
							`info` longtext NOT NULL
						"
					)
				);
				$db->alter("table", "created_".$table["name"], "ADD PRIMARY KEY (`id`)");
				$db->alter("table", "created_".$table["name"], "MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT");
			}

			if($table["updated"]["condition"]){
				$db->createTable(
					array(
						"name" => "updated_".$table["name"],
						"fields" => "
							`id` int(10) unsigned NOT NULL,
							`id_item` int(10) unsigned NOT NULL,
							`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							`updated_by` int(10) unsigned DEFAULT NULL,
							`info` longtext NOT NULL
						"
					)
				);
				$db->alter("table", "updated_".$table["name"], "ADD PRIMARY KEY (`id`)");
				$db->alter("table", "updated_".$table["name"], "MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT");
			}

			if($table["deleted"]["condition"]){
				$db->createTable(
					array(
						"name" => "deleted_".$table["name"],
						"fields" => "
							`id` int(10) unsigned NOT NULL,
							`id_item` int(10) unsigned NOT NULL,
							`deleted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
							`deleted_by` int(10) unsigned DEFAULT NULL,
							`info` longtext NOT NULL
						"
					)
				);
				$db->alter("table", "deleted_".$table["name"], "ADD PRIMARY KEY (`id`)");
				$db->alter("table", "deleted_".$table["name"], "MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT");
			}
		*/

		$f = fopen(__DIR__."/../local/app/Models/".modelNameFile($table["name"]).".php", "w");
		writeClass($f, $table["name"], $table["cascade"], true);
		fclose($f);

		$f = fopen(__DIR__."/../local/app/Models/".modelNameFile("created_".$table["name"]).".php", "w");
		writeClass($f, "created_".$table["name"]);
		fclose($f);

		$f = fopen(__DIR__."/../local/app/Models/".modelNameFile("updated_".$table["name"]).".php", "w");
		writeClass($f, "updated_".$table["name"]);
		fclose($f);

		$f = fopen(__DIR__."/../local/app/Models/".modelNameFile("deleted_".$table["name"]).".php", "w");
		writeClass($f, "deleted_".$table["name"]);
		fclose($f);
	}

	/*
	echo "<p>Tablas creadas!</p>";

	echo "<p>Creando relaciones...</p>";

	//establecer claves foraneas
	require_once "relations.php";
	foreach ($relations as $key => $relation) {
		//echo "<p>".trim($relation["restriction"])."</p>";
		$db->alter("table", $_POST["db"]["prefix"]."_".$relation["table"], trim($relation["restriction"]));
	}

	echo "<p>Relaciones creadas!</p>";
	*/

	//llenar tablas
	/*
	echo "<p>Creando data base...</p>";
	require_once "feeding.php";

	foreach ($inserts as $key => $value) {
		//$db->exec($value);
	}

	move_uploaded_file($_FILES["logo"]["tmp_name"], "../assets/images/logos/default.jpeg");
	move_uploaded_file($_FILES["icon"]["tmp_name"], "../assets/images/tab_icons/default.png");

	$f = fopen("../local/resources/views/app/sections/include/preferences.php", "w");
	fwrite($f, 
	"<?php\n".
		"\t\$globalPreferences = array (\n".
		"\t\t'logo' => '".WEB_ROOT."/assets/images/logos/default.jpeg',\n".
		"\t\t'logo_updated_by' => 14,\n".
		"\t\t'let_register_user' => '0',\n".
		"\t\t'let_register_user_updated_by' => 1,\n".
		"\t\t'recover_account' => '0',\n".
		"\t\t'recover_account_updated_by' => 1,\n".
		"\t\t'tab_title' => 'Admin Panel',\n".
		"\t\t'tab_title_updated_by' => 14,\n".
		"\t\t'tab_icon' => '".WEB_ROOT."/assets/images/tab_icons/default.png',\n".
		"\t\t'tab_icon_updated_by' => 1,\n".
		"\t\t'priority_status_display' => '0',\n".
		"\t\t'priority_status_display_updated_by' => 1,\n".
		"\t\t'status_display_amount_criterion' => '1',\n".
		"\t\t'status_display_amount_criterion_updated_by' => 1,\n".
		"\t\t'terms_of_use_and_privacy_policy' => '',\n".
		"\t\t'terms_of_use_and_privacy_policy_updated_by' => '',\n".
		"\t);\n".
	"?>");
	fclose($f);

	//crear usuario
	
	require_once "__phphash/lib/password.php";

	$db->exec("	INSERT INTO ".PREFIX."_user (nick, email, hash_pass, default_language_session, fullname) values (
		'".$_POST["user"]["nick"]."',
		'".$_POST["user"]["email"]."',
		'".password_hash($_POST["user"]["password"], PASSWORD_BCRYPT)."',
		'".$_POST["lng"]."',
		'".$_POST["user"]["fullname"]."'
	)");

	$id_user = $db->idOfLastInsert();
    include __DIR__."/../local/resources/views/app/sections/include/preferences.php";

	$db->exec("	INSERT INTO ".PREFIX."_user_preferences (id_user, logo, tab_title) values (
		'".$db->idOfLastInsert()."',
		'".substr(strrchr($globalPreferences["logo"], "/"), 1)."',
		'".$globalPreferences["tab_title"]."'
	)");

	$db->exec("	INSERT INTO ".PREFIX."_organization (name) values (
		'".$_POST["user"]["organization"]."'
	)");
	$idorg = $db->idOfLastInsert();

	echo "<p>Info raiz creada!</p>";

	$idrol = 42;
	//traerse secciones
	$sections = $db->select(PREFIX."_panel_admin_section");
	//traerse acciones
	$actions = $db->select(PREFIX."_panel_admin_action");

	foreach ($sections as $key => $section) {
		$db->exec("INSERT INTO ".PREFIX."_panel_admin_role_section (id_role, id_section) VALUES
			(
				'".$idrol."',
				'".$section["id"]."'
			)
		");
		$idlast = $db->idOfLastInsert();

		foreach ($actions as $key => $action) {
			$db->exec("INSERT INTO ".PREFIX."_panel_admin_role_section_action (id_role_section, id_action) VALUES
				(
					'".$idlast."',
					'".$action["id"]."'
				)
			");
		}
	}

	$db->exec("INSERT INTO ".PREFIX."_user_role_in_organization (id_user, id_organization, id_role) VALUES
		(
			'".$id_user."',
			'".$idorg."',
			'".$idrol."'
		)
	");
	*/


	//configurar configuraciones en laravel xD
	//require_once "config_laravel.php";

	echo "<p>Configuraciones terminadas!</p>";

	$time_end = microtime(true);

	//dividing with 60 will give the execution time in minutes other wise seconds
	$execution_time = ($time_end - $time_start)/60;

	//execution time of the script
	echo '<b>Tiempo usado:</b> '.$execution_time.' Minutos';

	echo "<br><br><a href = '../logout'>Iniciar Sistema</a>"
?>