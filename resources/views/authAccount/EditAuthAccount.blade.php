@extends('authAccount.auth_layout')
@section('filter-layout')
    <div class="container">
        <div class="menu_left col-lg-2">
            <select id="myselect" class="form-control" disabled="true">
                @if ($authAccount->developerToken !== null)
                    <option value="google" selected="selected">Google</option>
                @else
                    <option value="yahoo" selected="selected">Yahoo</option>
                @endif
            </select>
        </div>

        <div class="menu_right col-lg-10">

            @if ($authAccount->developerToken !== null)
                <form action="{{route('update-account',$authAccount->id)}}" method="POST" class="form-horizontal form-auth" role="form" id="google" name="google" >
                    {{csrf_field()}}
                    <h2 class="form-auth-heading">Google</h2>
                    <div class="form-warp">
                        <input type="hidden" class="form-control" value="{{ Auth::user()->id }}" id="account_id" name="account_id">
                        <input type="hidden" class="form-control" value="{{ $authAccount->userAgent }}" id="userAgent" name="userAgent">

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">DeveloperToken</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="developerToken" name="developerToken" placeholder="DeveloperToken" value="{{ $authAccount->developerToken }}">
                            </div>
                        </div>

                        @if ($errors->has('developerToken'))
                            <div class="alert alert-danger">
                                <strong>{{$errors->first('developerToken')}}</strong>
                            </div>
                        @endif

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">ClientCustomerId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="clientCustomerId" name="clientCustomerId" placeholder="ClientCustomerId" value="{{$authAccount->clientCustomerId}}">
                            </div>
                        </div>

                        @if ($errors->has('ClientCustomerId'))
                            <div class="alert alert-danger">
                                <strong>{{$errors->first('ClientCustomerId')}}</strong>
                            </div>
                        @endif

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">OnBehalfOfAccountId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="onBehalfOfAccountId" name="onBehalfOfAccountId" placeholder="OnBehalfOfAccountId" value="{{$authAccount->onBehalfOfAccountId}}">
                            </div>
                        </div>

                        @if ($errors->has('OnBehalfOfAccountId'))
                            <div class="alert alert-danger">
                                <strong>{{$errors->first('OnBehalfOfAccountId')}}</strong>
                            </div>
                        @endif

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">OnBehalfOfPassword</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" rows="5" id="onBehalfOfPassword" name="onBehalfOfPassword">{{$authAccount->onBehalfOfPassword}}</textarea>
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
            @else
                <form action="{{route('update-account',['id' => $authAccount->id])}}" method="POST" class="form-horizontal form-auth" role="form" id="yahoo" name="yahoo">
                    {{csrf_field()}}
                    <h2 class="form-auth-heading">Yahoo</h2>
                    <div class="form-warp">
                        <input type="hidden" class="form-control" value="{{ Auth::user()->id }}" id="account_id" name="account_id">
                        <input type="hidden" class="form-control" value="{{ $authAccount->userAgent }}" id="userAgent" name="userAgent">
                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">License</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="license" name="license" placeholder="License" value="{{$authAccount->license}}">
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
                                <input type="text" class="form-control" id="accountId" name="accountId" placeholder="AccountId" value="{{$authAccount->accountId}}">
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
                                <input type="text" class="form-control" id="apiAccountId" name="apiAccountId" placeholder="apiAccountId" value="{{$authAccount->apiAccountId}}">
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
                                <textarea  class="form-control" rows="5" id="apiAccountPassword" name="apiAccountPassword">{{$authAccount->apiAccountPassword}}</textarea>
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
            @endif

        </div>
    </div>
@stop
