@extends('authAccount.auth_layout')
@section('filter-layout')
    <div class="container">
        <a href="{{ route('create-account') }}" class="btn btn-primary btn-add">
            <i class="fa fa-plus" aria-hidden="true"></i>
        </a>
        <div class="row report-table">
            <div class="col-md-12">
            <table class="table table-striped table-bordered" id="reportTable">
                <thead>
                    <th>ID</th>
                    <th>Account_id</th>
                    <th>UserAgent</th>
                    <th>developerToken</th>
                    <th>license</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($authAccounts as $authAccount)
                        <tr>
                            <td>{{ $authAccount->id }}</td>
                            <td>{{ $authAccount->account_id }}</td>
                            <td>{{ $authAccount->userAgent }}</td>
                            <td>{{ $authAccount->developerToken }}</td>
                            <td>{{ $authAccount->license }}</td>
                            <td>
                                <a href="{{ route('edit-account', $authAccount->id) }}" class="btn btn-warning">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if (!empty($authAccounts))
                <div class="paginator">
                    <div>{{ $authAccounts->links('pagination') }}</div>
                </div>
            @endif
            </div>
        </div>
    </div>
@stop
