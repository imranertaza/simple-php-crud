# PHP OOP CRUD Library

This is a simple open-source PHP library that provides a basic implementation of CRUD operations using Object-Oriented Programming (OOP) principles.

## Features

- **Create:** Add new records to the database.
- **Read:** Retrieve records from the database.
- **Update:** Modify existing records in the database.
- **Delete:** Remove records from the database.


## Example

$crud = new Crud("TableName");

$x = $crud->groupStart()
    ->where(['a' =>'a'],"!=")
    ->NotGroupStart()
    ->orWhere('b', "=",'b')
    ->where('c', "=",'c')
    ->groupEnd()
    ->groupEnd()
    ->where('d', "=",'d')->get();