@extends('authAccount.auth_layout')
@section('filter-layout')
    <div class="container" id="content-auth-account">
        <input type="hidden" id="account_id" value="{{ $account_id }}">
        <div class="menu_left col-lg-3">
            <a class="btn btn-primary" href="#adw_form">GOOGLE ADWORDS</a>
            <a class="btn btn-primary" href="#ydn_form">YAHOO DISPLAY NETWORK</a>
            <a class="btn btn-primary" href="#yss_form">YAHOO SPONSORED SEARCH</a>
        </div>

        <div class="menu_right col-lg-7">

<!-- ADW Form -->
                <form class="form-horizontal form-auth" role="form" id="adw_form" name="google" >
                    <h2 class="form-auth-heading">Google Adwords</h2>
                    <div class="form-warp">
                        <input type="hidden" class="form-control" value="{{ isset($adw_record->id) ? $adw_record->id : '' }}
                        " id="adw_id" name="adw_id">

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">User Agent</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="userAgent" name="userAgent" placeholder="UserAgent" value="{{ isset($adw_record->userAgent) ? $adw_record->userAgent : '' }}">
                            </div>
                        </div>

                        <div id="error_userAgent"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">DeveloperToken</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="developerToken" name="developerToken" placeholder="DeveloperToken" value="{{ isset($adw_record->developerToken) ? $adw_record->developerToken : '' }}">
                            </div>
                        </div>

                        <div id="error_developerToken"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">ClientCustomerId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="clientCustomerId" name="clientCustomerId" placeholder="ClientCustomerId" value="{{ isset($adw_record->clientCustomerId) ? $adw_record->clientCustomerId : '' }}">
                            </div>
                        </div>

                        <div id="error_clientCustomerId"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">OnBehalfOfAccountId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="onBehalfOfAccountId" name="onBehalfOfAccountId" placeholder="OnBehalfOfAccountId" value="{{ isset($adw_record->onBehalfOfAccountId) ? $adw_record->onBehalfOfAccountId : '' }}">
                            </div>
                        </div>

                        <div id="error_onBehalfOfAccountId"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">OnBehalfOfPassword</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="password" id="onBehalfOfPassword" name="onBehalfOfPassword" value="{{ isset($adw_record->onBehalfOfPassword) ? $adw_record->onBehalfOfPassword : '' }}" />
                            </div>
                        </div>

                        <div id="error_onBehalfOfPassword"></div>

                        <div class="col-sm-offset-2 col-sm-8">
                            <button type="button" class="btn btn-primary" onclick="updateAdwApi()">Save</button>
                        </div>
                    </div>
                </form>

<!-- YDN Form-->
                <form class="form-horizontal form-auth" role="form" id="ydn_form" name="ydn_form">
                    <h2 class="form-auth-heading">Yahoo Display Network</h2>
                    <div class="form-warp">
                        <input type="hidden" class="form-control" value="{{ isset($ydn_record->id) ? $ydn_record->id : '' }}
                        " id="ydn_id" name="ydn_id">

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">User Agent</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="userAgent" name="userAgent" placeholder="UserAgent" value="{{ isset($ydn_record->userAgent) ? $ydn_record->userAgent : '' }}">
                            </div>
                        </div>

                        <div id="error_userAgent"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">License</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="license" name="license" placeholder="License" value="{{ isset($ydn_record->license) ? $ydn_record->license : '' }}">
                            </div>
                        </div>

                        <div id="error_license"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">AccountId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="accountId" name="accountId" placeholder="AccountId" value="{{ isset($ydn_record->accountId) ? $ydn_record->accountId : '' }}">
                            </div>
                        </div>

                        <div id="error_accountId"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">APIAccountId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="apiAccountId" name="apiAccountId" placeholder="apiAccountId" value="{{ isset($ydn_record->apiAccountId) ? $ydn_record->apiAccountId : '' }}">
                            </div>
                        </div>

                        <div id="error_apiAccountId"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">APIAccountPassword</label>
                            <div class="col-sm-8">
                                <input  class="form-control" type="password" id="apiAccountPassword" name="apiAccountPassword" value="{{ isset($ydn_record->apiAccountPassword) ? $ydn_record->apiAccountPassword : '' }}" />
                            </div>
                        </div>

                        <div id="error_apiAccountPassword"></div>

                        <div class="col-sm-offset-2 col-sm-8">
                            <button type="button" class="btn btn-primary" onclick="updateYdnApi()">Save</button>
                        </div>
                    </div>
                </form>

<!-- YSS Form -->
                <form class="form-horizontal form-auth" role="form" id="yss_form" name="yss_form">
                    <h2 class="form-auth-heading">Yahoo Sponsored Search</h2>
                    <div class="form-warp">
                        <input type="hidden" class="form-control" value="{{ isset($yss_record->id) ? $yss_record->id : '' }}
                        " id="yss_id" name="yss_id">

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">User Agent</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="userAgent" name="userAgent" placeholder="UserAgent" value="{{ isset($yss_record->userAgent) ? $yss_record->userAgent : '' }}">
                            </div>
                        </div>

                        <div id="error_userAgent"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">License</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="license" name="license" placeholder="License" value="{{ isset($yss_record->license) ? $yss_record->license : '' }}">
                            </div>
                        </div>

                        <div id="error_license"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">AccountId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="accountId" name="accountId" placeholder="AccountId" value="{{ isset($yss_record->accountId) ? $yss_record->accountId : '' }}">
                            </div>
                        </div>

                        <div id="error_accountId"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">APIAccountId</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="apiAccountId" name="apiAccountId" placeholder="apiAccountId" value="{{ isset($yss_record->apiAccountId) ? $yss_record->apiAccountId : '' }}">
                            </div>
                        </div>

                        <div id="error_apiAccountId"></div>

                        <div class="form-group form-auth-account">
                            <label class="control-label col-sm-4" for="">APIAccountPassword</label>
                            <div class="col-sm-8">
                                <input  class="form-control" type="password" id="apiAccountPassword" name="apiAccountPassword" value="{{ isset($yss_record->apiAccountPassword) ? $yss_record->apiAccountPassword : '' }}" />
                            </div>
                        </div>

                        <div id="error_apiAccountPassword"></div>

                        <div class="col-sm-offset-2 col-sm-8">
                            <button type="button" class="btn btn-primary" onclick="updateYssApi()">Save</button>
                        </div>
                    </div>
                </form>

        </div>

        <div class="menu_right menu_redirect col-lg-2">
            @if ($isAgency)
                <a href="/client-report" class="btn btn-primary">Client page</a>
                <a href="/auth-account" class="btn btn-danger">Cancel</a>
            @else
                <a href="/account_report" class="btn btn-primary" id="btn_cancel" style="display: {{ $isEmptyApi ? 'none' : 'block' }}">Account page</a>
            @endif
        </div>

    </div>
@stop