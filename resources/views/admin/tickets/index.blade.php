@extends('layouts.admin')
@section('content')
@can('ticket_create')

{{-- Konten --}}

                  <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                  </div>
                    <!-- Content Row -->
                  <div class="row">
                            <!-- Open Ticket -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                  Open </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">12</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                  Pending</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Working -->
                            <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-success shadow h-100 py-2">
                                  <div class="card-body">
                                      <div class="row no-gutters align-items-center">
                                          <div class="col mr-2">
                                              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Working</div>
                                              <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                          </div>
                                          <div class="col-auto">
                                              <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>


                            <!-- Confirm Client -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Confirm Client</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-comments fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pending Requests Card Example -->
                            <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-danger shadow h-100 py-2">
                                  <div class="card-body">
                                      <div class="row no-gutters align-items-center">
                                          <div class="col mr-2">
                                              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                                  Close</div>
                                              <div class="h5 mb-0 font-weight-bold text-gray-800">2</div>
                                          </div>
                                          <div class="col-auto">
                                              <i class="fas fa-comments fa-2x text-gray-300"></i>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                  </div>
    {{-- <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.tickets.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}
            </a>
        </div>
    </div> --}}
@endcan
<div class="card">
    <div class="card-header">
        <a href="{{ route("admin.tickets.create") }}"class="btn btn-success btn-circle btn-md">
          <i class="fas fa-plus"></i>
        </a> 
        <p style="display: inline">{{ trans('global.add') }} {{ trans('cruds.ticket.title_singular') }}</p>
    </div>
    @if(session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="card-body">
      {{ trans('cruds.ticket.title_singular') }} {{ trans('global.list') }}
        <table class=" table table-responsive table-bordered table-striped table-hover ajaxTable datatable datatable-Ticket">
            <thead>
                <tr>
                    <th width="10">
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.created_at') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.title') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.status') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.priority') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.category') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.author_name') }}
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.author_email') }}
                    </th>
                    <th>
                        Project
                    </th>
                    <th>
                        {{ trans('cruds.ticket.fields.assigned_to_user') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>


    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
let filters = `
<form class="form-inline" id="filtersForm">
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="status">
      <option value="">Semua Status</option>
      @foreach($statuses as $status)
        <option value="{{ $status->id }}"{{ request('status') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="priority">
      <option value="">Semua Prioritas</option>
      @foreach($priorities as $priority)
        <option value="{{ $priority->id }}"{{ request('priority') == $priority->id ? 'selected' : '' }}>{{ $priority->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group mx-sm-3 mb-2">
    <select class="form-control" name="category">
      <option value="">Semua Kategori</option>
      @foreach($categories as $category)
        <option value="{{ $category->id }}"{{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
      @endforeach
    </select>
  </div>
</form>`;
$('.card-body').on('change', 'select', function() {
  $('#filtersForm').submit();
})
  let dtButtons = []
@can('ticket_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tickets.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
          return entry.id
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan
  let searchParams = new URLSearchParams(window.location.search)
  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: {
      url: "{{ route('admin.tickets.index') }}",
      data: {
        'status': searchParams.get('status'),
        'priority': searchParams.get('priority'),
        'category': searchParams.get('category')
      }
    },
    columns: [
      { data: 'placeholder', name: 'placeholder' },
      { data: 'created_at', name: 'created_at' },
      {
          data: 'title',
          name: 'title', 
          render: function ( data, type, row) {
              return '<a href="'+row.view_link+'">'+data+' ('+row.comments_count+')</a>';
          }
      },
      { 
        data: 'status_name', 
        name: 'status.name', 
        render: function ( data, type, row) {
            return '<span style="color:'+row.status_color+'">'+data+'</span>';
        }
      },
      { 
        data: 'priority_name', 
        name: 'priority.name', 
        render: function ( data, type, row) {
            return '<span style="color:'+row.priority_color+'">'+data+'</span>';
        }
      },
      { 
        data: 'category_name', 
        name: 'category.name', 
        render: function ( data, type, row) {
            return '<span style="color:'+row.category_color+'">'+data+'</span>';
        } 
      },
      { data: 'author_name', name: 'author_name' },
      { data: 'author_email', name: 'author_email' },
      { data: 'project_name', name: 'project.name' },
      { data: 'assigned_to_user_name', name: 'assigned_to_user.name' },
      { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  };    
  $(".datatable-Ticket").one("preInit.dt", function () {
  $(".dataTables_filter").after(filters);
  });
    $('.datatable-Ticket').DataTable(dtOverrideGlobals);
      $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
          $($.fn.dataTable.tables(true)).DataTable()
              .columns.adjust();
      });
  });

</script>
@endsection