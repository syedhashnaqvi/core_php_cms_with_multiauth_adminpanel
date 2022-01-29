<?php $this->start(); ?>
<style>
  .media-img {
    margin: auto;
    height: 100%;
    object-fit: contain;
    cursor: pointer;
  }

  .media-thumb {
    height: 90px;
    box-shadow: 1px 1px 2px 0px #cecaca;
  }
</style>
<?php $this->end('head.css'); ?>

<?php $this->start(); ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?php route('/admin') ?>">Home</a></li>
            <li class="breadcrumb-item active">Media</li>
          </ol>
        </div><!-- /.col -->
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-8 offset-md-2">
          <?php 
        use Core\Sessions; 
        Sessions::flash_message(); 
        ?>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title mt-2">Media Library</h3>
              <div class="card-tools">
                <a href="#" id="upload_media_btn" class="btn btn-default"><i class="fa fa-upload"></i></a>
              </div>
            </div>
            <div class="card-body p-0 pb-3">
              <div class="media-menu mb-3">
                <ul class="px-1 mb-0">
                  <li><button class="btn btn-default" id="view_media_btn" data-item_url=""><i
                        class="fa fa-eye"></i></button></li>
                  <li><button class="btn btn-default"><i class="fa fa-copy"></i></button></li>
                  <li><button class="btn btn-default"><i class="fa fa-trash"></i></button></li>
                  <li><a id="dnldlnk" class="btn btn-default" href="" download="proposed_file_name"><i class="fa fa-download"></i></a></li>

                </ul>
              </div>
              <div class="row">
                <div class="col-12 col-sm-12 col-md-9">
                  <div class="row">
                    <?php foreach($mediaFiles as $item){ ?>
                    <div class="col-2">
                      <div class="media-thumb m-1">
                        <img class="media-img w-100" src="<?php getMedia($item); ?>"
                          data-item_url="<?php getMedia($item); ?>" />
                      </div>
                    </div>
                    <?php }?>
                  </div>
                </div>
                <div class="col-sm-12 col-md-3">
                  <p class="text-bold"></p>
                  <table class="w-100">
                    <tr>
                      <td>File Name</td>
                      <td id="file_name"></td>
                    </tr>
                    <tr>
                      <td>File Format</td>
                      <td id="file_ext"></td>
                    </tr>
                    <tr>
                      <td>File Size</td>
                      <td id="file_size"></td>
                    </tr>
                    <tr>
                      <td>Resolution</td>
                      <td id="file_resolution"></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </section>
</div>
<!-- Upload Modal -->
<div class="modal fade" id="upload_media_modal" tabindex="-1" role="dialog" aria-labelledby="upload_media_modalTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="upload_media_modalitle">Upload Media</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="mediafiles">Select Files</label>
          <input type="file" name="mediafiles[]" multiple class="form-control" id="mediafiles">
        </div>
        <div class="progress" id="progress_bar">
          <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0%">0%</div>
        </div>
        <div class="progress" id="progress_bar" style="display:none; ">
          <div class="progress-bar" id="progress_bar_process" role="progressbar" style="width:0%">0%</div>
        </div>
        <div id="uploaded_image" class="row mt-3 d-block"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="upload_btn"
          data-upload_url="<?php route('/admin/media/upload') ?>"
          data-upload_csrf="<?php echo $request->csrf; ?>">UPLOAD</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="media_url_modal" tabindex="-1" role="dialog" aria-labelledby="media_url_modalTitle"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="media_url_modalitle"><i class="fa fa-eye"></i> Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img class="w-100" src="" alt="" id="media_img_preview">
        <hr>
        <div class="input-group">
          <input type="text" name="media_file_url" multiple class="form-control" id="media_file_url">
          <span><button id="copy_url_btn" class="btn btn-info"><i class="fa fa-copy"></i></button></span>
        </div>
        <div id="uploaded_image" class="row mt-3 d-block"></div>
      </div>
    </div>
  </div>
</div>
<?php $this->end('content'); ?>

<?php $this->start(); ?>
<script>
  $("#upload_media_btn").on("click", function () {
    $('#upload_media_modal').modal('show');
  });

  $(".media-img").on("click", function () {
    var img = new Image();
    img.src = $(this).attr("src");
    var filename = img.src.substring(img.src.lastIndexOf('/')+1);
    var extension = filename.substring(filename.lastIndexOf('.'));
    $("#file_size").html((parseInt(fetchHeader($(this).attr("src"),'Content-Length'))/1024).toFixed(1)+" KB");
    $("#file_resolution").html(img.width+" X "+img.height);
    $("#file_ext").html(extension);
    $("#file_name").html(filename);
    $(".media-img").each(function (i, obj) {
      $(this).removeClass("media_thumb_selected");
    });
    $(this).addClass("media_thumb_selected");
    $("#view_media_btn").data("item_url", $(this).data("item_url"));
    $("#media_file_url").val($(this).data("item_url"));
    var filename = $(this).data("item_url").substring($(this).data("item_url").lastIndexOf('/')+1);
    $("#dnldlnk").attr("href",$(this).data("item_url"));
    $("#dnldlnk").attr("download",filename);
  });

  $("#copy_url_btn").on("click", function () {
    myFunction("media_file_url")
  });

  $("#view_media_btn").on("click", function () {
    $("#media_img_preview").attr("src", $(this).data("item_url"));
    $('#media_url_modal').modal('show');
  });

  function myFunction(id) {
    var copyText = document.getElementById(id);
    copyText.select();
    copyText.setSelectionRange(0, 99999); /* For mobile devices */
    navigator.clipboard.writeText(copyText.value);
    alert("Copied the text: " + copyText.value);
  }

  function fetchHeader(url, wch) {
    try {
        var req=new XMLHttpRequest();
        req.open("HEAD", url, false);
        req.send(null);
        if(req.status== 200){
            return req.getResponseHeader(wch);
        }
        else return false;
    } catch(er) {
        return er.message;
    }
}
</script>
<script src="<?php asset('admin/js/media.js')?>"></script>
<?php $this->end('footer.javascript'); ?>

<!-- Extend the layout template -->
<?php $this->extend('admin/layout'); ?>