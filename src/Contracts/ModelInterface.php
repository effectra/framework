<?php

declare(strict_types=1);

namespace Effectra\Core\Contracts;

interface ModelInterface {

    public static function data($data): self;
    public static function all();
    public static function find($id, ?bool $associative = null);
    public static function where($conditions, ?bool $associative = null);
    public static function create();
    public static function update($id);
    public static function updateWhere($conditions);
    public static function search($conditions);
    public static function delete($id);
    public static function deleteAll();
    public static function truncate();
}