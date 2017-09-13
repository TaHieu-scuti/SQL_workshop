<?php
    if (!isset($export)) {
        $export = false;
    }
?>

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
                    @if ($columnSort === 'accountName' && $sort === "desc")
                            <th>
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-down"></i>accountName</a>
                            </th>
                        @elseif ($columnSort === 'accountName' && $sort === "asc")
                            <th>
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-up"></i>accountName</a>
                            </th>
                        @else 
                            <th>
                                <a href="javascript:void(0)"></i>accountName</a>
                            </th>
                        @endif
                    @foreach($fieldNames as $fieldName)
                        @if ($columnSort === $fieldName && $sort === "desc")
                            <th>
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-down"></i>{{ $fieldName }}</a>
                            </th>
                        @elseif ($columnSort === $fieldName && $sort === "asc")
                            <th>
                                <a href="javascript:void(0)">
                                <i class="fa fa-arrow-up"></i>{{ $fieldName }}</a>
                            </th>
                        @else 
                            <th>
                                <a href="javascript:void(0)"></i>{{ $fieldName }}</a>
                            </th>
                        @endif
                    @endforeach
                @endif
            </tr>
        </thead>
        <tbody>
           @foreach($reports as $report)
            <tr>
                <td>{{ $report->repoYssAccounts->accountName }}</td>
                @foreach($fieldNames as $fieldName)
                    <td>{{ $report->$fieldName }}</td>
                @endforeach
            </tr>
            @endforeach
            <tr>
                <td>Total - all networks</td>
                @foreach($fieldNames as $fieldName)
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