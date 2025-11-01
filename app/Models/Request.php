<?php

namespace App\Models;

namespace App\Models;

class Request extends BaseModel
{
    protected string $table = 'requests';

    protected function attachUser(array $record): array
    {
        $db = $this->db();

        $user = $db->get('users', [
            'id',
            'name',
            'email',
            'username',
            'employee_code',
            'role',
        ], ['id' => $record['user_id']]);

        $record['user'] = $user;
        return $record;
    }

    public function find($id): ?array
    {
        $record = parent::find($id);
        return $record ? $this->attachUser($record) : null;
    }

    public function all(): array
    {
        $records = parent::all();
        return array_map(fn($r) => $this->attachUser($r), $records);
    }

    public function where(array $conditions, bool $single = false)
    {
        $records = parent::where($conditions, $single);

        if ($single && is_array($records)) {
            return $this->attachUser($records);
        }

        return array_map(fn($r) => $this->attachUser($r), $records ?: []);
    }
}

