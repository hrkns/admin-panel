<?php

if (!function_exists('ap_bootstrap_env_file')) {
    function ap_bootstrap_env_file()
    {
        static $loaded = false;

        if ($loaded) {
            return;
        }

        $loaded = true;
        $envPath = dirname(__DIR__).'/.env';

        if (!file_exists($envPath)) {
            return;
        }

        $lines = @file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (!is_array($lines)) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || strpos($line, '#') === 0) {
                continue;
            }

            $parts = explode('=', $line, 2);

            if (count($parts) !== 2) {
                continue;
            }

            $key = trim($parts[0]);
            $value = trim($parts[1]);

            if ($key === '') {
                continue;
            }

            if (
                strlen($value) >= 2 &&
                (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            if (getenv($key) === false) {
                putenv($key.'='.$value);
            }

            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
            }

            if (!isset($_SERVER[$key])) {
                $_SERVER[$key] = $value;
            }
        }
    }
}

if (!function_exists('ap_load_config_contract')) {
    function ap_load_config_contract()
    {
        static $contract = null;

        if ($contract !== null) {
            return $contract;
        }

        $contractPath = dirname(__DIR__).'/config/admin-panel-config-contract.php';

        if (!file_exists($contractPath)) {
            $contract = [
                'defaults' => [],
                'keys' => [],
                'mutable_keys' => [],
                'runtime_json_path' => dirname(__DIR__).'/storage/admin-panel/runtime-settings.json',
                'legacy_snapshot_path' => dirname(__DIR__).'/storage/admin-panel/legacy-settings.snapshot.php',
            ];
            return $contract;
        }

        $contract = require $contractPath;
        return $contract;
    }
}

if (!function_exists('ap_env_value')) {
    function ap_env_value($name)
    {
        ap_bootstrap_env_file();

        $value = getenv($name);

        if ($value !== false) {
            return $value;
        }

        if (isset($_ENV[$name])) {
            return $_ENV[$name];
        }

        if (isset($_SERVER[$name])) {
            return $_SERVER[$name];
        }

        return null;
    }
}

if (!function_exists('ap_cast_value')) {
    function ap_cast_value($value, $type)
    {
        if ($value === null) {
            return null;
        }

        switch ($type) {
            case 'int':
                return intval($value);
            case 'bool': {
                if (is_bool($value)) {
                    return $value;
                }

                $normalized = strtolower(trim(strval($value)));
                return in_array($normalized, ['1', 'true', 'yes', 'on', 'ssl', 'tls']);
            }
            case 'string':
            default:
                return strval($value);
        }
    }
}

if (!function_exists('ap_runtime_path')) {
    function ap_runtime_path()
    {
        $contract = ap_load_config_contract();
        return $contract['runtime_json_path'];
    }
}

if (!function_exists('ap_legacy_snapshot_path')) {
    function ap_legacy_snapshot_path()
    {
        $contract = ap_load_config_contract();
        return $contract['legacy_snapshot_path'];
    }
}

if (!function_exists('ap_load_runtime_settings')) {
    function ap_load_runtime_settings()
    {
        $path = ap_runtime_path();

        if (!file_exists($path)) {
            return [];
        }

        $raw = @file_get_contents($path);

        if ($raw === false || trim($raw) === '') {
            return [];
        }

        $decoded = json_decode($raw, true);

        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}

if (!function_exists('ap_load_legacy_settings')) {
    function ap_load_legacy_settings()
    {
        $path = ap_legacy_snapshot_path();

        if (!file_exists($path)) {
            return [];
        }

        $legacy = require $path;

        if (!is_array($legacy)) {
            return [];
        }

        return $legacy;
    }
}

if (!function_exists('ap_apply_env_overrides')) {
    function ap_apply_env_overrides($settings)
    {
        $contract = ap_load_config_contract();
        $keys = isset($contract['keys']) && is_array($contract['keys']) ? $contract['keys'] : [];

        foreach ($keys as $key => $definition) {
            if (!isset($definition['env'])) {
                continue;
            }

            $envCandidates = is_array($definition['env']) ? $definition['env'] : [$definition['env']];
            $envValue = null;

            foreach ($envCandidates as $envName) {
                $candidate = ap_env_value($envName);

                if ($candidate !== null && $candidate !== '') {
                    $envValue = $candidate;
                    break;
                }
            }

            if ($envValue === null) {
                continue;
            }

            $type = isset($definition['type']) ? $definition['type'] : 'string';
            $settings[$key] = ap_cast_value($envValue, $type);
        }

        return $settings;
    }
}

if (!function_exists('ap_enforce_complete_env')) {
    function ap_enforce_complete_env()
    {
        $flag = ap_env_value('AP_ENFORCE_COMPLETE_ENV');

        if ($flag === null || $flag === '') {
            return true;
        }

        return ap_cast_value($flag, 'bool');
    }
}

if (!function_exists('ap_collect_missing_env_requirements')) {
    function ap_collect_missing_env_requirements()
    {
        $contract = ap_load_config_contract();
        $keys = isset($contract['keys']) && is_array($contract['keys']) ? $contract['keys'] : [];
        $missing = [];

        foreach ($keys as $key => $definition) {
            if (!isset($definition['env'])) {
                continue;
            }

            $required = true;

            if (array_key_exists('required_env', $definition)) {
                $required = boolval($definition['required_env']);
            }

            if (!$required) {
                continue;
            }

            $envCandidates = is_array($definition['env']) ? $definition['env'] : [$definition['env']];
            $found = false;

            foreach ($envCandidates as $envName) {
                $candidate = ap_env_value($envName);

                if ($candidate !== null && trim(strval($candidate)) !== '') {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $missing[$key] = $envCandidates;
            }
        }

        return $missing;
    }
}

if (!function_exists('ap_assert_required_env_settings')) {
    function ap_assert_required_env_settings()
    {
        if (!ap_enforce_complete_env()) {
            return;
        }

        $missing = ap_collect_missing_env_requirements();

        if (count($missing) === 0) {
            return;
        }

        $parts = [];

        foreach ($missing as $key => $envCandidates) {
            $parts[] = $key.' ['.implode('|', $envCandidates).']';
        }

        throw new \RuntimeException(
            'Missing required environment settings in local/.env: '.implode(', ', $parts)
        );
    }
}

if (!function_exists('ap_build_global_settings')) {
    function ap_build_global_settings()
    {
        ap_assert_required_env_settings();

        $contract = ap_load_config_contract();

        $defaults = isset($contract['defaults']) && is_array($contract['defaults'])
            ? $contract['defaults']
            : [];

        $legacy = ap_load_legacy_settings();
        $runtime = ap_load_runtime_settings();

        $settings = array_replace_recursive($defaults, $legacy, $runtime);
        $settings = ap_apply_env_overrides($settings);

        return $settings;
    }
}

if (!function_exists('ap_runtime_mutable_keys')) {
    function ap_runtime_mutable_keys()
    {
        $contract = ap_load_config_contract();

        if (!isset($contract['mutable_keys']) || !is_array($contract['mutable_keys'])) {
            return [];
        }

        return $contract['mutable_keys'];
    }
}

if (!function_exists('ap_write_runtime_settings')) {
    function ap_write_runtime_settings($settings)
    {
        $path = ap_runtime_path();
        $directory = dirname($path);

        if (!is_dir($directory)) {
            @mkdir($directory, 0777, true);
        }

        $json = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            return false;
        }

        return file_put_contents($path, $json) !== false;
    }
}
