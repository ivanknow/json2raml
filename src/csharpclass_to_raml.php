<?php

// Set the content type to application/json for response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the C# class input from the request body
    $csharp = file_get_contents('php://input');

    // Convert C# class to RAML data type
    $raml = csharpToRaml($csharp);

    // Return the RAML data type as the response
    echo json_encode(['raml' => $raml]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

/**
 * Convert C# class to RAML data type.
 *
 * @param string $csharp The C# class as a string.
 * @return string The RAML data type.
 */
function csharpToRaml($csharp)
{
    $lines = explode("\n", $csharp);
    $raml = "type: object\nproperties:\n";
    $indent = "  ";

    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/public\s+(\w+)\s+(\w+)/', $line, $matches)) {
            $type = mapCsharpTypeToRaml($matches[1]);
            $name = $matches[2];
            $raml .= "$indent$name:\n$indent$indent"."type: $type\n";
        }
    }

    return $raml;
}

/**
 * Map C# types to RAML types.
 *
 * @param string $type The C# type.
 * @return string The RAML type.
 */
function mapCsharpTypeToRaml($type)
{
    $map = [
        'int' => 'integer',
        'float' => 'number',
        'double' => 'number',
        'decimal' => 'number',
        'bool' => 'boolean',
        'string' => 'string',
        'DateTime' => 'datetime',
        // Add other type mappings as needed
    ];

    return $map[$type] ?? 'string';
}
?>
