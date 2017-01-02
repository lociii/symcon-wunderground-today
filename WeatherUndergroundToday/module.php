<?php

include_once(__DIR__ . '/../shared/BaseModule.php');

class WeatherUndergroundToday extends IPSBaseModule
{
    protected $config = array('wut_api_key', 'wut_location', 'wut_interval');

    public function Create()
    {
        parent::Create();

        $this->RegisterPropertyString('wut_api_key', '');
        $this->RegisterPropertyString('wut_location', '');
        $this->RegisterPropertyInteger('wut_interval', 15);

        $this->CreateVarProfileRainfall();
        $this->CreateVarProfileWindSpeed();

        $this->RegisterTimer('update', $this->ReadPropertyInteger('wut_interval'), 'LOCIWUT_Update($_IPS[\'TARGET\']);');
    }

    protected function OnConfigValid()
    {
        $this->SetTimerInterval('update', $this->ReadPropertyInteger('wut_interval') * 1000 * 60);
        $this->MaintainVariable('temperature', 'Temperatur', 1, 'Temperature', 10, true);
        $this->MaintainVariable('humidity', 'Luftfeuchtigkeit', 1, 'Humidity.F', 20, true);
        $this->MaintainVariable('windspeed', 'Windgeschwindigkeit', 1, 'WUT.WindSpeedkmh', 30, true);
        $this->MaintainVariable('precip', 'Niederschlag', 1, 'WUT.Rainfall', 40, true);
        $this->MaintainVariable('icon', 'Symbol', 3, 'Temperature', 50, true);
        $this->SetStatus(102);
    }

    protected function OnConfigInvalid()
    {
        $this->SetTimerInterval('update', 0);
        $this->SetStatus(104);
    }

    public function Update()
    {
        $api_key = $this->ReadPropertyString('wut_api_key');
        $location = $this->ReadPropertyString('wut_location');

        $data = simplexml_load_string(
            file_get_contents('http://api.wunderground.com/api/' . $api_key . '/conditions/q/zmw:' . $location . '.xml'));

        $precip = 0;
        if ($data->current_observation->precip_1hr_metric > 0) {
            $precip = $data->current_observation->precip_1hr_metric * 10;
        }

        SetValue($this->GetIDForIdent('temperature'), (int)strval($data->current_observation->temp_c));
        SetValue($this->GetIDForIdent('humidity'), (int)strval($data->current_observation->relative_humidity));
        SetValue($this->GetIDForIdent('windspeed'), (int)strval($data->current_observation->wind_kph));
        SetValue($this->GetIDForIdent('precip'), $precip);
        SetValue($this->GetIDForIdent('icon'), strval($data->current_observation->icon_url));
    }

    // see https://github.com/paresy/SymconMisc/blob/master/WundergroundWeather/module.php
    private function CreateVarProfileRainfall()
    {
        if (!IPS_VariableProfileExists('WUT.Rainfall')) {
            IPS_CreateVariableProfile('WUT.Rainfall', 2);
            IPS_SetVariableProfileText('WUT.Rainfall', '', ' Liter/m²');
            IPS_SetVariableProfileValues('WUT.Rainfall', 0, 10, 0);
            IPS_SetVariableProfileDigits('WUT.Rainfall', 2);
            IPS_SetVariableProfileIcon('WUT.Rainfall', 'Rainfall');
        }
    }

    // see https://github.com/paresy/SymconMisc/blob/master/WundergroundWeather/module.php
    private function CreateVarProfileWindSpeed()
    {
        if (!IPS_VariableProfileExists('WUT.WindSpeedkmh')) {
            IPS_CreateVariableProfile('WUT.WindSpeedkmh', 2);
            IPS_SetVariableProfileText('WUT.WindSpeedkmh', '', ' km/h');
            IPS_SetVariableProfileValues('WUT.WindSpeedkmh', 0, 200, 0);
            IPS_SetVariableProfileDigits('WUT.WindSpeedkmh', 1);
            IPS_SetVariableProfileIcon('WUT.WindSpeedkmh', 'WindSpeed');
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 0, '%.1f', '', 0xFFFF00);
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 2, '%.1f', '', 0x66CC33);
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 4, '%.1f', '', 0xFF6666);
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 6, '%.1f', '', 0x33A488);
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 10, '%.1f', '', 0x00CCCC);
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 20, '%.1f', '', 0xFF33CC);
            IPS_SetVariableProfileAssociation('WUT.WindSpeedkmh', 36, '%.1f', '', 0XFFCCFF);
        }
    }
}
