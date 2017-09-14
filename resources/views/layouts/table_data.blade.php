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
                @foreach($fieldNames as $fieldName)
                    <td>{{ $report->$fieldName }}</td>
                @endforeach
            </tr>
            @endforeach
            <tr>
                <td>Total - all networks</td>
                @foreach($fieldNames as $fieldName)
                    @if($fieldName === 'accountName')
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