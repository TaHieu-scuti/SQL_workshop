<thead>
    <tr>
        @foreach($fieldNames as $fieldName)
        <th>
            <a href="#">{{ $fieldName }}</a>
        </th>
        @endforeach
    </tr>
</thead>
<tbody>
    @foreach($yssAccountReports as $yssAccountReport)
    <tr>
        @if($yssAccountReport->account_id)
            <td>{{ $yssAccountReport->account_id }}</td>
        @endif
        @if($yssAccountReport->cost)
            <td>{{ $yssAccountReport->cost }}</td>
        @endif
        @if($yssAccountReport->impressions)
            <td>{{ $yssAccountReport->impressions }}</td>
        @endif
        @if($yssAccountReport->clicks)
            <td>{{ $yssAccountReport->clicks }}</td>
        @endif
        @if($yssAccountReport->ctr)
            <td>{{ $yssAccountReport->ctr }}</td>
        @endif
        @if($yssAccountReport->averageCpc)
            <td>{{ $yssAccountReport->averageCpc }}</td>
        @endif
        @if($yssAccountReport->averagePosition)
            <td>{{ $yssAccountReport->averagePosition }}</td>
        @endif
        @if($yssAccountReport->invalidClicks)
            <td>{{ $yssAccountReport->invalidClicks }}</td>
        @endif
        @if($yssAccountReport->invalidClickRate)
            <td>{{ $yssAccountReport->invalidClickRate }}</td>
        @endif
        @if($yssAccountReport->impressionShare)
            <td>{{ $yssAccountReport->impressionShare }}</td>
        @endif
        @if($yssAccountReport->exactMatchImpressionShare)
            <td>{{ $yssAccountReport->exactMatchImpressionShare }}</td>
        @endif
        @if($yssAccountReport->budgetLostImpressionShare)
            <td>{{ $yssAccountReport->budgetLostImpressionShare }}</td>
        @endif
        @if($yssAccountReport->qualityLostImpressionShare)
            <td>{{ $yssAccountReport->qualityLostImpressionShare }}</td>
        @endif
        @if($yssAccountReport->trackingURL)
            <td>{{ $yssAccountReport->trackingURL }}</td>
        @endif
        @if($yssAccountReport->conversions)
            <td>{{ $yssAccountReport->conversions }}</td>
        @endif
        @if($yssAccountReport->convRate)
            <td>{{ $yssAccountReport->convRate }}</td>
        @endif
        @if($yssAccountReport->convValue)
            <td>{{ $yssAccountReport->convValue }}</td>
        @endif
        @if($yssAccountReport->costPerConv)
            <td>{{ $yssAccountReport->costPerConv }}</td>
        @endif
        @if($yssAccountReport->valuePerConv)
            <td>{{ $yssAccountReport->valuePerConv }}</td>
        @endif
        @if($yssAccountReport->allConv)
            <td>{{ $yssAccountReport->allConv }}</td>
        @endif
        @if($yssAccountReport->allConvRate)
            <td>{{ $yssAccountReport->allConvRate }}</td>
        @endif
        @if($yssAccountReport->allConvValue)
            <td>{{ $yssAccountReport->allConvValue }}</td>
        @endif
        @if($yssAccountReport->costPerAllConv)
            <td>{{ $yssAccountReport->costPerAllConv }}</td>
        @endif
        @if($yssAccountReport->valuePerAllConv)
            <td>{{ $yssAccountReport->valuePerAllConv }}</td>
        @endif
        @if($yssAccountReport->network)
            <td>{{ $yssAccountReport->network }}</td>
        @endif
        @if($yssAccountReport->device)
            <td>{{ $yssAccountReport->device }}</td>
        @endif
        @if($yssAccountReport->day)
            <td>{{ $yssAccountReport->day }}</td>
        @endif
        @if($yssAccountReport->dayOfWeek)
            <td>{{ $yssAccountReport->dayOfWeek }}</td>
        @endif
        @if($yssAccountReport->quarter)
            <td>{{ $yssAccountReport->quarter }}</td>
        @endif
        @if($yssAccountReport->month)
            <td>{{ $yssAccountReport->month }}</td>
        @endif
        @if($yssAccountReport->week)
            <td>{{ $yssAccountReport->week }}</td>
        @endif
    </tr>
    @endforeach
    <tr>
        <td>Total - all networks</td>
    </tr>
    <tr>
        <td class="paginator">
            {{ $yssAccountReports->links('pagination') }}
        </td>
    </tr>
</tbody>