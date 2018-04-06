<?php

namespace App\Model;

use App\Model\AbstractTemporaryModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Exception;

abstract class AbstractTemporaryAccountModel extends AbstractTemporaryModel
{
    const TEMPORARY_ACCOUNT_TABLE = 'temporary_account_table';
    const ACCOUNT_ID = 'account_id';

    const FIELDS_TYPE_BIGINT = [
        'client_id'
    ];

    const FIELDS_TYPE_STRING = [
        'account_id',
        'accountid',
        'accountName',
        'dayOfWeek',
        'prefecture',
        'device'
    ];

    const FIELDS_TYPE_INT = [
        'call_cv',
        'web_cv',
        'total_cv',
        'ydn_web_cv',
        'yss_web_cv',
        'adw_web_cv',
        'ydn_call_cv',
        'yss_call_cv',
        'adw_call_cv',
        'dailySpendingLimit',

    ];

    const FIELDS_TO_CHECK = [
        'clicks',
        'cost',
        'impressions',
        'ctr',
        'averageCpc',
        'averagePosition',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'web_cv',
        'web_cvr',
        'web_cpa',
    ];

    const SUM_FIELDS_OF_ENGINES = [
        'ydn_web_cv',
        'yss_web_cv',
        'adw_web_cv',
        'ydn_call_cv',
        'yss_call_cv',
        'adw_call_cv'
    ];

    const SUM_FIELDS_FOR_AGENCY_OF_ENGINES = [
        'ydn_web_cv',
        'yss_web_cv',
        'adw_web_cv'
    ];

    const AVERAGE_FIELDS_OF_ENGINES = [
        'ydn_web_cvr',
        'ydn_web_cpa',
        'yss_web_cvr',
        'yss_web_cpa',
        'adw_web_cvr',
        'adw_web_cpa',
        'ydn_call_cvr',
        'ydn_call_cpa',
        'yss_call_cvr',
        'yss_call_cpa',
        'adw_call_cvr',
        'adw_call_cpa',
    ];

    const AVERAGE_FIELDS_FOR_AGENCY_OF_ENGINES = [
        'ydn_web_cvr',
        'ydn_web_cpa',
        'yss_web_cvr',
        'yss_web_cpa',
        'adw_web_cvr',
        'adw_web_cpa'
    ];

    const DEFAULT_COLUMNS = [
        'account_id',
        'accountName',
        'impressions',
        'cost',
        'clicks',
        'ctr',
        'averageCpc',
        'averagePosition',
        'ydn_web_cv',
        'ydn_web_cvr',
        'ydn_web_cpa',
        'yss_web_cv',
        'yss_web_cvr',
        'yss_web_cpa',
        'adw_web_cv',
        'adw_web_cvr',
        'adw_web_cpa',
        'ydn_call_cv',
        'ydn_call_cvr',
        'ydn_call_cpa',
        'yss_call_cv',
        'yss_call_cvr',
        'yss_call_cpa',
        'adw_call_cv',
        'adw_call_cvr',
        'adw_call_cpa',
    ];

    const COLUMNS_NOT_MAKE = [
        'web_cv',
        'web_cvr',
        'web_cpa',
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];

    const UNSET_COLUMNS_FOR_AGENCY = [
        'call_cv',
        'call_cvr',
        'call_cpa',
        'total_cv',
        'total_cvr',
        'total_cpa'
    ];

    const GROUP_FIELDS_CALCULATE = [
        'ydn_call_cvr',
        'ydn_call_cpa',
        'yss_call_cvr',
        'yss_call_cpa',
        'adw_call_cvr',
        'adw_call_cpa',
        'ydn_call_cv',
        'yss_call_cv',
        'adw_call_cv',
        'call_cv',
        'call_cvr',
        'call_cpa'
    ];

