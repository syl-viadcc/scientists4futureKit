<?php return [
	'custom' => [
		'name' => [
			'required' => 'O Nome é o campo obrigatório.',
		],
        'bi_number' => [
            'required' => 'O Número do BI/CC é o campo obrigatório.',
        ],
		'email' => [
			'required' => 'O endereço de email é obrigatório.',
			'email' => 'Por favor, forneça um endereço de e-mail válido.',
			'unique_verified' => 'Já existe uma inscrição varificada com esse endereço de email. Obrigado!',
			'unique_not_verified' => 'Já existe uma inscrição NÂO verificada com esse endereço de email. Por favor carrega no link da validação no email que lhe enviamos.'
		],
        'orcid_id' => [
            'required' => 'ORCID ID é obrigatório.',
            'unique' => 'Já existe uma inscrição com o ORCID ID <orcid_id>!',
            'regex' => 'ORCID ID tem que ter o formato ex: 0000-0001-2345-6789!',
            'not_in' => 'Este ORCID ID !',
            'orcid_ping' => 'ORCID ID: <orcid_id> não existe!',

        ],
		'reCAPTCHA' => [
			'required' => 'Falha no teste humano, por favor selecione a caixa de verificação do reCAPTCHA'
		],
		'gdpr' => [
			'required' => 'Tem de autorizar a recolha e uso dos seus dados.'
		]
	],
	'recapcha' => [
		'flash-message'=>'O reCAPTCHA (serviço que protege o nosso site contra spam e abuso) falhou na validação humana.',
		'missing-input-secret'=>'O parâmetro secreto está em falta.',
		'invalid-input-secret'=>'O parâmetro secreto é inválido ou incorreto.',
		'missing-input-response'=>'O parâmetro de resposta está em falta.',
		'invalid-input-response'=>'O parâmetro de resposta é inválido ou incorreto.',
		'bad-request'=>'O pedido é inválido ou incorreto.',
		'timeout-or-duplicate'=>'Pedido de tempo limite ou de duplicação do reCAPTCHA.',
		'invalid-keys'=>'Chave do reCAPTCHA inválida.',
		'invalid-sol'=>'Chave do reCAPTCHA inválida.'
	]
];