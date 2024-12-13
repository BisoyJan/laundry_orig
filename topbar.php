<style>
  .logo {
    margin: auto;
    font-size: 20px;
    background: white;
    padding: 7px 11px;
    border-radius: 50% 50%;
    color: #000000b3;
  }
</style>

<nav class="navbar navbar-success bg-primary  " style="padding:0;">
  <div class="container-fluid mt-2 mb-2">
    <div class="col-lg-12">
      <div class="col-md-1 float-left" style="display: flex;">
        <div class="logo">
          <div class="laundry-logo" style="width: 40px; height: 40px;"></div>
        </div>
      </div>
      <div class="col-md-3 pt-3 float-left text-white">
        <large><b> Quick Laundry Shop </b></large>
      </div>
      <div class="col-md-2  pt-3 float-right text-white">
        <a href="ajax.php?action=logout" class="text-white"><?php echo $_SESSION['login_name'] ?> <i
            class="fa fa-power-off"></i></a>
      </div>
    </div>
  </div>

</nav>
