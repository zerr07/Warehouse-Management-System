<!DOCTYPE html>
<html style="max-width: 97vw;">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- PAGE settings -->
  <title>WMS v{$system.version} - Control Panel</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <!-- CSS dependencies -->
  <link rel="stylesheet" href="/templates/default/assets/css/bootstrap.css">
  <link rel="stylesheet" href="/templates/default/assets/css/style.css">
  <link rel="stylesheet" href="/templates/default/assets/css/editor.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
  <!--<link rel="stylesheet" href="/templates/default/assets/fileup/css/jquery.fileupload.css"> -->
  <!-- Script: Make my navbar transparent when the document is scrolled to top -->
  <script src="/templates/default/assets/js/navbar-ontop.js"></script>
  <!-- Script: Animated entrance -->

  <script src="/templates/default/assets/js/animate-in.js"></script>

  <script src="/bar.js"></script>
  <script src="/print.min.js"></script>
  <!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
  <!--<script src="/templates/default/assets/fileup/js/vendor/jquery.ui.widget.js"></script>-->
  <!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
  <!--<script src="/templates/default/assets/fileup/js/jquery.iframe-transport.js"></script>-->
  <!-- The basic File Upload plugin -->
  <script src="/templates/default/assets/fileup/js/jquery.fileupload.js"></script>
  <script src="/templates/default/assets/fileup/fileup.js"></script>
  <script src="/templates/default/assets/js/fontawesome-all.js"></script>


  <!-- Text editor plugin -->
  <script src="/templates/default/assets/fileup/js/vendor/jquery.ui.widget.js"></script>
  <script src="/templates/default/assets/js/editor.js"></script>
  <script src="/templates/default/assets/js/cookie.js"></script>

  <script src="/templates/default/assets/js/priceCalc.js"></script>
  <script src="/cp/POS/updateCart.js"></script>
  <script src="/cp/POS/displayCartPOS.js"></script>
  <script src="/cp/WMS/category/editLink.js"></script>
  <script src="/cp/WMS/item/edit/editEAN.js"></script>



  <script>
    $(window).on('load', function () {
      $preloader = $('.loaderArea'),
              $loader = $preloader.find('.loader');
      $loader.fadeOut();
      $preloader.delay(150).fadeOut(100);
    });
  </script>


</head>

<body class="text-center">
<div class="loaderArea">
  <div class="loader"></div><p class="preloader-text">Loading...</p>
</div>

  <wrapper class="d-flex flex-column fullHeight">

    <nav class="navbar navbar-expand-md navbar-light">
      <div class="container">
        <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse" data-target="#navbar6">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar6">
          {include file="logo.tpl"}
          <ul class="navbar-nav mx-auto">
            <li class="nav-item">
              {if isset($user)}
                <a class="nav-link text-primary " href="/cp"><i class="fas fa-home"></i> Home</a>

            </li>
            <li class="nav-item">
              <a href="/cp/POS" class="nav-link text-primary"><i class="fas fa-store"></i> POS</a>

            </li>


            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-qrcode"></i> Scanners
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="/mobileScanner.php" class="dropdown-item"><i class="fas fa-qrcode"></i> Mobile scanner</a>
                <a href="/manualMobileScanner.php" class="dropdown-item"><i class="fas fa-qrcode"></i> Manual mobile scanner</a>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cogs"></i> Tools
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="/cp/WMS/platforms/" class="dropdown-item"><i class="fas fa-store-alt"></i> Platforms</a>
                <a href="#staticBackdrop" data-toggle="modal" data-target="#staticBackdrop" class="dropdown-item"><i class="fas fa-tags"></i> Custom Label Generator</a>
                <a href="#staticBackdropLG" data-toggle="modal" data-target="#staticBackdropLG" class="dropdown-item"><i class="fas fa-tags"></i> Large Custom Label Generator</a>

                <a href="/cp/tree-links" class="dropdown-item"><i class="fas fa-tree"></i> Tree list</a>
              </div>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-qrcode"></i> Shards
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                {foreach $shards as $key => $shard}
                  <a class="dropdown-item text-white" onclick="
                          setCookie('shard', '{$shard}', 365);
                          setCookie('id_shard', '{$key}', 365);
                          location.reload();
                          ">{$shard}</a>
                {/foreach}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/cp/WMS/shards">Manage</a>

              </div>
            </li>

            {/if}
            {if $group=='2'}
            <li class="nav-item">
                <a class="nav-link text-primary " href="/cp/register">Register user</a>
            </li>
              <li class="nav-item">
                <a class="nav-link text-primary " href="/cp/manageServices">Manage services</a>
              </li>
            {/if}
          </ul>
          <ul class="navbar-nav" >
            <li class="nav-item">
              {if isset($user)}
                <a>Logged as <a style="color: white">{$user}</a></a>
                <a class="btn btn-outline-primary" href="?logout"><i class="fas fa-sign-out-alt"></i>Logout</a>
              {/if}
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Custom Label Modal -->
    <div class="modal fade" id="staticBackdrop" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Custom barcode</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body  text-left">
            <form method="GET" action="/bar.php" target="_blank">
              <div class="form-group">
                <label for="customBarcode" class="col-form-label">Barcode:</label>
                <input type="text" class="form-control" id="customBarcode" name="customBarcode">
              </div>
              <input type="submit" class="btn btn-primary w-100" name="customSubmit" value="Submit">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Custom Label Modal LG -->
    <div class="modal fade" id="staticBackdropLG" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabelLG" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabelLG">Custom barcode</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body  text-left">
            <form method="GET" action="/bar.php" target="_blank">
              <div class="form-group">
                <label for="customBarcode1" class="col-form-label">Barcode:</label>
                <input type="text" class="form-control" id="customBarcode1" name="customBarcode1">
              </div>
              <input type="submit" class="btn btn-primary w-100" name="customSubmit1" value="Submit">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>