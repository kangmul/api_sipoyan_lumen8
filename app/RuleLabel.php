<?php

namespace App;

class RuleLabel
{
    public static function validate()
    {
        return [
            'required' => 'Tidak boleh kosong',
            'string' => 'Harus berupa string',
            'numeric' => 'Harus berupa angka',
            'unique' => 'sudah ada dalam database, :attribute tidak boleh sama',
            'max' => 'String maksimum',
        ];
    }
}
