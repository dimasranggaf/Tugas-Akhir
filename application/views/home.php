<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
    <title>WebRTC (Web Real Time Communication)</title>
    <?php include 'header.php'; ?>
</head>
<body>
	<?php include 'navbar.php'; ?>
  <div class="container">
  	<div class="row">
      <div class="modal fade" id="formLogin" tabindex="-1" role="dialog" aria-labelledby="formLoginLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
              <h4 class="modal-title">Form Login</h4>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" method="POST" action="<?php echo base_url(); ?>home/login/">
                <fieldset>
                  <div class="form-group">
                    <label for="inputUser" class="col-lg-2 control-label">Username</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputUser" name="inputUser" placeholder="Username">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Password</label>
                    <div class="col-lg-10">
                      <input type="password" class="form-control" id="inputPassword" name="inputPassword" placeholder="Password">
                    </div>
                  </div>
                </fieldset>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Login</button>
            </div>
            </form>
          </div>
        </div>
      </div>
          <div class="col-lg-12">
            <div class="page-header">
              <h1 id="container">WebRTC (Web Real Time Communication)</h1>
            </div>
            <?php echo $daftar; ?>
            <div class="bs-component">
              <div class="jumbotron">
                <h1>Welcome</h1>
                <p>WebRTC enables all kind of real time communication (video and audio) between users by utilising the browser.</p>
                <p><a class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formLogin">Login</a></p>
                <p>Don't have account? Signup <a href="<?php echo base_url(); ?>signup/">here</a></p>
              </div>
            </div>
          </div>
        </div>
  </div>
</body>
</html>