    protected function createTemporaryAccountTable($agency = "")
    {
        $fieldNames = self::DEFAULT_COLUMNS;
        if ($agency !== "") {
            array_unshift($fieldNames, 'client_id');
        }
        array_unshift($fieldNames, 'ptu_id');

        Schema::create(
            self::TEMPORARY_ACCOUNT_TABLE,
            function (Blueprint $table) use ($fieldNames) {
                $table->increments('id');
                foreach ($fieldNames as $fieldName) {
                    if (in_array($fieldName, self::FIELDS_TYPE_BIGINT)) {
                        $table->bigInteger($fieldName)->nullable();
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_INT)) {
                        $table->integer($fieldName)->nullable();
                    } elseif (in_array($fieldName, self::SUM_FIELDS)) {
                        $table->integer('ydn_'.$fieldName)->nullable();
                        $table->integer('yss_'.$fieldName)->nullable();
                        $table->integer('adw_'.$fieldName)->nullable();
                    } elseif (in_array($fieldName, self::AVERAGE_FIELDS)) {
                        $table->double('ydn_'.$fieldName)->nullable();
                        $table->double('yss_'.$fieldName)->nullable();
                        $table->double('adw_'.$fieldName)->nullable();
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_STRING)) {
                        $table->string($fieldName)->nullable();
                    } elseif ($fieldName === 'day') {
                        $table->dateTime($fieldName)->nullable();
                    } else {
                        $table->double($fieldName)->nullable();
                    }
                }
                $table->temporary();
            }
        );
    }

    protected function getAccountYss($startDay, $endDay, $agency = "")
    {
        $modelYssAccount = new RepoYssAccountReportCost;
        $column = $this->checkIssetVariable($agency);
        $fieldNames = $this->updateColumnsForAgency();

        $yssAccountAgency = $modelYssAccount->getYssAccountAgency(
            $fieldNames,
            $startDay,
            $endDay,
            $agency
        );
        $rawExpressions = $this->setFieldNameToUpdate($fieldNames, 'yss');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($yssAccountAgency).')AS tbl set '.implode(', ', $rawExpressions).' where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.'. $column.' = tbl.account_id'
        );

        $this->updatePhoneTimeUseId($startDay, $endDay, $column, 'yss');
    }

    protected function getAccountYdn($startDay, $endDay, $agency = "")
    {
        $modelYdnAccount = new RepoYdnReport;
        $column = $this->checkIssetVariable($agency);
        $fieldNames = $this->updateColumnsForAgency();

        $ydnAccountAgency = $modelYdnAccount->getYdnAccountAgency(
            $fieldNames,
            $startDay,
            $endDay,
            $agency
        );

        $rawExpressions = $this->setFieldNameToUpdate($fieldNames, 'ydn');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($ydnAccountAgency).')AS tbl set '.implode(', ', $rawExpressions).' where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.'.$column.' = tbl.account_id'
        );

        $this->updatePhoneTimeUseId($startDay, $endDay, $column, 'ydn');
    }

    protected function getAccountAdw($startDay, $endDay, $agency = "")
    {
        $modelAdwAccount = new RepoAdwAccountReportCost;
        $column = $this->checkIssetVariable($agency);

        $fieldNames = $this->updateColumnsForAgency();

        $adwAccountAgency = $modelAdwAccount->getAdwAccountAgency(
            $fieldNames,
            $startDay,
            $endDay,
            $agency
        );

        $rawExpressions = $this->setFieldNameToUpdate($fieldNames, 'adw');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($adwAccountAgency).')AS tbl set '.implode(', ', $rawExpressions).' where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.'.$column.' = tbl.account_id'
        );

        $this->updatePhoneTimeUseId($startDay, $endDay, $column, 'adw');
    }

    protected function updatePhoneTimeUseId($startDay, $endDay, $column, $engine)
    {
        $model = new PhoneTimeUse;

        $phoneTimeUseIds = $model->getPhoneTimeUseId($startDay, $endDay, $engine);

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($phoneTimeUseIds).')AS tbl set '.$engine.'_ptu_id = tbl.id where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.'.$column.' = tbl.account_id'
        );
    }

    private function updateColumnsForAgency()
    {
        $fieldNames = static::SUBQUERY_FIELDS;
        return array_values($this->unsetColumns($fieldNames, self::UNSET_COLUMNS_FOR_AGENCY));
    }

    private function setFieldNameToUpdate($fieldNames, $engine)
    {
        $rawExpressions = [];
        foreach ($fieldNames as $fieldName) {
            if (in_array($fieldName, self::FIELDS_TO_CHECK)) {
                $rawExpressions[] = DB::raw($engine.'_'.$fieldName.' = tbl.'.$fieldName);
            }
        }

        return $rawExpressions;
    }

    protected function getRawExpressionToCalculateAgencyReportFromTemporaryTable($fieldName)
    {
        if ($fieldName === 'ctr') {
            $rawExpression = DB::raw(
                'IFNULL((AVG(ydn_ctr) + AVG(yss_ctr) + AVG(adw_ctr)) / 3, 0) AS ctr'
            );
        } elseif ($fieldName === 'averageCpc') {
            $rawExpression = DB::raw(
                'IFNULL((AVG(ydn_averageCpc) + AVG(yss_averageCpc) + AVG(adw_averageCpc)) / 3, 0) '
                . 'AS averageCpc'
            );
        } elseif ($fieldName === 'averagePosition') {
            $rawExpression = DB::raw(
                'IFNULL((AVG(ydn_averagePosition) + AVG(yss_averagePosition) + '
                . 'AVG(adw_averagePosition)) / 3, 0) AS averagePosition'
            );
        } elseif (in_array($fieldName, self::SUM_FIELDS_FOR_AGENCY_OF_ENGINES)) {
            $rawExpression = DB::raw(
                'IFNULL(SUM('.$fieldName.'), 0) AS '.$fieldName
            );
        } elseif (in_array($fieldName, self::AVERAGE_FIELDS_FOR_AGENCY_OF_ENGINES)) {
            $rawExpression = DB::raw(
                'IFNULL(AVG('.$fieldName.'), 0) AS '.$fieldName
            );
        } elseif ($fieldName === 'web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_web_cv) + SUM(yss_web_cv) + SUM(adw_web_cv), 0) AS web_cv'
            );
        } elseif ($fieldName === 'web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_web_cv) + SUM(yss_web_cv) + SUM(adw_web_cv)) / '
                . '(SUM(ydn_clicks) + SUM(yss_clicks) + SUM(adw_clicks)), 0) AS web_cvr'
            );
        } elseif ($fieldName === 'web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_cost) + SUM(yss_cost) + SUM(adw_cost)) / '
                . '(SUM(ydn_web_cv) + SUM(yss_web_cv) + SUM(adw_web_cv)), 0) AS web_cpa'
            );
        } elseif (in_array($fieldName, self::GROUP_FIELDS_CALCULATE)) {
            $rawExpression = $this->groupFieldsBeingCalculatedForAgency($fieldName);
        } elseif ($fieldName === 'total_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_ptu_id) + SUM(yss_ptu_id) + SUM(adw_ptu_id) + SUM(ydn_web_cv) + '
                . 'SUM(yss_web_cv) + SUM(adw_web_cv), 0) AS total_cv'
            );
        } elseif ($fieldName === 'total_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_ptu_id) + SUM(yss_ptu_id) + SUM(adw_ptu_id) + SUM(ydn_web_cv) + '
                . 'SUM(yss_web_cv) + SUM(adw_web_cv)) / '
                . '(SUM(ydn_clicks) + SUM(yss_clicks) + SUM(adw_clicks)), 0) AS total_cvr'
            );
        } elseif ($fieldName === 'total_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_cost) + SUM(yss_cost) + SUM(adw_cost)) / '
                . '(SUM(ydn_ptu_id) + SUM(yss_ptu_id) + SUM(adw_ptu_id) + SUM(ydn_web_cv) + '
                . 'SUM(yss_web_cv) + SUM(adw_web_cv)), 0) AS total_cpa'
            );
        } elseif (in_array($fieldName, static::SUM_FIELDS)) {
            $rawExpression = 'IFNULL(SUM('
                . $this->getSummedFieldNamesForTableAliasesTemporary($fieldName)
                . '), 0) AS ' . $fieldName;
        } elseif (in_array($fieldName, static::AVERAGE_FIELDS)) {
            $rawExpression = 'IFNULL(AVG('
                . $this->getSummedFieldNamesForTableAliasesTemporary($fieldName)
                . '), 0) AS ' . $fieldName;
        } else {
            throw new \InvalidArgumentException('Unsupported field name provided: `' . $fieldName . '`!');
        }

        return DB::raw($rawExpression);
    }

    public function getAggregatedForAgencyReportFromTemporaryTable(
        array $fieldNames,
        $accountNameAlias = 'clientName'
    ) {
        $arrayCalculate = [];
        $tableName = self::TEMPORARY_ACCOUNT_TABLE;
        foreach ($fieldNames as $fieldName) {
            if ($fieldName === 'accountName') {
                $arrayCalculate[] = DB::raw($tableName . '.' . $fieldName . ' AS ' . $accountNameAlias);
            }
            if ($fieldName === self::FOREIGN_KEY_YSS_ACCOUNTS) {
                $arrayCalculate[] = DB::raw($tableName.'.account_id AS account_id');
            }

            if (in_array($fieldName, array_merge(static::AVERAGE_FIELDS, static::SUM_FIELDS))) {
                $arrayCalculate = $this->groupSumFieldsAndAverageFields($arrayCalculate, $fieldName);
            } elseif (in_array(
                $fieldName,
                array_merge(
                    self::SUM_FIELDS_FOR_AGENCY_OF_ENGINES,
                    self::AVERAGE_FIELDS_FOR_AGENCY_OF_ENGINES
                )
            )) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL('.$fieldName.', 0) AS '.$fieldName
                );
            } elseif ($fieldName === 'web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_web_cv + yss_web_cv + adw_web_cv, 0) AS web_cv'
                );
            } elseif ($fieldName === 'web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_web_cv + yss_web_cv + adw_web_cv) / '
                    . '(ydn_clicks + yss_clicks + adw_clicks), 0) AS web_cvr'
                );
            } elseif ($fieldName === 'web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_cost + yss_cost + adw_cost) / '
                    . '(ydn_web_cv + yss_web_cv + adw_web_cv), 0) AS web_cpa'
                );
            } elseif (in_array($fieldName, self::GROUP_FIELDS_CALCULATE)) {
                $arrayCalculate = $this->groupFieldsBeingDisplayedOnTableForAgency($arrayCalculate, $fieldName);
            } elseif ($fieldName === 'total_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((yss_ptu_id) + (ydn_ptu_id) + (adw_ptu_id) + ydn_web_cv + '
                    . 'yss_web_cv + adw_web_cv, 0) AS total_cv'
                );
            } elseif ($fieldName === 'total_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(((yss_ptu_id) + (ydn_ptu_id) + (adw_ptu_id)'
                    . ' + ydn_web_cv + yss_web_cv + adw_web_cv) / '
                    . '(ydn_clicks + yss_clicks + adw_clicks), 0) AS total_cvr'
                );
            } elseif ($fieldName === 'total_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_cost + yss_cost + adw_cost) / '
                    . '((yss_ptu_id) + (ydn_ptu_id) + (adw_ptu_id) + '
                    . 'ydn_web_cv + yss_web_cv + adw_web_cv), 0) AS total_cpa'
                );
            }
        }

        return $arrayCalculate;
    }

    private function groupSumFieldsAndAverageFields($arrayCalculate, $fieldName)
    {
        if (in_array($fieldName, static::AVERAGE_FIELDS)) {
            $arrayCalculate[] = DB::raw(
                'IFNULL((adw_'. $fieldName .' +  ydn_'.$fieldName.' + yss_'.$fieldName.')/3, 0) AS '.$fieldName
            );
        } elseif (in_array($fieldName, static::SUM_FIELDS)) {
            $arrayCalculate[] = DB::raw(
                'IFNULL(adw_'. $fieldName . ' +  ydn_' . $fieldName . ' + yss_' . $fieldName . ', 0)'
                . ' AS '. $fieldName
            );
        }

        return $arrayCalculate;
    }

    private function groupFieldsBeingDisplayedOnTableForAgency($arrayCalculate, $fieldName)
    {
        if ($fieldName === 'ydn_call_cv') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(ydn_ptu_id, 0) AS ydn_call_cv'
            );
        } elseif ($fieldName === 'ydn_call_cvr') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(((ydn_ptu_id / ydn_clicks)* 100), 0) AS ydn_call_cvr'
            );
        } elseif ($fieldName === 'ydn_call_cpa') {
            $arrayCalculate[] = DB::raw(
                'IFNULL((ydn_cost / ydn_ptu_id), 0) AS ydn_call_cpa'
            );
        } elseif ($fieldName === 'yss_call_cv') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(yss_ptu_id, 0) AS yss_call_cv'
            );
        } elseif ($fieldName === 'yss_call_cvr') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(((yss_ptu_id / yss_clicks)* 100), 0) AS yss_call_cvr'
            );
        } elseif ($fieldName === 'yss_call_cpa') {
            $arrayCalculate[] = DB::raw(
                'IFNULL((yss_cost / yss_ptu_id), 0) AS yss_call_cpa'
            );
        } elseif ($fieldName === 'adw_call_cv') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(adw_ptu_id, 0) AS adw_call_cv'
            );
        } elseif ($fieldName === 'adw_call_cvr') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(((adw_ptu_id / adw_clicks)* 100), 0) AS adw_call_cvr'
            );
        } elseif ($fieldName === 'adw_call_cpa') {
            $arrayCalculate[] = DB::raw(
                'IFNULL((adw_cost / adw_ptu_id), 0) AS adw_call_cpa'
            );
        } elseif ($fieldName === 'call_cv') {
            $arrayCalculate[] = DB::raw(
                'IFNULL((yss_ptu_id) + (ydn_ptu_id) + (adw_ptu_id), 0) AS call_cv'
            );
        } elseif ($fieldName === 'call_cvr') {
            $arrayCalculate[] = DB::raw(
                'IFNULL(((yss_ptu_id) + (ydn_ptu_id) + (adw_ptu_id)) / '
                . '(ydn_clicks + yss_clicks + adw_clicks), 0) AS call_cvr'
            );
        } elseif ($fieldName === 'call_cpa') {
            $arrayCalculate[] = DB::raw(
                'IFNULL((ydn_cost + yss_cost + adw_cost) / '
                . '((yss_ptu_id) + (ydn_ptu_id) + (adw_ptu_id)), 0) AS call_cpa'
            );
        }

        return $arrayCalculate;
    }

    private function groupFieldsBeingCalculatedForAgency($fieldName)
    {
        if ($fieldName === 'ydn_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_ptu_id), 0) AS ydn_call_cv'
            );
        } elseif ($fieldName === 'ydn_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG((ydn_ptu_id / ydn_clicks) * 100), 0) AS ydn_call_cvr'
            );
        } elseif ($fieldName === 'ydn_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG((ydn_cost / ydn_ptu_id)), 0) AS ydn_call_cpa'
            );
        } elseif ($fieldName === 'yss_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(yss_ptu_id), 0) AS yss_call_cv'
            );
        } elseif ($fieldName === 'yss_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG((yss_ptu_id / yss_clicks) * 100), 0) AS yss_call_cvr'
            );
        } elseif ($fieldName === 'yss_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG((yss_cost / yss_ptu_id)), 0) AS yss_call_cpa'
            );
        } elseif ($fieldName === 'adw_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(adw_ptu_id), 0) AS adw_call_cv'
            );
        } elseif ($fieldName === 'adw_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG((adw_ptu_id / adw_clicks) * 100), 0) AS adw_call_cvr'
            );
        } elseif ($fieldName === 'adw_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG((adw_cost / adw_ptu_id)), 0) AS adw_call_cpa'
            );
        } elseif ($fieldName === 'call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_ptu_id) + SUM(yss_ptu_id) + SUM(adw_ptu_id), 0) AS call_cv'
            );
        } elseif ($fieldName === 'call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_ptu_id) + SUM(yss_ptu_id) + SUM(adw_ptu_id)) / '
                . '(SUM(ydn_clicks) + SUM(yss_clicks) + SUM(adw_clicks)), 0) AS call_cvr'
            );
        } elseif ($fieldName === 'call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_cost) + SUM(yss_cost) + SUM(adw_cost)) / '
                . '(SUM(ydn_ptu_id) + SUM(yss_ptu_id) + SUM(adw_ptu_id)), 0) AS call_cpa'
            );
        }

        return $rawExpression;
    }

    private function getSummedFieldNamesForTableAliasesTemporary($fieldName)
    {
        $rawExpression = '';
        foreach (static::TABLE_ALIASES as $i => $tableAlias) {
            $rawExpression .= $tableAlias . '_' . $fieldName;
            if ($i < count(static::TABLE_ALIASES) - 1) {
                $rawExpression .= ' + ';
            }
        }

        return $rawExpression;
    }

    private function checkIssetVariable($agency = "")
    {
        $column = "account_id";
        if ($agency !== "") {
            $column = "client_id";
        }

        return $column;
    }

    protected function createTemporaryAccountReport($fieldNames)
    {
        Schema::create(
            self::TEMPORARY_ACCOUNT_TABLE,
            function (Blueprint $table) use ($fieldNames) {
                $table->increments('id');
                $table->string('engine');
                $table->string('account_id', 50);
                $table->integer('totalPhoneTimeUse');
                $table->double('sumConversions');
                $table->bigInteger('adID');
                foreach ($fieldNames as $fieldName) {
                    if (in_array($fieldName, self::COLUMNS_NOT_MAKE)) {
                        continue;
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_BIGINT)
                        || in_array($fieldName, self::SUM_FIELDS)
                    ) {
                        $table->bigInteger($fieldName)->nullable();
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_INT)) {
                        $table->integer($fieldName)->nullable();
                    } elseif (in_array($fieldName, self::FIELDS_TYPE_STRING)) {
                        $table->string($fieldName)->nullable();
                    } else {
                        $table->double($fieldName)->nullable();
                    }
                }
                $table->temporary();
            }
        );
    }
}
