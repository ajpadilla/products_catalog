<?php


namespace App\Repositories;

use App\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserRepository
{

    public function selectAll(){
        return DB::select("call users_identification_types.select_all_users()");
    }

    public function selectById(int $id){
        return DB::select("call users_identification_types.select_user_id(?)", [$id]);
    }

    public function create(int $identification_type_id, string $first_name, string $last_name, string $email, string $phone, string $birthday, string $created_at, string $password){
        return DB::select('call insert_user(?,?,?,?,?,?,?,?)', [
            $identification_type_id	,
            $first_name,
            $last_name,
            $email,
            $phone,
            $birthday,
            $created_at,
            $password
        ]);
    }

    public function update(int $id, int $identification_type_id, string $first_name, string $last_name, string $email, string $phone, string $birthday, string $updated_at){
        return DB::select('call update_user_id(?,?,?,?,?,?,?,?)', [
            $id,
            $identification_type_id,
            $first_name,
            $last_name,
            $email,
            $phone,
            $birthday,
            $updated_at
        ]);
    }

    public function delete(string $id){
        return DB::select("call users_identification_types.delete_user_id('{$id}')");
    }

    public function selectLast(){
        return DB::select("call users_identification_types.select_last_user()");
    }

}
