<?php
return [
	'tbkmk' => [
		'THSMSTBKMK' => [
			'rules' => [
				'required'
				],
			'label' => 'Tahun Semester Matakuliah',
			],
		'KDPSTTBKMK' => [
			'rules' => [
				'required'
				],
			'label' => 'Kode Prodi Matakuliah',
			],
		'KDKMKTBKMK' => [
			'rules' => [
				'required',
				'min' => 3,
				'callback' => 'kdkmkIsExist'
				],
			'label' => 'Kode Matakuliah',
			'filter' => ['trim', 'strtolower', 'ucwords']
			],
		'NAKMKTBKMK' => [
			'rules' => [
				'required',
				'min' => 3,
				],
			'label' => 'Nama Matakuliah',
			'filter' => ['trim', 'strtolower', 'ucwords']
			],
		],
	'addreg' => [
		'THSMS'  => ['rules' => ['required', 'callback' => 'thsmsIsExist']],
		'NIMHS'  => ['rules' => ['required']],
		'SMTMHS' => ['rules' => ['required']]
		],
	'updatereg' => [
		'REGTA'  => ['rules' => ['callback' => 'checkSemester']],
		'REGKP'  => ['rules' => ['callback' => 'checkSemester']],
		'REGKKL' => ['rules' => ['callback' => 'checkSemester']]
	],
	'nilai' => [
		'NLAKHTRNLM' => ['rules' => ['callback' => 'inputNilai']],
		'NIMHSTRNLM' => ['rules' => ['required']]
		]
	
	];