<div class="row report-table">
                    <div class="col-md-12">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th class="Account">
                                <a href="#">Account</a>
                            </th>
                            <th class="Clicks">
                                <a href="#">Clicks</a>
                            </th>
                            <th class="Impr">
                                <a href="#">Impr</a>
                            </th>
                            <th class="Cost">
                                <a href="#">Cost</a>
                            </th>
                            <th class="CTR">
                                <a href="#">CTR</a>
                            </th>
                            <th class="AvgCPC">
                                <a href="#">AvgCPC</a>
                            </th>
                            <th class="AvgPos">
                                <a href="#">Avg Pos</a>
                            </th>
                            <th class="InvalidClicks">
                                <a href="#">InvalidClicks</a>
                            </th>
                            <th class="InvalidClickRate">
                                <a href="#">InvalidClickRate</a>
                            </th>
                            <th class="ImpressionShare">
                                <a href="#">ImpressionShare</a>
                            </th>
                            <th class="ExactMatchImpressionShare">
                                <a href="#">ExactMatchImpressionShare</a>
                            </th>
                            <th class="BudgetLostImpressionShare">
                                <a href="#">BudgetLostImpressionShare</a>
                            </th>
                            <th class="QualityLostImpressionShare">
                                <a href="#">QualityLostImpressionShare</a>
                            </th>
                            <th class="TrackingURL">
                                <a href="#">TrackingURL</a>
                            </th>
                            <th class="Conversions">
                                <a href="#">Conversions</a>
                            </th>
                            <th class="ConvRate">
                                <a href="#">ConvRate</a>
                            </th>
                            <th class="ConvValue">
                                <a href="#">ConvValue</a>
                            </th>
                            <th class="CostPerConv">
                                <a href="#">CostPerConv</a>
                            </th>
                            <th class="ValuePerConv">
                                <a href="#">ValuePerConv</a>
                            </th>
                            <th class="AllConv">
                                <a href="#">AllConv</a>
                            </th>
                            <th class="AllConvRate">
                                <a href="#">AllConvRate</a>
                            </th>
                            <th class="AllConvValue">
                                <a href="#">AllConvValue</a>
                            </th>
                            <th class="CostPerAllConv">
                                <a href="#">CostPerAllConv</a>
                            </th>
                            <th class="ValuePerAllConv">
                                <a href="#">ValuePerAllConv</a>
                            </th>
                            <th class="Network">
                                <a href="#">Network</a>
                            </th>
                            <th class="Device">
                                <a href="#">Device</a>
                            </th>
                            <th class="Day">
                                <a href="#">Day</a>
                            </th>
                            <th class="DayOfWeek">
                                <a href="#">DayOfWeek</a>
                            </th>
                            <th class="Quarter">
                                <a href="#">Quarter</a>
                            </th>
                            <th class="Month">
                                <a href="#">Month</a>
                            </th>
                            <th class="Week">
                                <a href="#">Week</a>
                            </th>
                    </tr>
                        </thead>
                    <tbody>
                        @foreach($yssAccountReports as $yssAccountReport)
                        <tr>
                            <td class="Account">{{ $yssAccountReport->account_id }}</td>
                            <td class="Clicks">{{ $yssAccountReport->clicks }}</td>
                            <td class="Impr">{{ $yssAccountReport->impressions }}</td>
                            <td class="Cost">{{ $yssAccountReport->cost }}</td>
                            <td class="CTR">{{ $yssAccountReport->ctr }}</td>
                            <td class="AvgCPC">{{ $yssAccountReport->averageCpc }}</td>
                            <td class="AvgPos">{{ $yssAccountReport->averagePosition }}</td>
                            <td class="InvalidClicks">{{ $yssAccountReport->invalidClicks }}</td>
                            <td class="InvalidClickRate">{{ $yssAccountReport->invalidClickRate }}</td>
                            <td class="ImpressionShare">{{ $yssAccountReport->impressionShare }}</td>
                            <td class="ExactMatchImpressionShare">{{ $yssAccountReport->exactMatchImpressionShare }}</td>
                            <td class="BudgetLostImpressionShare">{{ $yssAccountReport->budgetLostImpressionShare }}</td>
                            <td class="QualityLostImpressionShare">{{ $yssAccountReport->qualityLostImpressionShare }}</td>
                            <td class="TrackingURL">{{ $yssAccountReport->trackingURL }}</td>
                            <td class="Conversions">{{ $yssAccountReport->conversions }}</td>
                            <td class="ConvRate">{{ $yssAccountReport->convRate }}</td>
                            <td class="ConvValue">{{ $yssAccountReport->convValue }}</td>
                            <td class="CostPerAllConv">{{ $yssAccountReport->costPerConv }}</td>
                            <td class="ValuePerConv">{{ $yssAccountReport->valuePerConv }}</td>
                            <td class="AllConv">{{ $yssAccountReport->allConv }}</td>
                            <td class="AllConvRate">{{ $yssAccountReport->allConvRate }}</td>
                            <td class="AllConvValue">{{ $yssAccountReport->allConvValue }}</td>
                            <td class="CostPerConv">{{ $yssAccountReport->costPerAllConv }}</td>
                            <td class="ValuePerAllConv">{{ $yssAccountReport->valuePerAllConv }}</td>
                            <td class="Network">{{ $yssAccountReport->network }}</td>
                            <td class="Device">{{ $yssAccountReport->device }}</td>
                            <td class="Day">{{ $yssAccountReport->day }}</td>
                            <td class="DayOfWeek">{{ $yssAccountReport->dayOfWeek }}</td>
                            <td class="Quarter">{{ $yssAccountReport->quarter }}</td>
                            <td class="Month">{{ $yssAccountReport->month }}</td>
                            <td class="Week">{{ $yssAccountReport->week }}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>Total - all networks</td>
                            <td class="Clicks"></td>
                            <td class="Impr"></td>
                            <td class="Cost"></td>
                            <td class="CTR"></td>
                            <td class="AvgCPC"></td>
                            <td class="AvgPos"></td>
                            <td class="InvalidClicks"></td>
                            <td class="InvalidClickRate"></td>
                            <td class="ImpressionShare"></td>
                            <td class="ExactMatchImpressionShare"></td>
                            <td class="BudgetLostImpressionShare"></td>
                            <td class="QualityLostImpressionShare"></td>
                            <td class="TrackingURL"></td>
                            <td class="Conversions"></td>
                            <td class="ConvRate"></td>
                            <td class="ConvValue"></td>
                            <td class="CostPerAllConv"></td>
                            <td class="ValuePerConv"></td>
                            <td class="AllConv"></td>
                            <td class="AllConvRate"></td>
                            <td class="AllConvValue"></td>
                            <td class="CostPerConv"></td>
                            <td class="ValuePerAllConv"></td>
                            <td class="Network"></td>
                            <td class="Device"></td>
                            <td class="Day"></td>
                            <td class="DayOfWeek"></td>
                            <td class="Quarter"></td>
                            <td class="Month"></td>
                            <td class="Week"></td>
                        </tr><tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                    </tbody>
                    </table>
                    </div>
                </div>