<?php

namespace Imranertaza\SimplePhpCrud;

class Crud{
    public function add($tablename, $data) {

        $key = array_keys($data);
        $val = array_values($data);

        $query = "INSERT INTO $tablename (" . implode(', ', $key) . ") "
            . "VALUES ('" . implode("', '", $val) . "')";
        return $query;

    }


    public function getAll($tablename) {
        $query = "SELECT * FROM $tablename";
        return $query;
    }


    public function getData($tablename, $column, $value) {
        $allValue = "";
        for($x = 0; $x < count($column) ;$x++){
            if($x == 0){
                $allValue = $allValue . $column[$x] . "=" . "'" . $value[$x] . "'";
            }else{
                $allValue = $allValue . " AND " . $column[$x] . "=" . "'" . $value[$x] . "'";
            }
        }
        $query = "SELECT * FROM $tablename WHERE $allValue";
        return $query;
    }

    public function getSelectedData($selectedColumn, $tablename, $column, $value) {

        $query = "SELECT `$selectedColumn` FROM `$tablename` WHERE `$column` = $value";

        return $query;
    }


    public function getOrData($tablename, $column , $value) {

        $allValue = "";
        for($x = 0; $x < count($column) ;$x++){

            if($x == 0){
                $allValue = $allValue . $column[$x] . "=" . "'" . $value[$x] . "'";
            }else{
                $allValue = $allValue . " OR " . $column[$x] . "=" . "'" . $value[$x] . "'";
            }

        }
        $query = "SELECT * FROM $tablename WHERE $allValue";
        return $query;

    }


    public function delete($tablename, $column , $value) {
        $query = "DELETE FROM $tablename WHERE $column='$value'";
        return $query;

    }


    public function deleteAll($tablename, $column , $value) {
        $query = "DELETE FROM $tablename WHERE $column IN ($value)";
        return $query;
    }


    public function update($tablename, $data, $id_field, $id_value) {
        foreach ($data as $field=>$value) {
            $fields[] = sprintf("`%s` = '%s'", $field, $value);
        }
        $field_list = join(',', $fields);
        $query = sprintf("UPDATE `%s` SET %s WHERE `%s` = %s", $tablename, $field_list, $id_field, "'" . $id_value . "'");
        return $query;
    }

}

?>