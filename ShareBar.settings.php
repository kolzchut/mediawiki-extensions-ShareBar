<?php

$egShareBarDisabledServices = null;

$egShareBarServices['print'] = array(
	'openAs' => 'print',
);

$egShareBarServices['feedback'] = array(
	//'url' => '',
	'width' => 800,
	'height' => 700
);
$egShareBarServices['donate'] = array(
	//'url' => '',
	'width' => 1050,
	'height' => 750
);

$egShareBarServices['changerequest'] = array(
	'url' => '/forms/ChangeRequest/',
	'width' => 750,
	'height' => 650,
);

$egShareBarServices['send'] = array(
	'url' => '/forms/mailArticle/',
	'width' => 700,
	'height' => 500,
);

$egShareBarServices['facebook'] = array(
	'openAs' => 'window',
	'width' => 520,
	'height' => 350,
);

$egShareBarServices['twitter'] = array(
	'openAs' => 'window',
	'width' => 550,
	'height' => 420,
);

$egShareBarServices['gplus'] = array(
	'openAs' => 'window',
	'width' => 575,
	'height' => 400,
);
