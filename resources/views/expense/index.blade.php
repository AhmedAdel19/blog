@extends('layouts.app')

@section('content')
	<section class="content-header">
      <h1>{{$pageTitle}}</h1>
    </section>
	<div class="content">
		<div class="box box-primary">
	<div class="box-header with-border">
		@include( 'mmb/toolbarmain')
	</div>
	<div class="box-body">

	 {!! Form::open(array('url'=>$pageModule.'/delete/', 'class'=>'form-horizontal' ,'id' =>'MmbTable' )) !!}
	 <div class="table-responsive" style="min-height:300px; padding-bottom:60px; border: none !important">
		 <table class="table table-striped table-bordered " id="{{ $pageModule }}Table">
			 <thead>
			 <tr>
				 <th class="number"> No </th>
				 <th> <input type="checkbox" class="checkall" /></th>
				 <th> Edit</th>
				 <th>Name/Title</th>
				 <th>Category</th>
				 <th>Date</th>
				 <th>Account</th>
				 <th>Amount</th>
				 <th>Status</th>
				 <th>Attachment</th>
				 <th>Action</th>
			 </tr>
			 </thead>
			 <tbody>
			 @foreach ($rowData as $row)
                <tr>
					<td class="no" width="30"> {{ ++$i }} </td>
					<td class="empty" width="50">
						<input type="checkbox" class="ids minimal-red"
							   name="ids[]"
							   value="{{ $row->expense_id }}"
						/>
					</td>
					<td width="100" class="edit">
						@if($access['is_edit'] ==1)
						<a  href="{{ url($pageModule.'/'.$row->expense_id.'/edit?return='.$return) }}"
							class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit fa-2x"></i>
						</a>

						{{-- <a  href="{{ url($pageModule.'/update/'.$row->expense_id.'?return='.$return) }}"
							class="tips" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit fa-2x"></i>
						</a> --}}
						@endif

					</td>
                    <td class="Expense">
						<a  href="{{ url($pageModule.'/details/'.$row->expense_id.'?return='.$return) }}"
							class="tips">{{ $row->expense_name }}
						</a>

					</td>
					<td class="Category">
						<a  href="{{ url($pageModule.'/details/'.$row->expense_id.'?return='.$return) }}"
							class="tips" >{{ SiteHelpers::formatLookUp($row->category_id,'category_id','1:tbl_categories:category_id:category_name') }}
						</a>
                    </td>
                    <td class="date">
						{{ $row->date }}
					</td>
                    <td class="account_id">
						{{-- {{ $row->account_name }} --}}
						{{ SiteHelpers::formatLookUp($row->account_id,'account_id','1:tbl_accounts:account_id:account_name') }}
					</td>
					<td class="amount">
						{{ $row->amount}}
					</td>
					<td class="status">
				      {{-- {!! InvoiceStatus::ExpenseStatus($row->status) !!} --}}
					</td>
					<td class="attachment">
						{{ $row->attachment }}
					</td>
					<td class="action">
						<a  href="{{ url($pageModule.'/details/'.$row->expense_id.'?return='.$return) }}"
							class="tips" title="details"><i class="fa fa-info-circle fa-2x"></i>
						</a>
					</td>
				</tr>
            @endforeach
			 </tbody>
		 </table>
		 <input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	</div>
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
