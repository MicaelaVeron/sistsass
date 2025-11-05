<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationRolController extends Controller
{
    public function getRolWithOrganization($organization_id)
    {
        $roles = \App\Models\OrganizationRol::getRoles($organization_id);
        return $roles;
    }
}
