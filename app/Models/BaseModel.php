<?php
namespace App\Models;

use App\Database\DB;
use Exception;
use Medoo\Medoo;

abstract class BaseModel
{
    protected string $table;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (empty($this->table)) {
            throw new Exception('Model must define a $table property.');
        }
    }

    protected function db(): Medoo
    {
        return DB::connect();
    }

    public function all(): array
    {
        return $this->db()->select($this->table, '*');
    }

    public function find(int $id): ?array
    {
        return $this->db()->get($this->table, '*', ['id' => $id]);
    }

    public function where(array $conditions, bool $single = false)
    {
        $db = $this->db();

        if ($single) {
            return $db->get($this->table, '*', $conditions);
        }

        return $db->select($this->table, '*', $conditions);
    }

    public function create(array $data): bool
    {
        return $this->db()->insert($this->table, $data)->rowCount() > 0;
    }

    public function update(int $id, array $data): bool
    {
        return $this->db()->update($this->table, $data, ['id' => $id])->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        return $this->db()->delete($this->table, ['id' => $id])->rowCount() > 0;
    }
}
