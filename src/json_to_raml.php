<?php

// Set the content type to application/json for response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input from the request body
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        // Convert JSON to RAML data type
        $raml = jsonToRaml($data);

        // Return the RAML data type as the response
        echo json_encode(['raml' => $raml]);
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

/**
 * Convert JSON to RAML data type.
 *
 * @param mixed $data The JSON data.
 * @return string The RAML data type.
 */
function jsonToRaml($data, $level = 0)
{
    $raml = '';
    $indent = str_repeat('  ', $level);

    if (is_array($data)) {
        if (isAssoc($data)) {
            $raml .= $indent . "type:\n";
            $raml .= $indent . "properties:\n";
            foreach ($data as $key => $value) {
                $raml .= $indent . "  $key:\n";
                $raml .= jsonToRaml($value, $level + 2);
            }
        } else {
            $raml .= $indent . "type: array\n";
            $raml .= $indent . "items:\n";
            if (count($data) > 0) {
                $raml .= jsonToRaml($data[0], $level + 1);
            }
        }
    } else {
        $type = getItemType($data);
        $raml .= $indent . "type: $type\n";
    }

    return $raml;
}

/**
 * Determine if an array is associative.
 *
 * @param array $array The array to check.
 * @return bool True if the array is associative, false otherwise.
 */
function isAssoc(array $array)
{
    return array_keys($array) !== range(0, count($array) - 1);
}

/**
 * Get the RAML type for a given value.
 *
 * @param mixed $value The value to check.
 * @return string The RAML type.
 */
function getItemType($value)
{
    switch (gettype($value)) {
        case 'integer':
            return 'integer';
        case 'double':
            return 'number';
        case 'boolean':
            return 'boolean';
        case 'string':
            return 'string';
        default:
            return 'string';
    }
}
?>
