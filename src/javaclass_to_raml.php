<?php

// Set the content type to application/json for response
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the Java class input from the request body
    $java = file_get_contents('php://input');

    // Convert Java class to RAML data type
    $raml = javaToRaml($java);

    // Return the RAML data type as the response
    echo json_encode(['raml' => $raml]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
}

/**
 * Convert Java class to RAML data type.
 *
 * @param string $java The Java class as a string.
 * @return string The RAML data type.
 */
function javaToRaml($java)
{
    $lines = explode("\n", $java);
    $raml = "type: object\nproperties:\n";
    $indent = "  ";

    foreach ($lines as $line) {
        $line = trim($line);
        if (preg_match('/private\s+(\w+)\s+(\w+)/', $line, $matches)) {
            $type = mapJavaTypeToRaml($matches[1]);
            $name = $matches[2];
            $raml .= "$indent$name:\n$indent$indent"."type: $type\n";
        }
    }

    return $raml;
}

/**
 * Map Java types to RAML types.
 *
 * @param string $type The Java type.
 * @return string The RAML type.
 */
function mapJavaTypeToRaml($type)
{
    $primitiveMap = [
        'int' => 'integer',
        'float' => 'number',
        'double' => 'number',
        'boolean' => 'boolean',
        'String' => 'string',
        'Date' => 'datetime',
        'List' => 'array',
    ];

    // If the type is a primitive, return the mapped RAML type
    if (isset($primitiveMap[$type])) {
        return $primitiveMap[$type];
    }

    if(preg_match('/List<[^>]*>/', $string)){
        return 'array';
    
        
    }

    // Otherwise, assume it's a custom class and map it to 'object'
    return 'object';
}
?>
