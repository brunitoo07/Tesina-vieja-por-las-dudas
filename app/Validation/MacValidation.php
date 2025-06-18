<?php

namespace App\Validation;

use App\Models\MacValidationModel;

class MacValidation
{
    public function valid_mac_address(?string $str, ?string $fields = null, ?array $data = null): bool
    {
        if (empty($str)) {
            return false;
        }

        $macValidationModel = new MacValidationModel();
        return $macValidationModel->esMacValida($str);
    }
} 