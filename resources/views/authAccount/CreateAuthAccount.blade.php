@extends('authAccount.auth_layout')
@section('filter-layout')
    <div class="menu_left col-lg-2">
        <select id="myselect" class="form-control">
            <option value="google">Google</option>
            <option value="yahoo">Yahoo</option>
        </select>
    </div>
    <div class="menu_right col-lg-10">
        <form action="{{route('store-account')}}" method="POST" class="form-horizontal form-auth" role="form" id="google" name="google" >
            {{csrf_field()}}
            <h2 class="form-auth-heading">Google</h2>
            <div class="form-warp">
                <input type="hidden" class="form-control" value="{{ Auth::user()->account_id }}" id="account_id" name="account_id">
                <input type="hidden" class="form-control" value="google" id="userAgent" name="userAgent">

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">DeveloperToken :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="developerToken" name="developerToken" placeholder="DeveloperToken">
                    </div>
                </div>

                @if ($errors->has('developerToken'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('developerToken')}}</strong>
                    </div>
                @endif

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">ClientCustomerId :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="clientCustomerId" name="clientCustomerId" placeholder="ClientCustomerId">
                    </div>
                </div>

                @if ($errors->has('ClientCustomerId'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('ClientCustomerId')}}</strong>
                    </div>
                @endif

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">OnBehalfOfAccountId :</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="onBehalfOfAccountId" name="onBehalfOfAccountId" placeholder="OnBehalfOfAccountId">
                    </div>
                </div>

                @if ($errors->has('OnBehalfOfAccountId'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('OnBehalfOfAccountId')}}</strong>
                    </div>
                @endif

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">OnBehalfOfPassword :</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows="5" id="onBehalfOfPassword" name="onBehalfOfPassword"></textarea>
                    </div>
                </div>

                @if ($errors->has('onBehalfOfPassword'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('onBehalfOfPassword')}}</strong>
                    </div>
                @endif

                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>

        <form action="{{route('store-account')}}" method="POST" class="form-horizontal form-auth" role="form" id="yahoo" name="yahoo">
            {{csrf_field()}}
            <h2 class="form-auth-heading">Yahoo</h2>
            <div class="form-warp">
                <input type="hidden" class="form-control" value="{{ Auth::user()->id }}" id="account_id" name="account_id">
                <input type="hidden" class="form-control" value="yahoo_yss" id="userAgent" name="userAgent">
                <div class="form-group">
                    <label class="control-label col-sm-4" for="">License</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="license" name="license" placeholder="License">
                    </div>
                </div>

                @if ($errors->has('license'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('license')}}</strong>
                    </div>
                @endif

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">AccountId</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="accountId" name="accountId" placeholder="AccountId">
                    </div>
                </div>

                @if ($errors->has('accountId'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('accountId')}}</strong>
                    </div>
                @endif

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">APIAccountId</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="apiAccountId" name="apiAccountId" placeholder="License">
                    </div>
                </div>

                @if ($errors->has('apiAccountId'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('apiAccountId')}}</strong>
                    </div>
                @endif

                <div class="form-group">
                    <label class="control-label col-sm-4" for="">APIAccountPassword</label>
                    <div class="col-sm-8">
                        <textarea  class="form-control" rows="5" id="apiAccountPassword" name="apiAccountPassword"></textarea>
                    </div>
                </div>

                @if ($errors->has('apiAccountPassword'))
                    <div class="alert alert-danger">
                        <strong>{{$errors->first('apiAccountPassword')}}</strong>
                    </div>
                @endif

                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
@stop
