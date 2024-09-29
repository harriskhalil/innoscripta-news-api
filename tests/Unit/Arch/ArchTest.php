<?php
arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->relaxed();
arch()->preset()->laravel()->ignoring(['App\Http\Controllers\AuthController','App\Http\Controllers\ArticleController','App\Http\Controllers\UserPreferencesController']);
