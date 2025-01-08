<?php

namespace App\Imports;

use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;

class ClassImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $name = $row[1];
        $toLower = strtolower($name);
        $parts = explode(' ', $name, 2);
        $firstPart = $parts[0];
        $remainingPart = $parts[1] ?? '';

        if (is_numeric($firstPart)) {
            $romanNumeral = $this->toRoman($firstPart);
            $toLower = strtolower($romanNumeral . ' ' . $remainingPart);
        } else {
            $toLower = strtolower($name);
        }

        $slug = str_replace(' ', '-', $toLower);

        return new Kelas([
            'name' => $row[1],
            'slug' => $slug,
        ]);
    }

    public function toRoman($num)
    {
        $n = intval($num);
        $result = '';
        $lookup = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        foreach ($lookup as $roman => $value) {
            $matches = intval($n / $value);
            $result .= str_repeat($roman, $matches);
            $n = $n % $value;
        }
        return $result;
    }
}
