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
            <li class="breadcrumb-item"><a href="<?php route('/admin');?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?php route('/admin/demos');?>">Demos</a></li>
            <li class="breadcrumb-item active">Create</li>
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
        <div class="col-md-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Create Demo</h3>
            </div>
            <form action="<?php route('/admin/demos/store'); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?php echo $request->csrf; ?>">
              <div class="card-body">
                <div class="form-group">
                  <label for="inputName">Demo Title</label>
                  <input type="text" name="title" class="form-control">
                </div>
                <div class="form-group">
                  <label for="inputName">Demo Slug</label>
                  <input type="text" name="slug" class="form-control">
                </div>
                <div class="form-group">
                  <label for="inputDescription">Demo Description</label>
                  <textarea id="demo-desc" name="description" class="form-control" rows="6"></textarea>
                </div>
                <div class="form-group">
                  <label for="inputClientCompany">Demo Featured Image</label>
                  <input type="file" multiple="multiple" name="imageurl[]" class="form-control">
                </div>
              </div>
              <div class="card-footer">
                <button type="submit" class="btn btn-default float-right">CREATE</button>
              </div>
            </form>
          </div>
          <!-- /.card -->

        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->end('content'); ?>
<?php $this->start(); ?>
<script>
			$(document).ready(function() {
			$('#demo-desc').summernote();
			});
		</script>
<?php $this->end('footer.javascript'); ?>
<!-- Extend the layout template -->
<?php $this->extend('admin/layout'); ?>