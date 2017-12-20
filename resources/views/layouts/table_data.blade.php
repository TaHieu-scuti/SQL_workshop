@php
    if (!isset($export)) {
        $export = false;
    }
@endphp
@if($reports->total() !== 0)
    <div class="no-data-found-table hidden-no-data-found-message-table">
        <span class="no-data-found-message-table">No data found for table</span>
    </div>
@else
    <div class="no-data-found-table">
        <span class="no-data-found-message-table">No data found for table</span>
    </div>
@endif
<div class="row report-table">
    <div class="col-md-12">
    <table class="table table-striped" id="reportTable">
        <thead>
            <tr>
                @if($export)
                    @foreach($fieldNames as $fieldName)
                        @if($fieldName === "accountid" || $fieldName === "campaignID" || $fieldName === "adgroupID" || $fieldName === "account_id" || $fieldName === 'adType')
                            @continue
                        @endif
                        <th>
                            {{ __('language.' .str_slug($fieldName,'_')) }}
                        </th>
                    @endforeach
                @else
                    @foreach($fieldNames as $fieldName)
                        @if($fieldName === "accountid" || $fieldName === "campaignID" || $fieldName === "adgroupID" || $fieldName === "account_id" || $fieldName === 'adType')
                            @continue
                        @endif
                        @if($groupedByField === 'prefecture' && $fieldName === 'impressionShare')
                            @continue
                        @endif
                        @if($fieldName === 'accountName')
                            <th></th>
                        @endif
                        @if ($columnSort === $fieldName && $sort === "desc")
                            <th data-value="{{ $fieldName }}">
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-down"></i>{{ __('language.' .str_slug($fieldName,'_'))}}</a>
                            </th>
                        @elseif ($columnSort === $fieldName && $sort === "asc")
                            <th data-value="{{ $fieldName }}">
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-up"></i>{{  __('language.' .str_slug($fieldName,'_')) }}</a>
                            </th>
                        @else
                            <th data-value="{{ $fieldName }}">
                                <a href="javascript:void(0)"></i>{{  __('language.' .str_slug($fieldName,'_')) }}</a>
                            </th>
                        @endif
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
                <tr>
                @foreach($fieldNames as $fieldName)
                    @if($fieldName === 'accountid' || $fieldName === "campaignID" || $fieldName === "adgroupID" || $fieldName === 'account_id' || $fieldName === 'adType')
                        @continue
                    @endif
                    @if ($fieldName === 'agencyName')
                            <td>
                                <a href="javascript:void(0)" class="table-redirect"
                                   data-engine = "{{isset($report['engine']) ? $report['engine'] : ''}}"
                                   data-adgainerid = "{{isset($report['account_id']) ? $report['account_id'] : ''}}"
                                   data-id = "{{isset($report['accountid']) ? $report['accountid'] : ''}}"
                                   data-table="agency-report"
                                >{{ $report[$fieldName] }}</a>
                            </td>
                    @elseif ($fieldName === 'clientName')
                        <td>
                            <a href="javascript:void(0)" class="table-redirect"
                            data-engine = "{{isset($report['engine']) ? $report['engine'] : ''}}"
                            data-adgainerid = "{{isset($report['account_id']) ? $report['account_id'] : ''}}"
                            data-id = "{{isset($report['accountid']) ? $report['accountid'] : ''}}"
                            data-table="client-report"
                            >{{ $report[$fieldName] }}</a>
                        </td>
                    @elseif ($fieldName === 'accountName')
                        <td>
                        @if (isset($report['engine']))
                            @if ($report['engine'] === 'adw')
                                <img src="images/adwords.png" width="15px" height="15px" class="iconMedia" >
                            @else
                                <img src="images/yahoo.png" width="15px" height="15px" class="iconMedia" >
                            @endif
                        @endif
                        </td>
                        <td>
                            <a href="javascript:void(0)" class="table-redirect"
                            data-engine = "{{isset($report['engine']) ? $report['engine'] : ''}}"
                            data-adgainerid = "{{isset($report['account_id']) ? $report['account_id'] : ''}}"
                            data-id = "{{isset($report['accountid']) ? $report['accountid'] : ''}}"
                            data-table="account_report"
                            >{{ $report[$fieldName] }}</a>
                        </td>
                    @elseif ($fieldName === 'campaignName' || $fieldName === 'campaign')
                        <td>
                            <a href="javascript:void(0)" class="table-redirect"
                            data-engine = "{{isset($report['engine']) ? $report['engine'] : ''}}"
                            data-id = "{{isset($report['campaignID']) ? $report['campaignID'] : ''}}"
                            data-table="campaign-report">{{ $report[$fieldName] }}</a>
                        </td>
                    @elseif ($fieldName === 'adgroupName' || $fieldName === 'adGroup')
                        <td>
                            <a href="javascript:void(0)" class="table-redirect"
                            data-engine = "{{isset($report['engine']) ? $report['engine'] : ''}}"
                            data-id = "{{isset($report['adgroupID']) ? $report['adgroupID'] : ''}}"
                            data-table="adgroup-report">{{ $report[$fieldName] }}</a>
                        </td>
                    <!-- display ad for YDN -->
                    @elseif ($fieldName === 'adName')
                        <td>
                            <span class="ad-name"> {{ $report['adName'] }}</span><br>
                            <span class="display-url"> {{ $report['displayURL'] }}</span><br>
                            <span> {{ $report['description1'] }}</span>
                        </td>
                    <!-- display ad for Google -->
                    @elseif ($fieldName === 'ad')
                        @if($report['adType'] === 'TEXT_AD')
                        <td>
                            <span class="ad-name">{{ $report['ad'] }}</span><br>
                            <span class="display-url">{{ $report['displayURL'] }}</span><br>
                            <span> {{ $report['description'] }}</span>
                        </td>
                        @elseif($report['adType'] === 'IMAGE_AD')
                        <td>
                            <img class="ad-name" src="{{ $report['ad'] }}" style="width: 50px;height: 20px;"><br>
                            <span class="display-url">{{ $report['displayURL'] }}</span><br>
                            <span> {{ $report['description'] }}</span>
                        </td>
                        @endif
                    @elseif (ctype_digit($report[$fieldName]))
                        <td>{{ number_format($report[$fieldName], 0, '', ',') }}</td>
                    @elseif (($fieldName === 'cost' || $fieldName === 'web_cpa') && is_float($report[$fieldName]))
                        <td><i class="fa fa-rmb"></i>{{ number_format($report[$fieldName], 0, '', ',') }}</td>
                    @elseif ($fieldName === 'averageCpc')
                        <td><i class="fa fa-rmb"></i>{{ number_format($report[$fieldName], 2, '.', ',') }}</td>
                    @elseif (
                        is_float($report[$fieldName])
                        && ($fieldName === 'ctr'
                        || $fieldName === 'impressionShare')
                        || $fieldName === 'web_cvr')
                        <td>{{ number_format($report[$fieldName], 2, '.', ',') }}%</td>
                    @elseif (
                        $fieldName === 'averagePosition'
                        || $fieldName === 'call_cvr'
                        || $fieldName === 'call_cpa')
                        <td>{{ number_format($report[$fieldName], 2, '.', ',') }}</td>
                    @else
                        <td>{{ $report[$fieldName] }}</td>
                    @endif
                @endforeach
            </tr>
            @endforeach
            <tr>
                <?php
                $columnNames = [
                    'accountName',
                    'campaignName',
                    'campaign',
                    'adgroupName',
                    'adGroup',
                    'keyword',
                    'adName',
                    'ad',
                    'matchType'
                ];
                $totalColspan = 0;
                foreach ($fieldNames as $value) {
                    if (in_array($value, $columnNames)) {
                        $totalColspan ++;
                    }
                }
                ?>
                @if (in_array('accountName', $fieldNames))
                    <td></td>
                @endif
                <td colspan="{{ $totalColspan }}">@lang('language.Total_all_networks')</td>
                @foreach($fieldNames as $fieldName)
                    @if($fieldName === $groupedByField
                        || $fieldName === "accountid"
                        || $fieldName === "account_id"
                        || $fieldName === "campaignID"
                        || $fieldName === "adgroupID"
                        || $fieldName === "campaignName"
                        || $fieldName === "campaign")
                        @continue
                    @endif
                    @if(isset($totalDataArray->$fieldName))
                        @if (ctype_digit($totalDataArray->$fieldName))
                    <td>{{ number_format($totalDataArray->$fieldName, 0, '', ',') }}</td>
                        @elseif (($fieldName === 'cost' || $fieldName === 'web_cpa') && is_float($totalDataArray->$fieldName))
                    <td><i class="fa fa-rmb"></i>{{ number_format($totalDataArray->$fieldName, 0, '', ',') }}</td>
                        @elseif ($fieldName === 'averageCpc')
                    <td><i class="fa fa-rmb"></i>{{ number_format($totalDataArray->$fieldName, 2, '.', ',') }}</td>
                        @elseif (is_float($totalDataArray->$fieldName) && ($fieldName === 'ctr' || $fieldName === 'impressionShare' || $fieldName === 'web_cvr'))
                    <td>{{ number_format($totalDataArray->$fieldName, 2, '.', ',') }}%</td>
                        @elseif (is_float($totalDataArray->$fieldName) || $fieldName === 'call_cvr' || $fieldName === 'call_cpa')
                    <td>{{ number_format($totalDataArray->$fieldName, 2, '.', ',') }}</td>
                        @else
                    <td>{{ $totalDataArray->$fieldName }}</td>
                        @endif
                    @endif
                @endforeach
            </tr>

            @if (!$export)
            <tr>
                <td class="paginator">
                    {{ $reports->links('pagination') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    </div>
</div>
