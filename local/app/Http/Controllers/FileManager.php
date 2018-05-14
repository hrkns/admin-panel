<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Directory;
use App\Models\File;
use App\Models\CompressedFile;

class FileManager extends Controller
{
    public function createDirs(Request $request)
    {
        $info = $request->input("data.directories");
        $items = [];

        foreach ($info as $key => $value) {
            $item = new Directory;
            $item->name = $value["name"];
            $item->description = $value["description"];
            $item->parent = is_numeric($value["parent"])?$value["parent"]:null;
            $item->id_user = $request->session()->get("iduser");
            $item->__create__();
            array_push($items, $item);
        }

        operation("CREATE_DIRECTORIES");
        return \Response::json(["items"=>$items], 201);
    }

    public function createFiles(Request $request)
    {
        $info = $request->input("data.files");
        $items = [];

        foreach ($info as $key => $value) {
            $item = new File;
            $item->name = $value["name"];
            $item->description = $value["description"];
            $item->id_directory = is_numeric($value["directory"])?$value["directory"]:null;
            $item->id_user = $request->session()->get("iduser");
            $filename = rand_string();
            $item->filename = $filename;
            file_put_contents(CLOUD_ROUTE.$filename, "");
            $item->__create__();
            array_push($items, $item);
        }

        operation("CREATE_FILES");
        return \Response::json(["items"=>$items], 201);
    }

    public function uploadFilesInfo(Request $request)
    {
        $info = $request->input("data.info");
        $items = [];

        foreach ($info as $key => $value) {
            $item = new File;
            $item->name = $value["name"];
            $item->description = $value["description"];
            $item->id_directory = is_numeric($value["parent"])?$value["parent"]:null;
            $item->id_user = $request->session()->get("iduser");
            $item->filename = rand_string();
            $item->__create__();
            array_push($items, $item);
        }

        operation("UPLOAD_FILES_INFO");
        return \Response::json(["items"=>$items], 201);
    }

    public function uploadFilesFile(Request $request, $id)
    {
        $item = File::where("id", "=", $id)->get()[0];
        $file = $request->file("file");
        $ext = $file->getClientOriginalExtension();
        $filename = $item->filename.".".$ext;
        $file->move(CLOUD_ROUTE, $filename);
        $item->filename = $filename;
        $item->type = $file->getClientMimeType();
        $item->size = $file->getClientSize();
        $item->save();
        operation("UPLOAD_FILES_FILES");
        return \Response::json([], 201);
    }

    public function directoryContent(Request $request, $id)
    {
        if ($id == "root") {
            $id = null;
        }

        if ($id != null) {
            $directories = Directory::where("parent", "=", $id)->where("__read__", "=", 1)->get();
        } else {
            $directories = Directory::where("id_user", "=", $request->session()->get("iduser"))->where("parent", "=", null)->where("__read__", "=", 1)->get();
        }

        $files = File::where("id_user", "=", $request->session()->get("iduser"))->where("id_directory", "=", $id)->where("__read__", "=", 1)->get();

        operation("GET_DIRECTORY_CONTENT");
        return \Response::json(["directories"=>$directories, "files"=>$files, "message"=>HTTP_message("http_message_loaded_content_directory")], 200);
    }

    private function remove_directory($request, $id, $moveto = null)
    {
        $move_to = $moveto != null?$moveto:$request->input("data.move_content_to");

        if ($move_to == "root") {
            $move_to = null;
        } elseif (!is_numeric($move_to) || count(Directory::where("id", "=", $move_to)->where("id_user", "=", $request->session()->get("iduser"))) == 0) {
            $move_to = false;
        }

        $item = Directory::where("id", "=", $id)->get()[0];
        $dirs_to_move = Directory::where("parent", "=", $id)->get();
        $files_to_move = File::where("id_directory", "=", $id)->get();

        if (!($move_to === false)) {
            foreach ($dirs_to_move as $key => $value) {
                $value->parent = $move_to;
                $value->__update__();
            }

            foreach ($files_to_move as $key => $value) {
                $value->id_directory = $move_to;
                $value->__update__();
            }
        //hago esto porque por alguna razon que desconozco no funciona el cascade implicito debido a la relacion que debe ejecutarse en la ultima sentencia de este metodo
        } else {
            foreach ($dirs_to_move as $key => $value) {
                $this->remove_directory($request, $value->id);
            }

            foreach ($files_to_move as $key => $value) {
                $value->__delete__();
            }
        }

        $item->__delete__();
    }

