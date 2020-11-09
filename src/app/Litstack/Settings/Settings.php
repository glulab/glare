<?php

namespace Glare\Litstack\Settings;

use Ignite\Support\Facades\Form;

/**
 * \Ignite\Support\Facades\Form::load('settings', 'context')->value;
 * simplifies to
 * \LitSettings::context()->value;
 */
class Settings
{
    public function get($key = null, $default = null, $group = 'settings')
    {
        if (is_null($key) && is_null($default)) {
            return Form::load('settings', $group);
        }

        return Form::load('settings', $group)->$key ?? $default;
    }

    public function settings($key = null, $default = null)
    {
        if (is_null($key) && is_null($default)) {
            return Form::load('settings', 'settings');
        }

        return Form::load('settings', 'settings')->$key ?? $default;
    }

    public function site($key = null, $default = null)
    {
        if (is_null($key) && is_null($default)) {
            return Form::load('settings', 'site');
        }

        return Form::load('settings', 'site')->$key ?? $default;
    }

    public function context($key = null, $default = null)
    {
        if (is_null($key) && is_null($default)) {
            return Form::load('settings', 'context');
        }

        return Form::load('settings', 'context')->$key ?? $default;
    }
}
