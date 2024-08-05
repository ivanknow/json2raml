# json2raml

to run locally: php -S localhost:8000

# json sample:


```{}
{
  "name": "John",
  "age": 30,
  "isEmployed": true,
  "address": {
    "street": "123 Main St",
    "city": "Anytown"
  },
  "skills": ["PHP", "JavaScript"]
}

# xml sample:


```{}
<?xml version="1.0" encoding="ISO-8859-1"?>  
<user>
    <name>John</name>
    <age>30</age>
    <isEmployed>true</isEmployed>
    <address>
        <street>123 Main St</street>
        <city>Anytown</city>
    </address>
    <skills>
        <item>PHP</item>
        <item>JavaScript</item>
    </skills>

</user>