<?php namespace Stb\Letter\Models;

use Model;

class Settings extends Model
{
    public $implement = ['@System.Behaviors.SettingsModel'];

    public $settingsCode = 'stb_letter_settings';

    public $settingsFields = 'fields.yaml';
}
