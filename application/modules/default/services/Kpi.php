<?php
/**
 * SURFconext Manage
 *
 * LICENSE
 *
 * Copyright 2011 SURFnet bv, The Netherlands
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 *
 * @category  SURFconext Manage
 * @package
 * @copyright Copyright © 2010-2011 SURFnet bv, The Netherlands (http://www.surfnet.nl)
 * @license   http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */
/**
 * SURFconext Key Performance Indicators
 */
class Default_Service_Kpi
{
    /**
     * KPI Identity Provider
     *
     * @var String
     */
    const KPI_IDP = 'idp';

    /**
     * KPI Service Provider
     *
     * @var String
     */
    const KPI_SP  = 'sp';

    public function __construct()
    {
    }

    /**
     * Get the total number of logins and the number of unique user logins
     * for the previous month
     *
     * @param  Integer $timestamp Timestamp base
     *                 for
     *
     * @return Array('total' => 123, 'unique' => 45)
     */
    public function getLogins($timestamp)
    {
        /**
         * Get logins from last month.
         */
        $date = getdate($timestamp);
        $searchFields = array(
            'YEAR(loginstamp)'  => $date['year'],
            'MONTH(loginstamp)' => $date['mon']
        );
        $params = Surfnet_Search_Parameters::create()
                ->setSearchParams($searchFields);

        $service = new EngineBlock_Service_LoginLog();
        $results = $service->searchCountByType($params)
                           ->getResults();
        return $results[0]['num'];
    }

    public function getConnectedProviderTypes($timestamp)
    {
        $period = date('Y-m', $timestamp);
        $searchFields = array(
            "((`expiration` IS NULL) OR (LEFT(`expiration`,7) <= '{$period}'))"
            . "AND (LEFT(`created`,7) <= '{$period}')"
        );

        $params = Surfnet_Search_Parameters::create()
                ->setSearchParams($searchFields);
        
        $service = new ServiceRegistry_Service_JanusEntity();
        $providerTypes = $service->searchCountTypes($params)
                                 ->getResults();
        return array(
                        'idp' => $providerTypes[0]["num"],
                        'sp'  => $providerTypes[1]["num"]
                    );
    }

    /**
     * Get the number of Identity Provider logins  in a given month.
     *
     * @param Integer $timestamp timestamp to determine the month
     *                this KPI covers.
     * @return <type>
     */
    public function getIdpLogins($timestamp)
    {
        return $this->_getProviderTypeLogins($timestamp, self::KPI_IDP);
    }

    /**
     * Get the number of Service Provider logins  in a given month.
     *
     * @param Integer $timestamp timestamp to determine the month
     *                this KPI covers.
     * @return <type>
     */
    public function getSpLogins($timestamp)
    {
        return $this->_getProviderTypeLogins($timestamp, self::KPI_SP);
    }

    /**
     * Get the number of logins for a provider type in given month.
     * 
     * @param Integer $timestamp timestamp to determine the month
     *                this KPI covers.
     * @param String $type Provider type (IdP or SP)
     * @return <type> 
     */
    protected function _getProviderTypeLogins($timestamp, $type)
    {
        /**
         * Get logins from last month.
         */
        $date = getdate($timestamp);
        $searchFields = array(
            'YEAR(loginstamp)'  => $date['year'],
            'MONTH(loginstamp)' => $date['mon']
        );

        $params = Surfnet_Search_Parameters::create()
                ->setSearchParams($searchFields);

        $service = new EngineBlock_Service_LoginLog();
        switch ($type) {
            case self::KPI_IDP:
                $logins = $service->searchCountByIdp($params)
                                  ->getResults();
                break;
            case self::KPI_SP:
                $logins = $service->searchCountBySp($params)
                                  ->getResults();
                break;
            default:
                throw new Exception("KPI Provider Type");
                break;
        }
        return $logins[0]["num"];
    }

    /**
     * Tabs with a team connected to it.
     *
     * @param Integer $timestamp timestamp to determine the month
     *                this KPI covers.
     * @return Integer
     */
    public function getTeamTabs($timestamp)
    {
        /**
         * Teamtabs KPI per month
         * The timestamps are in milliseconds.
         */
        $date = getdate($timestamp);
        $searchFields = array(
            'YEAR(FROM_UNIXTIME(ROUND(creation_timestamp/1000))) <= ' . $date['year'],
            'MONTH(FROM_UNIXTIME(ROUND(creation_timestamp/1000))) <= ' . $date['mon']
        );

        $params = Surfnet_Search_Parameters::create()
                ->setSearchParams($searchFields);

        $service = new Portal_Service_Tab();
        $results = $service->searchTeams($params)->getResults();
        return $results[0]['num'];
    }
}

