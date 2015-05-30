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
  	<div class="bs-docs-section">
        <div class="row">
          <div class="col-lg-8">
            <div class="page-header">
              <h1 id="forms">Form Pendaftaran</h1>
            </div>
            <?php echo $prob; ?>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <div class="well bs-component">
              <form class="form-horizontal" method="POST" action="<?php echo base_url() ?>signup/ready/">
                <fieldset>
                  <div class="form-group">
                    <label for="inputNama" class="col-lg-2 control-label">Nama</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputNama" name="inputNama" placeholder="Nama">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Email</label>
                    <div class="col-lg-10">
                      <input type="text" class="form-control" id="inputEmail" name="inputEmail" placeholder="Email">
                    </div>
                  </div>
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
                  <div class="form-group">
                    <div class="col-lg-10 col-lg-offset-2">
                      <button type="reset" class="btn btn-default">Cancel</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
  </div>
</body>
</html>
