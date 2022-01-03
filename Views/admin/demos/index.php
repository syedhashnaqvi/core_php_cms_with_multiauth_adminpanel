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
            <li class="breadcrumb-item active">Demos</li>
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
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Demos</h3>
              <div class="card-tools">
                <a href="<?php route('/admin/demos/create');?>" class="btn btn-primary">Create</a>
              </div>
            </div>
            <div class="card-body">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th style="width: 15px">#</th>
                    <th>Title</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th style="width:40px"><i class="fa fa-bolt"></i></th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($demos as $key => $demo){?>
                  <tr>
                    <td><?php echo $key+1?></td>
                    <td><?php echo $demo->title ?></td>
                    <td><?php echo $demo->slug ?></td>
                    <td><?php echo $demo->description ?></td>
                    <td><?php echo $demo->imageurl ?></td>
                    <td>
                      <div class="btn-group">
                        <a href="<?php route('/admin/demos/edit/'.$demo->id) ?>" class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                      <form action="/admin/demos/destroy" method="POST">
                        <input type="hidden" name="id" value="<?php echo $demo->id;?>">
                        <input type="hidden" name="csrf" value="<?php echo $request->csrf; ?>">
                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                      </form>
                      </div>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-default float-right">Pagination</button>
            </div>
          </div>
          <!-- /.card -->

        </div>
      </div>
    </div>
  </section>
</div>

<?php $this->end('content'); ?>

<!-- Extend the layout template -->
<?php $this->extend('admin/layout'); ?>