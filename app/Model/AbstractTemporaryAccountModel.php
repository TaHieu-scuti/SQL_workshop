<?php

namespace App\Model;

use App\AbstractReportModel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

use Exception;

abstract class AbstractTemporaryAccountModel extends AbstractReportModel
{
    const TEMPORARY_ACCOUNT_TABLE = 'temporary_account_table';
    const ACCOUNT_ID = 'account_id';

    const FIELDS_TYPE_BIGINT = [
        'account_id',
    ];

    const FIELDS_TYPE_STRING = [
        'accountName'
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
        'adw_call_cv'
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

    protected function createTemporaryAccountTable(
        array $fieldNames
    ) {
        Schema::create(
            self::TEMPORARY_ACCOUNT_TABLE,
            function (Blueprint $table) use ($fieldNames) {
                $table->increments('id');
                foreach (self::DEFAULT_COLUMNS as $key => $fieldName) {
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

    protected function getAccountYss($startDay, $endDay)
    {
        $modelYssAccount = new RepoYssAccountReportCost;
        $yssAccountAgency = $modelYssAccount->getYssAccountAgency(
            static::SUBQUERY_FIELDS,
            $startDay,
            $endDay
        );
        $rawExpressions = $this->setFieldNameToUpdate('yss');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($yssAccountAgency).')AS tbl set '.implode(', ', $rawExpressions).' where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id'
        );
    }

    protected function getAccountYdn($startDay, $endDay)
    {
        $modelYdnAccount = new RepoYdnReport;
        $ydnAccountAgency = $modelYdnAccount->getYdnAccountAgency(
            static::SUBQUERY_FIELDS,
            $startDay,
            $endDay
        );

        $rawExpressions = $this->setFieldNameToUpdate('ydn');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($ydnAccountAgency).')AS tbl set '.implode(', ', $rawExpressions).' where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id'
        );


    }

    protected function getAccountAdw($startDay, $endDay)
    {
        $modelAdwAccount = new RepoAdwAccountReportCost;
        $adwAccountAgency = $modelAdwAccount->getAdwAccountAgency(
            static::SUBQUERY_FIELDS,
            $startDay,
            $endDay
        );

        $rawExpressions = $this->setFieldNameToUpdate('adw');

        DB::update(
            'update '.self::TEMPORARY_ACCOUNT_TABLE.', ('
            .$this->getBindingSql($adwAccountAgency).')AS tbl set '.implode(', ', $rawExpressions).' where '
            .self::TEMPORARY_ACCOUNT_TABLE.'.account_id = tbl.account_id'
        );

    }

    private function setFieldNameToUpdate($engine)
    {
        $rawExpressions = [];
        foreach (static::SUBQUERY_FIELDS as $fieldName) {
            if (in_array($fieldName, self::FIELDS_TO_CHECK)) {
                $rawExpressions[] = DB::raw($engine.'_'.$fieldName.' = tbl.'.$fieldName);
            }
        }

        return $rawExpressions;
    }

    public function getAggregatedTemporary(
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
            if (in_array($fieldName, static::AVERAGE_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((adw_'. $fieldName .' +  ydn_'.$fieldName.' + yss_'.$fieldName.')/3, 0) AS '.$fieldName
                );
            } elseif (in_array($fieldName, static::SUM_FIELDS)) {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_'. $fieldName . ' +  ydn_' . $fieldName . ' + yss_' . $fieldName . ', 0)'
                    . ' AS '. $fieldName
                );
            } elseif ($fieldName === 'ydn_web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_web_cv, 0) AS ydn_web_cv'
                );
            } elseif ($fieldName === 'ydn_web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_web_cvr, 0) AS ydn_web_cvr'
                );
            } elseif ($fieldName === 'ydn_web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_web_cpa, 0) AS ydn_web_cpa'
                );
            } elseif ($fieldName === 'yss_web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss_web_cv, 0) AS yss_web_cv'
                );
            } elseif ($fieldName === 'yss_web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss_web_cvr, 0) AS yss_web_cvr'
                );
            } elseif ($fieldName === 'yss_web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss_web_cpa, 0) AS yss_web_cpa'
                );
            } elseif ($fieldName === 'adw_web_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_web_cv, 0) AS adw_web_cv'
                );
            } elseif ($fieldName === 'adw_web_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_web_cvr, 0) AS adw_web_cvr'
                );
            } elseif ($fieldName === 'adw_web_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_web_cpa, 0) AS adw_web_cpa'
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
            } elseif ($fieldName === 'ydn_call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_call_cv, 0) AS ydn_call_cv'
                );
            } elseif ($fieldName === 'ydn_call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_call_cvr, 0) AS ydn_call_cvr'
                );
            } elseif ($fieldName === 'ydn_call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_call_cpa, 0) AS ydn_call_cpa'
                );
            } elseif ($fieldName === 'yss_call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss_call_cv, 0) AS yss_call_cv'
                );
            } elseif ($fieldName === 'yss_call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss_call_cvr, 0) AS yss_call_cvr'
                );
            } elseif ($fieldName === 'yss_call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(yss_call_cpa, 0) AS yss_call_cpa'
                );
            } elseif ($fieldName === 'adw_call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_call_cv, 0) AS adw_call_cv'
                );
            } elseif ($fieldName === 'adw_call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_call_cvr, 0) AS adw_call_cvr'
                );
            } elseif ($fieldName === 'adw_call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(adw_call_cpa, 0) AS adw_call_cpa'
                );
            } elseif ($fieldName === 'call_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_call_cv + yss_call_cv + adw_call_cv, 0) AS call_cv'
                );
            } elseif ($fieldName === 'call_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_call_cv + yss_call_cv + adw_call_cv) / '
                    . '(ydn_clicks + yss_clicks + adw_clicks), 0) AS call_cvr'
                );
            } elseif ($fieldName === 'call_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_cost + yss_cost + adw_cost) / '
                    . '(ydn_call_cv + yss_call_cv + adw_call_cv), 0) AS call_cpa'
                );
            } elseif ($fieldName === 'total_cv') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL(ydn_call_cv + yss_call_cv + adw_call_cv + ydn_web_cv + '
                    . 'yss_web_cv + adw_web_cv, 0) AS total_cv'
                );
            } elseif ($fieldName === 'total_cvr') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_call_cv + yss_call_cv + adw_call_cv + ydn_web_cv + yss_web_cv + adw_web_cv) / '
                    . '(ydn_clicks + yss_clicks + adw_clicks), 0) AS total_cvr'
                );
            } elseif ($fieldName === 'total_cpa') {
                $arrayCalculate[] = DB::raw(
                    'IFNULL((ydn_cost + yss_cost + adw_cost) / '
                    . '(ydn_call_cv + yss_call_cv + adw_call_cv + ydn_web_cv + yss_web_cv + '
                    . 'adw_web_cv), 0) AS total_cpa'
                );
            }
        }

        return $arrayCalculate;
    }

    protected function getRawExpressionTemporary($fieldName)
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
        } elseif ($fieldName === 'ydn_web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_web_cv), 0) AS ydn_web_cv'
            );
        } elseif ($fieldName === 'ydn_web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn_web_cvr), 0) AS ydn_web_cvr'
            );
        } elseif ($fieldName === 'ydn_web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn_web_cpa), 0) AS ydn_web_cpa'
            );
        } elseif ($fieldName === 'yss_web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(yss_web_cv), 0) AS yss_web_cv'
            );
        } elseif ($fieldName === 'yss_web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss_web_cvr), 0) AS yss_web_cvr'
            );
        } elseif ($fieldName === 'yss_web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss_web_cpa), 0) AS yss_web_cpa'
            );
        } elseif ($fieldName === 'adw_web_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(adw_web_cv), 0) AS adw_web_cv'
            );
        } elseif ($fieldName === 'adw_web_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw_web_cvr), 0) AS adw_web_cvr'
            );
        } elseif ($fieldName === 'adw_web_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw_web_cpa), 0) AS adw_web_cpa'
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
        } elseif ($fieldName === 'ydn_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_call_cv), 0) AS ydn_call_cv'
            );
        } elseif ($fieldName === 'ydn_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn_call_cvr), 0) AS ydn_call_cvr'
            );
        } elseif ($fieldName === 'ydn_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(ydn_call_cpa), 0) AS ydn_call_cpa'
            );
        } elseif ($fieldName === 'yss_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(yss_call_cv), 0) AS yss_call_cv'
            );
        } elseif ($fieldName === 'yss_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss_call_cvr), 0) AS yss_call_cvr'
            );
        } elseif ($fieldName === 'yss_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(yss_call_cpa), 0) AS yss_call_cpa'
            );
        } elseif ($fieldName === 'adw_call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(adw_call_cv), 0) AS adw_call_cv'
            );
        } elseif ($fieldName === 'adw_call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw_call_cvr), 0) AS adw_call_cvr'
            );
        } elseif ($fieldName === 'adw_call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL(AVG(adw_call_cpa), 0) AS adw_call_cpa'
            );
        } elseif ($fieldName === 'call_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_call_cv) + SUM(yss_call_cv) + SUM(adw_call_cv), 0) AS call_cv'
            );
        } elseif ($fieldName === 'call_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_call_cv) + SUM(yss_call_cv) + SUM(adw_call_cv)) / '
                . '(SUM(ydn_clicks) + SUM(yss_clicks) + SUM(adw_clicks)), 0) AS call_cvr'
            );
        } elseif ($fieldName === 'call_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_cost) + SUM(yss_cost) + SUM(adw_cost)) / '
                . '(SUM(ydn_call_cv) + SUM(yss_call_cv) + SUM(adw_call_cv)), 0) AS call_cpa'
            );
        } elseif ($fieldName === 'total_cv') {
            $rawExpression = DB::raw(
                'IFNULL(SUM(ydn_call_cv) + SUM(yss_call_cv) + SUM(adw_call_cv) + SUM(ydn_web_cv) + '
                . 'SUM(yss_web_cv) + SUM(adw_web_cv), 0) AS total_cv'
            );
        } elseif ($fieldName === 'total_cvr') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_call_cv) + SUM(yss_call_cv) + SUM(adw_call_cv) + SUM(ydn_web_cv) + '
                . 'SUM(yss_web_cv) + SUM(adw_web_cv)) / '
                . '(SUM(ydn_clicks) + SUM(yss_clicks) + SUM(adw_clicks)), 0) AS total_cvr'
            );
        } elseif ($fieldName === 'total_cpa') {
            $rawExpression = DB::raw(
                'IFNULL((SUM(ydn_cost) + SUM(yss_cost) + SUM(adw_cost)) / '
                . '(SUM(ydn_call_cv) + SUM(yss_call_cv) + SUM(adw_call_cv) + SUM(ydn_web_cv) + '
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
}