    public function removeDir(Request $request, $id)
    {
        $this->remove_directory($request, $id);
        operation("DELETE_DIRECTORY");
        return \Response::json([], 200);
    }

    private function remove_file($request, $id)
    {
        $item = File::where("id", "=", $id)->get()[0];
        $item->__delete__();
    }

    public function removeFile(Request $request, $id)
    {
        $this->remove_file($request, $id);
        operation("REMOVE_FILE");
        return \Response::json([], 200);
    }

    public function updateDir(Request $request, $id)
    {
        $idp = $request->input("data.parent");
        $item = Directory::where("id", "=", $id)->get()[0];
        $item->name = $request->input("data.name");
        $item->description = $request->input("data.description");
        $item->parent = is_numeric($idp) && count(Directory::where("id", "=", $idp)->where("id_user", "=", $request->session()->get("iduser"))) > 0?$idp:null;
        $item->__update__();
        operation("UPDATE_DIRECTORY");
        return \Response::json([], 200);
    }

    public function updateFile(Request $request, $id)
    {
        $idp = $request->input("data.parent");
        $item = File::where("id", "=", $id)->get()[0];
        $item->name = $request->input("data.name");
        $item->description = $request->input("data.description");
        $item->id_directory = is_numeric($idp) && count(Directory::where("id", "=", $idp)->where("id_user", "=", $request->session()->get("iduser"))) > 0?$idp:null;
        $item->__update__();
        operation("UPDATE_FILE_INFO");
        return \Response::json([], 200);
    }

    public function removeFiles(Request $request)
    {
        $items = $request->input("data");

        if (gettype($items) != "array") {
            $items = array();
        }

        foreach ($items as $key => $value) {
            $this->remove_file($request, $value);
        }

        operation("DELETE_FILE");
        return \Response::json([], 200);
    }

    public function removeDirectories(Request $request)
    {
        $items = $request->input("data");

        if (gettype($items) != "array") {
            $items = array();
        }

        foreach ($items as $key => $value) {
            $this->remove_directory($request, $value["id"], isset($value["move_to"])?$value["move_to"]:null);
        }

        operation("REMOVE_DIRECTORIES");
        return \Response::json([], 200);
    }

    public function search(Request $request)
    {
        $keywords = $request->input("data.keywords");
        $from_level = $request->input("data.from_level");
        $from_level = is_numeric($from_level) && count(Directory::where("id", "=", $from_level)->where("id_user", "=", $request->session()->get("iduser"))) > 0?$from_level:null;

        $directories = [];
        $files = [];
        $pending_parents = [$from_level];

        if ($from_level != null) {
            $it = Directory::where("id", "=", $from_level)->get()[0];
            $routes = [
                $from_level => [
                    "name" => $it->name."/",
                    "parent" => $it->parent
                ]
            ];
        } else {
            $routes = [
                "root" => [
                    "name" => "/",
                    "parent" => "root"
                ]
            ];
        }

        while (count($pending_parents) > 0) {
            $from_level = $pending_parents[0];
            $pending_parents = array_slice($pending_parents, 1);

            if ($from_level != null) {
                $parent = strval($from_level);
            } else {
                $parent = "root";
            }

            $dirs = Directory::where("name", "LIKE", "%".$keywords."%")->where("parent", "=", $from_level)->where("__read__", "=", 1)->get();
            $fils = File::where("name", "LIKE", "%".$keywords."%")->where("id_directory", "=", $from_level)->where("__read__", "=", 1)->get();
            $tmp = array();

            foreach ($dirs as $key => $v) {
                array_push($pending_parents, $v->id);
                /*
                $routes[strval($v->id)] = $routes[$parent].$v->name."/";
                $v["location"] = $routes[$parent];
                */
                $routes[strval($v->id)] = [
                    "name" => $routes[$parent]["name"].$v->name."/",
                    "parent" => $v->parent
                ];
                $v["location"] = $routes[$parent]["name"];
                array_push($tmp, $v);
            }

            $dirs = $tmp;
            $tmp = array();

            foreach ($fils as $key => $v) {
                /*
                $v["location"] = $routes[$parent];
                */
                $v["location"] = $routes[$parent]["name"];
                array_push($tmp, $v);
            }

            $fils = $tmp;
            $directories = array_merge($directories, $dirs);
            $files = array_merge($files, $fils);
        }

        operation("SEARCH_FILES_AND_DIRECTORIES");
        return \Response::json(["files"=>$files, "directories"=>$directories], 200);
    }

