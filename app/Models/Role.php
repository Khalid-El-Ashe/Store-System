<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PHPUnit\Runner\Extension\Extension;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    // i need get the relation with abilities
    public function abilities() {
        return $this->hasMany(RoleAbility::class);
    }

    //todo i need make my scope to make cleaning code
    public static function createWithAbilities(Request $request) {

        //todo note-> i need using transaction here to i need success all operation or rollback all operation
        DB::beginTransaction();
        try {
            $role = Role::create(
                [
                    'name' => $request->post('name')
                ]
            );

            foreach ($request->post('abilities') as $ability => $value) {
                RoleAbility::create([
                    'role_id' => $role->id,
                    'ability' => $ability,
                    'type' => $value
                ]);
            }
            DB::commit();
        }catch(Extension $e) {
            DB::rollBack();
            throw $e;
        }
        // end Transaction

        return $role;
    }

    // i need make another scope
    public function updateWithAbilities (Request $request) {
        //todo note-> i need using transaction here to i need success all operation or rollback all operation
        DB::beginTransaction();
        try {
            $this->update(
                [
                    'name' => $request->post('name')
                ]
            );

            foreach ($request->post('abilities') as $ability => $value) {

                // (updateOrCreate) this function what do-> if the record exists, it will update it, otherwise it will create a new one.
                // todo note-> i need make this function more clean
                RoleAbility::updateOrCreate([
                    'role_id' => $this->id,
                    'ability' => $ability,
                ], [
                    'type' => $value
                ]);
            }
            DB::commit();
        }catch(Extension $e) {
            DB::rollBack();
            throw $e;
        }
        // end Transaction

        return $this;
    }
}
