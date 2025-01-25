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

    private $tableName;
    // STORE SQL
    private $sql;
    private $selectStr;
    private $whereStr;
    private $limitStr;
    private $offsetStr;
    private $joinStr;
    private $searchStr;
    private $orderByStr;
    private $groupStartStr;
    private $groupEndStr;
    private $orGroupStartStr;
    private $notGroupStartStr;
    private $orNotGroupStartStr;
    private $group_by = "";
    //STORE GROUP ALL TYPE OF GROUP
    private $group;
    //STORE GROUP CLOSE
    private $groupCloseStr;
    //STORE GROUP ALL TYPE OF START GROUP
    private $groupStartString;
    public function __construct($tablename = null)
    {
        $this->tableName = $tablename;
    }

    // FOR SELECTING COLUMN
    public function select(String $select_str)
    {
        $this->selectStr .= $select_str;
        return $this;
    }

    // FOR WHERE ClAUSE
    public function where($fieldName,$conditional_operator = '=', $value= null)
    {
        // FOR CHECKING $FIELD TYPE
        $fieldNameType = gettype($fieldName)==='string';
        $groupStr="";
        isset($this->groupEndStr) && $this->groupEndStr !== "" ? $groupStr.=")" : "";
        isset($this->groupStartStr) && $this->groupStartStr !== ""? $groupStr.="(" : "";
        isset($this->notGroupStartStr) && $this->notGroupStartStr !== "" ? $groupStr.=" NOT (" : "";
        isset($this->orNotGroupStartStr) && $this->orNotGroupStartStr !== "" ? $groupStr.=" OR NOT (" : "";
        isset($this->orGroupStartStr) && $this->orGroupStartStr !== ""? $groupStr.=" OR (" : "";

        //RESET AFTER USING GROUP START
        $this->groupStartStr = "";
        //RESET AFTER USING OR GROUP START
        $this->orGroupStartStr = "";
        //RESET AFTER USING GROUP END
        $this->groupEndStr = "";
        //RESET AFTER USING OR NOT GROUP START
        $this->orNotGroupStartStr = "";
        //RESET AFTER USING NOT GROUP START
        $this->notGroupStartStr = "";

        if($fieldNameType){
            $this->whereStr .= !isset($this->whereStr) ? $this->group.' '.$fieldName.' '.$conditional_operator." "." '$value' ": $this->groupCloseStr." AND $this->groupStartString
            ".$fieldName.' '.$conditional_operator." "." '$value' ";
            $this->groupStartString = "";
            $this->groupCloseStr = "";
            $this->group = "";
        }else{
            $array_keys=  array_keys($fieldName);
            $fieldNameLength = count($array_keys);

            for ($i = 0 ; $i < $fieldNameLength; $i++){

                $valueStr = $fieldName[$array_keys[$i]];
                if($groupStr !== ""){
                    $this->whereStr .= !isset( $this->whereStr) ? $this->group ." " . $array_keys[$i].' '.$conditional_operator." ". " '$valueStr' ":
                        $this->groupCloseStr. ' AND '.$this->groupStartString .$array_keys[$i].' '.$conditional_operator." "." '$valueStr' ";
                    $groupStr = "";
                    $this->groupStartString = "";
                    $this->groupCloseStr = "";
                    $this->group = "";
                }else{
                    $this->whereStr .= !isset( $this->whereStr) ? $array_keys[$i].' '.$conditional_operator." ".$fieldName[$array_keys[$i]] :
                        ' AND '.$array_keys[$i].' '.$conditional_operator." "." '$valueStr' ";
                }

            }
        }
        return $this;
    }

    // FOR OR WHERE ClAUSE
    public function orWhere($fieldName,$conditional_operator = '=', $value= null)
    {
        // FOR CHECKING $FIELD TYPE
        $fieldNameType = gettype($fieldName)==='string';
        $groupStr="";
        isset($this->groupStartStr) && $this->groupStartStr !== ""? $groupStr.="(" : "";
        isset($this->notGroupStartStr) && $this->notGroupStartStr !== "" ? $groupStr.=" NOT (" : "";
        isset($this->orNotGroupStartStr) && $this->orNotGroupStartStr !== "" ? $groupStr.=" OR NOT (" : "";
        isset($this->groupStartStr) && $this->groupStartStr !== ""? $groupStr.="(" : "";
        isset($this->groupEndStr) && $this->groupEndStr !== "" ? $groupStr.=")" : "";
        //RESET AFTER USING GROUP START
        $this->groupStartStr = "";
        //RESET AFTER USING OR GROUP START
        $this->orGroupStartStr ="";
        //RESET AFTER USING OR NOT GROUP START
        $this->orNotGroupStartStr ="";
        //RESET AFTER USING NOT GROUP START
        $this->notGroupStartStr = "" ;
        //RESET AFTER USING GROUP END
        $this->groupEndStr = "";
        if($fieldNameType){
            $this->whereStr .= $this->groupCloseStr.' OR '.$this->groupStartString.$fieldName.' '.$conditional_operator." "." '$value' ";
            $this->groupStartString = "";
            $this->groupCloseStr = "";
            $this->group = "";
        }else{
            $array_keys=  array_keys($fieldName);
            $fieldNameLength = count($array_keys);
            for ($i = 0 ; $i < $fieldNameLength; $i++){
                $keyValue =  $fieldName[$array_keys[$i]];

                if($groupStr !== ""){

                    $this->whereStr .= $this->groupCloseStr.' OR '.$this->groupStartString.$array_keys[$i].' '.$conditional_operator." "." '$keyValue' ";
                    $groupStr="";
                    $this->groupStartString = "";
                    $this->groupCloseStr = "";
                    $this->group = "";
                }else{
                    $this->whereStr .= ' OR '.$array_keys[$i].' '.$conditional_operator." ". " '$keyValue' ";
                }
            }
        }
        return $this;
    }

    // FOR ADD OFFSET
    public  function  offset($offset)
    {
        $this->groupEndStr = "";
        $this->offsetStr = "$this->groupCloseStr OFFSET $offset";
        $this->groupCloseStr = "";
        return $this;
    }

    // FOR ADD LIMIT
    public function limit($limit)
    {
        $this->groupEndStr = "";
        $this->limitStr = "$this->groupCloseStr LIMIT $limit";
        $this->groupCloseStr = "";
        return $this;
    }

    // FOR ADD JOIN
    public function join($field,$condition,$join = 'JOIN')
    {
        $joinUpp = strtoupper($join);
        $this->joinStr .= ' '.$joinUpp.' '.$field.' '.'ON'.' '.$condition;
        return $this;
    }

    // FOR ADD LIKE
    public function  like($field,$str,$position = 'both')
    {
        $positionName = str_replace(' ','',$position);
        $isGroupEnd = isset($this->groupEndStr) && $this->groupEndStr !== "" ? ")" : "";
        $this->groupEndStr = "";
        if ($positionName === 'after'){
            if (isset( $this->whereStr)){

                $this->searchStr .= "$isGroupEnd AND $field LIKE $str% ESCAPE '!'";

            }else{

                $this->whereStr .= ' ';
                $this->searchStr .= "$isGroupEnd $field LIKE $str% ESCAPE '!'";

            }
        }else if ($positionName === 'before'){
            if (isset( $this->whereStr)){

                $this->searchStr .= "$isGroupEnd AND $field LIKE %$str ESCAPE '!'";

                $this->whereStr .= ' ';
                $this->searchStr .= "$isGroupEnd $field LIKE %$str ESCAPE '!'";
            }

        }else{
            if (isset( $this->whereStr)){

                $this->searchStr .= "$isGroupEnd AND $field LIKE %$str% ESCAPE '!'";

            }else{

                $this->whereStr .= ' ';
                $this->searchStr .= "$isGroupEnd $field LIKE %$str% ESCAPE '!'";

            }
        }
        return $this;
    }

    // FOR ADD OR LIKE
    public function  orLike($field,$str,$position = 'both')
    {
        $positionName = str_replace(' ','',$position);

        $this->groupEndStr = "";
        if ($positionName === 'after'){
            $this->searchStr .= "$positionName OR $field LIKE $str% ESCAPE '!'";
        }else if ($positionName === 'before'){
            $this->searchStr .= "$positionName OR $field LIKE %$str ESCAPE '!'";
        }else{
            $this->searchStr .= "$positionName OR $field LIKE %$str% ESCAPE '!'";
        }
        return $this;
    }

    // FOR ADD NOT OFFSET
    public function  notLike($field,$str,$position = 'both')
    {
        $positionName = str_replace(' ','',$position);
        $isGroupEnd = isset($this->groupEndStr) && $this->groupEndStr !== "" ? ")" : "";
        $this->groupEndStr = "";
        if ($positionName === 'after'){
            if (isset( $this->whereStr)){
                $this->searchStr .= "$isGroupEnd AND $field NOT LIKE $str% ESCAPE '!'";
            }else{
                $this->whereStr .= ' ';
                $this->searchStr .= "$isGroupEnd $field NOT LIKE $str% ESCAPE '!'";
            }
            $this->searchStr .= isset( $this->whereStr) ? " AND $field LIKE $str% ESCAPE '!'" : " WHERE $field LIKE $str% ESCAPE '!'";
        }else if ($positionName === 'before'){
            if (isset( $this->whereStr)){
                $this->searchStr .= "$isGroupEnd AND $field NOT LIKE %$str ESCAPE '!'";
            }else{
                $this->whereStr .= ' ';
                $this->searchStr .= "$isGroupEnd $field NOT LIKE %$str ESCAPE '!'";
            }

        }else{
            if (isset( $this->whereStr)){

                $this->searchStr .= "$isGroupEnd AND $field NOT LIKE %$str% ESCAPE '!'";

            }else{
                $this->whereStr .= ' ';
                $this->searchStr .= "$isGroupEnd $field NOT LIKE %$str% ESCAPE '!'";
            }

        }
        return $this;
    }

    // FOR ADD OR NOT LIKE
    public function  orNotLike($field,$str,$position = 'both')
    {
        $positionName = str_replace(' ','',$position);
        $isGroupEnd = isset($this->groupEndStr) && $this->groupEndStr !== "" ? ")" : "";
        $this->groupEndStr = "";
        if ($positionName === 'after'){
            $this->searchStr .= " $isGroupEnd OR  $field NOT LIKE $str% ESCAPE '!'";
        }else if ($positionName === 'before'){
            $this->searchStr .= " $isGroupEnd OR $isGroupEnd $field NOT LIKE %$str ESCAPE '!'";
        }else{
            $this->searchStr .= " $isGroupEnd OR $isGroupEnd $field NOT LIKE %$str% ESCAPE '!'";
        }

        return $this;
    }

    // FOR ADD ORDER BY
    public function orderBy($field,$order = null)
    {
        $this->groupEndStr = "";
        if($order){
            $orderUpp = strtoupper($order);
            $this->orderByStr .= !isset($this->orderByStr) ? " $this->groupCloseStr ORDER BY $field $orderUpp" : ", $field $orderUpp";
            $this->groupCloseStr="";
        }else{
            $this->orderByStr .= !isset($this->orderByStr) ? " $this->groupCloseStr ORDER BY $field " : ", $field";
            $this->groupCloseStr = "";
        }
        return $this;
    }

    // FOR ADD GROUP START
    public function groupStart()
    {
        $this->group .="(";
        $this->groupStartString .= "(";
        $this->groupStartStr = "(";
        return $this;
    }

    // FOR ADD GROUP END
    public function groupEnd()
    {
        $this->groupCloseStr .= ")";
        $this->groupEndStr = ")";
        return $this;
    }

    // FOR ADD START OR GROUP
    public function orGroupStart()
    {
        $this->group .="RO (";
        $this->orGroupStartStr = "RO (";
        $this->groupStartString .= $this->orGroupStartStr;
        return $this;
    }

    // FOR ADD START NOT GROUP
    public  function notGroupStart()
    {
        $this->notGroupStartStr = "NOT (";
        $this->groupStartString .= $this->notGroupStartStr ;
        $this->group .=" NOT (";
        return $this;
    }

    // FOR ADD START OR NOT GROUP
    public  function  orNotGroupStart()
    {
        $this->orNotGroupStartStr = "OR NOT (";
        $this->groupStartString .= $this->orNotGroupStartStr;
        $this->group .= $this->orNotGroupStartStr;
        return $this;
    }

    // group by
    public function group_by(string $field_name)
    {
        $this->group_by .="GROUP BY $field_name";
        return $this;
    }

    // FOR GET FINAL SQL QUERY
    public function get()
    {
        $select = $this->selectStr ?? '*';
        $where = isset( $this->whereStr) ? " WHERE $this->whereStr" : "";
        $join = $this->joinStr ?? "";
        $orderBy = $this->orderByStr ?? "";
        $this->groupEndStr = "";
        $this->sql = "SELECT $select FROM $this->tableName $join $where $this->searchStr $this->group_by $orderBy $this->limitStr $this->offsetStr $this->groupCloseStr";
        return $this->sql;
    }

}

