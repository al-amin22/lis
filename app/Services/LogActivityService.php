<?php

namespace App\Services;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LogActivityService
{
    public static function log(
        string $action,
        string $module,
        ?string $description = null,
        $oldData = null,
        $newData = null
    ) {
        $request = request();

        LogActivity::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'module'     => $module,
            'description'=> $description,
            'old_data'   => $oldData ? json_encode($oldData) : null,
            'new_data'   => $newData ? json_encode($newData) : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url'        => $request->fullUrl(),
            'method'     => $request->method(),
        ]);
    }
}
