<?php

namespace App\Support\Database;

class LegacySqlSnapshot
{
    public static function extractSchemaStatements($sqlDump)
    {
        $statements = array();
        $chunks = preg_split('/;\s*(?:\r?\n|$)/', str_replace("\r\n", "\n", $sqlDump));

        foreach ($chunks as $chunk) {
            $statement = self::normalizeStatement($chunk);

            if ($statement === '' || !self::isSchemaStatement($statement)) {
                continue;
            }

            $statements[] = $statement.';';
        }

        return $statements;
    }

    private static function normalizeStatement($statement)
    {
        $lines = preg_split('/\n/', trim($statement));
        $normalized = array();

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if ($trimmed === '') {
                continue;
            }

            if (strpos($trimmed, '--') === 0) {
                continue;
            }

            if (strpos($trimmed, '/*') === 0 && strpos($trimmed, '*/') !== false) {
                continue;
            }

            $normalized[] = $line;
        }

        return trim(implode("\n", $normalized));
    }

    private static function isSchemaStatement($statement)
    {
        $prefix = strtoupper(substr(ltrim($statement), 0, 16));

        return strpos($prefix, 'CREATE TABLE') === 0 || strpos($prefix, 'ALTER TABLE') === 0;
    }
}
