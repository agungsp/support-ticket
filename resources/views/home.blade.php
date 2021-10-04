@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    
                    {{-- <div class="card-shadow mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card text-white bg-primary">
                                    <div class="card-body pb-3">
                                        <div class="text-value">{{ number_format($totalTickets) }}</div>
                                        <div>Total tickets</div>
                                        <br />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card text-white bg-success">
                                    <div class="card-body pb-3">
                                        <div class="text-value">{{ number_format($openTickets) }}</div>
                                        <div>Open tickets</div>
                                        <br />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card text-white bg-danger">
                                    <div class="card-body pb-3">
                                        <div class="text-value">{{ number_format($closedTickets) }}</div>
                                        <div>Closed tickets</div>
                                        <br />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

              
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
                                          <i class="fas fa-envelope-open  fa-4x text-300"></i>
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
                                          <i class="fas fa-pause-circle fa-4x text-300"></i>
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
                                          <i class="fas fa-chalkboard-teacher fa-4x text-300"></i>
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
                                          <i class="fas fa-check-circle fa-4x text-300"></i>
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
                                          <i class="fas fa-envelope fa-4x tex-300"></i>
                                      </div>
                                  </div>
                              </div>
                          </div>
                        </div>
                    </div>
            

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent

@endsection