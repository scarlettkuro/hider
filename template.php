<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container">

      <main role="main">

        <div class="jumbotron">
          <form action="/upload" method="POST" enctype="multipart/form-data">
            <h4>Load image</h4>
            <p class="lead">You can write hidden text in image without changing it. Just upload it, type and save</p>
            <div class="form-group">
              <input type="file" name="pic" accept="image/jpeg, image/png" class="form-control-file">
            </div>
            <button type="submit" class="btn btn-primary">Load</button>
          </form>
        </div>

    <?php if ($error) : ?>
        <div class="alert alert-danger" role="alert"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($write) : ?>
        <div class="jumbotron">
          <p class="lead">
          <form action = "<?= $saveUrl ?>" method="POST">
            <div class="form-group">
              <textarea class="form-control" name="text" rows="3" placeholder="Write your text here"><?= $readed ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
          </form>
</p>
          <p class="text-center">
            <img src="<?= $picture_src ?>" class="img-fluid"></img>
          </p>
        </div>
    <?php endif; ?>

    <?php if ($read) : ?>
        <div class="jumbotron">
          <p class="lead">Hidden text :</p>
          <p class="lead"><?= $readed ?></p>
          <p class="lead">
            <a href="<?= $writeUrl ?>" class="btn btn-primary">Edit</a>
            <a href="<?= $picture_src ?>" download="<?=$picture_src?>" class="btn btn-primary">Download</a>
</p>
          <p class="text-center">
            <img src="<?= $picture_src ?>" class="img-fluid"></img>
          </p>
        </div>
    <?php endif; ?>

      </main>

    </div> <!-- /container -->
  </body>
</html>
