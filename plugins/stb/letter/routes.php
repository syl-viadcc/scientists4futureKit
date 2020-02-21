<?php

Route::get('/verifyEmail/{token}/{expire}', 'Stb\Letter\Components\VerifyEmail@verifyEmail')->middleware('web');