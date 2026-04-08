<?php

use App\Http\Controllers\Api\PromptRouterController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/prompt-router', PromptRouterController::class);
