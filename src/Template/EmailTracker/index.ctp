<?php if(count($emails) == 0): ?>
    <script type="text/javascript">
        alert('Please run migration to create tables : bin/cake migration migrate\nSeed the table : bin/cake migration seed\nIf still an issue try truncating the table using the path("/truncate") and try again');
    </script>
<?php endif; ?>
<div class="row">
    <div class="navbar navbar-default">
      <div class="container-fluid">
        <div class="navbar-header">
           <a class="navbar-brand">Filters</a>
        </div>
        <?php echo $this->Form->create('',['class'=>'navbar-form']);?>
          <div class="form-group">
            <input class="form-control" type="text" name="client-id" placeholder="Client ID" style="width: 110px;" />
          </div>
          <div class="form-group">
            <input class="form-control datetime-picker" type="text" name="date-sent" placeholder="Date Sent" />
          </div>
          <div class="form-group">
            <select class="form-control" type="text" name="sender-email" placeholder='Sender Email'>
              <option value="" selected="">ALL</option>
              <?php foreach($sender_emails as $key=>$value): ?>
                <option value=<?php echo $key; ?>><?php echo $key; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        <?php echo $this->Form->end();?>
      </div>
    </div>
</div>
<div class="row">
    <table id="email_info_table" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Client id</th>
                <th>Subject</th>
                <th>Sender Name</th>
                <th>Sender Email</th>
                <th>Sent Time</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Client id</th>
                <th>Subject</th>
                <th>Sender Name</th>
                <th>Sender Email</th>
                <th>Sent Time</th>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach($emails as $email): ?>
                <tr>
                    <td><?php echo $email->client_id; ?></td>
                    <td><?php echo $email->subject; ?></td>
                    <td><?php echo $email->sender_name; ?></td>
                    <td><?php echo $email->sender_email; ?></td>
                    <td><?php echo date('d M Y', strtotime($email->time_sent)); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $('#email_info_table').DataTable();
    } );
</script>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>