    public function parentsLine(Request $request, $id)
    {
        $item = Directory::where("id", "=", $id)->get();
        $parents_line = [];

        while (count($item) == 1) {
            $item = $item[0];
            array_push($parents_line, [
                "id" => $item->id,
                "name" => $item->name
            ]);
            $item = Directory::where("id", "=", $item->parent)->get();
        }

        return \Response::json(["items"=>array_reverse($parents_line)], 200);
    }

    public function downloadFile(Request $request, $id)
    {
        $file = File::where("id", "=", $id)->get()[0];
        $filepath = storage_path()."/admin-panel/cloud/".$file->filename;

        if (file_exists($filepath)) {
            $file->create_Download([
                "id_user" => $request->session()->get("iduser")
            ]);
            operation("DOWNLOAD_FILE");
            $response = \Response::download($filepath, $file->name.substr($file->filename, strrpos($file->filename, ".")));
            ob_end_clean();
            return $response;
        } else {
            return \Response::json([], 404);
        }
    }

    public function compression(Request $request)
    {
        $directories = $request->input("data.directories");
        $directories = $directories == null?array():$directories;
        $files = $request->input("data.files");
        $files = $files == null?array():$files;
        $name = $request->input("data.name");
        $description = $request->input("data.description");
        $save_compressed_in = $request->input("data.save_compressed_in");

        if ($save_compressed_in == "root") {
            $save_compressed_in = null;
        } elseif (!is_numeric($save_compressed_in) || count(Directory::where("id", "=", $save_compressed_in)->where("id_user", "=", $request->session()->get("iduser"))) == 0) {
            $save_compressed_in = false;
        }

        $compressed = new File;
        $compressed->name = $name;
        $compressed->description = $description;
        $compressed->filename = rand_string();
        $compressed->id_user = $request->session()->get("iduser");

        if (gettype($save_compressed_in) != "boolean") {
            $compressed->id_directory = $save_compressed_in;
        } else {
            $compressed->__read__ = 0;
        }

        //$compressed->__create__();
        $base_route_folder_to_compress = storage_path()."/admin-panel/compressed_tmp/".$compressed->filename."/";
        mkdir($base_route_folder_to_compress);

        foreach ($files as $key => $value) {
            $item = File::where("id", "=", $value)->get();
            if (count($item) > 0) {
                $count = 0;

                do {
                    $index = strrpos($item[0]->filename, ".");
                    if (!($index === false)) {
                        $ext = substr($item[0]->filename, $index);
                    } else {
                        $ext = "";
                    }
                    $filename = $base_route_folder_to_compress.$item[0]->name.($count > 0?" (".$count.")":"").$ext;
                    $count++;
                } while (is_file($filename));

                copy(CLOUD_ROUTE.$item[0]->filename, $filename);
            }
        }

        $parent_folder = array();

        foreach ($directories as $key => $value) {
            $parent_folder[strval($value)] = $base_route_folder_to_compress;
        }

        $n = count($directories);

        while ($n > 0) {
            $iddir = $directories[0];
            $directories = array_slice($directories, 1);
            $n--;
            $dir = Directory::where("id", "=", $iddir)->get();

            if (count($dir) > 0) {
                $dir = $dir[0];
                $count = 0;

                do {
                    $foldername = $parent_folder[strval($iddir)].$dir->name.($count > 0?" (".$count.")":"")."/";
                    $count++;
                } while (is_dir($foldername));

                mkdir($foldername);
                $sons = Directory::where("parent", "=", $iddir)->get();

                foreach ($sons as $key => $value) {
                    $parent_folder[strval($value->id)] = $foldername;
                    array_push($directories, $value->id);
                    $n++;
                }

                $files = File::where("id_directory", "=", $dir->id)->get();

                foreach ($files as $key => $item) {
                    $count = 0;

                    do {
                        $index = strrpos($item->filename, ".");
                        if (!($index === false)) {
                            $ext = substr($item->filename, $index);
                        } else {
                            $ext = "";
                        }
                        $filename = $foldername.$item->name.($count > 0?" (".$count.")":"").$ext;
                        $count++;
                    } while (is_file($filename));

                    copy(CLOUD_ROUTE.$item->filename, $filename);
                }
            }
        }

        $compressed->filename .= ".zip";
        compressFolder($base_route_folder_to_compress, $compressed->filename);
        $compressed->type = "application/zip";
        $compressed->size = filesize(CLOUD_ROUTE.$compressed->filename);
        $compressed->__create__();
        deleteDir($base_route_folder_to_compress);
        operation("DOWNLOAD_COMPRESSION");
        return \Response::json(["item"=>$compressed], 200);
    }

