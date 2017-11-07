<?php
    if (!isset($export)) {
        $export = false;
    }
?>
<div class="loading-gif-on-table hidden-table"></div>
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
                        <th>
                            {{ __('language.' .str_slug($fieldName,'_')) }}
                        </th>
                    @endforeach
                @else
                    @foreach($fieldNames as $fieldName)
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
                    @if (ctype_digit($report->$fieldName))
                        <td>{{ number_format($report->$fieldName, 0, '', ',') }}</td>
                    @elseif (is_float($report->$fieldName))
                        <td>{{ number_format($report->$fieldName, 2, '.', ',') }}</td>
                    @else
                        <td>{{ $report->$fieldName }}</td>
                    @endif
                @endforeach
            </tr>
            @endforeach
            <tr>
                <td>@lang('language.Total_all_networks')</td>
                @foreach($fieldNames as $fieldName)
                    @if($fieldName === $groupedByField)
                        <?php continue; ?>
                    @endif
                    @if(isset($totalDataArray[$fieldName]))
                    <td>{{ $totalDataArray[$fieldName] }}</td>
                    @else
                    <td></td>
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