<?php
namespace Apps\Models;

use Cygnite\Foundation\Application;
use Cygnite\Database\Schema;
use Cygnite\Common\UrlManager\Url;
use Cygnite\Database\ActiveRecord;
use Cygnite\Common\Pagination;

/**
* This file is generated by Cygnite CLI Crud generator
*/

class Role extends ActiveRecord
{
    //your database connection name
    // protected $database = 'cygnite';

    /*
     | By default Every model class name used as table name
     | "User" => 'user'
     | You can also override the table name here
     */
    protected $tableName = 'role';

    protected $primaryKey = 'role_id';

    public $perPage = 5;

    public function __construct()
    {
        parent::__construct();
    }

    // used for pagination
    public function pageLimit()
    {
        return Url::segment($this->params['start_segment'] + 4);
    }

    /**
     * Skip columns from the select query
     * @return array
     */
    public function skip()
    {
        return array('created_at', 'updated_at');
    }

    public function getList($keyword='', $limit="")
    {
        $segment = $this->params['start_segment'];
        $page  = Url::segment($segment+3) ? Url::segment($segment+3)-1 : 0;
        $this->perPage = $limit ? $limit : $this->perPage;
        $start = ($page) ? $page * $this->perPage : 0;
        $limit = $limit ? $limit : $this->perPage;
        $sql         = "select SQL_CALC_FOUND_ROWS * from role where role_name like ? limit $start,$limit";
        $stmt        = $this->query( $sql , [ '%'.$keyword.'%' ] );
        $data        = $stmt->getAll(\PDO::FETCH_ASSOC);
        $total       = $this->query("select FOUND_ROWS()")->getAll(\PDO::FETCH_ASSOC);
        $totalRecord = is_object($total->offsetGet(0)) ? $total->offsetGet(0)->attributes['found_rows()'] : 0;
        $paginator   = Pagination::instance($this);
        $pagination  = $paginator->createLinks($totalRecord);
        return array( 'data'=> $data, 'pagination' => $pagination);
    }
}// End of the User Model