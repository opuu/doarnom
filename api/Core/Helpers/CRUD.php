<?php

namespace Opu\Core\Helpers;

use Opu\Core\Controller;
use Opu\Core\Database\Connect;

class CRUD
{
    private static $user_id = XUSER['role'] == 'superadmin' ? XUSER['id'] : XUSER['owner_id'];

    public static function index($table, $fields = '*', $conditions = [])
    {
        global $events;

        $events->dispatch('crud.before_index', $table);
        $events->dispatch('crud.before_index.' . trim($table), $table);

        // conditions
        // $conditions = [
        //    'and' => [
        //        'id' => 1,
        //        'name' => 'John',
        //    ],
        //    'or' => [
        //        'id' => 2,
        //        'name' => 'Doe',
        //    ],
        // ];

        // get params (?key=value)
        $params = $_GET;
        // get page number
        if (isset($params['page'])) {
            $page = isset($params['page']) ? $params['page'] : 1;
            $limit = isset($params['limit']) ? $params['limit'] : 10;
            $offset = ($page - 1) * $limit;
        }

        // get order by
        $order_by = isset($params['order_by']) ? $params['order_by'] : 'id';
        // get order
        $order = isset($params['order']) ? $params['order'] : 'desc';
        // get search
        $search = isset($params['search']) ? $params['search'] : '';
        // search fields
        $search_fields = isset($params['search_fields']) ? $params['search_fields'] : '';

        // get all
        $db = new Connect();
        $db = $db->connect();

        $sql = "SELECT $fields FROM $table";
        $sql .= ' WHERE deleted_at IS NULL ';
       

        // if conditions
        if (!empty($conditions)) {
            if (isset($conditions['and'])) {
                foreach ($conditions['and'] as $key => $value) {
                    $sql .= " AND $key = '$value'";
                }
            } else if (isset($conditions['or'])) {
                $sql .= ' AND (';
                foreach ($conditions['or'] as $key => $value) {
                    $sql .= " $key = '$value' OR";
                }
                $sql = substr($sql, 0, -3);
                $sql .= ')';
            }
        }

        // if search
        if ($search) {
            $search = trim($search);
            $search = trim($search, '"');
            $search = strip_tags($search);
            $search = htmlspecialchars($search);
            $search_fields = explode(',', $search_fields);
            $sqlCond = '';
            foreach ($search_fields as $field) {
                $sqlCond .= $field . ' LIKE \'%' . $search . '%\' OR ';
            }
            $sqlCond = substr($sqlCond, 0, -4);

            $sqlCond = ' AND (' . $sqlCond . ' ) ';
            $sql .= $sqlCond;
        }

        // if order by
        if ($order_by) {
            $sql .= " ORDER BY $order_by $order";
        }

        if (isset($params['page'])) {
            // if limit
            if ($limit) {
                $sql .= " LIMIT $limit";
            }

            // if offset
            if ($offset) {
                $sql .= " OFFSET $offset";
            }
        }

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();

        // if has custom fields parse them from json to array
        foreach ($result as $key => $value) {
            // unset deleted_at from result
            unset($result[$key]['deleted_at']);

            // if has custom fields parse them from json to array
            if (isset($result[$key]['custom_fields']) && $result[$key]['custom_fields']) {
                $result[$key]['custom_fields'] = json_decode($result[$key]['custom_fields'], true);
            } else if (isset($result[$key]['custom_fields'])) {
                $result[$key]['custom_fields'] = [];
            }

            // if has options parse them from json to array
            if (isset($result[$key]['options']) && $result[$key]['options']) {
                $result[$key]['options'] = json_decode($result[$key]['options'], true);
            } else if (isset($result[$key]['options'])) {
                $result[$key]['options'] = [];
            }
        }


        // count total without limit and offset but with search
        $sql = "SELECT COUNT(*) FROM $table WHERE deleted_at IS NULL ";
       

        // if conditions
        if (!empty($conditions)) {
            if (isset($conditions['and'])) {
                foreach ($conditions['and'] as $key => $value) {
                    $sql .= " AND $key = '$value'";
                }
            } else if (isset($conditions['or'])) {
                $sql .= ' AND (';
                foreach ($conditions['or'] as $key => $value) {
                    $sql .= " $key = '$value' OR";
                }
                $sql = substr($sql, 0, -3);
                $sql .= ')';
            }
        }

        // if search
        if ($search) {
            $sqlCond = '';
            foreach ($search_fields as $field) {
                $sqlCond .= $field . ' LIKE \'%' . $search . '%\' OR ';
            }
            $sqlCond = substr($sqlCond, 0, -4);

            $sqlCond = ' AND (' . $sqlCond . ' ) ';
            $sql .= $sqlCond;
        }

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $total = $stmt->fetchColumn();

        $result = [
            'list' => $result,
            'total' => $total,
        ];

        $events->dispatch('crud.index', $result);
        $events->dispatch('crud.index.' . trim($table), $result);
        return $result;
    }

