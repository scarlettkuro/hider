<?php
    require "Hider.php";

    $picture_src = false;
    $read = false;
    $write = false;
    $save = false;

    if (isset($_GET['read'])) {
        $read = true;
        $name = $_GET['read'];
    }
    if (isset($_GET['write'])) {
        $write = true;
        $name = $_GET['write'];
    }
    if (isset($_GET['save'])) {
        $save = true;
        $name = $_GET['save'];
    }
    if ($read || $write || $save) {
        $picture_src = '/pics/' . $name . ".png";
    }


    $readed = "";
    $writeFormUrl = "";
    $error = false;

    if (!empty($_FILES) && isset($_FILES['pic'])) {
      $name = time();
      $uploadfile = __DIR__ . '/pics/' . $name . '.png';
      $picinfo = explode('/', $_FILES['pic']['type']);
        //is image?
        if ($picinfo[0] == 'image') {
          //load file
          if (move_uploaded_file($_FILES['pic']['tmp_name'], $uploadfile)) {
                //if not png -> convert to png
                if ($picinfo[1] != 'png') {
                    $src = imagecreatefromstring(file_get_contents($uploadfile));
                    imagepng($src, $uploadfile);
                }
                //check if image has contents
                  $image = imagecreatefrompng($uploadfile);
                  $hider = new Hider($image);
                $query = false;
                if ($hider->readString() != "") {
                    $query = http_build_query(['read' => $name]);
                } else {
                    $query = http_build_query(['write' => $name]);
                } 
                header("Location:/?". $query);  
                exit;
            }
        }
        //load denied
        $error = 'Not image';
        header("Location:/");
        exit;
    }

    if ($read || $write) {
      $image = imagecreatefrompng(__DIR__ . $picture_src);
      $hider = new Hider($image);
      $writeUrl = "/?". http_build_query(['write' => $name]);
      $saveUrl = "/?". http_build_query(['save' => $name]);
      $readed = $hider->readString();
    }

    if ($save) {
      $image = imagecreatefrompng(__DIR__ . $picture_src);
      $hider = new Hider($image);

      if (isset($_POST['text'])) {
        $hider->writeString($_POST['text']);
        imagepng($hider->getImage(), __DIR__ . $picture_src);
        header("Location:/?". http_build_query(['read' => $name]));   
        exit;
      }

      $readed = $hider->readString();
    }

    require "template.php";

?>