    public function downloadDirectory(Request $request, $id)
    {
        $dir = Directory::where("id", "=", $id)->get()[0];
        $namedirectory = $dir->name;
        $hash = rand_string();
        $base_route_folder_to_compress = storage_path()."/admin-panel/compressed_tmp/".$hash."/";
        mkdir($base_route_folder_to_compress);
        $directories = [$id];
        $parent_folder = array();

        foreach ($directories as $key => $value) {
            $parent_folder[strval($value)] = $base_route_folder_to_compress;
        }

        $n = count($directories);

        while ($n > 0) {
            $iddir = $directories[0];
            $directories = array_slice($directories, 1);
            $n--;
            $dir = Directory::where("id", "=", $iddir)->get();

            if (count($dir) > 0) {
                $dir = $dir[0];
                $count = 0;

                do {
                    $foldername = $parent_folder[strval($iddir)].$dir->name.($count > 0?" (".$count.")":"")."/";
                    $count++;
                } while (is_dir($foldername));

                mkdir($foldername);
                $sons = Directory::where("parent", "=", $iddir)->get();

                foreach ($sons as $key => $value) {
                    $parent_folder[strval($value->id)] = $foldername;
                    $n++;
                    array_push($directories, $value->id);
                }

                $files = File::where("id_directory", "=", $dir->id)->get();

                foreach ($files as $key => $item) {
                    $count = 0;

                    do {
                        $index = strrpos($item->filename, ".");
                        if (!($index === false)) {
                            $ext = substr($item->filename, $index);
                        } else {
                            $ext = "";
                        }
                        $filename = $foldername.$item->name.($count > 0?" (".$count.")":"").$ext;
                        $count++;
                    } while (is_file($filename));

                    copy(CLOUD_ROUTE.$item->filename, $filename);
                }
            }
        }

        $dir->create_Download([
            "id_user" => $request->session()->get("iduser"),
            "filename" => $hash.".zip"
        ]);

        compressFolder($base_route_folder_to_compress/*.$namedirectory."/"*/, $hash.".zip");
        deleteDir($base_route_folder_to_compress);

        if (is_file(CLOUD_ROUTE.$hash.".zip")) {
            $response = \Response::download(CLOUD_ROUTE.$hash.".zip", $namedirectory.".zip");
            ob_end_clean();
            operation("DOWNLOAD_COMPRESSED_DIRECTORY");
        } else {
            $response = \Response::json(HTTP_message("http_message_downloading_folder_with_no_content"), 400);
        }

        return $response;
    }