    public static function single($table, $id, $fields = '*', $conditions = [])
    {
        global $events;

        // if id is array, get the first id
        if (is_array($id)) {
            $id = $id['id'];
        }

        $events->dispatch('crud.before_single', $id);
        $events->dispatch('crud.before_single.' . trim($table), $id);

        $db = new Connect();
        $db = $db->connect();

        $sql = "SELECT $fields FROM $table WHERE (id = :id";

        // if table is users
        if ($table == 'users') {
            $sql .= " OR email = :id OR phone = :id)";
            $sql .= ' AND deleted_at IS NULL ';
        } else {
            $sql .= ') AND deleted_at IS NULL ';
        }

        // if conditions
        if (!empty($conditions)) {
            if (isset($conditions['and'])) {
                foreach ($conditions['and'] as $key => $value) {
                    $sql .= " AND $key = '$value'";
                }
            } else if (isset($conditions['or'])) {
                $sql .= ' AND (';
                foreach ($conditions['or'] as $key => $value) {
                    $sql .= " $key = '$value' OR";
                }
                $sql = substr($sql, 0, -3);
                $sql .= ')';
            }
        }

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();

        if (!$result) {
            return false;
        }

        // unset deleted_at from result
        unset($result['deleted_at']);

        // if has custom fields parse them from json to array
        if (isset($result['custom_fields']) && $result['custom_fields']) {
            $result['custom_fields'] = json_decode($result['custom_fields'], true);
        } else if (isset($result['custom_fields'])) {
            $result['custom_fields'] = [];
        }

        // if has options parse them from json to array
        if (isset($result['options']) && $result['options']) {
            $result['options'] = json_decode($result['options'], true);
        } else if (isset($result['options'])) {
            $result['options'] = [];
        }

        $events->dispatch('crud.single', $result);
        $events->dispatch('crud.single.' . trim($table), $result);

        return $result;
    }

    public static function create($table, $data)
    {
        global $events;

        $events->dispatch('crud.before_create', $data);
        $events->dispatch('crud.before_create.' . trim($table), $data);

        $db = new Connect();
        $db = $db->connect();

        if (isset($data['email']) && strlen($data['email']) > 0) {
            $data['email'] = strtolower($data['email']);
            $data['email'] = trim($data['email']);

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                Controller::send(400, null, 'Invalid email address.');
            }
        }

        if (isset($data['phone']) && strlen($data['phone']) > 0) {
            $data['phone'] = trim($data['phone']);
            // keep only numbers and +
            $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);

