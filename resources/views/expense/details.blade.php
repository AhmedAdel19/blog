@extends('layouts.app')

@section('content')
	<section class="content-header">
      <h1>{{$pageTitle}}</h1>
    </section>
	<div class="content">
        <h3>Expense Details</h3>

		<div class="box box-primary">
	{{-- <div class="box-header with-border">
		@include( 'mmb/toolbarmain')
	</div> --}}
	<div class="box-body">
        <table class="table">
            <tbody>

                <tr>
                  <th>Name/Title : </th>
                    <td>{{$row['expense_name']}}</td>
                </tr>
                <tr>
                  <th>Date : </th>
                    <td>{{$row['date']}}</td>
                </tr>
                <tr>
                  <th>Accounts : </th>
                    <td>{{ SiteHelpers::formatLookUp($row['account_id'],'account_id','1:tbl_accounts:account_id:account_name') }}</td>
                </tr>
                <tr>
                    <th>Amount : </th>
                      <td>{{$row['amount']}} ج.م</td>
                  </tr>
                  <tr>
                    <th>Paid By : </th>
                    <td>{{ SiteHelpers::formatLookUp($row['paid_by'],'paid_by','1:tb_users:id:username') }}</td>
                  </tr>
                  <tr>
                    <th>Payment Method : </th>
                    <td>{{ SiteHelpers::formatLookUp($row['payment_id'],'payment_id','1:def_payment_types:paymenttypeID:payment_type') }}</td>
                  </tr>
                  <tr>
                    <th>Status : </th>
                      <td>{!! InvoiceStatus::ExpenseStatus($row['status']) !!}</td>
                  </tr>
                  <tr>
                    <th>Category : </th>
                      <td>{{ SiteHelpers::formatLookUp($row['category_id'],'category_id','1:tbl_categories:category_id:category_name') }}</td>
                  </tr>            
                  <tr>
                    <th>Attachment : </th>
                      <td>{{$row['attachment']}}</td>
                  </tr>
                  <tr>
                    <th>Notes : </th>
                      <td>{{$row['expense_notes']}}</td>
                  </tr>
            </tbody>
        </table>
	</div>
</div>
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#MmbTable').attr('action','{{ url("$pageModule/multisearch")}}');
		$('#MmbTable').submit();
	});

	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});

	$('#{{ $pageModule }}Table .checkall').on('ifChecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('check');
	});

    	$('#{{ $pageModule }}Table .checkall').on('ifUnchecked',function(){
		$('#{{ $pageModule }}Table input[type="checkbox"]').iCheck('uncheck');
	});

	$('.copy').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('{{ Lang::get('core.rusureyouwanttocopythis') }}'))
		{
				$('#MmbTable').attr('action','{{ url("$pageModule/copy")}}');
				$('#MmbTable').submit();// do the rest here
		}
	})

});
</script>
<style>
.table th , th { text-align: none !important;  }
.table th.right { text-align:right !important;}
.table th.center { text-align:center !important;}

</style>

<script>
  $(function () {
    $('#{{ $pageModule }}Table').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "lengthMenu": [ [25, 50, -1], [25, 50, "All"] ],
      "autoWidth": true
    });
  });
</script>

@stop
