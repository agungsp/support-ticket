<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>E - Ticket</title>
        <!-- Custom fonts for this template-->
        <link href="{{ asset('theme/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{ asset('theme/css/sb-admin-2.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
         
              <!-- Sidebar - Brand -->
              <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route("admin.home") }}">
                  <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-ticket-alt"></i>
                  </div>
                  <div class="sidebar-brand-text mx-3">E - Ticketing</sup></div>
              </a>

              <!-- Divider -->
              <hr class="sidebar-divider my-0">

              <!-- Nav Item - Dashboard -->
              <li class="nav-item active">
                  <a class="nav-link" href="{{ route("admin.home") }}">
                      <i class="fas fa-fw fa-tachometer-alt"></i>
                      <span>{{ trans('global.dashboard') }}</span></a>
              </li>

           @can('user_management_access')
            <!-- Heading -->
            <div class="sidebar-heading">
                Managements
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-users"></i>
                    <span>{{ trans('cruds.userManagement.title') }}</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                      @can('permission_access')
                          <a class="collapse-item" href="{{ route("admin.permissions.index") }}">{{ trans('cruds.permission.title') }}</a>
                      @endcan
                      @can('role_access')
                      <a class="collapse-item" href="{{ route("admin.roles.index") }}">{{ trans('cruds.role.title') }}</a>
                      @endcan
                      @can('user_access')
                      <a class="collapse-item" href="{{ route("admin.users.index") }}">{{ trans('cruds.user.title')}} </a>
                      @endcan 
                      @can('audit_log_acces')
                      <a class="collapse-item" href="{{ route("admin.audit-logs.index") }}">{{ trans('cruds.auditLog.t')}}</a>
                      @endcan
                    </div>
                </div>
            </li>
            @endcan
            <!-- Nav Item - Utilities Collapse Menu -->
            @can('status_access')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>{{ trans('cruds.setting.title') }}</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                      @can('status_access')
                        <a class="collapse-item" href="{{ route("admin.statuses.index") }}">{{ trans('cruds.status.title') }}</a>
                      @endcan
                      @can('config_settings')
                        <a class="collapse-item" href="{{ route("admin.priorities.index") }}">{{ trans('cruds.priority.title') }}</a>
                      @endcan
                      @can('user_access')
                        <a class="collapse-item" href="{{ route("admin.categories.index") }}"> {{ trans('cruds.category.title') }}</a>
                      @endcan
                    </div>
                </div>
            </li>
            @endcan
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Action
            </div>

            <!-- Nav Item - Ticket -->
            @can('ticket_access')
            <li class="nav-item">
                <a class="nav-link" href="{{ route("admin.tickets.index") }}">
                    <i class="fas fa-ticket-alt"></i>
                    <span>{{ trans('cruds.ticket.title') }}</span>
                </a>
                @endcan
            </li>
            

            <!-- Nav Item - Comments -->
            <li class="nav-item">
              @can('comment_access')
              <a class="nav-link" href="{{ route("admin.comments.index") }}">
                <i class="fas fa-comments"></i>
                  <span> {{ trans('cruds.comment.title') }}</span>
              </a>
              @endcan
          </li>
            <!-- Nav Item - Project -->
            <li class="nav-item">
              @can('comment_access')
              <a class="nav-link" href="{{ route("admin.projects.index") }}">
                  <i class="fas fa-tasks"></i>
                  <span> {{ trans('cruds.project.title') }}</span>
                  </a>
              </a>
              @endcan
          </li>

          <!-- Divider -->
          <hr class="sidebar-divider d-none d-md-block">
                 <!-- Nav Item - Project -->
            <li class="nav-item">
               
                <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                  <i class="fas fa-sign-out-alt"></i>
                    <span>{{ trans('global.logout') }}</span>
                    </a>
                </a>
                
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        {{-- <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Putu Mahardika</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('theme/img/undraw_profile.svg')}}">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ trans('global.logout') }}
                                </a>
                            </div>
                        </li> --}}
                    </ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                 <!-- Content Row -->
                    <div class="card shadow mb-4">
                      
                        @yield('content')
                      
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      {{ csrf_field() }} 
      <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Yakin keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih Keluar untuk mengakhiri.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a href="{{ route('logout') }}" class="btn btn-danger" type="submit" data-dismiss="modal">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Logout Form --}}

    <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
      {{ csrf_field() }}
    </form>

     <!-- Bootstrap core JavaScript-->
     <script src="{{ asset('theme/vendor/jquery/jquery.min.js') }}"></script>
     <script src="{{ asset('theme/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
 
     <!-- Core plugin JavaScript-->
     <script src="{{ asset('theme/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
 
     <!-- Custom scripts for all pages-->
     <script src="{{ asset('theme/js/sb-admin-2.min.js') }}"></script>

</body>

</html>