            // if phone number is less than 10 digits
            if (strlen($data['phone']) < 10) {
                Controller::send(400, null, 'Invalid phone number.');
            }
        }

        // if table is users
        if ($table == 'users') {

            // check if email or phone already exists
            $user = CRUD::single('users', $data['email'], 'id');
            if ($user) {
                Controller::send(400, null, 'Email already in use.');
            }

            $user = CRUD::single('users', $data['phone'], 'id');
            if ($user) {
                Controller::send(400, null, 'Phone number already in use.');
            }
        }

        // if data contains id, remove it
        if (isset($data['id'])) {
            unset($data['id']);
        }

        // if custom_fields.keys found in data remove them from data and add the key value pairs to custom_fields
        foreach ($data as $key => $value) {
            if (strpos($key, 'custom_fields.') !== false) {
                $custom_field_key = str_replace('custom_fields.', '', $key);
                $data['custom_fields'][$custom_field_key] = $value;
                unset($data[$key]);
            }
        }

        // if data contains custom_fields, convert it to json
        if (isset($data['custom_fields']) && !empty($data['custom_fields'])) {
            $data['custom_fields'] = json_encode($data['custom_fields']);
        } else {
            $data['custom_fields'] = null;
        }

        // if options is set, convert it to json
        if (isset($data['options']) && !empty($data['options'])) {
            $data['options'] = json_encode($data['options']);
        } elseif (isset($data['options'])) {
            $data['options'] = null;
        }

        $sql = "INSERT INTO $table (";
        foreach ($data as $key => $value) {
            $sql .= $key . ', ';
        }
        $sql = substr($sql, 0, -2);
        $sql .= ') VALUES (';
        foreach ($data as $key => $value) {
            $sql .= ':' . trim($key) . ', ';
        }
        $sql = substr($sql, 0, -2);
        $sql .= ')';

        try {
            $stmt = $db->prepare($sql);
            foreach ($data as $key => $value) {
                if (empty($data[$key]) || $data[$key] == '') {
                    $data[$key] = null;
                }
                $stmt->bindParam(':' . trim($key), $data[$key]);
            }
            $stmt->execute();

            $data['id'] = $db->lastInsertId();

            $events->dispatch('crud.create', $data);
            $events->dispatch('crud.create.' . trim($table), $data);

            return $db->lastInsertId();
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), '1062') !== false) {
                Controller::send(400, null, 'Something went wrong, you might be trying to create an item that already exists.');
            } else {
                throw $th;
            }
        }
    }

    public static function update($table, $id, $data)
    {
        global $events;

        $events->dispatch('crud.before_update', $id);
        $events->dispatch('crud.before_update.' . trim($table), $id);

        $db = new Connect();
        $db = $db->connect();

        if (isset($data['id'])) {
            unset($data['id']);
        }

        if (isset($data['created_at'])) {
            unset($data['created_at']);
        }

        if (isset($data['updated_at'])) {
            unset($data['updated_at']);
        }

        if (isset($data['deleted_at'])) {
            unset($data['deleted_at']);
        }

        if (isset($data['owner_id'])) {
            unset($data['owner_id']);
        }

        // check if table has item with id
        $item = CRUD::single($table, $id, 'id');
        if (!$item) {
            Controller::send(404, null, 'The item you are trying to update does not exist.');
        }

        if (isset($data['email']) && strlen($data['email']) > 0) {
            $data['email'] = strtolower($data['email']);
            $data['email'] = trim($data['email']);

            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                Controller::send(400, null, 'Invalid email address.');
            }
        }

        if (isset($data['phone']) && strlen($data['phone']) > 0) {
            $data['phone'] = trim($data['phone']);
            // keep only numbers and +
            $data['phone'] = preg_replace('/[^0-9+]/', '', $data['phone']);

            // if phone number is less than 10 digits
            if (strlen($data['phone']) < 10) {
                Controller::send(400, null, 'Invalid phone number.');
            }
        }

        if ($table == 'users') {
            // check if email or phone already exists
            $user = CRUD::single('users', $data['email'], 'id');
            // if this email belongs to another user other than the current user
            if ($user && $user['id'] != $id) {
                Controller::send(400, null, 'Email already in use.');
            }

            $user = CRUD::single('users', $data['phone'], 'id');
            // if this phone belongs to another user other than the current user
            if ($user && $user['id'] != $id) {
                Controller::send(400, null, 'Phone number already in use.');
            }
        }

        // if custom_fields.keys found in data remove them from data and add the key value pairs to custom_fields
        foreach ($data as $key => $value) {
            if (strpos($key, 'custom_fields.') !== false) {
                $custom_field_key = str_replace('custom_fields.', '', $key);
                $data['custom_fields'][$custom_field_key] = $value;
                unset($data[$key]);
            }
        }

        // if data contains custom_fields, convert it to json
        if (isset($data['custom_fields']) && !empty($data['custom_fields'])) {
            $data['custom_fields'] = json_encode($data['custom_fields']);
        } else {
            $data['custom_fields'] = null;
        }

        // if options is set, convert it to json
        if (isset($data['options']) && !empty($data['options'])) {
            $data['options'] = json_encode($data['options']);
        } elseif (isset($data['options'])) {
            $data['options'] = null;
        }

        $sql = "UPDATE $table SET ";
        foreach ($data as $key => $value) {
            $sql .= $key . ' = :' . trim($key) . ', ';
        }
        $sql = substr($sql, 0, -2);
        $sql .= ' WHERE id = :id';
        $sql .= ' AND deleted_at IS NULL ';
       


        try {
            $stmt = $db->prepare($sql);
            foreach ($data as $key => $value) {
                if (empty($data[$key]) || $data[$key] == '') {
                    $data[$key] = null;
                }
                $stmt->bindParam(':' . trim($key), $data[$key]);
            }
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $data['id'] = $id;

            $events->dispatch('crud.update', $data);
            $events->dispatch('crud.update.' . trim($table), $data);

            return $id;
        } catch (\Throwable $th) {
            if (strpos($th->getMessage(), '1062') !== false) {
                Controller::send(400, null, 'Something went wrong, you might be trying to update email or phone to an existing one.');
            } else {
                throw $th;
            }
        }
    }

    public static function delete($table, $id)
    {
        global $events;
        $db = new Connect();
        $db = $db->connect();

        $events->dispatch('crud.before_delete', $id);
        $events->dispatch('crud.before_delete.' . trim($table), $id);

        $sql = "UPDATE $table SET deleted_at = CURRENT_TIMESTAMP WHERE id = :id";
       
        $sql .= ' AND deleted_at IS NULL ';

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $events->dispatch('crud.delete', $id);
        $events->dispatch('crud.delete.' . trim($table), $id);

        return $id;
    }
}
