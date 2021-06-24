<?php

use Ignite\Support\Facades\Route as LitstackRoute;
use Litstack\Wikipedia\Controllers\WikipediaController;

LitstackRoute::post('wikipedia-preview', [WikipediaController::class, 'preview'])->name('wikipedia.preview');
