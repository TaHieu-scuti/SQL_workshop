@extends('authAccount.auth_layout')
@section('filter-layout')
<?php
$error_of_yahoo = false;
if ($errors->has('license')
    || $errors->has('accountId')
    || $errors->has('apiAccountId')
    || $errors->has('apiAccountPassword')
) {
    $error_of_yahoo = true;
}
?>
    <div class="container">
        <div class="menu_left col-lg-2">
            <select id="myselect" class="form-control">
                <option value="google">Google</option>
                @if ($error_of_yahoo)
                    <option value="yahoo" selected="selected">Yahoo</option>
                @else
                    <option value="yahoo">Yahoo</option>
                @endif
            </select>
        </div>
        <div class="menu_right col-lg-10">
            <form action="{{route('store-account')}}" method="POST"
            class="form-horizontal form-auth" role="form" id="google" name="google"
            @if ($error_of_yahoo)
                style="display: none"
            @endif
            >
                {{csrf_field()}}
                <h2 class="form-auth-heading">Google</h2>
                <div class="form-warp">
                    <input type="hidden" class="form-control" value="{{ Auth::user()->account_id }}" id="account_id" name="account_id">
                    <input type="hidden" class="form-control" value="google" id="userAgent" name="userAgent">

                    <div class="form-group form-auth-account">
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

                    <div class="form-group form-auth-account">
                        <label class="control-label col-sm-4" for="">ClientCustomerId :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="clientCustomerId" name="clientCustomerId" placeholder="ClientCustomerId">
                        </div>
                    </div>

                    @if ($errors->has('clientCustomerId'))
                        <div class="alert alert-danger">
                            <strong>{{$errors->first('clientCustomerId')}}</strong>
                        </div>
                    @endif

                    <div class="form-group form-auth-account">
                        <label class="control-label col-sm-4" for="">OnBehalfOfAccountId :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="onBehalfOfAccountId" name="onBehalfOfAccountId" placeholder="OnBehalfOfAccountId">
                        </div>
                    </div>

                    @if ($errors->has('onBehalfOfAccountId'))
                        <div class="alert alert-danger">
                            <strong>{{$errors->first('onBehalfOfAccountId')}}</strong>
                        </div>
                    @endif

                    <div class="form-group form-auth-account">
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
                        <a href="/auth-account" type="button" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>

            <form action="{{route('store-account')}}" method="POST" class="form-horizontal form-auth" role="form" id="yahoo" name="yahoo"
            @if (!$error_of_yahoo)
                style="display: none"
            @endif
            >
                {{csrf_field()}}
                <h2 class="form-auth-heading">Yahoo</h2>
                <div class="form-warp">
                    <input type="hidden" class="form-control" value="{{ Auth::user()->id }}" id="account_id" name="account_id">
                    <input type="hidden" class="form-control" value="yahoo" id="userAgent" name="userAgent">
                    <div class="form-group form-auth-account">
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

                    <div class="form-group form-auth-account">
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

                    <div class="form-group form-auth-account">
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

                    <div class="form-group form-auth-account">
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
                        <a href="/auth-account" type="button" class="btn btn-default">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