    public function setParent(Request $request)
    {
        $directories = $request->input("data.directories");
        $directories = $directories == null?array():$directories;
        $files = $request->input("data.files");
        $files = $files == null?array():$files;
        $new_parent = $request->input("data.new_parent");

        if ($new_parent == "root") {
            $new_parent = null;
        } elseif (!is_numeric($new_parent) || count(Directory::where("id", "=", $new_parent)->where("id_user", "=", $request->session()->get("iduser"))) == 0) {
            $new_parent = false;
        }

        foreach ($directories as $key => $value) {
            $item = Directory::where("id", "=", $value)->get();
            if (count($item)>0 && $item[0]->id != $new_parent) {
                $item = $item[0];
                $item->parent = $new_parent;
                $item->__update__();
            }
        }

        foreach ($files as $key => $value) {
            $item = File::where("id", "=", $value)->get();
            if (count($item)>0) {
                $item = $item[0];
                $item->id_directory = $new_parent;
                $item->__update__();
            }
        }

        operation("SET_PARENT_DIRECTORY_OF_FILES_AND_DIRECTORIES");
        return \Response::json([], 200);
    }

    private function copy_directory($directory, $destiny)
    {
        $files = File::where("id_directory", "=", $directory->id)->get();
        $directories = Directory::where("parent", "=", $directory->id)->get();

        $new_directory                    = new Directory;
        $new_directory->name            = $directory->name;
        $new_directory->description    = $directory->description;
        $new_directory->parent            = $destiny;
        $new_directory->id_user        = $directory->id_user;
        $new_directory->__create__        = $directory->__create__;
        $new_directory->__read__        = $directory->__read__;
        $new_directory->__update__        = $directory->__update__;
        $new_directory->__delete__        = $directory->__delete__;
        $new_directory->__create__();

        foreach ($files as $key => $file) {
            $this->copy_file($file, $new_directory->id);
        }

        foreach ($directories as $key => $directory) {
            $this->copy_directory($directory, $new_directory->id);
        }
    }

    private function copy_file($file, $destiny)
    {
        $new_file = new File;
        $new_file->name = $file->name;
        $new_file->description = $file->description;
        $new_file->type = $file->type;
        $new_file->id_directory = $destiny;
        $new_file->id_user = $file->id_user;
        $new_file->size = $file->size;
        $new_file->__create__ = $file->__create__;
        $new_file->__read__   = $file->__read__;
        $new_file->__update__ = $file->__update__;
        $new_file->__delete__ = $file->__delete__;
        $new_file->filename = rand_string().substr($file->filename, strrpos($file->filename, "."));
        copy(CLOUD_ROUTE.$file->filename, CLOUD_ROUTE.$new_file->filename);
        $new_file->__create__();
    }

    public function copyItemsTo(Request $request)
    {
        $directories = $request->input("data.directories");
        $directories = $directories == null?array():$directories;
        $files = $request->input("data.files");
        $files = $files == null?array():$files;
        $destiny = $request->input("data.destiny");

        if ($destiny == "root") {
            $destiny = null;
        } elseif (!is_numeric($destiny) || count(Directory::where("id", "=", $destiny)->where("id_user", "=", $request->session()->get("iduser"))) == 0) {
            $destiny = false;
        }

        foreach ($directories as $key => $value) {
            $item = Directory::where("id", "=", $value)->get();
            if (count($item)>0/* && $item[0]->id != $destiny*/) {
                $item = $item[0];
                $this->copy_directory($item, $destiny);
            }
        }

        foreach ($files as $key => $value) {
            $item = File::where("id", "=", $value)->get();
            if (count($item)>0) {
                $item = $item[0];
                $this->copy_file($item, $destiny);
            }
        }

        operation("COPY_FILES_AND_DIRECTORIES");
        return \Response::json([], 200);
    }
}
