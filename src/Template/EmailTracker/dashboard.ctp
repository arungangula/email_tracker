<div class="row">
    <div class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
           <a class="navbar-brand">Filters</a>
        </div>
        <?php echo $this->Form->create('',['class'=>'navbar-form']);?>
          <div class="form-group">
            <input class="form-control datetime-picker" type="text" name="start-date" placeholder="Start Date" />
          </div>
          <div class="form-group">
            <input class="form-control datetime-picker" type="text" name="end-date" placeholder="End Date" />
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Filter</button>
          </div>
        <?php echo $this->Form->end();?>
      </div>
    </div>
</div>
<div class="row">
    <table id="client_count_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Client id</th>
                <th>Email Count</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Client id</th>
                <th>Email Count</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach($clients as $client_id => $count): ?>
                <tr>
                    <td><?php echo $client_id; ?></td>
                    <td><?php echo $count; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#client_count_table').DataTable({
        	language: {
		        searchPlaceholder: "Client Id"
		    }
        });
    } );
</script>
