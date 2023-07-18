<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait DisableForiegnKeys
{
    protected function disableForiegnKeys()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    protected function enableForiegnKeys()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
