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
                            {{ $fieldName }}
                        </th>
                    @endforeach
                @else
                    @foreach($fieldNames as $fieldName)
                        @if ($columnSort === $fieldName && $sort === "desc")
                            <th>
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-down"></i>{{ __('language.' .str_slug($fieldName,'_'))}}</a>
                            </th>
                        @elseif ($columnSort === $fieldName && $sort === "asc")
                            <th>
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-up"></i>{{  __('language.' .str_slug($fieldName,'_')) }}</a>
                            </th>
                        @else 
                            <th>
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
                    <td>{{ $report->$fieldName }}</td>
                @endforeach
            </tr>
            @endforeach
            <tr>
                <td>Total - all networks</td>
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