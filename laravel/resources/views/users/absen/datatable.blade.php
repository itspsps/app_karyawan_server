<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<table id="jquery-datatable-example-no-configuration" class="display" style="width:100%">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Username</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data_user as $du)
            <tr>
                <td>{{ $du->id }}</td>
                <td>{{ $du->name }}</td>
                <td>{{ $du->email }}</td>
                <td>{{ $du->username }}</td>
                <td>{{ $du->username }}</td>
            </tr>
        @endforeach
    </tbody>
  </table>

  <script type="text/javascript">
	$(document).ready(function() {
	    $('#jquery-datatable-example-no-configuration').DataTable();
	});
</script>
