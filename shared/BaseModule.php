<?php

abstract class IPSBaseModule extends IPSModule
{
    protected $config = array();

    protected function CheckConfig()
    {
        foreach ($this->config as $variable) {
            if ($this->ReadPropertyString($variable) == '') {
                return false;
            }
        }
        return true;
    }

    protected function OnConfigValid()
    {
        return;
    }

    protected function OnConfigInvalid()
    {
        return;
    }

    public function ApplyChanges()
    {
        parent::ApplyChanges();

        if ($this->CheckConfig()) {
            $this->OnConfigValid();
        } else {
            $this->OnConfigInvalid();
        }
    }
}
