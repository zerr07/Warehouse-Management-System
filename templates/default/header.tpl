<!DOCTYPE html>
<html>

<head>
  <script type="text/javascript">
    var timerStart = Date.now();
  </script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- PAGE settings -->
  <title>WMS v{$system.version} - Control Panel</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <!-- CSS dependencies -->
  <link rel="stylesheet" href="/templates/default/assets/css/bootstrap.min.css?t=16102020T165624">
  <link rel="stylesheet" href="/templates/default/assets/css/style.css?t=16102020T165622">
  <link href="/templates/default/assets/css/sidebar.css?t=16102020T165621" rel="stylesheet">
  <script src="/templates/default/assets/js/jquery.min.js?t=16102020T165620"></script>
  <script src="/templates/default/assets/js/script.js?t=16102020T165618"></script>
  <script>
    if('ontouchstart' in window){
      document.write('<script type="text/javascript" src="/templates/default/assets/js/swipe_menu.js?t=16102020T165616""><\/script>');
    }
  </script>

</head>

<body class="">

<div class="loaderArea" id="loaderArea">
  <div class="loader spinner-border text-primary" role="status">
    <span class="sr-only">Loading...</span>
  </div>
  <p class="preloader-text">Loading...</p>
</div>
<div class="loaderAreaProgress" id="loaderAreaProgress">
  <div class="loaderProgress spinner-border text-primary" role="status">
    <span class="sr-only">Loading...</span>
  </div>
  <p class="preloaderProgress-text">Loading...</p>



  <div class="progress">
    <div class="progress-bar progress-bar-striped progress-bar-animated" id="preloaderProgress_progressBar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
  </div>
</div>
{if isset($user)}
  {include file='sidebar.tpl'}
{/if}
<main id="main_block" style="filter: blur(1.5rem)">
<div class="container-fluid h-100 pt-1">
  <div class="row mt-4">
    {assign var="filter" value="filter1"}
    {assign var="filter_text" value="filter_text1"}
    {if isset($user)}
      <span class="ml-1" id="sidebar_close_btn" style="cursor: pointer;top: 1.5rem;" onclick="openNav()"><i class="fas fa-bars"></i></span>
    {/if}
    <div class="col-6 ml-5">{include file="logo.tpl"}</div>
  </div>
  <hr style="top: 50px;
    position: absolute;
    width: 100%;
    left: 0;
    border: none;
    height: 2px;
    background-color: #344a5fc9;">
  <div class="row">

    <div class="col-sm-12" id="content_block">
      <div class="container-fluid pl-md-5 pr-md-5">
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